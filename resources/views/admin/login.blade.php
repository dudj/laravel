<!doctype html>
<html  class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>管理中心</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link href="{{ asset('xadmin/css/font.css') }}" rel="stylesheet">
    <link href="{{ asset('xadmin/css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('xadmin/css/xadmin.css') }}" rel="stylesheet">


    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('xadmin/lib/layui/layui.js') }}"></script>
</head>
<body class="login-bg">

<div class="login layui-anim layui-anim-up">
    <div class="message">管理中心</div>
    <div id="darkbannerwrap"></div>
    <form class="layui-form" role="form" method="POST" action="{{ url('/admin/login') }}">
        {{ csrf_field() }}
        <input name="name" placeholder="用户名" value="{{ old('name') }}" type="text" lay-verify="required" class="layui-input" >
        <hr class="hr15">
        <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
        <hr class="hr15">
        <input name="remember"  type="checkbox" class="layui-input layui-form-checkbox">&nbsp;&nbsp;&nbsp;Remember Me
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
        <hr class="hr20" >
    </form>
</div>

<script>
    $(function  () {
        layui.use('form', function(){
            var form = layui.form;
            var name = '{{$errors->first('name')}}';
            var password = '{{$errors->first('password')}}';
            if(name){
                layer.msg(name);
            }
            if(password){
                layer.msg(password);
            }
        });
    })
</script>
<!-- 底部结束 -->
</body>
</html>