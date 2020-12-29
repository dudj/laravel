<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Email: dudongjiangphp@163.com
 * Date: 2020/12/29
 * Time: 13:12
 * Summary: 前台用戶退出界面
 */
namespace App\Extensions;
use Illuminate\Http\Request;
trait AuthenticatesHomeLogout{
    public function logout(Request $request){
        //\Illuminate\Auth\SessionGuard.php：
        $this->guard('home')->logout();
        $request->session()->forget($this->guard('home')->getName());
        $request->session()->regenerate();
        return redirect('/');
    }
}