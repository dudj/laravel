<?php

namespace App\Http\Controllers\Admin;

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
            ->paginate(5);
        $data->appends([
            'name' => $request->name,
            'id' => $request->id,
        ]);
        return view('admin.rbac.access.index', [
            'data' => $data,
            'treeData' => json_encode($menus)
        ]);
    }
    public function member(Request $request){
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
            ->paginate(5);
        $data->appends([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end
        ]);
        return view('admin.rbac.member.index',['data'=>$data]);
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
        $data = DB::table('group')->where($where)->where('id','!=',1)->orderBy("id","asc")->paginate(1);
        $data->appends([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end
        ]);
        return view('admin.rbac.group.index',['data'=>$data]);
    }
}
