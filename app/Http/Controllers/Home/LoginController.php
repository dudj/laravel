<?php

namespace App\Http\Controllers\Home;

use App\Extensions\AuthenticatesLogout;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\HomeController;
use \Illuminate\Http\Request;

class LoginController extends HomeController
{
    use AuthenticatesUsers,AuthenticatesLogout{
        AuthenticatesLogout::logout insteadof AuthenticatesUsers;
    }
    protected $redirectTo = '/';
    public function __construct(){
//        $this->middleware('auth.home', ['except' => 'logout']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 显示登录模板
     */
    public function showLoginForm(){
        return view('home.login');
    }

    /**
     * @return mixed
     * 使用 home guard
     */
    protected function guard(){
        return auth()->guard('home');
    }

    protected function validateLogin(Request $request){
        $this->validate($request,[
            $this->username() => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha_check',
        ],[
            'captcha.required' => trans('validation.required'),
            'captcha.captcha_check' => trans('validation.captcha'),
        ]);
    }

    public function username(){
        return 'username';
    }
}
