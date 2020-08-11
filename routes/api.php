<?php
use Illuminate\Http\Request;

Route::group(['prefix' => 'api'], function () {
    Route::post('/goods/getCategory', 'GoodsController@getCategory');
});