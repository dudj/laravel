<?php

namespace App\Http\Controllers\Home;

use App\Extensions\AuthenticatesHomeLogout;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\HomeController;
use \Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends HomeController
{
    use AuthenticatesUsers,AuthenticatesHomeLogout{
        AuthenticatesHomeLogout::logout insteadof AuthenticatesUsers;
    }
    protected $redirectTo = '/';
    public function __construct(){
        $this->middleware('guest.home');
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
            'captcha' => 'required|captcha',
        ],[
            'captcha.required' => trans('validation.required'),
            'captcha.captcha' => trans('validation.captcha'),
        ]);
    }

    public function username(){
        return 'username';
    }
}
