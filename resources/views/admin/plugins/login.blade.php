@extends('layouts.admin')
@section('content')
    <style>
        .layui-inline{
            width: 11%;
        }
    </style>
    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">插件管理</a>
            <a><cite>快捷登录插件设置</cite></a>
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
<script>
    layui.use('table', function(){
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var jsonParam = getFormJson('listSearchForm');
        var tableList = table.render({
            elem: '#list',
            id: 'list',
            url:'{{url('admin/plugins/login')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            toolbar:'#toolbarHeader',
            defaultToolbar: ['exports', 'print'],
            where:jsonParam,
            method:'post',
            sort: true,
            title: '快捷登录插件列表',
            cols: [[
                {type:'checkbox'},
                {field:'name', title: '名称'},
                {field:'desc', title: '描述'},
                {fixed: 'right', title:'操作', align: 'center', templet: function (d) {
                    if(d.status == 0){
                        return '<a class="layui-btn layui-btn-xs" lay-event="open">启用</a>';
                    }else{
                        return '<a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="edit">配置</a>\
                         <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="close">停用</a>';
                    }
                    }
                }
            ]],
            page: true
        });
        //监听行工具事件(操作)导航
        table.on('tool(list)', function(obj){
            var data = obj.data;
            var status = data.status;
            if(status == 1){
                status = 0;
            }else{
                status = 1;
            }
            switch(obj.event){
                case 'open':
                    var resStatus = commonAjax('{{url('admin/plugins/switchStatus')}}','post',Base64.encode('code='+data.code+'&status='+status),'json',false);
                    if(resStatus > 0){
                        layer.msg('启用成功', {icon: 1});
                        table.reload('list');
                    }
                    break;
                case 'edit':
                    xadmin.open('配置登录参数','{{url('admin/plugins/setting?code=')}}'+data.code+'&type='+data.type);
                    break;
                case 'close':
                    var resStatus = commonAjax('{{url('admin/plugins/switchStatus')}}','post',Base64.encode('code='+data.code+'&status='+status),'json',false);
                    if(resStatus > 0){
                        layer.msg('停用成功', {icon: 1});
                        table.reload('list');
                    }
                    break;
            }
        });
    });
</script>
@endsection