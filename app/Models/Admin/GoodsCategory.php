<?php
namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Email: dudongjiangphp@163.com
 * Date: 2020/07/09
 * Time: 15:54
 * Summary: 商品分类信息模型
 */
class GoodsCategory extends Model
{
    const UPDATED_AT = null;
    // 数据信息
    protected $data = [];
    protected $table = 'goods_category';
    const exec_success = '执行成功';
    const exec_fail = '执行失败';
    // 验证规则
    public $rule = [
        'name' => 'required|unique:goods_category',
        'mobile_name' => 'required|unique:goods_category',
        'is_show' => 'required|integer',
        'sort_order' => 'required|integer',
    ];
    //错误信息
    protected $message = [
        'name.required' => '分类名称必填',
        'name.unique' => '分类名称重复',
        'mobile_name.required' => '分类名称必填',
        'mobile_name.unique' => '分类名称重复',
        'is_show.unique' => '是否展示必须选择',
        'sort_order.unique' => '排序必须填写',
    ];
    /**
     * @param $request
     * @return array
     * 验证表单提交的内容 增加和修改均需要验证 因此作为公共方法
     */
    public function checkForm($request){
        try{
            $model = new GoodsCategory();
            if($request->id){
                $model->rule['name'] = 'required|unique:goods_category,name,'.$request->id.',id';
                $model->rule['mobile_name'] = 'required|unique:goods_category,mobile_name,'.$request->id.',id';
            }
            $validator = Validator::make($request->all(), $model->rule,$model->message);
            if($validator->fails()){
                foreach ($validator->errors()->all() as $message) {
                    return ['code'=>-1,'msg'=>$message];
                }
            }
            $data = $request->all();
            $data = filterFields($data, new GoodsCategory());
            if(isset($data['id']) && $data['id'] > 0){
                $id = $data['id'];
                unset($data['id']);
                $model->where('id',$id)->update($data);
            }else{
                unset($data['id']);
                $id = $model->insertGetId($data);
            }
            self::afterUpdate($id);
            return ['code'=>1,'msg'=>self::exec_success];
        }catch (ValidationException $e){
            return ['code'=>-1,'msg'=>$e->getMessage()];
        }
    }
    /**
     * @param $id
     * 改变或者添加分类时 需要修改他下面的 parent_id_path  和 level
     */
    public function afterUpdate($id)
    {
        $model = new GoodsCategory();
        $data = $model->where("id", "=", $id)->first()->toArray();
        if($data['parent_id_path'] == '')
        {
            $data['parent_id'] == 0?$model->where('id',$id)->update(['parent_id_path'=>'0_'.$id,'level'=>1]):'';
            DB::update("update goods_category as p,goods_category as s set p.parent_id_path = CONCAT_WS('_',s.parent_id_path,'$id'),p.level = (s.level+1) where p.parent_id = s.id and p.id = ?", [$id]);
        }
        if($data['parent_id'] == 0)
        {
            $parentData['parent_id_path'] = '0';
            $parentData['level'] = 0;
        }
        else{
            $parentData = $model->where("id", "=", $data['parent_id'])->first()->toArray();
        }
        $replace_level = $data['level'] - ($parentData['level'] + 1);
        $replace_str = $parentData['parent_id_path'].'_'.$id;
        $paramLike = $data['parent_id_path']."%";
        $model->where('parent_id_path', 'LIKE', "'$paramLike'")->update([
            'parent_id_path'=>"REPLACE(parent_id_path,".$data['parent_id_path'].",".$replace_str.")",
            'level'=>'`level`-'.$replace_level
        ]);
    }
}