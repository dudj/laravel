@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">会员管理</a>
            <a><cite>添加会员</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
      <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
    <!--表单数据-->
    <form method="post" id="addMemberForm" class="layui-form">
    <div class="layui-tab layui-tab-card">
      <div class="layui-form-item">
        <label class="layui-form-label">会员昵称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="text" name="username" value="{{isset($member['username'])?$member['username']:''}}" lay-verify="username" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">登录密码 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="password" name="password" value="{{isset($member['password'])?$member['password']:''}}" lay-verify="password" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">手机号码 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="text" name="mobile" value="{{isset($member['mobile'])?$member['mobile']:''}}" autocomplete="off" lay-verify="mobile" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">邮箱 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="email" name="email" lay-verify="email" value="{{isset($member['email'])?$member['email']:''}}" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button type="submit" class="layui-btn" lay-submit="" lay-filter="memberSave">确认提交</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
      </div>
    </div>
    <input type="hidden" name="id" value="{{isset($member['id'])?$member['id']:''}}">
  </form>
<script>
  layui.use(['table','form','laydate'], function(){
    var form = layui.form;
    form.verify({
      username: function(value){
        if(value.length == ''){
          return '用户昵称不能为空';
        }
      },
      password: [/(.+){6,18}$/, '密码必须6到18位'],
      email:[/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,'邮箱不符合要求'],
      mobile  :[/^1\d{10}$/,'手机号不符合要求'],
    });
    form.on('submit(memberSave)', function(data){
      var param = getFormJson('addMemberForm');
      var resStatus = commonAjax('{{url('admin/member/addMember')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
      //发异步，把数据提交给php
      if(resStatus > 0){
        $('#submit').attr('disabled', true);
        var msg = '添加成功';
        if($("input[name='id']").val()){
          msg = '修改成功';
        }
        layer.alert(msg, {icon: 6},function () {
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