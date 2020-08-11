<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


/**
 * Class IntegralController
 * Created by dudj
 * Email: dudongjiangphp@163.com
 * Date: 2020-08-05
 * Summary: 积分相关操作
 */
class IntegralController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View|mixed
     * 积分操作修改
     */
    public function index(Request $request){
        try{
            if(!isset($request->inc_type)){
                $request->inc_type = 'integral';
            }
            if($request->ajax() && $request->isMethod('post')){
                $param = $request->all();
                $list[] = ['name'=>'is_integral_expired','value'=>$param['is_integral_expired'],'inc_type'=>"{$param['inc_type']}"];
                if($param['is_integral_expired'] == 2) {
                    $expired_time = $param['month'].",".$param['day'];
                    $list[] = ['name'=>'expired_time','value'=>$expired_time,'inc_type'=>"{$param['inc_type']}"];
                }
                $param['is_not_integral'] = (isset($param['is_not_integral']) &&  $param['is_not_integral'] == '0') ? '0' : '1';
                $list[] = ['name' => 'is_not_integral', 'value' => $param['is_not_integral'], 'inc_type' => "{$param['inc_type']}"];
                $list[] = ['name' => 'is_reg_integral', 'value' => $param['is_reg_integral'], 'inc_type' => "{$param['inc_type']}"];
                $list[] = ['name' => 'reg_integral', 'value' => $param['reg_integral'], 'inc_type' => "{$param['inc_type']}"];
                $list[] = ['name' => 'invite', 'value' => $param['invite'], 'inc_type' => "{$param['inc_type']}"];
                $list[] = ['name' => 'invite_integral', 'value' => $param['invite_integral'], 'inc_type' => "{$param['inc_type']}"];
                $list[] = ['name' => 'invited_integral', 'value' => $param['invited_integral'], 'inc_type' => "{$param['inc_type']}"];
                $list[] = ['name' => 'is_point_min_limit', 'value' => $param['is_point_min_limit'], 'inc_type' => "{$param['inc_type']}"];
                $list[] = ['name' => 'point_min_limit', 'value' => $param['point_min_limit'], 'inc_type' => "{$param['inc_type']}"];
                $list[] = ['name' => 'is_point_rate', 'value' => $param['is_point_rate'], 'inc_type' => "{$param['inc_type']}"];
                $list[] = ['name' => 'point_rate', 'value' => $param['point_rate'], 'inc_type' => "{$param['inc_type']}"];
                foreach($list as $key=>$val){
                    $confInfo = Db::table("config")->where("name",$val['name'])->where("inc_type",$param['inc_type'])->get()->first();
                    if(!empty($confInfo)){
                        DB::table("config")->where("id",$confInfo['id'])->update($list[$key]);
                    }else{
                        DB::table("config")->insert($val);
                    }
                }
                return $this->success([],'更新数据成功');
            }
            $data = DB::table('config')->where('inc_type',$request->inc_type)->get()->toArray();
            $arr = [];
            foreach($data as $val){
                $arr[$val['name']] = $val['value'];
            }
            return view('admin.integral.index',[
                'data' => $arr
            ]);
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }

    /**
     * @return mixed
     * 清除所有用户的积分，一般不建议清除
     */
    public function clear(){
        try{
            //防止用户过多，导致时间过长
            set_time_limit(0);
            $data = DB::table('member')->where('pay_points','>',0)->select('id','pay_points')->get()->toArray();
            if($data){
                foreach ($data as $k=>$v)
                {
                    accountLog($v['id'], 0, -$v['pay_points'], '系统执行积分清零');
                }
//                adminLog('管理员手动清零积分成功');
            }
            ini_set("max_execution_time",30);
            return $this->success([],'积分清零成功');
        }catch (\Exception $exception){
            return $this->error([],$exception->getMessage());
        }
    }
}
