<?php

namespace App\Http\Controllers\Admin;

use App\Models\Common\Nav;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 权限规则
     */
    public function access(Request $request){
        $menus = self::getAllMenu();
        $where = function($query) use($request){
            if ($request->has('name') and $request->name != '') {
                $search = "%" . $request->name . "%";
                $query->where('s.name', 'like', $search);
            }
        };
        $query = DB::table('access as s')
            ->leftjoin('access as p',function($join){
            $join->on('s.parent_id','=','p.id');
        });
        $data = $query
            ->select('s.*','p.name as parent_name','p.eng_name as parent_eng_name')
            ->where($where)
            ->orderBy("s.id","desc")
            ->orderBy("s.order_by" ,"asc")
            ->paginate(10);
        $data->appends([
            'name' => $request->name,
            'id' => $request->id,
        ]);
        return view('admin.rbac.access.index', [
            'data' => $data,
            'treeData' => json_encode($menus)
        ]);
    }
    public function user(Request $request){
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
            });
        $data = $query
            ->select('a.*','g.name as group_name')
            ->where($where)
            ->where('a.id','!=',1)
            ->orderBy("a.id","desc")
            ->orderBy("a.createtime" ,"desc")
            ->paginate(10);
        $data->appends([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end
        ]);
        return view('admin.rbac.user.index',['data'=>$data]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 分组
     */
    public function group(Request $request){
        $where = function($query) use($request){
            if ($request->has('name') and $request->name != '') {
                $search = "%" . $request->name . "%";
                $query->where('name', 'like', $search);
            }
            if ($request->has('start') and $request->start != '') {
                $search = strtotime($request->start);
                $query->where('createtime', '>=', $search);
            }
            if ($request->has('end') and $request->end != '') {
                $search = strtotime($request->end);
                $query->where('createtime', '<=', $search);
            }
        };
        $data = DB::table('group')->where($where)->where('id','!=',1)->orderBy("id","asc")->paginate(10);
        $data->appends([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end
        ]);
        return view('admin.rbac.group.index',['data'=>$data]);
    }

    /**
     * 监听 检测
     */
    public function loginTask(){
        $time = time() - 3600; // 删除购物车数据  1小时以前的
        DB::table("cart")->where('user_id', '=', 0)->where('add_time','<',$time)->delete();
    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * 提现设置
     */
    public function cash(Request $request){
        try{
            if($request->ajax() && $request->isMethod('post')){
                $data = [];
                foreach($request->all() as $k=>$v){
                    $data[$k] = $v;
                }
                LaravelRedisCache('cash.all',$data);
                return $this->success([],'成功');
            }
            $data = LaravelRedisCache('cash.',[]);
            return view('admin.system.cash',[
                'data' => $data
            ]);
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * 导航栏展示列表
     */
    public function navList(Request $request){
        try{
            if($request->ajax() && $request->isMethod('post')){
                if(!isset($request->sortfield)){
                    $request->sortfield = 'id';
                }
                if(!isset($request->sorttype)){
                    $request->sorttype = 'desc';
                }
                $limit = $request->limit;
                $offset = ($request->page - 1) * $limit;

                $list = DB::table('nav')->orderBy($request->sortfield, $request->sorttype)->offset($offset)->paginate($limit)->toArray();
                $data = [
                    'code' => 0,
                    'data' => $list['data'],
                    'count' => $list['total'],
                    'msg' => '查询成功'
                ];
                return response()->json($data);
            }
            return view('admin.system.navList');
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 删除导航
     */
    public function deleteNav(Request $request){
        try{

            $res = self::commonDel('nav',[['key'=>'id','relation'=>'in','val'=>$request->ids]]);
            if($res){
                return $this->success([],'删除成功');
            }else{
                return $this->error([],'数据有误');
            }
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * 添加、修改、展示
     */
    public function addEditNav(Request $request){
        try{
            $positionData = array(
                'top' => '导航顶部',
                'bottom' => '导航底部'
            );
            $data = [];
            if($request->ajax() && $request->isMethod('post')){
                $model = new Nav();
                $res = $model->checkForm($request);
                return $res;
            }elseif (isset($request->id)){
                $data = DB::table('nav')->where('id',$request->id)->get()->first();
            }
            return view('admin.system._nav',[
                'data' => $data,
                'positionData' => $positionData
            ]);
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 修改，更换状态
     */
    public function handleNav(Request $request){
        $res = $this->commonUpdate('nav', $request->all(), [['key'=>'id','relation'=>'=','val'=>$request->id]]);
        if($res){
            return $this->success([],'操作成功');
        }else{
            return $this->error([],'操作有误！');
        }
    }
}
