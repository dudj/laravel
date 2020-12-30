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
 * Date: 2020-07-30
 * Summary: 会员管理模型
 */
class Member extends Model
{
    const UPDATED_AT = null;
    // 数据信息
    protected $data = [];
    protected $table = 'member';
    const exec_success = '执行成功';
    const exec_fail = '执行失败';
    // 验证规则
    public $adminRule = [
        'username' => 'required|min:6|max:16|unique:member',
        'email' => ['required', 'unique:member', 'regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/'],
        'mobile' => ['required','unique:member', 'regex:/^1\d{10}$/'],
        'password' => 'required|regex:/^(.+){6,18}$/',
    ];
    //错误信息
    protected $adminMessage = [
        'username.required' => '用户昵称必填',
        'username.min' => '用户昵长度至少6个字符',
        'username.max' => '用户昵长度至多16个字符',
        'username.unique' => '用户昵名称重复',
        'email.unique' => '邮箱重复',
        'email.regex' => '邮箱格式不对',
        'mobile.unique' => '手机号重复',
        'mobile.regex' => '手机号格式不对',
        'password.regex' => '密码格式不对',
    ];
    public $homeRule = [
        'sex' => 'required',
        'birthday' => 'required',
        'captcha' => 'required|captcha',
    ];
    //错误信息
    protected $homeMessage = [
        'sex.required' => '性別必填',
        'birthday.required' => '生日必填',
        'captcha.required' => '验证码必填',
        'captcha.captcha' => '验证码错误',
    ];
    /**
     * @param $request
     * @param $type
     * @return array
     * 验证表单提交的内容 增加和修改均需要验证 因此作为公共方法
     * type 作为区分前后端认证的标识
     */
    public function checkForm($request,$type='admin'){
        try{
            $model = new Member();
            $validator = '';
            $data = $request->all();
            if($type == 'admin'){
                if($request->id){
                    $model->adminRule['username'] = 'required|min:3|max:150|unique:member,username,'.$request->id.',id';
                    $model->adminRule['mobile'] = 'required|regex:/^1\d{10}$/|unique:member,mobile,'.$request->id.',id';
                    $model->adminRule['email'] = 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:member,email,'.$request->id.',id';
                    if($request->password == ''){
                        unset($model->adminRule['password']);
                    }
                }
                $validator = Validator::make($request->all(), $model->adminRule,$model->adminMessage);
            }else if($type == 'home'){
                if($request->id){
                    $model->adminRule['username'] = 'required|min:3|max:150|unique:member,username,'.$request->id.',id';
                    $model->adminRule['mobile'] = 'required|regex:/^1\d{10}$/|unique:member,mobile,'.$request->id.',id';
                    $model->adminRule['email'] = 'required|regex:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/|unique:member,email,'.$request->id.',id';
                    if($request->password == ''){
                        unset($model->adminRule['password']);
                    }
                }
                $data['birthday'] = strtotime($data['birthday']);
                $validator = Validator::make($request->all(), array_merge($model->homeRule,$model->adminRule),array_merge($model->homeMessage,$model->adminMessage));
            }
            if($validator->fails()){
                foreach ($validator->errors()->all() as $message) {
                    return ['code'=>-1,'msg'=>$message];
                }
            }
            $data = filterFields($data, new Member());
            if(isset($data['id']) && $data['id'] > 0){
                $member = DB::table($this->table)->where('id','=',$data['id'])->first();
                if(isset($data['password']) && $data['password'] != ''){
                    $data['password'] = generatePassword($data['password'],$member['salt']);
                }
                $id = $data['id'];
                unset($data['id']);
                $model->where('id',$id)->update($data);
            }else{
                $data['register_time'] = time();
                $data['salt'] = generateSalt();
                $data['password'] = generatePassword($data['password'],$data['salt']);
                $data['mobile_validated'] = 1;
                $data['email_validated'] = 1;
                unset($data['id']);
                $id = $model->insertGetId($data);
            }
            return ['code'=>1, 'data'=>$id, 'msg'=>self::exec_success];
        }catch (ValidationException $e){
            return ['code'=>-1, 'data'=>'', 'msg'=>$e->getMessage()];
        }
    }

}
