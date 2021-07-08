<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\HomeController;
class CommonController extends HomeController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact(){
        return view('home.common.contact');
    }
}
