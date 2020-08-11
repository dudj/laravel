<?php
namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class Nav
 * Created by PHP strom
 * User: dudj
 * Email: dudongjiangphp@163.com
 * Date: 2020-08-11
 * Summary: 导航
 */
class Nav extends Model
{
    const UPDATED_AT = null;
    // 数据信息
    protected $data = [];
    protected $table = 'nav';
    const exec_success = '执行成功';
    const exec_fail = '执行失败';
    // 验证规则
    public $rule = [
        'name' => 'required|min:2|max:10|unique:nav',
        'url' => ['required','unique:nav'],
        'position' => 'required',
        'sort' => 'required',
    ];
    //错误信息
    protected $message = [
        'name.required' => '名称必填',
        'name.min' => '名称长度至少6个字符',
        'name.max' => '名称长度至多16个字符',
        'name.unique' => '名称重复',
        'url.required' => '链接必填',
        'position.required' => '位置必填',
        'sort.required' => '序号必填',
    ];
    /**
     * @param $request
     * @return array
     * 验证表单提交的内容 增加和修改均需要验证 因此作为公共方法
     */
    public function checkForm($request){
        try{
            $model = new Nav();
            if($request->id){
                $model->rule['name'] = 'required|min:2|max:10|unique:nav,name,'.$request->id.',id';
                $model->rule['url'] = 'required|unique:nav,url,'.$request->id.',id';
            }
            $validator = Validator::make($request->all(), $model->rule,$model->message);
            if($validator->fails()){
                foreach ($validator->errors()->all() as $message) {
                    return ['code'=>-1,'msg'=>$message];
                }
            }
            $data = $request->all();
            $data = filterFields($data, new Nav());
            if(isset($data['id']) && $data['id'] > 0){
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
