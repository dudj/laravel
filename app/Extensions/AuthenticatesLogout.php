<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Email: dudongjiangphp@163.com
 * Date: 2019/9/18
 * Time: 18:47
 * Summary: ${summary}
 */
namespace App\Extensions;
use Illuminate\Http\Request;
trait AuthenticatesLogout{
    public function logout(Request $request){
        //\Illuminate\Auth\SessionGuard.php：
        $this->guard()->logout();

        $request->session()->forget($this->guard()->getName());

        $request->session()->forget('menu');

        $request->session()->regenerate();

        return redirect('/admin/login');
    }
}