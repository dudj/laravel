@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">页面管理</a>
            <a>导航</a>
            <a><cite>导航数据操作</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
      <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
    <!--表单数据-->
    <form method="post" id="addEditForm" class="layui-form">
    <div class="layui-tab layui-tab-card">
      <div class="layui-form-item">
        <label class="layui-form-label">导航位置 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <select name="position" id="position" class="layui-select">
            @foreach($positionData as $k=>$v)
              @if(isset($data['position']) && $k == $data['position'])
                <option value="{{$k}}" selected>{{$v}}</option>
              @else
                <option value="{{$k}}">{{$v}}</option>
              @endif
            @endforeach
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="text" name="name" required value="{{isset($data['name'])?$data['name']:''}}" autocomplete="off" class="layui-input">
        </div>
        <label class="layui-form-label">英文名称</label>
        <div class="layui-input-inline">
          <input type="text" name="eng_name" value="{{isset($data['eng_name'])?$data['eng_name']:''}}" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">导航url <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="text" name="url" required value="{{isset($data['url'])?$data['url']:''}}" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux"><span class="x-grey">前台页面展示的url：/home/index</span></div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">排序 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="number" min="0" required name="sort" value="{{isset($data['sort'])?$data['sort']:''}}" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button type="submit" class="layui-btn" lay-submit="" lay-filter="dataSave">确认提交</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
      </div>
    </div>
    <input type="hidden" name="id" value="{{isset($data['id'])?$data['id']:''}}">
  </form>
<script>
  layui.use(['table','form','laydate'], function(){
    var form = layui.form;
    form.on('submit(dataSave)', function(data){
      var param = getFormJson('addEditForm');
      var resStatus = commonAjax('{{url('admin/system/addEditNav')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
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