<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

class GroupController extends Controller
{
    public $table = 'group';
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 添加
     */
    public function add(){
        $menus = self::getAllMenu();
        return view('admin.rbac.group.add', [
            'menus'=>$menus
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * 保存数据
     */
    public function store(Request $request){
        try{
            $res = Group::checkForm($request);
            if($res['code'] < 0){
                return $this->error([],$res['msg']);
            }
            $data = $request->all();
            $data['createtime'] = time();
            $data['updatetime'] = time();
            $data['status'] = 1;
            if($data['isall'] == 1){
                $data['nodestr'] = 'all';
            }
            $res = $this->insert('group',$data);
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
    public function change_status(Request $request){
        try{
            $id = $request->get('id');
            $status = $request->get('status');
            $res = Group::findGroupAndUpdate($id,$status);
            if($res['code'] == 1){
                return $this->success([],$res['msg']);
            }
            return $this->error([],$res['msg']);
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 渲染编辑页面
     */
    public function edit(Request $request){
        $menus = self::getAllMenu();
        $id = $request->get('id');
        $info = DB::table($this->table)->where('id','=',$id)->first();
        return view('admin.rbac.group.edit', [
            'menus'=>$menus,
            'info'=>$info
        ]);
    }
    public function update(Request $request){
        try{
            $res = Group::checkForm($request,'update');
            if($res['code'] < 0){
                return $this->error([],$res['msg']);
            }
            $data = [
                'updatetime' => time(),
            ];
            $data = $request->all();
            $data['updatetime'] = time();
            if($data['isall'] == 1){
                $data['nodestr'] = 'all';
            }
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
            $data = Group::checkGroupIsAdmin($id,$type);
            if($data){
                return ['code'=>-1,'msg'=>'此角色已经被分配不允许停用'];
            }
            $res = false;
            if($type == 'one'){
                $res = self::commonDel($this->table,[['key'=>'id','relation'=>'=','val'=>$id]]);
            }else if($type='more'){
                $res = self::commonDel($this->table,[['key'=>'id','relation'=>'in','val'=>$id]]);
            }
            if($res){
                return $this->success([],'删除成功');
            }
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }
}
