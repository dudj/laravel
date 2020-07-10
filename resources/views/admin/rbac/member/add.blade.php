@extends('layouts.admin')
@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">
            <div class="layui-form-item">
                <label for="L_name" class="layui-form-label">
                    <span class="x-red">*</span>名称</label>
                <div class="layui-input-inline">
                    <input type="text" id="L_name" name="name" required="" lay-verify="name" autocomplete="off" class="layui-input"></div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>将会成为您唯一的登入名</div>
            </div>
            <div class="layui-form-item">
                <label for="L_mailbox" class="layui-form-label">
                    <span class="x-red">*</span>邮箱</label>
                <div class="layui-input-inline">
                    <input type="text" id="L_mailbox" name="mailbox" required="" lay-verify="mailbox" autocomplete="off" class="layui-input"></div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>example:204586313@qq.com</div>
            </div>
            <div class="layui-form-item">
                <label for="L_mobile" class="layui-form-label">
                    <span class="x-red">*</span>手机号</label>
                <div class="layui-input-inline">
                    <input type="text" id="L_mobile" name="mobile" required="" lay-verify="mobile" autocomplete="off" class="layui-input"></div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>example:13020078873</div>
            </div>
            <div class="layui-form-item">
                <label for="L_password" class="layui-form-label">
                    <span class="x-red">*</span>密码</label>
                <div class="layui-input-inline">
                    <input type="password" id="L_password" name="password" required="" lay-verify="password" autocomplete="off" class="layui-input"></div>
                <div class="layui-form-mid layui-word-aux"><span class="x-red">*</span>6到16个字符</div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                    <span class="x-red">*</span>确认密码</label>
                <div class="layui-input-inline">
                    <input type="password" id="L_repass" name="repass" required="" lay-verify="repass" autocomplete="off" class="layui-input"></div>
                <div class="layui-form-mid layui-word-aux"><span class="x-red">*</span>和密码保持一致</div>
            </div>
            <div class="layui-form-item">
                <label for="L_address" class="layui-form-label">地址</label>
                <div class="layui-input-inline">
                    <input type="text" id="L_address" name="address" autocomplete="off" class="layui-input"></div>
            </div>
            <div class="layui-form-item">
                <label for="L_group_id" class="layui-form-label"><span class="x-red">*</span>角色</label>
                <div class="layui-input-inline">
                    <select name="group_id" id="L_group_id" lay-verify="group_id">
                        <option value="">---请选择---</option>
                        @foreach($groupData as $key=>$val)
                            <option value="{{$val['id']}}">{{$val['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">头像</label>
                <div class="layui-input-inline">
                    <input type="text" name="logo" lay-verify="required" id="inputimgurl" placeholder="图片地址" value="/static/upload/default.jpg" class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <div class="layui-upload-list" style="margin:0">
                        <img src="/static/upload/default.jpg" width="150px" id="srcimgurl" class="layui-upload-img">
                    </div>
                </div>
                <div class="layui-input-inline layui-btn-container" style="width: auto;">
                    <button class="layui-btn layui-btn-primary" type="button" id="editimg">修改图片</button >
                </div>
                <div class="layui-form-mid layui-word-aux">头像的尺寸限定150x150px,大小在2M以内</div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label"></label>
                <button class="layui-btn" lay-filter="add" lay-submit="">增加</button></div>
        </form>
    </div>
</div>
<script>
    layui.config({base: '/static/cropper/'}).use(['form','croppers','layer'], function () {
        var $ = layui.jquery,form = layui.form,croppers = layui.croppers,layer= layui.layer;
        //创建一个头像上传组件
        croppers.render({
            elem: '#editimg',
            saveW:150,
            saveH:150,
            mark:1/1,
            area:'900px',
            url: "{{url('admin/common/upload_img')}}"
            ,done: function(url){
                $("#inputimgurl").val(url);
                $("#srcimgurl").attr('src',url);
            }
        });
    });
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form,layer = layui.layer;
        //自定义验证规则
        form.verify({
            name: function(value) {
                if (value.length < 5) {
                    return '昵称至少得5个字符啊';
                }
            },
            password: [/(.+){6,12}$/, '密码必须6到12位'],
            repass: function(value) {
                if ($('input[name="password"]').val() != $('input[name="repass"]').val()) {
                    return '两次密码不一致';
                }
            },
            mailbox:[/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,'邮箱不符合要求'],
            mobile  :[/^1\d{10}$/,'手机号不符合要求'],
            group_id:function(value){
                if(!value){
                    return '角色不能为空';
                }
            }
        });
        //监听提交
        form.on('submit(add)', function(data){
            var resStatus = commonAjax('{{url('admin/member/store')}}','post',Base64.encode(JSON.stringify(data.field)),'json',false);
            //发异步，把数据提交给php
            if(resStatus > 0){
                layer.alert("增加成功", {icon: 6},function () {
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
    });
</script>
@endsection