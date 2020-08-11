@extends('layouts.admin')
@section('content')
    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a><cite>权限管理</cite></a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <div class="layui-row">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <form class="layui-form layui-col-space5">
                            <div class="layui-inline layui-show-xs-block">
                                <input type="text" placeholder="权限名称" name="name" value="{{request('name')}}" autocomplete="off" class="layui-input">
                                <input type="hidden" name="id" value="{{request('id')}}" autocomplete="off" class="layui-input">
                                <div class="eleTree ele5" lay-filter="data5"></div>
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-header">
                        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                        <button class="layui-btn" onclick="xadmin.open('添加权限规则','{{url('admin/access/add')}}',670,400)"><i class="layui-icon"></i>添加</button>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                                <th width="2%">
                                    <input type="checkbox" lay-skin="switch" lay-filter="switchTest" name="ids"/>
                                </th>
                                <th>权限规则</th>
                                <th>权限名称</th>
                                <th>父节点(顶级节点为空)</th>
                                <th>操作</th>
                            </thead>
                            <tbody>
                            @foreach($data->items() as $value)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="id" name="id" value="{{ $value['id'] }}" lay-skin="primary">
                                    </td>
                                    <td>{{ $value['controller'] }}/{{ $value['method'] }}</td>
                                    <td>{{ $value['name'] }}</td>
                                    <td>{{$value['parent_name']}}</td>
                                    <td class="td-manage">
                                        <a title="编辑"  onclick="xadmin.open('编辑权限规则','{{url('admin/access/edit?id='.$value['id'])}}')" href="javascript:;">
                                            <i class="layui-icon">&#xe642;</i>
                                        </a>
                                        <a title="删除" onclick="access_del(this,'{{$value['id']}}')" href="javascript:;">
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
        layui.config({
            base: "/xadmin/lib/lay/"        //eleTree.js所在目录
        }).use(['jquery','eleTree'], function() {
            var $ = layui.jquery;
            var eleTree = layui.eleTree;
            var param = {
                elem: '.ele5',
                data: eval('<?=$treeData?>'),
                defaultExpandAll: true,
                expandOnClickNode: false,
                highlightCurrent: true,
                accordion: true,
                request :{
                    name: "name",
                    key: "id",
                    children: "list",
                    checked: "checked",
                    disabled: "disabled",
                    isLeaf: "isLeaf"
                }
            };
            var el5 = eleTree.render(param);
            $("[name='name']").on("click",function (e) {
                e.stopPropagation();
                if(!el5){
                    el5 = eleTree.render(param);
                }
                $(".ele5").toggle();
            });
            eleTree.on("nodeClick(data5)",function(data) {
                $("[name='name']").val(data.data.currentData.name);
                $("[name='id']").val(data.data.currentData.id);
                $(".ele5").hide();
            });
            $(".ele5").hide();
        });
        layui.use(['laydate','form'], function(){
            var laydate = layui.laydate;
            var form = layui.form;

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
        /*删除*/
        function access_del(obj,id){
            layer.confirm('确认要删除吗？',function(index){
                var resStatus = commonAjax('{{url('admin/access/del')}}','post',Base64.encode('id='+id+'type=one'),'json',false);
                if(resStatus > 0){
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!',{icon:1,time:1000});
                }
            });
        }
        function delAll () {
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
                var resStatus = commonAjax('{{url('admin/access/del')}}','post',Base64.encode('id='+chk_value+'&type=more'),'json',false);
                if(resStatus > 0){
                    layer.msg('删除成功', {icon: 1});
                    $(".layui-form-checked").not('.header').parents('tr').remove();
                }
            });
        }
    </script>
@endsection

