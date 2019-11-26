<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
error_reporting('E_ALL^E_NOTICE');
class IndexController extends Controller
{
    //
    public function index(){
        return view('admin.index');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 登录成功后展示页面
     */
    public function welcome(){
        return view('admin.welcome');
    }
}
