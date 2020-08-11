@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a>系统管理</a>
            <a>系统设置</a>
            <a><cite>积分兑换</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
      <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
    <!--表单数据-->
    <form method="post" id="IntegralForm" class="layui-form">
      <div class="layui-tab layui-tab-card">
        <div class="layui-form-item">
          <label class="layui-form-label">过期积分设置 <span class="x-red">*</span></label>
          <div class="layui-input-block">
            <input type="radio" name="is_integral_expired" value="1" @if($data['is_integral_expired'] == 1) checked @endif>
            一直有效，永不过期
            <br/>
            <br/>
            <input type="radio" name="is_integral_expired" @if($data['is_integral_expired'] == 2) checked @endif value="2">
            每年
            <div class="layui-inline">
              <select name="month" class="layui-form-selected" lay-search="">
                @for($i=1;$i<13;$i++)
                  <option value="{{$i}}">{{$i}}</option>
                @endfor
              </select>
            </div>
            月
            <div class="layui-inline">
              <select name="day" class="layui-form-selected">
                @for($i=1;$i<32;$i++)
                  <option value="{{$i}}">{{$i}}</option>
                @endfor
              </select>
            </div>
            日，凌晨0：00，清零之前的所有积分
          </div>
        </div>

        <div class="layui-form-item">
        </div>

        <div class="layui-form-item">
          <label class="layui-form-label">积分赠送规则 <span class="x-red">*</span></label>
          <div class="layui-input-block">
            <input type="checkbox" name="is_reg_integral" value="1" @if($data['is_reg_integral'] == 1) checked @endif>首次注册登录，可获得
            <div class="layui-inline">
              <input type="number" name="reg_integral" value="{{$data['reg_integral']}}" autocomplete="off" class="layui-input">
            </div>
            积分
            <br/>
            <br/>
            <input type="checkbox" name="invite" value="1" @if($data['invite'] == 1) checked @endif>已注册用户邀请其它用户注册，邀请人，可获得
            <div class="layui-inline">
              <input type="number" name="invite_integral" value="{{$data['invite_integral']}}" autocomplete="off" class="layui-input">
            </div>
            积分，被邀请人，可获得
            <div class="layui-inline">
              <input type="number" name="invited_integral" value="{{$data['invited_integral']}}" autocomplete="off" class="layui-input">
            </div>
            积分

          </div>
        </div>

        <div class="layui-form-item">
        </div>
        <div class="layui-form-item">
          <label class="layui-form-label">积分交易抵扣规则 <span class="x-red">*</span></label>
          <div class="layui-input-block">
            <input type="checkbox" name="is_not_integral" value="0" @if($data['is_not_integral'] == 0) checked @endif>非积分商品不能使用积分
            <br/>
            <input type="checkbox" name="is_point_min_limit" value="1" @if($data['is_point_min_limit'] == 1) checked @endif>积分小于
            <div class="layui-inline">
              <input type="number" name="point_min_limit" value="{{$data['point_min_limit']}}" autocomplete="off" class="layui-input">
            </div>
            时 ，不能使用积分<span class="x-red">（注：此规则适用非积分商品）</span>
            <br/>
            <input type="checkbox" name="is_point_rate" value="1" @if($data['is_point_rate'] == 1) checked @endif>消费时，积分可抵扣订单金额，每
            <div class="layui-inline">
              <select name="point_rate" class="layui-form-selected">
                <option value="">请选择</option>
                <option value="1" @if($data['point_rate'] == 1) selected @endif>1</option>
                <option value="10" @if($data['point_rate'] == 10) selected @endif>10</option>
                <option value="100" @if($data['point_rate'] == 100) selected @endif>100</option>
              </select>
            </div>
            积分抵扣1元
            <span class="x-red">（注：此规则适用所有商品, 此项若不勾选，则不能使用积分）</span>
          </div>
        </div>
        <div class="layui-form-item">
          <div class="layui-input-block">
            <button type="submit" class="layui-btn" lay-filter="save">确认提交</button>
            <button type="button" onclick="clears()" class="layui-btn layui-btn-danger">手动清零积分</button>
          </div>
        </div>
      </div>
    <input type="hidden" name="inc_type" value="integral">
  </form>
<script>
  layui.use(['table','form'], function(){
    var form = layui.form;
    form.on('submit(save)', function(data){
      var param = getFormJson('IntegralForm');
      var resStatus = commonAjax('{{url('admin/integral/index')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
      //发异步，把数据提交给php
      if(resStatus > 0){
        $('#submit').attr('disabled', true);
        layer.alert('配置成功，需清除缓存生效', {icon: 6},function () {
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

  function clears(){
    publicHandle('{{url('admin/integral/clear')}}',1,'post','{{url('admin/member/list')}}');
  }
</script>
@endsection