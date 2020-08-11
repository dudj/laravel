@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">插件设置</a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
      <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
    <!--表单数据-->
    <form method="post" id="editForm" class="layui-form">
    <div class="layui-tab layui-tab-card">
      @foreach($data['config'] as $key=>$val)
      <div class="layui-form-item">
        <label class="layui-form-label">{{$val['label']}}</label>
        <div class="layui-input-inline">
          @if($val['type'] == 'select')
            <select name="config[{{$val['name']}}]" class="layui-form-selected">
              @foreach($val['option'] as $k=>$v)
                @if($val['config_value'][$val['name']] == $k)
                  <option value="{{$v}}" selected>{{$v}}</option>
                @else
                  <option value="{{$v}}">{{$v}}</option>
                @endif
              @endforeach
            </select>
          @elseif($val['type'] == 'textarea')
            <textarea rows="6" cols="90" name="config[{{$val['name']}}]">{{$val['config_value'][$val['name']]}}</textarea>
          @elseif($val['type'] == 'file')
            <span class="type-file-box">
              <input type="text"  name="config[{{$val['name']}}]" value="{{$data['config_value'][$val['name']]}}" class="type-file-text" >
              <input type="button"  id="button1" value="选择上传..." class="type-file-button">
              <input class="type-file-file" onchange="uploadCert(this)" type="file" title="上传文件" >
            </span>
          @else
            <input type="{{$val['type']}}" value="{{$data['config_value'][$val['name']]}}" name="config[{{$val['name']}}]" class="layui-input"/>
          @endif
        </div>
      </div>
      @endforeach
        <div class="layui-form-item">
          <div class="layui-input-block">
            <p class="notic" @if($data['name'] == 'alipay_private_key') style="color:red;font-weight:bold"@endif >{{$data['desc']}}</p>
          </div>
        </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button type="submit" class="layui-btn" lay-submit="" lay-filter="save">确认提交</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
      </div>
    </div>
    <input type="hidden" name="code" value="{{isset($data['code'])?$data['code']:''}}">
    <input type="hidden" name="type" value="{{isset($data['type'])?$data['type']:''}}">
  </form>
<script>
  layui.use(['table','form','laydate'], function(){
    var form = layui.form;
    form.on('submit(save)', function(data){
      var param = getFormJson('editForm');
      var resStatus = commonAjax('{{url('admin/plugins/setting')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
      //发异步，把数据提交给php
      if(resStatus > 0){
        $('#submit').attr('disabled', true);
        layer.alert('配置成功', {icon: 6},function () {
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