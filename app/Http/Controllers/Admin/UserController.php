<?php

namespace App\Http\Controllers\Admin;

use App\Extensions\AuthenticatesLogout;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function info(){
        return view('admin.user_info');
    }
}
