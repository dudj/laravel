<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\HomeController;
use App\Models\Common\Member;
use Illuminate\Http\Request;

class RegisterController extends HomeController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 展示注册页面
     */
    public function index(){
        return view('home.register');
    }
    public function register(Request $request){
        $model = new Member();
        $res = $model->checkForm($request, 'home');
        if($res['code']>0){
            return $this->success($res['data']);
        }else{
            return $this->error('',$res['msg']);
        }
    }
}
