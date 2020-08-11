<?php
namespace App\Logic\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Predis\Client;
use Illuminate\Support\Facades\Log;
/**
 * 分类逻辑定义
 * Class GoodsLogic
 * @package App\Logic\Admin
 */
class GoodsLogic extends Model
{
    public $redis;
    public function __construct(){
        $this->redis = new Client();
    }
    /**
     * 获得指定分类下的子分类的数组
     * @access  public
     * @param   int     $cat_id     分类的ID
     * @param   int     $selected   当前选中分类的ID
     * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
     * @param   int     $level      限定返回的级数。为0时返回所有级数
     * @return  mix
     */
    public function goods_cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0)
    {
        global $goods_category, $goods_category2;
        $goods_category = DB::table('goods_category')->orderBy('parent_id', 'asc')->orderBy('sort_order', 'asc')->get()->toArray();
        $goods_category = convert_arr_key($goods_category, 'id');
        foreach ($goods_category AS $key => $value)
        {
            if($value['level'] == 1)
                $this->get_cat_tree($value['id']);
        }
        return $goods_category2;
    }

    /**
     * 商品分类 树形结构展示 以select形式展示
     */
    public function treeSelectCategoryList(){
        $goods_category = DB::table('goods_category')->where('parent_id','=',0)->orderBy('sort_order', 'asc')->get(['id', 'name','level'])->toArray();
        foreach($goods_category as $key=>$val){
            $children = self::treeSelectCategoryListSon($val['id']);
            if($children){
                $goods_category[$key]['children'] = $children;
            }
            $goods_category[$key]['open'] = false;
            $goods_category[$key]['checked'] = false;
        }
        return $goods_category;
    }
    private function treeSelectCategoryListSon($paranet_id){
        $data = DB::table('goods_category')->where('parent_id','=',$paranet_id)->orderBy('sort_order', 'asc')->get(['id', 'name','level'])->toArray();
        foreach($data as $key=>$val){
            $children = self::treeSelectCategoryListSon($val['id']);
            if($children){
                $data[$key]['children'] = $children;
            }
            $data[$key]['open'] = false;
            $data[$key]['checked'] = false;
        }
        return $data;
    }
    /**
     * @param int $id 当前显示的 菜单id
     * 获取指定id下的 所有分类
     */
    public function get_cat_tree($id)
    {
        global $goods_category, $goods_category2;
        $goods_category2[$id] = $goods_category[$id];
        foreach ($goods_category AS $key => $value){
            if($value['parent_id'] == $id)
            {
                $this->get_cat_tree($value['id']);
                $goods_category2[$id]['have_son'] = 1; // 还有下级
            }
        }
    }
    /**
     * 获取排好序的品牌列表
     * @param int $cat_id
     * @return mixed
     */
    public function getSortBrands($cat_id=0)
    {
        $brandList = $this->redis->get('getSortBrands');
        if(!empty($brandList)){
            return json_decode($brandList, true);
        }
        $brand_where = [];
        if ($cat_id){
            $brand_where['cat_id|parent_cat_id'] = $cat_id;  //查找分类下的品牌，没值就查找全部
        }
        $brandList = DB::table('brand')->where($brand_where)->get()->toArray();
        $brandIdArr = DB::table('brand')->select('id','cat_id')->where($brand_where)->whereIn('name',
            DB::table('brand')->select('name')
            ->groupBy('name')
            ->havingRaw('count(id) > 1'))->get();
        $goodsCategoryArr = DB::table('goods_category')->select('id','name')->where("level", "=", 1)->get()->toArray();
        $nameList = array();
        foreach($brandList as $k => $v)
        {
            $name = getFirstCharter($v['name']) .'  --   '. $v['name']; // 前面加上拼音首字母
            if(array_key_exists($v['id'],$brandIdArr) && $v['cat_id']) // 如果有双重品牌的 则加上分类名称
                    $name .= ' ( '. $goodsCategoryArr[$v['cat_id']] . ' ) ';
             $nameList[] = $v['name'] = $name;
             $brandList[$k] = $v;
        }
        array_multisort($nameList,SORT_STRING,SORT_ASC,$brandList);
        $this->redis->set('getSortBrands', json_encode($brandList));
        return $brandList;
    }

    /**
     * @param $cat_id
     * @return array
     * 获取选中的下拉框
     */
    function find_parent_cat($cat_id)
    {
        if($cat_id == null)
            return [];
        $cat_list =  DB::table('goods_category')->select('id','parent_id','level')->get()->toArray();
        $cat_list = array_reduce($cat_list, create_function('$result, $v', '$result[$v["id"]] = ["parent_id"=>$v["parent_id"],"level"=>$v["level"]];return $result;'));
        $cat_level_arr[$cat_list[$cat_id]['level']] = $cat_id;
        // 找出父类
        $parent_id = $cat_list[$cat_id]['parent_id'];
        if($parent_id > 0){
            $cat_level_arr[$cat_list[$parent_id]['level']] = $parent_id;
            $grandpa_id = $cat_list[$parent_id]['parent_id'];
            if($grandpa_id > 0){
                $cat_level_arr[$cat_list[$grandpa_id]['level']] = $grandpa_id;
                $grandfather_id = $cat_list[$grandpa_id]['parent_id'];
                if($grandfather_id > 0){
                    $cat_level_arr[$cat_list[$grandfather_id]['level']] = $grandfather_id;
                }
            }
        }
        return $cat_level_arr;
    }
    /**
     * 获取排好序的分类列表
     * @param string $level  //需要获取第几级分类
     * @return mixed
     */
    public function getSortCategory()
    {
        $categoryList = $this->redis->get('categoryList');
        if($categoryList)
        {
            return json_decode($categoryList,true);
        }
        $categoryList = DB::table('goods_category')->select('id','name','parent_id','level')->get()->toArray();
        $nameList = array();
        foreach($categoryList as $k => $v)
        {
            $name = getFirstCharter($v['name']) .' '. $v['name']; // 前面加上拼音首字母
            $nameList[] = $v['name'] = $name;
            $categoryList[$k] = $v;
        }
        array_multisort($nameList,SORT_STRING,SORT_ASC,$categoryList);
        $this->redis->set('categoryList', json_encode($categoryList));
        return $categoryList;
    }
    /**
     * @param $request
     * @return mixed
     * 根据查询条件获取数据
     */
    public function getGoodsList($request){
        if(isset($request->sortfield)){
            $request->sortfield = 'goods_id';
        }
        if(isset($request->sorttype)){
            $request->sorttype = 'desc';
        }
        $param = [
            'paramWhere' => ['brand_id','is_on_sale','suppliers_id'],
            'paramLike' => ['key_word'],
            'paramDate' => [],
            'paramIn' => [
                'cat_id' => 'getCatGrandson'
            ],
        ];
        $where = function($query) use($request,$param){
            //=
            foreach($param['paramWhere'] as $val){
                if ($request->has($val) && $request->$val != '') {
                    $query->where($val, '=', $request->$val);
                }
            }
            //特殊操作 传递的是字段
            if ($request->has('intro') && $request->intro != '') {
                $query->where($request->intro, '=', 1);
            }
            //like
            foreach($param['paramLike'] as $val){
                if ($request->has($val) && $request->$val != '') {
                    $search = "%" . trim($request->$val) . "%";
                    $query->where(function ($sonQuery) use ($search){
                        $sonQuery->where('goods_name', 'LIKE', $search);
                        $sonQuery->orWhere('goods_sn', 'LIKE', $search);
                    });
                }
            }
            //in
            foreach($param['paramIn'] as $key=>$val){
                if($request->has($key) && $request->$key > 0){
                    $query->whereIn($key, $val($request->$key));
                }
            }
            //查询审核通过的数据
            $query->where('audit', '=', 0);
        };
        $pagesize = $request->limit;
        $offset = ($request->page - 1) * $pagesize;
        $goodsList = DB::table('goods as g')
            ->leftjoin('goods_category as gc',function($join){
                $join->on('g.cat_id','=','gc.id');
            })->leftjoin('suppliers as s',function($join){
                $join->on('g.suppliers_id','=','s.suppliers_id');
            })->select('g.*','gc.name as cat_name','s.suppliers_name')->where($where)->orderBy($request->sortfield, $request->sorttype)->offset($offset)->paginate($pagesize)->toArray();
        return $goodsList;
    }

    /**
     * @param $request
     * @return mixed
     * 获取品牌列表
     */
    public function getBrandList($request){
        $keyword = '%'.trim($request->keyword).'%';
        $pagesize = $request->limit;
        $offset = ($request->page - 1) * $pagesize;
        $goodsList = DB::table('brand')->where('name','LIKE',$keyword)->offset($offset)->paginate($pagesize)->toArray();
        return $goodsList;
    }
    /**
     * @param $request
     * @return int
     * ajax修改 多字段 单字段修改
     */
    public function ajaxUpdate($request){
        $param = [
            'is_new','is_hot','is_recommend','is_on_sale'
        ];
        if ($request->has('type') && $request->type != '') {
            if ($request->has('status') && $request->status != '' && $request->has('goods_id') && $request->goods_id != '') {
                switch($request->status){
                    case 'one':
                        return DB::table('goods')
                            ->where('goods_id', $request->goods_id)
                            ->update([$request->type => $request->value]);
                        break;
                    case 'more':
                        break;
                    default :
                        break;
                }
            }else{
                Log::info("ajax修改 参数异常");
                return -1;
            }
        }else{
            Log::error("ajax修改 参数异常");
            return -1;
        }
    }
    /**
     * 后置操作方法
     * 自定义的一个函数 用于数据保存后做的相应处理操作, 使用时手动调用
     * @param int $goods_id 商品id
     * @param array $data 商品信息
     */
    public function afterSave($goods_id,$data)
    {
        // 商品图片相册  图册
        $goods_images = $data['goods_images'];
        if(count($goods_images) > 1)
        {
            array_pop($goods_images); // 弹出最后一个
            $goodsImagesArr = DB::table('goods_images')->select(['img_id','image_url'])->where("goods_id", "=", $goods_id)->get()->toArray();
            // 删除图片
            foreach($goodsImagesArr as $key => $val)
            {
                if(!in_array($val, $goods_images)) DB::table('goods_images')->where("img_id", "=", $key)->delete();
            }
            // 添加图片
            foreach($goods_images as $key => $val)
            {
                if($val == null)  continue;
                if(!in_array($val, $goodsImagesArr))
                {
                    $data = array('goods_id' => $goods_id,'image_url' => $val);
                    DB::table('goods_images')->insert($data);
                }
            }
        }
        // 查看主图是否已经存在相册中
        $original_img = $data['original_img'];
        $c = DB::table('goods_images')->where('goods_id', '=', $goods_id)->where('image_url', '=', $original_img)->count();
        //fix:删除商品详情的图片(相册图刚好是主图时)删除的图片仍然在相册中显示. 如果主图存物理图片存在才添加到相册 @{
        $deal_orignal_img = str_replace('../','',$original_img);
        $deal_orignal_img= trim($deal_orignal_img,'.');
        $deal_orignal_img= trim($deal_orignal_img,'/');
        if($c == 0 && $original_img && file_exists($deal_orignal_img)) //@}
        {
            DB::table('goods_images')->insert(['goods_id'=>$goods_id,'image_url'=>$original_img]);
        }
    }
    /**
     *  给指定商品添加属性 或修改属性 更新到 tp_goods_attr
     * @param int $goods_id  商品id
     * @param int $data  商品相关数据
     */
    public function saveGoodsAttr($goods_id,$data)
    {
        $goods_type = $data['goods_type'];
        $GoodsAttr = DB::table('goods_attr');
        if($goods_type == 0)
        {
            $GoodsAttr->where('goods_id', '= ',$goods_id)->delete();
            return;
        }
        $GoodsAttrList = $GoodsAttr->where('goods_id', '= ', $goods_id)->get()->toArray();
        $old_goods_attr = array();
        foreach($GoodsAttrList as $k => $v)
        {
            $old_goods_attr[$v['attr_id'].'_'.$v['attr_value']] = $v;
        }
        $post_goods_attr = array();
        foreach($data as $k => $v)
        {
            $attr_id = str_replace('attr_','',$k);
            if(!strstr($k, 'attr_') || strstr($k, 'attr_price_'))
                continue;
            foreach ($v as $k2 => $v2)
            {
                $v2 = str_replace('_', '', $v2); // 替换特殊字符
                $v2 = str_replace('@', '', $v2); // 替换特殊字符
                $v2 = trim($v2);
                if(empty($v2))
                    continue;
                $tmp_key = $attr_id."_".$v2;
                $post_attr_price = $data['attr_price_'.$attr_id];
                $attr_price = $post_attr_price[$k2];
                $attr_price = $attr_price ? $attr_price : 0;
                if(array_key_exists($tmp_key , $old_goods_attr))
                {
                    if($old_goods_attr[$tmp_key]['attr_price'] != $attr_price)
                    {
                        $goods_attr_id = $old_goods_attr[$tmp_key]['goods_attr_id'];
                        $GoodsAttr->where('goods_attr_id', '=', $goods_attr_id)->update(['attr_price'=>$attr_price]);
                    }
                }
                else
                {
                    $GoodsAttr->insert(['goods_id'=>$goods_id,'attr_id'=>$attr_id,'attr_value'=>$v2,'attr_price'=>$attr_price]);
                }
                unset($old_goods_attr[$tmp_key]);
            }

        }
        foreach($old_goods_attr as $k => $v)
        {
            $GoodsAttr->where('goods_attr_id', '= ',$v['goods_attr_id'])->delete();
        }
    }
}