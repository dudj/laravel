<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\HomeController;
class MemberController extends HomeController
{
    public function center(){
        return view('home.member.center');
    }
}
