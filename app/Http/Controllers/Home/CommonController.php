<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
class CommonController extends Controller
{
    public function contact(){
        return view('home.common.contact');
    }
}
