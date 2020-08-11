@extends('layouts.admin')
@section('content')
    <style>
        .layui-inline{
            width: 11%;
        }
    </style>
    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">页面管理</a>
            <a>PC端导航</a>
            <a><cite>导航列表</cite></a>
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
            <button class="layui-btn layui-btn-sm" lay-event="delAll"><i class="iconfont">&#xe69d;</i>批量删除</button>
            <button class="layui-btn layui-btn-sm" lay-event="add"><i class="layui-icon">&#xe654;</i>添加导航</button>
        </div>
    </script>
    {{--是否打开--}}
    <script type="text/html" id="is_open">
        <input type="checkbox" name="is_open" data-id = "@{{ d.id }}" value="@{{ d.is_open }}" lay-skin="switch" lay-text="是|否" lay-filter="isOpenListen" @{{ d.is_open == 1?'checked':'' }}/>
    </script>
    {{--是否显示--}}
    <script type="text/html" id="is_show">
        <input type="checkbox" name="is_show" data-id = "@{{ d.id }}" value="@{{ d.is_show }}" lay-skin="switch" lay-text="是|否" lay-filter="isShowListen" @{{ d.is_show == 1?'checked':'' }}/>
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
            url:'{{url('admin/system/navList')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            toolbar:'#toolbarHeader',
            defaultToolbar: ['filter', 'exports', 'print'],
            where:jsonParam,
            method:'post',
            sort: true,
            title: '导航列表',
            cols: [[
                {type:'checkbox'},
                {field:'name', width:150, title: '导航名称'},
                {field:'url', width:150, title: '跳转链接'},
                {field:'position', width:100, title: '位置'},
                {field:'is_show',width:100, title: '是否显示', templet: '#is_show'},
                {field:'is_open',width:100, title: '是否打开', templet: '#is_open'},
                {field:'sort',width:100, title: '排序',edit:true},
                {fixed: 'right',width:250, title:'操作', align: 'center', templet: function () {
                    return '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>\
                         <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="delete">删除</a>\
                         <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="look">查看</a>';
                    }
                }
            ]],
            page: true
        });
        //监听头部导航
        table.on('toolbar(list)',function(obj){
            switch(obj.event){
                case 'delAll':
                    //获取选中状态 checkStatus参数是table的id值     
                    var data = table.checkStatus('list');
                    var selectCount = data.data.length;
                    if(selectCount == 0){
                        layer.msg('靓仔，至少选中一项数据',function(){});
                        return false;
                    }
                    var ids = "";
                    for(var i=0; i<selectCount; i++){
                        ids += data.data[i].id + ",";
                    }
                    publicHandle('{{url('admin/system/deleteNav')}}',ids,'get','{{url('admin/system/navList')}}');
                    break;
                case 'add':
                        xadmin.open('添加导航','{{url('admin/system/addEditNav')}}');
                    break;
            }
        });
        //监听行工具事件(操作)导航
        table.on('tool(list)', function(obj){
            var data = obj.data;
            switch(obj.event){
                case 'edit':
                    xadmin.open('修改导航','{{url('admin/system/addEditNav?id=')}}'+data.id);
                    break;
                case 'delete':
                    publicHandle('{{url('admin/system/deleteNav')}}',data.id,'get','{{url('admin/system/navList')}}');
                    break;
                case 'look':
                    window.open(data.url);
                    break;
            }
        });
        /**
         * 处理 排序 sort(table属性lay-filter的值)
         **/
        table.on('edit(list)', function(obj){
            var data = {};
            data[obj.field] = obj.value;
            data.id = obj.data.id;
            changeTableVal('{{url('admin/system/handleNav')}}',data);
            reloadTable();
        });
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
        form.on('switch(isShowListen)', function(obj){
            var value = this.value == 1 ? 0 : 1;
            changeTableVal('{{url('admin/system/handleNav')}}', {'id':this.getAttribute('data-id'),'is_show':value});
            reloadTable();
        });
        form.on('switch(isOpenListen)', function(obj){
            var value = this.value == 1 ? 0 : 1;
            changeTableVal('{{url('admin/system/handleNav')}}', {'id':this.getAttribute('data-id'),'is_open':value});
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