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
}
