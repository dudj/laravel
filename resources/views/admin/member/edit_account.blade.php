@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">管理资金</a>
            <a href="javascript:history.back();">调整用户余额和积分</a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
      <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
    <!--表单数据-->
    <form method="post" id="editAccountForm" class="layui-form">
    <div class="layui-tab layui-tab-card">
      <div class="layui-form-item">
        <label class="layui-form-label">金额</label>
        <div class="layui-input-inline">
          <select name="money_type" id="money_type" class="layui-form-selected">
            <option value="1">增加</option>
            <option value="-1">减少</option>
          </select>
        </div>
        <div class="layui-input-inline">
          <input type="number" min="0" lay-verify="member_money" name="member_money" lay-filter="money_focus" value="0" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">
          可用金额：{{$member['member_money']}}
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">积分</label>
        <div class="layui-input-inline">
          <select name="points_type" id="points_type" class="layui-form-selected">
            <option value="1">增加</option>
            <option value="-1">减少</option>
          </select>
        </div>
        <div class="layui-input-inline">
          <input type="number" lay-verify="pay_points" min="0" name="pay_points" value="0" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">
          可用积分：{{$member['pay_points']}}
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">冻结金额</label>
        <div class="layui-input-inline">
          <select name="frozen_type" id="frozen_type" class="layui-form-selected">
            <option value="1">增加冻结金额</option>
            <option value="-1">减少冻结金额</option>
          </select>
        </div>
        <div class="layui-input-inline">
          <input type="number" min="0" lay-verify="frozen_money" name="frozen_money" value="0" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">
          冻结资金：{{$member['frozen_money']}}
        </div>
        <div class="layui-form-mid layui-word-aux"><span class="x-blue">单位元, 当操作冻结资金时，金额那一栏不用填写数值。</span></div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">操作备注 <span class="x-red">*</span></label>
        <div class="layui-input-block">
          <textarea placeholder="描述" lay-verify="desc" id="desc" name="desc" class="layui-textarea"></textarea>
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button type="submit" class="layui-btn layui-btn-danger" lay-submit="" lay-filter="accountSave">确认提交</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
      </div>
    </div>
    <input type="hidden" name="member_id" value="{{isset($member['id'])?$member['id']:''}}">
  </form>
<script>
  $(function(){
    $("input[name='member_money']").on("focus",function(e){
      $("input[name='frozen_money']").val(0);
    });
    $("input[name='frozen_money']").on("focus",function(e){
      $("input[name='member_money']").val(0);
    });
  })
  layui.use(['table','form','laydate'], function(){
    var form = layui.form;
    form.verify({
      member_money: function(value){
        var money = '{{$member['member_money']}}';
        if($('#money_type').val() < 1){
          if(parseFloat(money) < parseFloat(value)){
            return '用户剩余金额不足';
          }
        }
      },
      frozen_money: function(value){
        var money = '{{$member['member_money']}}';
        if($('#frozen_type').val() < 1){
          if(parseFloat(money) < parseFloat(value)){
            return '用户剩余金额不足';
          }
        }
      },
      pay_points: function(value){
        var points = '{{$member['pay_points']}}';
        if($('#points_type').val() < 1){
          if(parseInt(points) < parseInt(value)){
            return '积分剩余不足';
          }
        }
      }
    });
    form.on('submit(accountSave)', function(data){
      var param = getFormJson('editAccountForm');
      var resStatus = commonAjax('{{url('admin/member/editAccount')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
      //发异步，把数据提交给php
      if(resStatus > 0){
        $('#submit').attr('disabled', true);
        var msg = '添加成功';
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