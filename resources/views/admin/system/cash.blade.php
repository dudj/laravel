@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a>系统管理</a>
            <a>系统设置</a>
            <a><cite>提现设置</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
      <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
    <!--表单数据-->
    <form method="post" id="cashForm" class="layui-form">
      <div class="layui-tab layui-tab-card">
        <div class="layui-form-item">
          <label class="layui-form-label">提现设置 <span class="x-red">*</span></label>
          <div class="layui-input-block">
            <input type="radio" name="is_cash" value="1" @if($data['is_cash'] == 1) checked @endif>
            开启
            <input type="radio" name="is_cash" @if($data['is_cash'] == 2) checked @endif value="0">
            关闭
          </div>
          <div class="layui-form-mid layui-word-aux">
            <p class="x-red">是否开启提现功能</p>
          </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">手续费比例</label>
          <div class="layui-input-inline">
            <input type="number" max="100" min="1" name="service_ratio" value="{{$data['service_ratio']}}" autocomplete="off" class="layui-input"/>
          </div>%
          <span class="x-grey">（注：默认是百分比，如填1就是 代表每笔提现，收取提现金额1%的手续费）</span>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">最低手续费</label>
          <div class="layui-input-inline">
            <input type="number" min="1" name="min_service_charge" value="{{$data['min_service_charge']}}" autocomplete="off" class="layui-input">
          </div>
          <span class="x-grey">（注：单笔手续费如果小于该值，就取此值）</span>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">最高手续费</label>
          <div class="layui-input-inline">
            <input type="number" min="1" name="max_service_charge" value="{{$data['max_service_charge']}}" autocomplete="off" class="layui-input">
          </div>
          <span class="x-grey">（注：单笔手续费如果大于该值，就取此值）</span>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">最低提现额</label>
          <div class="layui-input-inline">
            <input type="number" min="1" name="min_withdrawal" value="{{$data['min_withdrawal']}}" autocomplete="off" class="layui-input">
          </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">最高提现额</label>
          <div class="layui-input-inline">
            <input type="number" name="max_withdrawal" value="{{$data['max_withdrawal']}}" autocomplete="off" class="layui-input">
          </div>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">每日累计提现额</label>
          <div class="layui-input-inline">
            <input type="number" name="add_up_withdrawal" value="{{$data['add_up_withdrawal']}}" autocomplete="off" class="layui-input">
          </div>
          <span class="x-red">注意：每天提现的额度不能超过此值</span>
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">每日累计提现次数</label>
          <div class="layui-input-inline">
            <input type="number" name="add_up_num" value="{{$data['add_up_num']}}" autocomplete="off" class="layui-input">
          </div>
          <span class="x-red">注意：每天提现的次数不能超过此值</span>
        </div>
        <div class="layui-form-item">
          <div class="layui-input-block">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="saveCash">确认提交</button>
          </div>
        </div>
      </div>
  </form>
<script>
  layui.use(['table','form'], function(){
    var form = layui.form;
    form.on('submit(saveCash)', function(data){
      var param = getFormJson('cashForm');
      var resStatus = commonAjax('{{url('admin/system/cash')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
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