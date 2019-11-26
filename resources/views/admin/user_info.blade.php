@extends('layouts.admin')
@section('content')
    <div class="layui-collapse" lay-accordion>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">{{auth()->guard('admin')->user()->name}}</h2>
            <div class="layui-colla-content layui-show">
                <div class="layui-card-body layui-table-body layui-table-main">
                    <table class="layui-table layui-form">
                        <thead>
                            <tr>
                                <th>性别</th>
                                <th>手机</th>
                                <th>地址</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>性别</th>
                                <th>手机</th>
                                <th>地址</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection