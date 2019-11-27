<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

DB::connection()->enableQueryLog();

/**
 * Class Controller
 * @package App\Http\Controllers
 * DB::getQueryLog()
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    //用于分配权限 将所有的节点查询出来
    public static function getAllMenu(){
        $nodeData = DB::table('access')->where('parent_id','=',0)->orderBy('order_by')->get()->toArray();
        self::getAllMenuSon($nodeData);
        return $nodeData;
    }
    public static function getAllMenuSon(&$nodeData){
        foreach ($nodeData as $key => $value) {
            $query = DB::table('access');
            $nodeDataSon = $query->where('parent_id','=',$value['id'])->orderBy('order_by')->get()->toArray();
            self::getAllMenuSon($nodeDataSon);
            $nodeData[$key]['list'] = $nodeDataSon;
        }
        return $nodeData;
    }

    /**
     * @param $data
     * @param $msg
     * @return mixed
     * json数据 公共处理
     */
    public function success($data,$msg='ok'){
        $res = [
            'code' => 1,
            'data' => $data,
            'msg' => $msg
        ];
        return response()->json($res);
    }
    /**
     * @param $data
     * @param $msg
     * @return mixed
     * json数据 公共处理
     */
    public function error($data,$msg){
        $res = [
            'code' => -1,
            'data' => $data,
            'msg' => $msg
        ];
        return response()->json($res);
    }

    /**
     * @param $table
     * @param $data
     * @param bool $isId
     * @return mixed
     * 公共添加方法 isId为true时返回新插入的id值 不为true返回插入结果集
     */
    public function insert($table,$data,$isId = false){
        if($isId){
            $res = DB::table($table)
                    ->insertGetId($data);
        }else{
            $res = DB::table($table)
                ->insert($data);
        }
        return $res;
    }

    /**
     * @param $table
     * @param $data
     * @param $where
     * @return mixed
     * 公共更新数据
     * $where = [['key'=>'id','relation'=>'=','val'=>$request->get('id')]]
     */
    public function commonUpdate($table,$data,$where){
        $model = DB::table($table);
        for($i = 0;$i<count($where);$i++){
            $model->where($where[$i]['key'], $where[$i]['relation'], $where[$i]['val']);
        }
        $res = $model
            ->update($data);
        return $res;
    }
    /**
     * @param $table
     * @param $where
     * @return mixed
     * 公共删除数据
     * [['key'=>'id','relation'=>'=','val'=>$request->get('id')]]
     */
    public function commonDel($table,$where){
        $model = DB::table($table);
        for($i = 0;$i<count($where);$i++){
            if($where[$i]['relation'] == 'in'){
                $model->whereIn($where[$i]['key'], explode(',',$where[$i]['val']));
            }else if($where[$i]['relation'] == 'like'){
                $model->where($where[$i]['key'], $where[$i]['relation'], '%'.$where[$i]['val'].'%');
            }else{
                $model->where($where[$i]['key'], $where[$i]['relation'], $where[$i]['val']);
            }
        }
        $res = $model->delete();
        Log::info('删除的SQL：',DB::getQueryLog());
        return $res;
    }
    /**
     * @param $data
     * @param $id
     * @return mixed
     * 将树结构加入默认数据
     */
    /*public function treeDataDefault(&$data,$id){
        foreach($data as $key=>$val){
            if($val['id'] == $id){
                $data[$key]['checked'] = true;
                break;
            }
            if(isset($val['list']) && $val['list']){
                self::treeDataDefault($val['list'],$id);
            }
        }
        return $data;
    }*/
}
