<?php

namespace App\Http\Controllers\Admin;

use App\Extensions\AuthenticatesLogout;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    use AuthenticatesUsers,AuthenticatesLogout{
        AuthenticatesLogout::logout insteadof AuthenticatesUsers;
    }
    protected $redirectTo = '/admin';
    public function __construct(){
        $this->middleware('guest.admin', ['except' => 'logout']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 显示后台登录模板
     */
    public function showLoginForm(){
        return view('admin.login');
    }

    /**
     * @return mixed
     * 使用 admin guard
     */
    protected function guard(){
        return auth()->guard('admin');
    }

    /**
     * @return string
     * 重写验证时使用的用户名字段
     */
    public function username(){
        return 'name';
    }
}
