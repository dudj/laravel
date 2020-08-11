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
            <a><cite>品牌列表</cite></a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <div class="layui-row">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <form class="layui-form layui-col-space7" id="brandListSearchForm" onsubmit="return false">
                            <div class="layui-inline">
                                <!--排序规则-->
                                <input type="text" name="keyword" value="{{request('keyword')}}" placeholder="搜索词..." autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-inline">
                                <button class="layui-btn search" type="button" lay-skin="switch" lay-filter="search"><i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-body" style="overflow: auto;">
                        <table class="layui-table layui-form" id="brandList" lay-filter="brandList">
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
            <button class="layui-btn layui-btn-sm" lay-event="add"><i class="layui-icon">&#xe654;</i>添加品牌</button>
        </div>
    </script>
    {{--是否推荐--}}
    <script type="text/html" id="is_recommend">
        <input type="checkbox" name="is_recommend" data-id = "@{{ d.id }}" value="@{{ d.is_recommend }}" lay-skin="switch" lay-text="是|否" lay-filter="isRecommendListen" @{{ d.is_recommend == 1?'checked':'' }}/>
    </script>
<script>
    layui.use('table', function(){
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var jsonParam = getFormJson('brandListSearchForm');
        var tableList = table.render({
            elem: '#brandList',
            id: 'brandList',
            url:'{{url('admin/goods/ajaxBrandList')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            toolbar:'#toolbarHeader',
            defaultToolbar: ['filter', 'exports', 'print'],
            where:jsonParam,
            method:'post',
            sort: true,
            title: '商品列表',
            cols: [[
                {type:'checkbox'},
                {field:'id', width:60, title: 'ID', sort: true},
                {field:'name', width:150, title: '名称'},
                {field:'Logo',width:200, title: 'Logo', templet: function (d) {
                    var logo = '';
                    if(d.logo == ''){
                        logo = '/images/icon_goods_thumb_empty_300.jpg';
                    }else{
                        logo = d.logo;
                    }
                    return '<img src='+logo+' width="40px" onMouseOver="layer.tips(\'<img src='+logo+'>\',this,{tips:[1,\'#fff\']})" onMouseOut="layer.closeAll()" height="30px"/>';
                }},
                {field:'sort', width:120, title: '排序', edit:true, sort: true},
                {field:'cat_name', title: '品牌分类'},
                {field:'is_recommend', title: '推荐', templet: '#is_recommend', sort: true},
                {fixed: 'right', title:'操作', align: 'center', templet: function () {
                    return '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>\
                         <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>';
                    }
                }
            ]],
            page: true
        });
        //监听头部导航
        table.on('toolbar(brandList)',function(obj){
            switch(obj.event){
                case 'delAll':
                    //获取选中状态 checkStatus参数是table的id值     
                    var data = table.checkStatus('brandList');
                    //获取选中数量
                    var selectCount = data.data.length;
                    if(selectCount == 0){
                        layer.msg('至少选中一项数据',function(){});
                        return false;
                    }
                    var ids = "";
                    for(var i=0; i<selectCount; i++){
                        ids += data.data[i].id + ",";
                    }
                    publicHandle('{{url('admin/goods/deleteBrand')}}',ids,'get','{{url('admin/goods/brandList')}}');
                    break;
                case 'add':
                        xadmin.open('添加品牌','{{url('admin/goods/addEditBrand')}}');
                    break;
            }
        });
        //监听行工具事件(操作)导航
        table.on('tool(brandList)', function(obj){
            var data = obj.data;
            switch(obj.event){
                case 'del':
                    publicHandle('{{url('admin/goods/deleteBrand')}}',obj.data.id,'get','{{url('admin/goods/brandList')}}');
                    break;
                case 'edit':
                    xadmin.open('编辑品牌','{{url('admin/goods/addEditBrand?id=')}}'+data.id);
                    break;
            }
        });
        /**
         * 处理 排序 sort(table属性lay-filter的值)
         **/
        table.on('sort(brandList)', function(obj){
            $('input[name="sortfield"]').val(obj.field);
            $('input[name="sorttype"]').val(obj.type);
            //重新加载页面的数据
            table.reload('brandList', {
                where: {
                    'sortfield': obj.field,
                    'sorttype': obj.type
                }
            });
        });
        /**
         * 是否推荐
         */
        form.on('switch(isRecommendListen)', function(obj){
            var value = this.value == 1 ? 0 : 1;
            changeTableVal('{{url('admin/goods/changeBrand')}}', {'id':this.getAttribute('data-id'),'is_recommend':value});
        });
        $('.search').on('click', function(){
            reloadTable();
        });
        //监听单元格编辑
        table.on('edit(brandList)', function(obj){
            var data = {};
            data[obj.field] = obj.value;
            data.id = obj.data.id;
            changeTableVal('{{url('admin/goods/changeBrand')}}',data);
        });
        function reloadTable(){
            table.reload('brandList', {
                where: getFormJson('brandListSearchForm')
            });
        }
    });
</script>
@endsection