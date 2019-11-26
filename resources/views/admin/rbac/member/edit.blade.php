@extends('layouts.admin')
@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">
            <div class="layui-form-item">
                <label for="L_name" class="layui-form-label">
                    <span class="x-red">*</span>名称</label>
                <div class="layui-input-inline">
                    <input type="hidden" id="id" name="id" value="{{$info['id']}}" autocomplete="off" class="layui-input">
                    <input type="text" id="L_name" name="name" required="" value="{{$info['name']}}" lay-verify="name" autocomplete="off" class="layui-input"></div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>将会成为您唯一的登入名</div>
            </div>
            <div class="layui-form-item">
                <label for="L_mailbox" class="layui-form-label">
                    <span class="x-red">*</span>邮箱</label>
                <div class="layui-input-inline">
                    <input type="text" id="L_mailbox" name="mailbox" value="{{$info['mailbox']}}" required="" lay-verify="mailbox" autocomplete="off" class="layui-input"></div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>example:204586313@qq.com</div>
            </div>
            <div class="layui-form-item">
                <label for="L_mobile" class="layui-form-label">
                    <span class="x-red">*</span>手机号</label>
                <div class="layui-input-inline">
                    <input type="text" id="L_mobile" name="mobile" value="{{$info['mobile']}}" required="" lay-verify="mobile" autocomplete="off" class="layui-input"></div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>example:13020078873</div>
            </div>
            <div class="layui-form-item">
                <label for="L_password" class="layui-form-label">密码</label>
                <div class="layui-input-inline">
                    <input type="password" id="L_password" name="password" required="" lay-verify="password" autocomplete="off" class="layui-input"></div>
                <div class="layui-form-mid layui-word-aux"><span class="x-red">*</span>6到16个字符(如果为空，默认原密码)</div>
            </div>
            <div class="layui-form-item">
                <label for="L_password" class="layui-form-label">状态</label>
                @if($info['status'] == 1)
                    <input name="status" lay-skin="primary" lay-verify="status" checked type="radio" title="正常" value="1">
                    <input name="status" lay-skin="primary" lay-verify="status" type="radio" title="弃用" value="0">
                @else
                    <input name="status" lay-skin="primary" lay-verify="status" type="radio" title="正常" value="1">
                    <input name="status" lay-skin="primary" lay-verify="status" checked type="radio" title="弃用" value="0">
                @endif
            </div>
            <div class="layui-form-item">
                <label for="L_address" class="layui-form-label">地址</label>
                <div class="layui-input-inline">
                    <input type="text" id="L_address" name="address" value="{{$info['address']}}" autocomplete="off" class="layui-input"></div>
            </div>
            <div class="layui-form-item">
                <label for="L_group_id" class="layui-form-label"><span class="x-red">*</span>角色</label>
                <div class="layui-input-inline">
                    <select name="group_id" id="L_group_id" lay-verify="group_id">
                        <option value="">---请选择---</option>
                        @foreach($groupData as $key=>$val)
                            @if($val['id'] == $info['group_id'])
                                <option value="{{$val['id']}}" selected>{{$val['name']}}</option>
                            @else
                                <option value="{{$val['id']}}">{{$val['name']}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">头像</label>
                <div class="layui-input-inline">
                    <input type="text" name="logo" lay-verify="required" id="inputimgurl" placeholder="图片地址" value="{{$info['logo']}}" class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <div class="layui-upload-list" style="margin:0">
                        <img src="{{$info['logo']}}" width="150px" id="srcimgurl" class="layui-upload-img">
                    </div>
                </div>
                <div class="layui-input-inline layui-btn-container" style="width: auto;">
                    <button class="layui-btn layui-btn-primary" type="button" id="editimg">修改图片</button >
                </div>
                <div class="layui-form-mid layui-word-aux">头像的尺寸限定150x150px,大小在2M以内</div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-filter="update" lay-submit="">修改</button>
            </div>
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
            mailbox:[/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,'邮箱不符合要求'],
            mobile  :[/^1\d{10}$/,'手机号不符合要求'],
            group_id:function(value){
                if(!value){
                    return '角色不能为空';
                }
            }
        });
        //监听提交
        form.on('submit(update)', function(data){
            var resStatus = commonAjax('{{url('admin/member/update')}}','post',Base64.encode(JSON.stringify(data.field)),'json');
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
    });
</script>
@endsection