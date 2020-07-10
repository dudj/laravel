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
 * Date: 2020/07/03
 * Time: 15:54
 * Summary: 品牌验证模型
 */
class Brand extends Model
{
    const UPDATED_AT = null;
    // 数据信息
    protected $data = [];
    protected $table = 'brand';
    const exec_success = '执行成功';
    const exec_fail = '执行失败';
    // 验证规则
    public $rule = [
        'name' => 'required|unique:brand',
        'parent_cat_id' => 'required|integer',
        'cat_id' => 'required|integer',
    ];
    //错误信息
    protected $message = [
        'name.required' => '品牌名称必填',
        'name.unique' => '品牌名称重复',
        'parent_cat_id.unique' => '分类必须填写',
        'cat_id.unique' => '分类必须填写',
    ];
    /**
     * @param $request
     * @return array
     * 验证表单提交的内容 增加和修改均需要验证 因此作为公共方法
     */
    public function checkForm($request){
        try{
            $model = new Brand();
            if($request->id){
                $model->rule['name'] = 'required|unique:brand,name,'.$request->id.',id';
            }
            $validator = Validator::make($request->all(), $model->rule,$model->message);
            if($validator->fails()){
                foreach ($validator->errors()->all() as $message) {
                    return ['code'=>-1,'msg'=>$message];
                }
            }
            $data = $request->all();
            $data = filterFields($data, new Brand());
            if(isset($data['id']) && $data['id'] > 0){
//                $goods = DB::table($this->table)->where('id','=',$data['id'])->first();
                $id = $data['id'];
                unset($data['id']);
                $model->where('id',$id)->update($data);
            }else{
                unset($data['id']);
                $id = $model->insertGetId($data);
            }
            return ['code'=>1,'msg'=>self::exec_success];
        }catch (ValidationException $e){
            return ['code'=>-1,'msg'=>$e->getMessage()];
        }
    }
}