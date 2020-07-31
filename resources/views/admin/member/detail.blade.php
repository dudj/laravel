@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">会员管理</a>
            <a href="javascript:history.back();">会员列表</a>
            <a><cite>会员详情-网站系统会员信息</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
      <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
    <!--表单数据-->
    <form method="post" id="editMemberForm" class="layui-form">
    <div class="layui-tab layui-tab-card">
      <div class="layui-form-item">
        <label class="layui-form-label">会员昵称</label>
        <div class="layui-input-inline">
          <input type="text" name="username" value="{{isset($member['username'])?$member['username']:''}}" readonly autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux"><span class="x-grey">会员昵称不允许修改</span></div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">登录密码 </label>
        <div class="layui-input-inline">
          <input type="password" name="password" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux"><span class="x-red">密码不填 默认不修改</span></div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">会员资产</label>
        <div class="layui-form-mid layui-word-aux">
          余额：<span class="x-red">{{isset($member['member_money'])?$member['member_money']:0.00}}</span>
        </div>
        <div class="layui-form-mid layui-word-aux">
          积分：<span class="x-red">{{isset($member['pay_points'])?$member['pay_points']:0}}</span>
        </div>
        <div class="layui-form-mid layui-word-aux">
          冻结余额：<span class="x-red">{{isset($member['frozen_money'])?$member['frozen_money']:0.00}}</span>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">会员级别</label>
        <div class="layui-input-inline">
          <select name="level" id="level" class="layui-form-selected">
            <option value="">---请选择----</option>
            @foreach($levelList as $vo)
              @if($vo['id'] == $member['level'])
                <option value="{{$vo['id']}}" selected>{{$vo['level_name']}}</option>
              @else
                <option value="{{$vo['id']}}">{{$vo['level_name']}}</option>
              @endif
            @endforeach
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">手机号码 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="text" name="mobile" value="{{isset($member['mobile'])?$member['mobile']:''}}" autocomplete="off" lay-verify="mobile" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux"><span class="x-grey">请输入常用的手机号码，将用来找回密码、接受订单通知等。</span></div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">邮箱 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="email" name="email" lay-verify="email" value="{{isset($member['email'])?$member['email']:''}}" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux"><span class="x-grey">请输入常用的邮箱，将用来找回密码、接受订单通知等。</span></div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">性别 </label>
        <div class="layui-form-mid layui-word-aux">
          男 <input type="radio" name="sex" value="1" @if($member['sex'] == 1) checked @endif autocomplete="off" class="layui-input">
          女 <input type="radio" name="sex" value="2" @if($member['sex'] == 2) checked @endif autocomplete="off" class="layui-input">
          保密 <input type="radio" name="sex" value="0" @if($member['sex'] == 0) checked @endif autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">注册时间</label>
        <div class="layui-form-mid layui-word-aux">
          <?=date('Y-m-d H:i:s',$member['register_time'])?>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">会员状态 </label>
        <div class="layui-input-inline">
          @if(isset($member['is_lock']) && $member['is_lock'] == 1)
          <input type="checkbox" name="is_lock" lay-filter="is_lock" checked lay-skin="switch" value="1" lay-text="冻结|开启">
          @else
            <input type="checkbox" name="is_lock" lay-filter="is_lock" lay-skin="switch" value="0" lay-text="冻结|开启">
          @endif
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button type="submit" class="layui-btn layui-btn-danger" lay-submit="" lay-filter="memberSave">确认提交</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
          <button type="button" onclick="javascript:history.back();" class="layui-btn">返回列表</button>
        </div>
      </div>
    </div>
    <input type="hidden" name="id" value="{{isset($member['id'])?$member['id']:''}}">
  </form>
<script>
  layui.use(['table','form','laydate'], function(){
    var form = layui.form;
    form.on('switch(is_lock)',function(data){
      var res = 0;
      if(data.value == 0){
        res = 1;
        layer.tips('温馨提示：如果冻结会员，会员将无法操作！', data.othis);
      }
      $("input[name='is_lock']").val(res);
    });
    form.verify({
      username: function(value){
        if(value.length == ''){
          return '用户昵称不能为空';
        }
      },
      password: function (val) {
        if(val.length != ''){
          if(!(/(.+){6,18}$/).test(val)){
            return '密码必须6到18位';
          }
        }
      },
      email:[/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,'邮箱不符合要求'],
      mobile  :[/^1\d{10}$/,'手机号不符合要求'],
    });
    form.on('submit(memberSave)', function(data){
      var param = getFormJson('editMemberForm');
      var resStatus = commonAjax('{{url('admin/member/editMember')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
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