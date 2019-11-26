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
class Access extends Model
{
    protected $table = 'access';
    const exec_success = '执行成功';
    const exec_fail = '执行失败';
    /**
     * @param $request
     * @param $type
     * @return array
     * 验证表单提交的内容 增加和修改均需要验证 因此作为公共方法
     */
    public static function checkForm($request,$type='insert'){
        $model = new Access();
        $messages = [
            'required' => ':attribute 的字段是必要的。',
            'unique' => ':attribute 的字段的值已经存在。',
        ];
        if($type !== 'insert'){
            //update过程中 当前信息不在验证中
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:'.$model->getTable().',name,'.$request->get('id').',id|max:255',
                'eng_name' => 'required',
                'controller' => 'required',
                'method' => 'required',
                'parent_id' => 'required',
                'order_by' => 'required',
                'type' => 'required',
                'icon' => 'required'
            ],$messages);
        }else{
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:'.$model->getTable().'|max:255',
                'eng_name' => 'required',
                'controller' => 'required',
                'method' => 'required',
                'parent_id' => 'required',
                'order_by' => 'required',
                'type' => 'required',
                'icon' => 'required'
            ],$messages);
        }
        if($validator->fails()){
            foreach ($validator->errors()->all() as $message) {
                return ['code'=>-1,'msg'=>$message];
            }
        }
        return ['code'=>1,'msg'=>self::exec_success];
    }

    /**
     * @param $id
     * @param string $type
     * @return mixed
     * 检测当前信息是否拥有子节点 有不允许删除
     */
    public static function checkIsSonNode($id,$type='one'){
        $query = DB::table('access');
        if($type == 'one'){
            $query->where('parent_id','=',$id);
        }elseif($type == 'more'){
            $query->whereIn('parent_id',[$id]);
        }
        $data = $query
            ->get()
            ->toArray();
        return $data;
    }
}