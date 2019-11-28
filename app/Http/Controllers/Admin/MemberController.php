<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Admins;
use App\Models\Admin\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

class MemberController extends Controller
{
    public $table = 'admins';
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 添加
     */
    public function add(){
        $groupData = DB::table('group')->where('id','!=',1)->where('status','=',1)->get()->toArray();
        return view('admin.rbac.member.add',[
            'groupData' => $groupData
        ]);
    }
    /**
     * @param Request $request
     * @return string
     * 动态列表
     */
    public function indexJson(Request $request){
        $where = function($query) use($request){
            if ($request->has('name') and $request->name != '') {
                $search = "%" . $request->name . "%";
                $query->where('a.name', 'like', $search);
            }
            if ($request->has('start') and $request->start != '') {
                $search = strtotime($request->start);
                $query->where('a.createtime', '>=', $search);
            }
            if ($request->has('end') and $request->end != '') {
                $search = strtotime($request->end);
                $query->where('a.createtime', '<=', $search);
            }
        };
        $query = DB::table('admins as a')
            ->leftjoin('group as g',function($join){
                $join->on('a.group_id','=','g.id');
            })
            ->select('a.*','g.name as group_name')
            ->where($where)
            ->where('a.id','!=',1);
        $offset = ($request->get('page') - 1) * $request->get('limit');
        $data = $query
            ->orderBy("a.id","desc")
            ->orderBy("a.createtime" ,"desc")
            ->offset($offset)
            ->limit($request->get('limit'))
            ->get();
        return json_encode([
            'code'=>0,
            'success' => true,
            'count' => $query->count(),
            'data'=>$data
        ]);
    }
    /**
     * @param Request $request
     * @return mixed
     * 保存数据
     */
    public function store(Request $request){
        try{
            $res = Admins::checkForm($request);
            if($res['code'] < 0){
                return $this->error([],$res['msg']);
            }
            $data = $request->all();
            $data['salt'] = generateSalt();
            $data['password'] = generatePassword($data['repass'],$data['salt']);
            unset($data['repass']);
            $data['createtime'] = time();
            $data['updatetime'] = time();
            $data['status'] = 1;
            $res = $this->insert('admins',$data);
            if($res){
                return $this->success([],$res);
            }
        }catch (Exception $e){
            return $this->error([],$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 渲染编辑页面
     */
    public function edit(Request $request){
        $groupData = DB::table('group')->where('id','!=',1)->where('status','=',1)->get()->toArray();
        $id = $request->get('id');
        $info = DB::table($this->table)->where('id','=',$id)->first();
        return view('admin.rbac.member.edit',[
            'groupData' => $groupData,
            'info' => $info
        ]);
    }
    public function update(Request $request){
        try{
            $res = Admins::checkForm($request,'update');
            if($res['code'] < 0){
                return $this->error([],$res['msg']);
            }
            $data = [
                'updatetime' => time(),
            ];
            $data = $request->all();
            if($data['password'] == ""){
                unset($data['password']);
            }
            $data['updatetime'] = time();
            $res = $this->commonUpdate($this->table, $data, [['key'=>'id','relation'=>'=','val'=>$request->get('id')]]);
            if($res){
                return $this->success([],$res);
            }
        }catch (Exception $e){
            return $this->error([],$e->getMessage());
        }
    }
    /**
     * @param Request $request
     * @return mixed
     * 改变状态
     */
    public function del(Request $request){
        try{
            $id = $request->get('id');
            $type = $request->get('type');
            $res = false;
            if($type == 'one'){
                $res = self::commonDel($this->table,[['key'=>'id','relation'=>'=','val'=>$id]]);
            }else if($type='more'){
                $res = self::commonDel($this->table,[['key'=>'id','relation'=>'in','val'=>$id]]);
            }
            if($res){
                return $this->success([],'删除成功');
            }
            return $this->error([],$res['msg']);
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }
}
