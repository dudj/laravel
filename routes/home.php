<?php
/*
    前台页面路由注册
*/
Route::group(['prefix' => '/'], function () {
    Route::group(['middleware' => 'auth.home'], function () {
        Route::get('/member/center', 'MemberController@center');
    });
    Route::get('/', 'Home\IndexController@index');
    Route::get('/index/index', 'Home\IndexController@index');
    Route::get('/common/contact', 'Home\CommonController@contact');
});


