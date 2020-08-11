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
            <a><cite>会员等级列表</cite></a>
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
            <button class="layui-btn layui-btn-sm" lay-event="add"><i class="layui-icon">&#xe654;</i>添加会员等级</button>
        </div>
    </script>
<script>
    layui.use('table', function(){
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var jsonParam = getFormJson('listSearchForm');
        var tableList = table.render({
            elem: '#list',
            id: 'list',
            url:'{{url('admin/member/levelList')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            toolbar:'#toolbarHeader',
            defaultToolbar: ['exports', 'print'],
            where:jsonParam,
            method:'get',
            sort: true,
            title: '商品列表',
            cols: [[
                {type:'checkbox'},
                {field:'id', width:60, title: 'ID', sort: true},
                {field:'level_name', width:150, title: '等级名称'},
                {field:'amount', width:150, title: '消费额度'},
                {field:'discount', width:150, title: '折扣度'},
                {field:'description',width:300, title: '描述'},
                {fixed: 'right',width:150, title:'操作', align: 'center', templet: function () {
                    return '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>\
                         <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="delete">删除</a>';
                    }
                }
            ]],
            page: true
        });
        //监听头部导航
        table.on('toolbar(list)',function(obj){
            switch(obj.event){
                case 'add':
                        xadmin.open('添加会员等级','{{url('admin/member/addEditMemberLevel')}}');
                    break;
            }
        });
        //监听行工具事件(操作)导航
        table.on('tool(list)', function(obj){
            var data = obj.data;
            switch(obj.event){
                case 'edit':
                    xadmin.open('修改会员等级','{{url('admin/member/addEditMemberLevel?id=')}}'+data.id);
                    break;
                case 'delete':
                    publicHandle('{{url('admin/member/deleteMemberLevel')}}',obj.data.id,'get','{{url('admin/member/levelList')}}');
                    break;
            }
        });
        /**
         * 处理 排序 sort(table属性lay-filter的值)
         **/
        table.on('sort(list)', function(obj){
            $('input[name="sortfield"]').val(obj.field);
            $('input[name="sorttype"]').val(obj.type);
            //重新加载页面的数据
            table.reload('list', {
                where: {
                    'sortfield': obj.field,
                    'sorttype': obj.type
                }
            });
        });
        $('.search').on('click', function(){
            reloadTable();
        });
        function reloadTable(){
            table.reload('list', {
                where: getFormJson('listSearchForm')
            });
        }
    });
</script>
@endsection