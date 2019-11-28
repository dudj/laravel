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
                                <th>手机</th>
                                <th>邮箱</th>
                                <th>地址</th>
                                <th>头像</th>
                                <th>添加时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>{{$data['mobile']}}</th>
                                <th>{{$data['mailbox']}}</th>
                                <th>{{$data['address']}}</th>
                                <th><img src="{{$data['logo']}}" width="150px"/></th>
                                <th>{{date('Y-m-d H:i',$data['createtime'])}}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection