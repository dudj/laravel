<?php
namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Email: dudongjiangphp@163.com
 * Date: 2019/11/1
 * Time: 15:54
 * Summary: ${summary}
 */
class Admins extends Model
{
    protected $table = 'admins';
    const exec_success = '执行成功';
    const exec_fail = '执行失败';
    /**
     * @param $request
     * @param $type
     * @return array
     * 验证表单提交的内容 增加和修改均需要验证 因此作为公共方法
     */
    public static function checkForm($request,$type='insert'){
        $model = new Admins();
        $messages = [
            'required' => ':attribute 的字段是必要的。',
            'unique' => ':attribute 的字段的值已经存在。',
        ];
        if($type !== 'insert'){
            //update过程中 当前信息不在验证中
            if($request->get('password') != ""){
                $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:'.$model->getTable().',name,'.$request->get('id').',id|max:255',
                    'mailbox' => 'required|unique:'.$model->getTable().',mailbox,'.$request->get('id').',id|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                    'mobile' => 'required|unique:'.$model->getTable().',mobile,'.$request->get('id').',id|regex:/^1\d{10}$/',
                    'password' => 'required|regex:/^[a-zA-Z0-9_-]{6,12}$/',
                    'group_id' => 'required'
                ],$messages);
            }else{
                $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:'.$model->getTable().',name,'.$request->get('id').',id|max:255',
                    'mailbox' => 'required|unique:'.$model->getTable().',mailbox,'.$request->get('id').',id|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                    'mobile' => 'required|unique:'.$model->getTable().',mobile,'.$request->get('id').',id|regex:/^1\d{10}$/',
                    'group_id' => 'required'
                ],$messages);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:'.$model->getTable().'|max:255',
                'mailbox' => 'required|unique:'.$model->getTable().'|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',
                'mobile' => 'required|unique:'.$model->getTable().'|regex:/^1\d{10}$/',
                'password' => 'required|regex:/^[a-zA-Z0-9_-]{6,12}$/',
                'group_id' => 'required'
            ],$messages);
        }
        if($validator->fails()){
            foreach ($validator->errors()->all() as $message) {
                return ['code'=>-1,'msg'=>$message];
            }
        }
        return ['code'=>1,'msg'=>self::exec_success];
    }
}