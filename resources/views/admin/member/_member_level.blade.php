@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">会员管理</a>
            <a><cite>添加会员等级</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
      <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
    <!--表单数据-->
    <form method="post" id="memberLevelForm" class="layui-form">
    <div class="layui-tab layui-tab-card">
      <div class="layui-form-item">
        <label class="layui-form-label">等级名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="text" name="level_name" value="{{isset($data['level_name'])?$data['level_name']:''}}" lay-verify="level_name" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">等级额度 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="number" name="amount" value="{{isset($data['amount'])?$data['amount']:''}}" lay-verify="amount" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">等级折扣 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="number" name="discount" value="{{isset($data['discount'])?$data['discount']:''}}" autocomplete="off" lay-verify="discount" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">描述</label>
        <div class="layui-input-inline">
          <textarea name="description" class="layui-input">{{isset($data['description'])?$data['description']:''}}</textarea>
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button type="submit" class="layui-btn" lay-submit="" lay-filter="memberLevelSave">确认提交</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
      </div>
    </div>
    <input type="hidden" name="id" value="{{isset($data['id'])?$data['id']:''}}">
  </form>
<script>
  layui.use(['table','form'], function(){
    var form = layui.form;
    form.verify({
      level_name: function(value){
        if(value.length == ''){
          return '等级名称不能为空';
        }
      },
      amount: function(value){
        if(value.length == ''){
          return '等级额度不能为空';
        }
      },
      discount: function(value){
        if(value.length == ''){
          return '等级折扣不能为空';
        }
      }
    });
    form.on('submit(memberLevelSave)', function(data){
      var param = getFormJson('memberLevelForm');
      var resStatus = commonAjax('{{url('admin/member/addEditMemberLevel')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
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