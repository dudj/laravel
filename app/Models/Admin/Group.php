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
 *
 * laravel不支持 [ 'type', 'in', '1,2,3']
 */
class Group extends Model
{
    protected $table = 'group';
    const exec_success = '执行成功';
    const exec_fail = '执行失败';
    /**
     * @param $group_id
     * @param $type
     * @return mixed
     */
    public static function checkGroupIsAdmin($group_id,$type='one'){
        $query = DB::table('admins')->leftjoin('group',function($join){
            $join->on('group.id','=','admins.group_id');
        })
            ->where('group.status','=',1);
        if($type == 'one'){
            $query->where('group.id','=',$group_id);
        }elseif($type == 'more'){
            $query->whereIn('group.id',[$group_id]);
        }
        $data = $query
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * @param $group_id
     * @param $status
     * @return string
     * 查找角色 并修改
     */
    public static function findGroupAndUpdate($group_id,$status){
        $data = self::checkGroupIsAdmin($group_id);
        if($data){
            return ['code'=>-1,'msg'=>'此角色已经被分配不允许停用'];
        }
        $res = DB::table('group')
            ->where('id','=',$group_id)
            ->update(['status' => $status]);
        if($res){
            return ['code'=>1,'msg'=>self::exec_success];
        }
        return ['code'=>-1,'msg'=>self::exec_fail];
    }

    /**
     * @param $request
     * @return array
     * 验证表单提交的内容 增加和修改均需要验证 因此作为公共方法
     */
    public static function checkForm($request,$type='insert'){
        $messages = [
            'required' => ':attribute 的字段是必要的。',
            'unique' => ':attribute 的字段的值已经存在。',
        ];
        if($type !== 'insert'){
            //update过程中 当前信息不在验证中
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:group,name,'.$request->get('id').',id|max:255',
                'nodestr' => 'required',
                'description' => 'required',
            ],$messages);
        }else{
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:group|max:255',
                'nodestr' => 'required',
                'description' => 'required',
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