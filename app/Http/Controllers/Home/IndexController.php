<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\HomeController;
class IndexController extends HomeController
{
    public function index(){
//        var_dump(auth()->guard('home')->user());die;
        return view('home.index.index');
    }
}
