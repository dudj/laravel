<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
class AuthAdmin
{
    public $modules,$controller,$method;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guard('admin')->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }else{
                return redirect()->guest('admin/login');
            }
        }
        if(!session('menu')){
            session(['menu' => serialize(self::getMenu())]);
        }
        $res = self::checkNode(getTemplatePath());
        if(!$res){
            return redirect()->guest('admin/common/deny');
        }
        return $next($request);
    }

    /**
     * @param $nodeUrl
     * @return bool
     * 验证权限节点
     * add和edit的权限拥有之后 store和update也有 反之
     */
    protected function checkNode($nodeUrl){
        if(empty($nodeUrl)){
            return true;
        }
        $nodeArr = explode('/',$nodeUrl);
        $data = DB::table('access')->select('id')->where(['controller'=>$nodeArr[1],'method'=>$nodeArr[2]])->first();
        $nodestr = session('nodestr');
        $nodestr_arr = explode(',', $nodestr);
        if(!in_array($data['id'], $nodestr_arr) && ($nodeArr[1] <> 'index' && $nodeArr[2] <> 'index') && $nodestr != 'all' && !in_array($nodeArr[1],getCommonController()) && !in_array($nodeArr[2],getCommonMethod())){
            return false;
        }
        return true;
    }
    //用到左侧菜单
    protected function getMenu(){
        $info = DB::table('group')->leftjoin('admins',function($join){
            $join->on('group.id','=','admins.group_id')
                ->where('group.status','=',1);
        })
            ->select('admins.*','group.name','group.nodestr','group.isall')
            ->where('admins.id','=',auth()->guard('admin')->user()->id)
            ->first();
        session(['nodestr'=>$info['nodestr']]);
        if($info['nodestr'] == 'all' && $info['isall'] == 1){
            $nodeData = DB::table('access')->where('parent_id','=',0)->orderBy('order_by')->get()->toArray();
        }else{
            $nodeData = DB::table('access')->where('parent_id','=',0)->where('id','in',$info['nodestr'])->orderBy('order_by')->get()->toArray();
        }
        self::getMenuSon($info,$nodeData);
        return $nodeData;
    }
    protected function getMenuSon($info,&$nodeData){
        foreach ($nodeData as $key => $value) {
            $query = DB::table('access');
            if($info['nodestr'] <> 'all'){
                $query->where('id','in',$info['nodestr']);
            }
            $nodeDataSon = $query->where('parent_id','=',$value['id'])->where('type','=',1)->orderBy('order_by')->get()->toArray();
            $this->getMenuSon($info,$nodeDataSon);
            $nodeData[$key]['list'] = $nodeDataSon;
        }
        return $nodeData;
    }
}
