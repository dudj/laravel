@extends('layouts.admin')
@section('content')
  <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">商品分类管理</a>
            <a><cite>网站文章分类添加与管理</cite></a>
          </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
          <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
  </div>
  <div class="layui-row">
      <div class="layui-row layui-col-space15">
          <div class="layui-col-md12">
              <div class="layui-card">
                  <div class="layui-card-body" style="overflow: auto;">
                      <table class="layui-table" id="categoryList" lay-filter="categoryList">
                      </table>
                  </div>
              </div>
          </div>
      </div>
  </div>
  {{--头部导航--}}
  <script type="text/html" id="toolbarHeader">
      <div class="layui-btn-container">
          <button class="layui-btn" lay-event="expand" id="btn-expand">全部展开</button>
          <button class="layui-btn" lay-event="fold" id="btn-fold">全部折叠</button>
          <button class="layui-btn" lay-event="refresh" id="btn-refresh">刷新表格</button>
          <button class="layui-btn" lay-event="add" id="btn-add">添加分类</button>
      </div>
  </script>
  {{--左侧菜单--}}
  <script type="text/html" id="bar">
      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
      @{{ d.level != 3?'<a class="layui-btn layui-btn-xs" lay-event="addson">增加子类</a>':'' }}
  </script>
  <!-- 是否将此类型推荐为热评 -->
  <script type="text/html" id="is_hot">
      <input type="checkbox" name="is_hot" data-id = "@{{ d.id }}" value="@{{ d.is_hot }}" lay-skin="switch" lay-text="是|否" lay-filter="isHotListen" @{{ d.is_hot == 1?'checked':'' }}/>
  </script>
  <!-- 是否展示 -->
  <script type="text/html" id="is_show">
      <input type="checkbox" name="is_show" data-id = "@{{ d.id }}" value="@{{ d.is_show }}" lay-skin="switch" lay-text="是|否" lay-filter="isShowListen" @{{ d.is_show == 1?'checked':'' }}/>
  </script>
  <script>
      layui.config({
          base: '/xadmin/lib/layui/lay/modules/'
      }).extend({
          treetable: 'treetable-lay/treetable'
      }).use(['layer', 'table', 'form', 'treetable'], function () {
          var treetable = layui.treetable;
          var table = layui.table;
          var form = layui.form;
          var jsonParam = getFormJson('categoryListSearchForm');
          var renderTable = function(){
              layer.load(2);
              treetable.render({
                  elem: '#categoryList',
                  id: 'categoryList',
                  url:'{{url('admin/goods/ajaxCategoryList')}}',
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  toolbar:'#toolbarHeader',
                  defaultToolbar: ['filter', 'exports', 'print'],
                  method:'get',
                  treeColIndex: 1,
                  treeSpid: 0,
                  treeIdName: 'id',
                  treePidName: 'parent_id',
                  treeDefaultClose: true,
                  title: '分类列表',
                  cols: [[
                      {field:'id', title: 'ID'},
                      {field:'name', width:200,title: '名称'},
                      {field:'mobile_name',width:150, title: 'APP端显示名称',edit:true},
                      {field:'parent_id', width:80, title: '父类id'},
                      {field:'level', title: '等级'},
                      {field:'sort_order', title: '排序', sort: true,edit:true},
                      {field:'is_hot', title: '是否推荐为热门',templet:"#is_hot"},
                      {field:'is_show', title: '是否显示',templet:"#is_show"},
                      {fixed: 'right', title:'操作', toolbar: '#bar', width:190}
                  ]],
                  done: function () {
                      layer.closeAll('loading');
                  }
              });
          };
          /**
           * 是否推荐
           */
          form.on('switch(isHotListen)', function(obj){
              var value = this.value == 1 ? 0 : 1;
              changeTableVal('{{url('admin/goods/changeCategory')}}', {'id':this.getAttribute('data-id'),'is_hot':value});
          });
          /**
           * 是否展示
           */
          form.on('switch(isShowListen)', function(obj){
              var value = this.value == 1 ? 0 : 1;
              changeTableVal('{{url('admin/goods/changeCategory')}}', {'id':this.getAttribute('data-id'),'is_show':value});
          });
          //监听行工具事件(操作)导航
          table.on('tool(categoryList)', function(obj){
              var data = obj.data;
              switch(obj.event){
                  case 'del':
                      publicHandle('{{url('admin/goods/deleteCategory')}}',obj.data.id,'get','{{url('admin/goods/categoryList')}}');
                      break;
                  case 'edit':
                      xadmin.open('编辑分类信息','{{url('admin/goods/addEditCategory?id=')}}'+data.id);
                      break;
                  case 'addson':
                      xadmin.open('添加子类信息','{{url('admin/goods/addEditCategory?parent_id=')}}'+data.id);
                      break;
              }
          });
          //监听单元格编辑
          table.on('edit(categoryList)', function(obj){
              var data = {};
              data[obj.field] = obj.value;
              data.id = obj.data.id;
              changeTableVal('{{url('admin/goods/changeCategory')}}',data);
          });
          table.on('toolbar(categoryList)',function(obj){
              switch(obj.event){
                  case 'expand':
                      treetable.expandAll('#categoryList');
                      break;
                  case 'fold':
                      treetable.foldAll('#categoryList');
                      break;
                  case 'refresh':
                      renderTable();
                      break;
                  case 'add':
                      xadmin.open('添加分类信息','{{url('admin/goods/addEditCategory')}}');
                      break;
              }
          });
          renderTable();
      });
  </script>
@endsection