@extends('layouts.admin')
@section('content')
    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a><cite>会员管理</cite></a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input" value="{{request('mailbox')}}"  autocomplete="off" placeholder="邮箱" name="mailbox">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <input class="layui-input" value="{{request('mobile')}}"  autocomplete="off" placeholder="手机号" name="mobile">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <input type="text" name="name" value="{{request('name')}}" placeholder="名称" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn" data-type="getInfo" id="search"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table" lay-data="{url:'{{url('admin/member/index_json')}}',page:true,toolbar: '#memberHeader',id:'memberData'}" lay-filter="member">
                            <thead>
                            <tr>
                                <th lay-data="{type:'checkbox'}">ID</th>
                                <th lay-data="{field:'id', width:60, sort: true}">ID</th>
                                <th lay-data="{field:'name', width:120, sort: true}">名称</th>
                                <th lay-data="{field:'mailbox'}">邮箱</th>
                                <th lay-data="{field:'mobile',templet: '#switchTpl'}">手机</th>
                                <th lay-data="{field:'address'}">地址</th>
                                <th lay-data="{field:'group_name', sort: true }">角色名</th>
                                <th lay-data="{field:'right', title: '操作', width:70,toolbar:'#memberRight'}">操作</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/html" id="memberHeader">
        <div class = "layui-btn-container" >
            <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event = "delAll"><i class="layui-icon"></i>批量删除</button>
            <button class="layui-btn layui-btn-sm" onclick="xadmin.open('添加会员','{{url('admin/member/add')}}')"><i class="layui-icon"></i>添加</button>
        </div >
    </script>
    <script type="text/html" id="memberRight">
        <a class="" href="javascript:;" lay-event="edit"><i class="layui-icon">&#xe642;</i></a>
        <a class="" href="javascript:;" lay-event="del"><i class="layui-icon">&#xe640;</i></a>
    </script>
    <script>
        layui.use('table',function() {
            var table = layui.table;
            //头工具栏事件
            table.on('toolbar(member)', function (obj) {
                var checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case 'delAll':
                        var checkStatus = table.checkStatus(obj.config.id)
                        if(checkStatus == ""){
                            layer.msg('请选择删除用户', {icon: 1});
                            return false;
                        }
                        layer.alert(JSON.stringify(checkStatus.data));
                        alert(checkStatus.data);return false;
                        layer.confirm('确认要删除吗？'+chk_value,function(index){
                            //捉到所有被选中的，发异步进行删除
                            var resStatus = commonAjax('{{url('admin/group/del')}}','post',Base64.encode('id='+chk_value+'&type=more'),'json');
                            if(resStatus > 0){
                                layer.msg('删除成功', {icon: 1});
                                $(".layui-form-checked").not('.header').parents('tr').remove();
                            }
                        });
                        break;
                }
            });
            //监听工具条
            table.on('tool(member)', function (obj) {
                var data = obj.data;
                if (obj.event === 'del') {
                    layer.confirm('确认删除吗？', function (index) {
                        var resStatus = commonAjax('{{url('admin/member/del')}}','post',Base64.encode('id='+data.id+'&type=one'),'json');
                        if(resStatus > 0){
                            obj.del();
                            layer.close(index);
                            layer.msg("删除成功", {icon: 6});
                        }else{
                            layer.msg("删除失败", {icon: 5});
                        }
                    });
                } else if (obj.event === 'edit') {
                    layer.open({
                        type:2,
                        title: '编辑'+data.name+'会员',
                        shadeClose: true,
                        skin: 'layui-layer-rim', //加上边框
                        area: ['80%', '80%'], //宽高
                        content: '{{url('admin/member/edit?id=')}}'+data.id
                    });
                }
            });
            $('#search').on('click',function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
            // 点击获取数据
            var  active = {
                getInfo: function () {
                    var index = layer.msg('查询中，请稍候...',{icon: 16,time:false,shade:0});
                    setTimeout(function(){
                        table.reload('memberData', {
                            url:'{{url('admin/member/index_json')}}',
                            where: {
                                'name' : $('input[name="name"]').val(),
                                'mobile' : $('input[name="mobile"]').val(),
                                'mailbox' : $('input[name="mailbox"]').val()
                            }
                        });
                        layer.close(index);
                    },800);
                }
            };
            //监听回车事件,扫描枪一扫描或者按下回车键就直接执行查询
            $("#select_orderId").bind("keyup", function (e) {
                if (e.keyCode == 13) {
                    var type = "getInfo";
                    active[type] ? active[type].call(this) : '';
                }
            });
        });
        function delAll (argument) {
            var chk_value =[];
            $('input[name="id"]:checked').each(function(){
                chk_value.push($(this).val());
                $(this).removeClass('header');
            });
            if(chk_value == ""){
                layer.msg('请选择删除用户', {icon: 1});
                return false;
            }
            layer.confirm('确认要删除吗？'+chk_value,function(index){
                //捉到所有被选中的，发异步进行删除
                var resStatus = commonAjax('{{url('admin/group/del')}}','post',Base64.encode('id='+chk_value+'&type=more'),'json');
                if(resStatus > 0){
                    layer.msg('删除成功', {icon: 1});
                    $(".layui-form-checked").not('.header').parents('tr').remove();
                }
            });
        }
    </script>
@endsection

