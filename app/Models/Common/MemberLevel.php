<?php
namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class Member
 * Created by PHP strom
 * User: dudj
 * Email: dudongjiangphp@163.com
 * Date: 2020-08-03
 * Summary: 会员等级模型
 */
class MemberLevel extends Model
{
    const UPDATED_AT = null;
    // 数据信息
    protected $data = [];
    protected $table = 'member_level';
    const exec_success = '执行成功';
    const exec_fail = '执行失败';
    // 验证规则
    public $rule = [
        'level_name' => 'required|min:2|max:16|unique:member_level',
        'amount' => 'required',
        'discount' => 'required',
    ];
    //错误信息
    protected $message = [
        'level_name.required' => '等级名称必填',
        'level_name.min' => '等级名称长度至少2个字符',
        'level_name.max' => '等级名称长度至多16个字符',
        'level_name.unique' => '等级名称重复',
        'amount.required' => '等级额度必填',
        'discount.required' => '等级折扣必填',
    ];
    /**
     * @param $request
     * @return array
     * 验证表单提交的内容 增加和修改均需要验证 因此作为公共方法
     */
    public function checkForm($request){
        try{
            $model = new MemberLevel();
            if($request->id){
                $model->rule['level_name'] = 'required|min:2|max:16|unique:member_level,level_name,'.$request->id.',id';
            }
            $validator = Validator::make($request->all(), $model->rule,$model->message);
            if($validator->fails()){
                foreach ($validator->errors()->all() as $message) {
                    return ['code'=>-1,'msg'=>$message];
                }
            }
            $data = $request->all();
            $data = filterFields($data, new MemberLevel());
            if(isset($data['id']) && $data['id'] > 0){
                $member = DB::table($this->table)->where('id','=',$data['id'])->first();
                $id = $data['id'];
                unset($data['id']);
                $model->where('id',$id)->update($data);
            }else{
                unset($data['id']);
                $id = $model->insertGetId($data);
            }
            return ['code'=>1, 'data'=>$id, 'msg'=>self::exec_success];
        }catch (ValidationException $e){
            return ['code'=>-1, 'data'=>'', 'msg'=>$e->getMessage()];
        }
    }

}
