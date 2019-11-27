@extends('layouts.admin')
@section('content')
    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a><cite>角色管理</cite></a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <form class="layui-form layui-col-space5">
                            <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input" value="{{request('start')}}"  autocomplete="off" placeholder="开始日" name="start" id="start">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input" value="{{request('end')}}"  autocomplete="off" placeholder="截止日" name="end" id="end">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <input type="text" name="name" value="{{request('name')}}" placeholder="请输入角色名" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-header">
                        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                        <button class="layui-btn" onclick="xadmin.open('添加角色','{{url('admin/group/add')}}')"><i class="layui-icon"></i>添加</button>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" lay-skin="switch" lay-filter="switchTest" name="ids"/>
                                </th>
                                <th>角色名</th>
                                <th>拥有权限串</th>
                                <th>描述</th>
                                <th>添加时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </thead>
                            <tbody>
                            @foreach($data->items() as $value)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="layui-form-checked header id" name="id" value="{{ $value['id'] }}" lay-skin="primary"/>
                                    </td>
                                    <td>{{ $value['name'] }}</td>
                                    <td>{{ $value['nodestr'] }}</td>
                                    <td>{{ $value['description'] }}</td>
                                    <td>{{ date('Y-m-d H:i:s',$value['createtime']) }}</td>
                                    <td class="td-status">
                                        @if($value['status'] == 1)
                                            <span class="layui-btn layui-btn-mini">已启用</span>
                                        @else
                                            <span class="layui-btn layui-btn-mini">已停用</span>
                                        @endif
                                    </td>
                                    <td class="td-manage">
                                        @if($value['status'] == 1)
                                            <a onclick="group_status(this,'{{$value['id']}}')" href="javascript:;"  title="停用">
                                                <i class="layui-icon">&#xe601;</i>
                                            </a>
                                        @else
                                            <a onclick="group_status(this,'{{$value['id']}}')" href="javascript:;"  title="启用">
                                                <i class="layui-icon">&#xe601;</i>
                                            </a>
                                        @endif
                                        <a title="编辑"  onclick="xadmin.open('编辑','{{url('admin/group/edit?id='.$value['id'])}}')" href="javascript:;">
                                            <i class="layui-icon">&#xe642;</i>
                                        </a>
                                        <a title="删除" onclick="group_del(this,'{{$value['id']}}')" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-card-body ">
                        <div class="page">
                            <div>
                                {{$data->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#start' //指定元素
            });
            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });
        });
        layui.use('form', function () {
            var form = layui.form;
            //反选
            form.on('switch(switchTest)', function(data){
                var item = $(".id");
                item.each(function () {
                    if ($(this).prop("checked")) {
                        $(this).prop("checked", false);
                    } else {
                        $(this).prop("checked", true);
                    }
                });
                form.render('checkbox');
            });
        });
        /*用户-停用*/
        function group_status(obj,id){
            layer.confirm('确认要'+$(obj).attr('title')+'吗？',function(index){
                var status = 1;
                if($(obj).attr('title')=='停用'){
                    status = 0;
                }
                var resStatus = commonAjax('{{url('admin/group/change_status')}}','post',Base64.encode('id='+id+"&status="+status),'json');
                if(resStatus > 0){
                    if($(obj).attr('title')=='停用'){
                        //发异步把用户状态进行更改
                        $(obj).attr('title','启用')
                        $(obj).find('i').html('&#xe601;');
                        $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                        layer.msg('已停用!',{icon: 5,time:1000});
                    }else{
                        $(obj).attr('title','停用')
                        $(obj).find('i').html('&#xe62f;');
                        $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                        layer.msg('已启用!',{icon: 5,time:1000});
                    }
                }
            });
        }
        /*角色-删除*/
        function group_del(obj,id){
            layer.confirm('确认要删除吗？',function(index){
                //发异步删除数据
                var resStatus = commonAjax('{{url('admin/group/del')}}','post',Base64.encode('id='+id),'json');
                if(resStatus > 0){
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!',{icon:1,time:1000});
                }
            });
        }
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

