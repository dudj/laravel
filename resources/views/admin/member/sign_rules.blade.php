@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a>系统管理</a>
            <a>系统设置</a>
            <a><cite>签到规则设定</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
      <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
    <!--表单数据-->
    <form method="post" id="cashForm" class="layui-form">
      <div class="layui-tab layui-tab-card">
        <div class="layui-form-item">
          <label class="layui-form-label">首次签到 <span class="x-red">*</span></label>
          <div class="layui-input-inline">
            <input type="number" lay-verify="first_integral" name="first_integral" value="{{isset($data['first_integral'])?$data['first_integral']:''}}" autocomplete="off" class="layui-input"/>
          </div>
          <div class="layui-form-mid layui-word-aux">
            <p class="x-grey">（注：第一次签到赠送积分）</p>
          </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">连续签到 <span class="x-red">*</span></label>
          <div class="layui-input-inline">
            <input type="number" lay-verify="continuity_integral" name="continuity_integral" value="{{isset($data['continuity_integral'])?$data['continuity_integral']:''}}" autocomplete="off" class="layui-input"/>
          </div>
          <span class="x-grey">（注：连续签到赠送积分）</span>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">连续签到N天</label>
          <div class="layui-input-inline">
            <input type="number" name="fixed_day" value="{{isset($data['fixed_day'])?$data['fixed_day']:''}}" autocomplete="off" class="layui-input">
          </div>
          <span class="x-red">（注：连续签到N天，此值填写后最小最大值必填，如果不填，最小最大填写无意义）</span>
        </div>
        <div class="layui-form-item">
          <div class="layui-form-block" style="margin-left:2%;">连续签到N天赠送的积分范围</div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">最小：</label>
          <div class="layui-input-inline">
            <input type="number" min="1" name="min_fixed_integral" value="{{isset($data['min_fixed_integral'])?$data['min_fixed_integral']:''}}" autocomplete="off" class="layui-input">
          </div>
          <label class="layui-form-label">最大：</label>
          <div class="layui-input-inline">
            <input type="number" min="1" name="max_fixed_integral" value="{{isset($data['max_fixed_integral'])?$data['max_fixed_integral']:''}}" autocomplete="off" class="layui-input">
          </div>
          <span class="x-grey">（注：连续N天后赠送的积分，在范围内随机赠送）</span>
        </div>
        <div class="layui-form-item">
          <div class="layui-input-block">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="saveIntegral">确认提交</button>
          </div>
        </div>
      </div>
  </form>
<script>
  layui.use(['table','form'], function(){
    var form = layui.form;
    //自定义验证规则
    form.verify({
      first_integral: function(value) {
        if (value.length < 1) {
          return '首次签到获得积分必填';
        }
      },
      continuity_integral: function(value) {
        if (value.length < 1) {
          return '连续签到获得积分必填';
        }else if(value<$('input[name="continuity_integral"]').val()){
          return '连续签到获得积分不能小于首次签到获得积分';
        }
      }
    });
    form.on('submit(saveIntegral)', function(data){
      var fixed_day = $('input[name="fixed_day"]').val();
      var min_fixed_integral = $('input[name="min_fixed_integral"]').val();
      var max_fixed_integral = $('input[name="max_fixed_integral"]').val();
      if(fixed_day){
        if(!min_fixed_integral || !max_fixed_integral){
          layer.alert('确保数据完整性');
          return false;
        }
        if(min_fixed_integral>max_fixed_integral){
          layer.alert('最大积分和最小积分不要填反了');
          return false;
        }
      }else{
        $('input[name="min_fixed_integral"]').val('');
        $('input[name="max_fixed_integral"]').val('');
      }
      var param = getFormJson('cashForm');
      var resStatus = commonAjax('{{url('admin/member/signRules')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
      //发异步，把数据提交给php
      if(resStatus > 0){
        $('#submit').attr('disabled', true);
        layer.alert('配置成功', {icon: 6},function () {
          window.parent.location.reload();
        });
      }
      return false;
    });
  });
</script>
@endsection