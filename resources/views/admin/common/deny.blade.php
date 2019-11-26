@extends('layouts.admin')
@section('content')
    <div class="layui-container">
        <div class="fly-panel">
            <div class="fly-none">
                <h2><i class="layui-icon layui-icon-close"></i></h2>
                <p>页面禁止访问</p>
            </div>
        </div>
    </div>
    <script>
        layui.use('layer', function() { //独立版的layer无需执行这一句
            var $ = layui.jquery, layer = layui.layer;
            layer.msg('页面禁止访问<br/>请联系管理员开通权限或者操作其它功能', {
                time: 20000, //20s后自动关闭
                btn: ['清楚吗？','知道了，', '哦！']
            });
        });
    </script>
@endsection