<?php
/*
    前台页面路由注册
    驼峰命名
*/

Route::pattern('id', '\d+');

Route::group(['prefix' => '/'], function () {
    Route::group(['middleware' => 'auth.home'], function () {
        Route::get('/member/center', 'MemberController@center');
        Route::any('/purchase/cart', 'PurchaseController@cart');
    });
    /*Route::get('/purchase/goodDetail/{id}',function($id){
        echo "Hello World!".$id;
    });*/
    Route::get('/purchase/goodDetail/{id}', 'PurchaseController@goodDetail');
    Route::get('', 'IndexController@index');
    Route::get('login', 'LoginController@showLoginForm')->name('home.login');
    Route::post('login', 'LoginController@login');
    Route::get('register', 'RegisterController@index');
    Route::post('register', 'RegisterController@register');
    Route::get('logout', 'LoginController@logout');
    Route::get('/common/contact', 'CommonController@contact');
    Route::get('/index/index', 'IndexController@index');
});