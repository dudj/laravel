@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">商品管理</a>
            <a><cite>分类</cite></a>
            <a><cite>添加或修改</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
      <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
    <!--表单数据-->
    <form method="post" id="addEditCategoryForm" class="layui-form">
    <div class="layui-tab layui-tab-card">
      <div class="layui-form-item">
        <label class="layui-form-label">分类名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="text" name="name" value="{{isset($data['name'])?$data['name']:''}}" lay-verify="name" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">手机分类名称 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="text" name="mobile_name" value="{{isset($data['mobile_name'])?$data['mobile_name']:''}}" lay-verify="mobile_name" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">上级分类</label>
        <div class="layui-input-inline">
          <input type="text" name="parent_id" lay-verify="parent_id" id="parent_id" lay-filter="parent_id" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item goods_shipping">
        <label class="layui-form-label">导航显示 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          @if(isset($data['is_show']) && $data['is_show'] == 1)
            <input type="checkbox" name="is_show" lay-filter="is_show" checked lay-skin="switch" value="1" lay-text="是|否">
          @else
            <input type="checkbox" name="is_show" lay-filter="is_show" lay-skin="switch" value="0" lay-text="是|否">
          @endif
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">分类展示图片</label>
        <div class="input-file-show">
          <span class="show">
                  <a id="img_a" target="_blank" class="nyroModal" rel="gal" href="{{isset($data['image'])?$data['image']:''}}">
                      <i id="img_i" class="iconfont"
                         onMouseOver="layer.tips('<img src={{isset($data['image'])?$data['image']:''}}>',this,{tips: [1, '#fff']});" onMouseOut="layer.closeAll();">&#xe6a8;</i>
                  </a>
          </span>
          <span class="type-file-box">
              <input type="text" id="imagetext" name="image" value="{{isset($data['image'])?$data['image']:''}}" class="type-file-text">
              <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
              <input class="type-file-file" onClick="GetUploadify(1,'','category','img_call_back')" size="30"
                     title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
          </span>
        </div>
        <p class="notic">此分类图片用于手机端显示, 并有且仅是第三级分类上传的图片才有效,图片建议尺寸:100*100(px)</p>
      </div>

      <div class="layui-form-item">
        <label class="layui-form-label">排序 <span class="x-red">*</span></label>
        <div class="layui-input-inline">
          <input type="text" lay-verify="sort_order" name="sort_order" value="{{isset($data['sort_order'])?$data['sort_order']:''}}" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button type="submit" class="layui-btn" lay-submit="" lay-filter="categorySave">确认提交</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
      </div>
    </div>
    <input type="hidden" name="id" value="{{isset($data['id'])?$data['id']:''}}">
    <input type="hidden" name="parent_id_val" value="{{isset($data['parent_id'])?$data['parent_id']:''}}">
  </form>
<script>
  layui.config({
    base: '/xadmin/lib/layui/lay/modules/'
  }).extend({
    treeSelect: 'treeSelect/treeSelect'
  }).use(['table','form','treeSelect'], function(){
    var form = layui.form;
    form.verify({
      name: function(value){
        if(value.length == ''){
          return '分类名称不能为空';
        }
      },
      mobile_name: function(value){
        if(value.length == ''){
          return '手机分类名称不能为空';
        }
      },
      sort_order: function(value){
        if(value.length == ''){
          return '排序不能为空';
        }
      },
    });
    var treeSelect= layui.treeSelect;
    treeSelect.render({
      elem: '#parent_id',
      data: '{{url('admin/goods/ajaxTreeSelectCategoryList')}}',
      type: 'get',
      placeholder: '请选择',
      search: true,
      click: function(d){
        $('input[name="parent_id"]').val(d.current.id);
      },
      success: function (d) {
        var parent_id = $('input[name="parent_id_val"]').val();
        if(parent_id > 0){
          treeSelect.checkNode('parent_id', parent_id);
          $('input[name="parent_id"]').val(parent_id);
        }
      }
    });
    form.on('submit(categorySave)', function(data){
      var param = getFormJson('addEditCategoryForm');
      param.is_show = param.is_show?param.is_show:0;
      var resStatus = commonAjax('{{url('admin/goods/addEditCategory')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
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
    $("input[name='image']").val(fileurl_tmp);
    $("#img_a").attr('href', fileurl_tmp);
    $("#img_i").attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
  }
</script>
@endsection