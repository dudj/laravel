<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Access;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccessController extends Controller
{
    public $table = 'access';
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 添加
     */
    public function add(){
        $treeData = self::getAllMenu();
        return view('admin.rbac.access.add',[
            'treeData' => json_encode($treeData)
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * 保存
     */
    public function store(Request $request){
        try{
            $res = Access::checkForm($request);
            Log::info('保存验证数据：',$res);
            if($res['code'] < 0){
                return $this->error([],$res['msg']);
            }
            $data = $request->all();
            unset($data['parent_name']);
            $data['icon'] = '&'.$data['icon'];
            $res = $this->insert($this->table, $data);
            if($res){
                return $this->success([],$res);
            }
        }catch (Exception $e){
            Log::error('权限规则保存失败：'+$e->getMessage());
            return $this->error([],$e->getMessage());
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 渲染编辑页面
     */
    public function edit(Request $request){
        $treeData = self::getAllMenu();
        $id = $request->get('id');
        $info = DB::table($this->table.' as s')
            ->leftjoin($this->table.' as p',function($join){
                $join->on('s.parent_id','=','p.id');
            })
            ->select('s.*','p.name as parent_name','p.eng_name as parent_eng_name')
            ->where('s.id','=',$id)
            ->first();
        $info['icon'] = str_replace("&","",$info['icon']);
        return view('admin.rbac.access.edit',[
            'treeData'=>json_encode($treeData),
            'info'=>$info
        ]);
    }
    /**
     * @param Request $request
     * @return mixed
     * 保存
     */
    public function update(Request $request){
        try{
            $res = Access::checkForm($request);
            Log::info('修改验证数据：',$res);
            if($res['code'] < 0){
                return $this->error([],$res['msg']);
            }
            $data = $request->all();
            unset($data['parent_name']);
            $data['icon'] = '&'.$data['icon'];
            $res = $this->commonUpdate($this->table, $data, [['key'=>'id','relation'=>'=','val'=>$data['id']]]);
            if($res){
                return $this->success([],$res);
            }
        }catch (Exception $e){
            Log::error('权限规则保存失败：'+$e->getMessage());
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
            //判断不能随意删除
            $id = $request->get('id');
            $type = $request->get('type');
            $data = Access::checkIsSonNode($id,$type);
            if($data){
                return $this->error([],'请先删除子节点');
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
