<?php
use Illuminate\Http\Request;

//后台路由 change 开头的方法均为post请求
Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'auth.admin'], function () {
        //经过路由验证的方法
        Route::get('/', 'IndexController@index');
        Route::get('/right_menu', 'IndexController@rightMenu');
        //分组路由开始
        Route::get('/system/group', 'SystemController@group');
        Route::get('/group/add', 'GroupController@add');
        Route::post('/group/store', 'GroupController@store');
        Route::post('/group/change_status', 'GroupController@change_status');//改变状态
        Route::post('/group/del', 'GroupController@del');//删除角色
        Route::get('/group/edit', 'GroupController@edit');//编辑角色
        Route::post('/group/update', 'GroupController@update');//编辑角色
        //分组路由结束
        //设置
        Route::any('/system/cash', 'SystemController@cash');//提现设置
        Route::any('/member/signRules', 'MemberController@signRules');//积分规则设定
        //权限开始
        Route::get('/system/access', 'SystemController@access');
        Route::get('/access/add', 'AccessController@add');
        Route::post('/access/store', 'AccessController@store');
        Route::get('/access/edit', 'AccessController@edit');
        Route::post('/access/update', 'AccessController@update');
        Route::post('/access/del', 'AccessController@del');
        //权限结束
        //管理员开始
        Route::get('/system/user', 'SystemController@user');
        Route::get('/user/add', 'UserController@add');
        Route::post('/user/store', 'UserController@store');
        Route::post('/user/change_status', 'UserController@change_status');//改变状态
        Route::post('/user/del', 'UserController@del');//删除
        Route::get('/user/edit', 'UserController@edit');//编辑
        Route::get('/user/index_json', 'UserController@indexJson');
        Route::post('/user/update', 'UserController@update');//编辑
        //欢迎页
        Route::get('welcome', 'IndexController@welcome');
        //商品
        Route::get('/goods/goodsList', 'GoodsController@goodsList');
        Route::post('/goods/ajaxGoodsList', 'GoodsController@ajaxGoodsList');
        Route::post('/goods/ajaxUpdate', 'GoodsController@ajaxUpdate');
        Route::get('/goods/addEditGoods', 'GoodsController@addEditGoods');
        Route::get('/goods/delGoodsImages', 'GoodsController@delGoodsImages');
        Route::post('/goods/save', 'GoodsController@save');
        Route::get('/goods/deleteGoods', 'GoodsController@deleteGoods');
        //品牌
        Route::get('/goods/brandList', 'GoodsController@brandList');
        Route::post('/goods/ajaxBrandList', 'GoodsController@ajaxBrandList');
        Route::post('/goods/changeBrand', 'GoodsController@changeBrand');
        Route::any('/goods/addEditBrand', 'GoodsController@addEditBrand');
        Route::get('/goods/deleteBrand', 'GoodsController@deleteBrand');
        //类别
        Route::get('/goods/categoryList', 'GoodsController@categoryList');
        Route::get('/goods/ajaxCategoryList', 'GoodsController@ajaxCategoryList');
        Route::post('/goods/changeCategory', 'GoodsController@changeCategory');
        Route::get('/goods/deleteCategory', 'GoodsController@deleteCategory');
        Route::any('/goods/addEditCategory', 'GoodsController@addEditCategory');
        Route::get('/goods/ajaxTreeSelectCategoryList', 'GoodsController@ajaxTreeSelectCategoryList');
        //上传
        Route::get('/uploadify/upload', 'UploadifyController@upload');
        Route::post('/uploadify/preview', 'UploadifyController@preview');
        Route::get('/uploadify/fileList', 'UploadifyController@fileList');
        Route::get('/uploadify/delupload', 'UploadifyController@delupload');
        //Ueditor
        Route::get('/ueditor/index', 'UeditorController@index');
        Route::any('/ueditor/imageUp', 'UeditorController@imageUp');
        Route::any('/ueditor/videoUp', 'UeditorController@videoUp');
        //监听
        Route::any('/system/loginTask', 'SystemController@loginTask');
        //会员
        Route::get('/member/list', 'MemberController@indexList');//列表
        Route::any('/member/ajaxList', 'MemberController@ajaxList');//ajax返回列表数据
        Route::any('/member/addMember', 'MemberController@addMember');//添加
        Route::post('/member/editMember', 'MemberController@editMember');//修改
        Route::get('/member/detail', 'MemberController@detail');//详情，可编辑页面
        Route::any('/member/accountLog', 'MemberController@accountLog');//会员资金详情
        Route::any('/member/editAccount', 'MemberController@editAccount');//会员资金编辑添加
        Route::any('/member/address', 'MemberController@address');//会员收货地址
        Route::get('/member/levelList', 'MemberController@levelList');//会员等级列表
        Route::any('/member/addEditMemberLevel', 'MemberController@addEditMemberLevel');//添加修改会员等级
        Route::any('/member/deleteMemberLevel', 'MemberController@deleteMemberLevel');//删除会员等级
        Route::any('/member/signList', 'MemberController@signList');//会员签到列表
        Route::any('/member/rechargeList', 'MemberController@rechargeList');//充值记录列表
        Route::any('/member/withdrawalsList', 'MemberController@withdrawalsList');//提现记录列表
        Route::post('/member/withdrawalsEdit', 'MemberController@withdrawalsEdit');//提现记录修改审核状态
        //插件
        Route::any('/plugins/payment', 'PluginsController@payment');//支付插件列表
        Route::any('/plugins/login', 'PluginsController@login');//快捷登录插件列表
        Route::post('/plugins/switchStatus', 'PluginsController@switchStatus');//改变插件的状态
        Route::any('/plugins/setting', 'PluginsController@setting');//设置
        //系统设置-积分兑换
        Route::any('/integral/index', 'IntegralController@index');//index和其它不同
        Route::post('/integral/clear', 'IntegralController@clear');//清除所有会员积分
    });
    //登录路由相关
    Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout');
    Route::get('relogin',function (){
        echo "<script>parent.location.href='/admin/login';</script>";
    });
    //公共页面方法路由
    Route::get('/common/unicode', 'CommonController@unicode');
    Route::get('/common/deny', 'CommonController@deny');
    Route::get('/common/errors', 'CommonController@errors');
    Route::get('/common/clear', 'CommonController@clear');
    Route::post('/common/upload_img', 'CommonController@uploadImg');
    Route::post('/common/updatePwd', 'CommonController@updatePwd');
    Route::get('common/user_info', 'CommonController@userInfo');

});