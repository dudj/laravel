@extends('layouts.admin')
@section('content')
    <style>
        .layui-inline{
            width: 11%;
        }
    </style>
    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">会员管理</a>
            <a><cite>收货地址</cite></a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <div class="layui-row">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body" style="overflow: auto;">
                        <table class="layui-table layui-form" id="list" lay-filter="list">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--头部导航--}}
    <script type="text/html" id="toolbarHeader">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" onclick="javascript:history.back();"><i class="iconfont">&#xe697;</i>返回会员列表</button>
        </div>
    </script>
<script>
    layui.use('table', function(){
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var tableList = table.render({
            elem: '#list',
            id: 'list',
            url:'{{url('admin/member/address')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            toolbar:'#toolbarHeader',
            defaultToolbar: ['filter', 'exports', 'print'],
            where:{
                'member_id': '{{$member_id}}'
            },
            method:'post',
            sort: true,
            title: '商品列表',
            cols: [[
                {field:'consignee',title: '收货人'},
                {field:'mobile',  title: '联系电话'},
                {field:'zipcode', title: '邮政编码'},
                {field:'country', title: '地址', templet: function (d) {
                    return d.province_name + '-' + d.city_name + '-' + d.district_name + '-' + d.twon_name + '-' + d.address;
                }},
                {field:'is_default', title: '默认地址', sort: true,templet: function (d) {
                    if(d.is_default == 1){
                        return '是';
                    }
                    return '否';
                }}
            ]]
        });
    });
</script>
@endsection