<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'auth.admin'], function () {
        //经过路由验证的方法
        Route::get('/', 'Admin\IndexController@index');
        //分组路由开始
        Route::get('/system/group', 'Admin\SystemController@group');
        Route::get('/group/add', 'Admin\GroupController@add');
        Route::post('/group/store', 'Admin\GroupController@store');
        Route::post('/group/change_status', 'Admin\GroupController@change_status');//改变状态
        Route::post('/group/del', 'Admin\GroupController@del');//删除角色
        Route::get('/group/edit', 'Admin\GroupController@edit');//编辑角色
        Route::post('/group/update', 'Admin\GroupController@update');//编辑角色
        //分组路由结束
        //权限开始
        Route::get('/system/access', 'Admin\SystemController@access');
        Route::get('/access/add', 'Admin\AccessController@add');
        Route::post('/access/store', 'Admin\AccessController@store');
        Route::get('/access/edit', 'Admin\AccessController@edit');
        Route::post('/access/update', 'Admin\AccessController@update');
        Route::post('/access/del', 'Admin\AccessController@del');
        //权限结束
        //会员开始
        Route::get('/system/member', 'Admin\SystemController@member');
        Route::get('/member/add', 'Admin\MemberController@add');
        Route::post('/member/store', 'Admin\MemberController@store');
        Route::post('/member/change_status', 'Admin\MemberController@change_status');//改变状态
        Route::post('/member/del', 'Admin\MemberController@del');//删除
        Route::get('/member/edit', 'Admin\MemberController@edit');//编辑
        Route::get('/member/index_json', 'Admin\MemberController@indexJson');
        Route::post('/member/update', 'Admin\MemberController@update');//编辑
        //欢迎页
        Route::get('welcome', 'Admin\IndexController@welcome')->name('admin.welcome');
        //用户信息
        Route::get('user_info', 'Admin\UserController@info')->name('admin.user_info');
    });
    //登录路由相关
    Route::get('login', 'Admin\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Admin\LoginController@login');
    Route::get('logout', 'Admin\LoginController@logout');
    //公共页面方法路由
    Route::get('/common/unicode', 'Admin\CommonController@unicode');
    Route::get('/common/deny', 'Admin\CommonController@deny');
    Route::get('/common/errors', 'Admin\CommonController@errors');
    Route::get('/common/clear', 'Admin\CommonController@clear');
    Route::post('/common/upload_img', 'Admin\CommonController@uploadImg');

});