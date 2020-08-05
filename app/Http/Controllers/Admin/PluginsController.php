<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


/**
 * Class PluginsController
 * Created by dudj
 * Email: dudongjiangphp@163.com
 * Date: 2020-08-04
 * Summary: 插件控制器
 */
class PluginsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * 支付插件列表
     */
    public function payment(Request $request){
        if($request->ajax() && $request->isMethod('post')) {
            $list = DB::table('plugins')->select("*")->where('type', 'payment')->paginate(20)->toArray();
            $data = [
                'code' => 0,
                'data' => $list['data'],
                'count' => $list['total'],
                'msg' => '查询成功'
            ];
            return response()->json($data);
        }
        return view('admin.plugins.payment');
    }

    /**
     * @param Request $request
     * @return mixed
     * 登录配置
     */
    public function login(Request $request){
        if($request->ajax() && $request->isMethod('post')) {
            $list = DB::table('plugins')->select("*")->where('type', 'login')->paginate(20)->toArray();
            $data = [
                'code' => 0,
                'data' => $list['data'],
                'count' => $list['total'],
                'msg' => '查询成功'
            ];
            return response()->json($data);
        }
        return view('admin.plugins.login');
    }

    /**
     * @param Request $request
     * @return mixed
     * 切换状态
     */
    public function switchStatus(Request $request){
        try{
            $model = DB::table('plugins');
            $model->where('code',$request->code);
            $res = $model->update(['status'=>$request->status]);
            if($res > 0){
                return $this->success([],'修改成功');
            }else{
                return $this->error([],'修改失败');
            }
        }catch (\Exception $exception){
            return $this->error([],$exception->getMessage());
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * 设置
     */
    public function setting(Request $request){
        try{
            if($request->ajax() && $request->isMethod('post')) {
                $model = DB::table('plugins');
                $config = $request->config;
                if($request->code == 'alipaynew'){
                    $config['app_rsa_private_key'] = str_replace(PHP_EOL, '', $config['app_rsa_private_key']);
                    $config['alipay_rsa_public_key'] = str_replace(PHP_EOL, '', $config['alipay_rsa_public_key']);
                }
                if($config){
                    $config = serialize($config);
                }
                $res = $model->where('code',$request->code)->where('type',$request->type)->update(['config_value'=>$config]);
                if($res > 0){
                    return $this->success([],'修改成功');
                }else{
                    return $this->error([],'修改失败');
                }
            }
            $data = DB::table('plugins')->where('code',$request->code)->where('type',$request->type)->get()->first();
            $data['config'] = unserialize($data['config']);
            $data['config_value'] = unserialize($data['config_value']);
            return view('admin.plugins._setting',[
                'data' => $data
            ]);
        }catch (\Exception $exception){
            return $this->error([],$exception->getMessage());
        }
    }
}
