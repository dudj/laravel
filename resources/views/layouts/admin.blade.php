<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>
    <!-- x-admin css -->
    <link href="{{ asset('xadmin/css/font.css') }}" rel="stylesheet">
    <link href="{{ asset('xadmin/css/xadmin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/customer.css') }}" rel="stylesheet">
    <link href="{{ asset('xadmin/lib/lay/eleTree.css') }}" rel="stylesheet">
    <link href="{{ asset('xadmin/lib/layui/css/layui.css') }}" rel="stylesheet">

    <!-- x-admin js -->
    <script src="{{ asset('xadmin/lib/layui/layui.js') }}"></script>
    <script src="{{ asset('xadmin/js/xadmin.js') }}"></script>
    <script src="{{ asset('js/custompackage.js') }}"></script>
    <script src="{{ asset('js/base64.js') }}"></script>
    <script src="{{ asset('xadmin/lib/lay/eleTree.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
</head>
<body>
@yield('nav')
@yield('left')
@yield('content')
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script>
    //由于 多选框和单选框以及下拉框 默认是隐藏的 需要重新渲染form layui.js的用法
//    layui.use('form', function(){
//        var form = layui.form();
//        form.render();
//    });
    //layui.all.js用法
//    var form = layui.form();
//    form.render();
    //定时任务
    setInterval(function(){
        $.ajax({
            type:'post',
            url:"{{url('admin/system/loginTask')}}",
            success:function(){
                // 执行定时任务
            }
        });
    },2000*60);

</script>
</body>
</html>