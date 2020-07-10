@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">商品管理</a>
            <a><cite>品牌详情</cite></a>
            <a><cite>品牌添加与管理</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
      <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
    <!--表单数据-->
    <form method="post" id="addEditBrandForm" class="layui-form">
    <div class="layui-tab layui-tab-card">
      <div class="layui-form-item">
        <label class="layui-form-label">品牌名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="text" name="name" value="{{isset($brand['name'])?$brand['name']:''}}" lay-verify="name" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">品牌网址</label>
        <div class="layui-input-inline">
          <input type="text" name="url" value="{{isset($brand['url'])?$brand['url']:''}}" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">所属分类 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <select name="parent_cat_id" id="parent_cat_id" class="layui-select" lay-filter="parent_cat_id">
            <option value="">请选择分类</option>
            @foreach($categoryList as $vo)
              @if(isset($level_cat['1']) && $vo['id'] == $level_cat['1'])
                <option value="{{$vo['id']}}" selected>{{$vo['name']}}</option>
              @else
                <option value="{{$vo['id']}}">{{$vo['name']}}</option>
              @endif
            @endforeach
          </select>
        </div>
        <div class="layui-input-inline">
          <select name="cat_id" id="cat_id" class="layui-select" lay-filter="cat_id">
            <option value="">请选择分类</option>
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">品牌logo</label>
        <div class="input-file-show">
          <span class="show">
                  <a id="img_a" target="_blank" class="nyroModal" rel="gal" href="{{isset($brand['logo'])?$brand['logo']:''}}">
                      <i id="img_i" class="iconfont"
                         onMouseOver="layer.tips('<img src={{isset($brand['logo'])?$brand['logo']:''}}>',this,{tips: [1, '#fff']});" onMouseOut="layer.closeAll();">&#xe6a8;</i>
                  </a>
          </span>
          <span class="type-file-box">
              <input type="text" id="imagetext" name="logo" value="{{isset($brand['logo'])?$brand['logo']:''}}" class="type-file-text">
              <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
              <input class="type-file-file" onClick="GetUploadify(1,'','brand','img_call_back')" size="30"
                     title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
          </span>
        </div>
        <p class="notic">请上传图片格式文件，建议图片尺寸281*180像素</p>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">排序</label>
        <div class="layui-input-inline">
          <input type="text" name="sort" value="{{isset($brand['sort'])?$brand['sort']:''}}" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">品牌描述</label>
        <div class="layui-input-inline">
          <textarea name="desc" class="layui-input">{{isset($brand['desc'])?$brand['desc']:''}}</textarea>
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button type="submit" class="layui-btn" lay-submit="" lay-filter="brandSave">确认提交</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
      </div>
    </div>
    <input type="hidden" name="id" value="{{isset($brand['id'])?$brand['id']:''}}">
    <input type="hidden" value="@if(isset($level_cat[1])){{$level_cat[1]}}@endif" name="level_cat_1" disabled="disabled"/>
    <input type="hidden" value="@if(isset($level_cat[2])){{$level_cat[2]}}@endif" name="level_cat_2" disabled="disabled"/>
  </form>
<script>
  layui.use(['table','form','laydate'], function(){
    var form = layui.form;
//        商品分类联动
    form.on('select(parent_cat_id)',function(data){
      var parent_cat_id = data.value;
      get_category('{{url('api/goods/getCategory')}}',parent_cat_id,'cat_id','0');
      $('#cat_id').html("<option value=''>请选择分类</option>");
    });
    form.verify({
      name: function(value){
        if(value.length == ''){
          return '品牌名称不能为空';
        }
      }
    });
    form.on('submit(brandSave)', function(data){
      var cat_id = $("#cat_id").val();
      if(cat_id == 0) {
        layer.msg('所属分类必须选择第二级！！', {icon: 2});return false;
      }
      var param = getFormJson('addEditBrandForm');
      var resStatus = commonAjax('{{url('admin/goods/addEditBrand')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
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
  function img_call_back(fileurl_tmp)
  {
    $("input[name='logo']").val(fileurl_tmp);
    $("#img_a").attr('href', fileurl_tmp);
    $("#img_i").attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
  }
  function initCategory(){
    var level_cat_1 = $.trim($("input[name='level_cat_1']").val());
    var level_cat_2 = $.trim($("input[name='level_cat_2']").val());
    if(level_cat_2 > 0 || level_cat_1 > 0){
      get_category('{{url('api/goods/getCategory')}}',level_cat_1,'cat_id',level_cat_2);
    }
  }
  $(document).ready(function(){
    initCategory();
  })
</script>
@endsection