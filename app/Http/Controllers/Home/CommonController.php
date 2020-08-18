<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\HomeController;
class CommonController extends HomeController
{
    public function contact(){
        return view('home.common.contact');
    }
}
