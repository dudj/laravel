<?php
/*
    前台页面路由注册
*/

Route::group(['prefix' => '/'], function () {
    Route::group(['middleware' => 'auth.home'], function () {
        Route::get('/member/center', 'MemberController@center');
    });
    Route::get('', 'IndexController@index');
    Route::get('login', 'LoginController@showLoginForm');
    Route::post('login', 'LoginController@login');
    Route::get('/common/contact', 'CommonController@contact');
    Route::get('/index/index', 'IndexController@index');
});
