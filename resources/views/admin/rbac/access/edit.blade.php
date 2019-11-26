@extends('layouts.admin')
@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form action="" method="post" class="layui-form layui-form-pane">
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>权限名称
                </label>
                <div class="layui-input-inline">
                    <input type="hidden" name="id" value="{{$info['id']}}" autocomplete="off" class="layui-input">
                    <input type="text" id="name" value="{{$info['name']}}" name="name" required="" lay-verify="name" autocomplete="off" class="layui-input">
                </div>
                <label for="eng_name" class="layui-form-label">
                    <span class="x-red">*</span>英文名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="eng_name" value="{{$info['eng_name']}}" name="eng_name" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="controller" class="layui-form-label">
                    <span class="x-red">*</span>控制器
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="controller" value="{{$info['controller']}}" name="controller" required="" lay-verify="controller" autocomplete="off" class="layui-input">
                </div>
                <label for="method" class="layui-form-label">
                    <span class="x-red">*</span>方法
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="method" value="{{$info['method']}}" name="method" required=""  lay-verify="method" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>父节点</label>
                    <div class="layui-input-inline">
                        <input type="text" name="parent_name" value="{{$info['parent_name']}}" autocomplete="off" class="layui-input">
                        <input type="hidden" name="parent_id" value="{{$info['parent_id']}}" autocomplete="off" class="layui-input">
                        <div class="eleTree ele5" lay-filter="data5"></div>
                    </div>
                    <label for="order_by" class="layui-form-label">
                        <span class="x-red">*</span>排序
                    </label>
                    <div class="layui-input-inline">
                        <input type="number" id="order_by" value="{{$info['order_by']}}" name="order_by" autocomplete="off" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="type" class="layui-form-label" style="width: 150px;">
                    <span class="x-red">*</span>是否作为菜单显示
                </label>
                <div class="layui-input-inline">
                    @if($info['type'] == 1)
                        <input type="radio" id="type" name="type" checked value="1" title="是" autocomplete="off" class="layui-input"/>
                        <input type="radio" id="type" name="type"  value="0" title="否" autocomplete="off" class="layui-input"/>
                    @else
                        <input type="radio" id="type" name="type" value="1" title="是" autocomplete="off" class="layui-input"/>
                        <input type="radio" id="type" name="type" checked value="0" title="否" autocomplete="off" class="layui-input"/>
                    @endif
                </div>
            </div>
            <div class="layui-form-item">
                <label for="icon" class="layui-form-label">
                    <span class="x-red">*</span>图标
                </label>
                <div class="layui-input-inline">
                    <input type="text" placeholder="#xe697;" value="{{$info['icon']}}" id="icon" lay-verify="icon" name="icon" autocomplete="off" class="layui-input"/>
                </div>
                <button type="button" class="layui-btn" onclick="javascript:window.open('{{url('admin/common/unicode')}}')">查看</button>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="update">修改</button>
            </div>
        </form>
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
        $("[name='parent_name']").on("click",function (e) {
            e.stopPropagation();
            if(!el5){
                el5 = eleTree.render(param);
            }
            $(".ele5").toggle();
        });
        eleTree.on("nodeClick(data5)",function(data) {
            $("[name='parent_name']").val(data.data.currentData.name);
            $("[name='parent_id']").val(data.data.currentData.id);
            $(".ele5").hide();
        });
        $(".ele5").hide();
    });
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form,layer = layui.layer;
        //自定义验证规则
        form.verify({
            name: function(value) {
                if (value.length < 1) {
                    return '权限名称必填';
                }
            },
            controller: [/[a-z]$/, '控制器必须为英文字母小写'],
            method: [/[a-z]$/, '方法必须为英文字母小写'],
            icon: function (val) {
                if(val.length < 0){
                    return '图标必填';
                }
                if(val.indexOf('&') >= 0){
                    return '请正确按照提示语填写信息';
                }
            }
        });
        //监听提交
        form.on('submit(update)', function(data){
            var resStatus = commonAjax('{{url('admin/access/update')}}','post',Base64.encode(JSON.stringify(data.field)),'json');
            //发异步，把数据提交给php
            if(resStatus > 0){
                layer.alert("修改成功", {icon: 6},function () {
                    window.parent.location.reload();
                    //刷新父页面
                    // 获得frame索引
                    var index = parent.layer.getFrameIndex(window.name);
                    //关闭当前frame
                    parent.layer.close(index);
                });
            }
            return false;
        });
        form.on('radio', function(data){
            if(data.value == 1){
                $('.ids').attr('checked',true);
                form.render();
            }else{
                $('.ids').attr('checked',false);
                form.render();
            }
        });
    });
</script>
@endsection