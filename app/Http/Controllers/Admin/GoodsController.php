<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Logic\Admin\GoodsLogic;
use App\Models\Admin\Brand;
use App\Models\Admin\GoodsCategory;
use App\Models\Common\Goods;
use App\Models\Admin\Goods as adminGoods;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use League\Flysystem\Exception;


/**
 * Class GoodsController
 * Created by dudj
 * Email: dudongjiangphp@163.com
 * Date: 2020-05-21
 * Summary: 商品管理操作
 */
class GoodsController extends Controller
{
    private $goodsLogic;
    public function __construct()
    {
        $this->goodsLogic = new GoodsLogic();
    }

    /**
     *  商品分类列表
     */
    public function categoryList(){
        $cat_list = $this->goodsLogic->goods_cat_list();
        return view('admin.goods.categorylist',[
            'cat_list' => $cat_list,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * ajax获取分类列表
     */
    public function ajaxCategoryList(){
        $cat_list = $this->goodsLogic->goods_cat_list();
        $data = [
            'code' => 0,
            'data' => array_values($cat_list),
            'count' => count($cat_list),
            'msg' => '查询成功'
        ];
        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * ajax获取treeSelect格式的数据
     */
    public function ajaxTreeSelectCategoryList(){
        $listData = $this->goodsLogic->treeSelectCategoryList();
        return response()->json($listData);
    }
    /**
     * @param Request $request
     * @throws \Exception
     * 更改商品分类中不同的字段
     */
    public function changeCategory(Request $request){
        if($request->get('id')){
            $this->commonUpdate('goods_category',$request->all(),[['key'=>'id','relation'=>'=','val'=>$request->get('id')]]);
        }else{
            throw new \Exception("参数错误");
        }
    }
    /**
     * @param Request $request
     * @return mixed
     * 删除分类信息
     */
    public function deleteCategory(Request $request){
        try{
            $ids = $request->ids;
            if(empty($ids)) return $this->error([], '非法操作');
            $ids = [rtrim($ids,",")];
            $data = DB::table('goods_category')->whereIn('parent_id',$ids)->get()->groupBy('id')->toArray();
            if($data){
                return $this->error('', '有子类不能删除');
            }
            $goodsData = DB::table('goods')->whereIn('cat_id',$ids)->get()->groupBy('cat_id')->toArray();
            if($goodsData){
                return $this->error('', '该分类下有商品不得删除');
            }
            DB::table('goods_category')->whereIn('id',$ids)->delete();
            return $this->success(1, '操作成功');
        }catch (Exception $e){
            return $this->error('', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * 添加和修改 商品分类
     */
    public function addEditCategory(Request $request){
        $model = new GoodsCategory();
        $data = [];
        $level_cat = [];
        $id = $request->id;
        if($request->ajax() && $request->isMethod('post')){
            $res = $model->checkForm($request);
            if($res['code'] >= 1){
                return $this->success($res, $res['msg']);
            }else{
                return $this->error([], $res['msg']);
            }
        }
        if(isset($id) && $id){
            $data = $model->where('id', '=', $id)->first()->toArray();
        }
        //增加当前分类的子类，需要将当前分类默认
        if(isset($request->parent_id) && $request->parent_id){
            $data['parent_id'] = $request->parent_id;
        }
        $categoryList = DB::table('goods_category')->where('parent_id', '=', '0')->get();
        return view('admin.goods._category',[
            'data' => $data,
            'categoryList' => $categoryList,
        ]);
    }
    /**
     * 获取商品列表
     */
    public function goodsList(){
        $brandList = $this->goodsLogic->getSortBrands();
        $categoryList = $this->goodsLogic->getSortCategory();
        //供应商列表
        $supplierList = DB::table('suppliers')->select('suppliers_id', 'suppliers_name')->get()->toArray();
        return view('admin.goods.goodslist',[
            'brandList' => $brandList,
            'categoryList' => $categoryList,
            'supplierList' => $supplierList
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 商品品牌
     */
    public function brandList(){
        return view('admin.goods.brandlist');
    }
    public function ajaxBrandList(Request $request){
        $goodsList = $this->goodsLogic->getBrandList($request);
        $data = [
            'code' => 0,
            'data' => $goodsList['data'],
            'count' => $goodsList['total'],
            'msg' => '查询成功'
        ];
        return response()->json($data);
    }
    /**
     * @param Request $request
     * @throws \Exception
     * 改变品牌的数据
     */
    public function changeBrand(Request $request){
        if($request->get('id')){
            $this->commonUpdate('brand',$request->all(),[['key'=>'id','relation'=>'=','val'=>$request->get('id')]]);
        }else{
            throw new \Exception("参数错误");
        }
    }
    /**
     * 获取商品列表
     */
    public function ajaxGoodsList(Request $request){
        $goodsList = $this->goodsLogic->getGoodsList($request);
        $data = [
            'code' => 0,
            'data' => $goodsList['data'],
            'count' => $goodsList['total'],
            'msg' => '查询成功'
        ];
        return response()->json($data);
    }
    /**
     * @param Request $request
     * @return mixed
     * ajax 修改数据Goods.php
     */
    public function ajaxUpdate(Request $request){
        $result = $this->goodsLogic->ajaxUpdate($request);
        if($result > 0){
            return $this->success([],'更新成功');
        }
        return $this->error([],'更新失败，传递数据有误');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 编辑和添加展示页面
     */
    public function addEditGoods(Request $request){
        $goods = new Goods();
        $goodsData = [];
        $level_cat = [];
        $level_cat2 = [];
        $goods_id = $request->goods_id;
        $goods_type = $request->goods_type;
        if(isset($goods_id) && $goods_id){
            $goodsData = $goods->where('goods_id', '=', $goods_id)->first()->toArray();
            $level_cat = $this->goodsLogic->find_parent_cat($goodsData['cat_id']); // 获取分类默认选中的下拉框
            $level_cat2 = $this->goodsLogic->find_parent_cat($goodsData['extend_cat_id']); // 获取分类默认选中的下拉框
        }
        $brandList = $this->goodsLogic->getSortBrands();
        $categoryList = DB::table('goods_category')->where('parent_id', '=', '0')->get()->toArray();
        $goodsType = DB::table('goods_type')->get()->toArray();
        $goodsLabel = DB::table('goods_label')->orderBy('sort','desc')->get()->toArray();
        $suppliersList = DB::table("suppliers")->where('is_check', '=', 1)->select('suppliers_id', 'suppliers_name')->get()->toArray();
        $freight_template = DB::table("freight_template")->select('template_id', 'template_name')->get()->toArray();
//        echo '<pre>';var_dump($goodsData);die;
        return view('admin.goods._goods',[
            'goods' => $goodsData,
            'level_cat' => $level_cat,
            'level_cat2' => $level_cat2,
            'brandList' => $brandList,
            'goodsType' => $goodsType,
            'goodsLabel' => $goodsLabel,
            'freight_template' => $freight_template,
            'suppliersList' => $suppliersList,
            'categoryList' => $categoryList,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 添加、编辑、保存功能及一身
     */
    public function addEditBrand(Request $request){
        $brand = new Brand();
        $brandData = [];
        $level_cat = [];
        $level_cat2 = [];
        $id = $request->id;
        if($request->ajax() && $request->isMethod('post')){
            $brand = new Brand();
            $res = $brand->checkForm($request);
            if($res['code'] >= 1){
                return $this->success($res, $res['msg']);
            }else{
                return $this->error([], $res['msg']);
            }
        }
        if(isset($id) && $id){
            $brandData = $brand->where('id', '=', $id)->first()->toArray();
            $level_cat = $this->goodsLogic->find_parent_cat($brandData['cat_id']); // 获取分类默认选中的下拉框
        }
        $categoryList = DB::table('goods_category')->where('parent_id', '=', '0')->get();
        return view('admin.goods._brand',[
            'level_cat' => $level_cat,
            'brand' => $brandData,
            'categoryList' => $categoryList,
        ]);
    }
    /**
     * 删除图片
     */
    public function delGoodsImages(){
        $filename = request('filename');
        DB::table('goods_images')->where('image_url', '=', $filename)->delete();
    }

    /**
     * @param Request $request
     * @return mixed
     * 保存或者修改 根据goods_id操作
     */
    public function save(Request $request){
        $adminGoods = new adminGoods();
        $res = $adminGoods->checkForm($request);
        if($res['code'] >= 1){
            return $this->success($res, $res['msg']);
        }else{
            return $this->error([], $res['msg']);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 删除商品相关信息
     */
    public function deleteGoods(Request $request){
        DB::beginTransaction();
        try{
            $ids = $request->ids;
            if(empty($ids)) return $this->error([], '非法操作');
            $goods_ids = [rtrim($ids,",")];
            //当前商品如果有订单，不允许删除
            //团购
            DB::table('goods')->whereIn('goods_id',$goods_ids)->delete();  //商品表
            DB::table("cart")->whereIn('goods_id',$goods_ids)->delete();  // 购物车
            //商品评论
            //商品咨询
            DB::table("goods_images")->whereIn('goods_id',$goods_ids)->delete();  //商品相册
            DB::table("goods_attr")->whereIn('goods_id',$goods_ids)->delete();  //商品属性
            DB::table('goods_collect')->whereIn('goods_id',$goods_ids)->delete();  //商品收藏
            DB::commit();
            return $this->success(1, '操作成功');
        }catch (Exception $e){
            DB::rollback();
            return $this->error('', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 删除品牌
     */
    public function deleteBrand(Request $request){
        try{
            $ids = $request->ids;
            if(empty($ids)) return $this->error([], '非法操作');
            $ids = [rtrim($ids,",")];
            //当前品牌如果有对应的商品 不允许删除
            $data = DB::table('goods')->whereIn('brand_id',$ids)->get()->groupBy('brand_id')->toArray();
            if($data){
                return $this->error('', '有商品在用不得删除');
            }
            DB::table('brand')->whereIn('id',$ids)->delete();
            return $this->success(1, '操作成功');
        }catch (Exception $e){
            return $this->error('', $e->getMessage());
        }
    }
}
