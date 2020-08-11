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

//前台路由-测试
Route::get('/', 'Home\IndexController@index');
Route::get('/index/index', 'Home\IndexController@index');
Route::get('/common/contact', 'Home\CommonController@contact');


