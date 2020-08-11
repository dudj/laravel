@extends('layouts.admin')
@section('content')
    <style>
        .layui-inline{
            width: 11%;
        }
    </style>
    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">商品管理</a>
            <a><cite>商城所有商品索引及管理</cite></a>
            <a><cite>商品列表</cite></a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <div class="layui-row">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <form class="layui-form layui-col-space7" id="goodsListSearchForm" onsubmit="return false">
                            <div class="layui-inline">
                                <select name="suppliers_id" id="suppliers_id" lay-filter="suppliers_id" class="layui-form-selected">
                                    <option value="">选择供应商</option>
                                    <option value="0">平台</option>
                                    @foreach($supplierList as $vo)
                                        <option value="{{$vo['suppliers_id']}}">{{$vo['suppliers_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="layui-inline">
                                <select name="cat_id" id="cat_id" lay-filter="cat_id" class="layui-form-selected" lay-search="">
                                    <option value="">所有分类</option>
                                    @foreach($categoryList as $vo)
                                        <option value="{{$vo['id']}}">{{$vo['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="layui-inline">
                                <select name="brand_id" id="brand_id" lay-filter="brand_id" class="layui-form-selected" lay-search="">
                                    <option value="">所有品牌</option>
                                    @foreach($brandList as $vo)
                                        <option value="{{$vo['id']}}">{{$vo['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="layui-inline">
                                <select name="is_on_sale" class="select">
                                    <option value="">全部</option>
                                    <option value="1">上架</option>
                                    <option value="0">下架</option>
                                </select>
                            </div>
                            <div class="layui-inline">
                                <select name="intro" class="select">
                                    <option value="">全部</option>
                                    <option value="is_new">新品</option>
                                    <option value="is_recommend">推荐</option>
                                </select>
                            </div>
                            <div class="layui-inline">
                                <!--排序规则-->
                                <input type="hidden" name="sortfield" value="goods_id" />
                                <input type="hidden" name="sorttype" value="desc" />
                                <input type="text" name="key_word" value="{{request('key_word')}}" placeholder="搜索词..." autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-inline">
                                <button class="layui-btn search" type="button" lay-skin="switch" lay-filter="search"><i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-body" style="overflow: auto;">
                        <table class="layui-table layui-form" id="goodsList" lay-filter="goodsList">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--头部导航--}}
    <script type="text/html" id="toolbarHeader">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="delAll"><i class="layui-icon">&#xe640;</i>批量删除</button>
            <button class="layui-btn layui-btn-sm" lay-event="add"><i class="layui-icon">&#xe654;</i>添加商品</button>
        </div>
    </script>
    {{--右侧导航--}}
    <script type="text/html" id="bar">
        <div class="layui-inline">
            <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
            <a class="layui-btn layui-btn-xs" lay-event="addGoodsComment">添加商品评论</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </div>
    </script>
    {{--是否推荐--}}
    <script type="text/html" id="is_recommend">
        <input type="checkbox" name="is_recommend" data-id = "@{{ d.goods_id }}" value="@{{ d.is_recommend }}" lay-skin="switch" lay-text="是|否" lay-filter="isRecommendListen" @{{ d.is_recommend == 1?'checked':'' }}/>
    </script>
    {{--是否新品--}}
    <script type="text/html" id="is_new">
        <input type="checkbox" name="is_new" data-id = "@{{ d.goods_id }}" value="@{{ d.is_new }}" lay-skin="switch" lay-text="是|否" lay-filter="isNewListen" @{{ d.is_new == 1?'checked':'' }}/>
    </script>
    {{--是否热卖--}}
    <script type="text/html" id="is_hot">
        <input type="checkbox" name="is_hot" data-id = "@{{ d.goods_id }}" value="@{{ d.is_hot }}" lay-skin="switch" lay-text="是|否" lay-filter="isHotListen" @{{ d.is_hot == 1?'checked':'' }}/>
    </script>
    {{--是否上下架--}}
    <script type="text/html" id="is_on_sale">
        <input type="checkbox" name="is_on_sale" data-id = "@{{ d.goods_id }}" value="@{{ d.is_on_sale }}" lay-skin="switch" lay-text="是|否" lay-filter="isOnSaleListen" @{{ d.is_on_sale == 1?'checked':'' }}/>
    </script>
<script>
    layui.use('table', function(){
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var jsonParam = getFormJson('goodsListSearchForm');
        var tableList = table.render({
            elem: '#goodsList',
            id: 'goodsList',
            url:'{{url('admin/goods/ajaxGoodsList')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            toolbar:'#toolbarHeader',
            defaultToolbar: ['filter', 'exports', 'print'],
            where:jsonParam,
            method:'post',
            sort: true
            ,title: '商品列表',
            cols: [[
                {type:'checkbox'},
                {field:'goods_id', width:60, title: 'ID', sort: true},
                {field:'goods_name', width:300, title: '商品名称',templet:'#goodsDetail'},
                {field:'goods_sn', width:120, title: '货号', sort: true},
                {field:'suppliers_name', width:120, title: '供应商'},
                {field:'cat_name', width:120, title: '分类'},
                {field:'shop_price', width:100, title: '价格', sort: true},
                {field:'is_recommend', width:100, title: '推荐', templet: '#is_recommend', sort: true},
                {field:'is_new', width:100, title: '新品', templet: '#is_new', sort: true},
                {field:'is_hot', width:100, title: '热卖', templet: '#is_hot', sort: true},
                {field:'is_on_sale', width:100, title: '上/下架', templet: '#is_on_sale', sort: true},
                {field:'store_count', width:100, title: '库存', sort: true},
                {field:'sort', width:100, title: '排序', sort: true, edit:'text'},
                {fixed: 'right', title:'操作', toolbar: '#bar', width:'30%'}
            ]],
            page: true
        });
        //监听头部导航
        table.on('toolbar(goodsList)',function(obj){
            switch(obj.event){
                case 'delAll':
                    //获取选中状态 checkStatus参数是table的id值     
                    var data = table.checkStatus('goodsList');
                    //获取选中数量
                    var selectCount = data.data.length;
                    if(selectCount == 0){
                        layer.msg('至少选中一项数据',function(){});
                        return false;
                    }
                    var ids = "";
                    for(var i=0; i<selectCount; i++){
                        ids += data.data[i].goods_id + ",";
                    }
                    publicHandle('{{url('admin/goods/deleteGoods')}}',ids,'get','{{url('admin/goods/goodsList')}}');
                    break;
                case 'add':
                        xadmin.open('添加商品','{{url('admin/goods/addEditGoods')}}');
                    break;
            }
        });
        //监听行工具事件(操作)导航
        table.on('tool(goodsList)', function(obj){
            var data = obj.data;
            switch(obj.event){
                case 'del':
                    publicHandle('{{url('admin/goods/deleteGoods')}}',obj.data.goods_id,'get','{{url('admin/goods/goodsList')}}');
                    break;
                case 'edit':
                    xadmin.open('编辑商品','{{url('admin/goods/addEditGoods?goods_id=')}}'+data.goods_id);
                    break;
            }
        });
        /**
         * 处理 排序 sort(table属性lay-filter的值)
         **/
        table.on('sort(goodsList)', function(obj){
            $('input[name="sortfield"]').val(obj.field);
            $('input[name="sorttype"]').val(obj.type);
            //重新加载页面的数据
            table.reload('goodsList', {
                where: {
                    'sortfield': obj.field,
                    'sorttype': obj.type
                }
            });
        });
        /**
         *  监听是否推荐 处理的数据 均为相反的
         **/
        form.on('switch(isRecommendListen)', function(obj){
            var value = this.value == 1 ? 0 : 1;
            var resStatus = commonAjax('{{url('admin/goods/ajaxUpdate')}}', 'post', Base64.encode('goods_id='+this.getAttribute('data-id')+'&type=is_recommend&value='+value+'&status=one'),'json',false);
            if(resStatus < 0){
                reloadTable();
            }
        });
        /**
         *  监听是否为新品
         **/
        form.on('switch(isNewListen)', function(obj){
            var value = this.value == 1 ? 0 : 1;
            var resStatus = commonAjax('{{url('admin/goods/ajaxUpdate')}}', 'post', Base64.encode('goods_id='+this.getAttribute('data-id')+'&type=is_new&value='+value+'&status=one'),'json',false);
            if(resStatus < 0){
                reloadTable();
            }
        });
        /**
         *  监听是否热卖
         **/
        form.on('switch(isHotListen)', function(obj){
            var value = this.value == 1 ? 0 : 1;
            var resStatus = commonAjax('{{url('admin/goods/ajaxUpdate')}}', 'post', Base64.encode('goods_id='+this.getAttribute('data-id')+'&type=is_hot&value='+value+'&status=one'),'json',false);
            if(resStatus < 0){
                reloadTable();
            }
        });
        /**
         *  监听是否上下架
         **/
        form.on('switch(isOnSaleListen)', function(obj){
            var value = this.value == 1 ? 0 : 1;
            var resStatus = commonAjax('{{url('admin/goods/ajaxUpdate')}}', 'post', Base64.encode('goods_id='+this.getAttribute('data-id')+'&type=is_on_sale&value='+value+'&status=one'),'json',false);
            if(resStatus < 0){
                reloadTable();
            }
        });
        $('.search').on('click', function(){
            reloadTable();
        });
        //监听单元格编辑
        table.on('edit(goodsList)', function(obj){
            var resStatus = commonAjax('{{url('admin/goods/ajaxUpdate')}}', 'post', Base64.encode('goods_id='+obj.data.goods_id+'&type=sort&value='+this.value+'&status=one'),'json',false);
            if(resStatus < 0){
                reloadTable();
            }
        });
        function reloadTable(){
            table.reload('goodsList', {
                where: getFormJson('goodsListSearchForm')
            });
        }
    });
</script>
@endsection