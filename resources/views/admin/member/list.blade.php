@extends('layouts.admin')
@section('content')
    <style>
        .layui-inline{
            width: 11%;
        }
    </style>
    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">会员管理</a>
            <a><cite>会员列表</cite></a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <div class="layui-row">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <form class="layui-form layui-col-space7" id="listSearchForm" onsubmit="return false">
                            <div class="layui-inline">
                                <input type="text" name="email" value="{{request('email')}}" placeholder="邮箱" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-inline">
                                <input type="text" name="username" value="{{request('username')}}" placeholder="昵称" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-inline">
                                <input type="text" name="mobile" value="{{request('mobile')}}" placeholder="手机" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input" value="{{request('register_time_start')}}"  autocomplete="off" placeholder="注册开始时间" name="register_time_start" id="register_time_start">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input" value="{{request('register_time_end')}}"  autocomplete="off" placeholder="注册结束时间" name="register_time_end" id="register_time_end">
                            </div>
                            <div class="layui-inline">
                                <select name="level" id="level" lay-filter="level" class="layui-form-selected" lay-search="">
                                    <option value="">所有分类</option>
                                    @foreach($levelList as $vo)
                                        <option value="{{$vo['id']}}">{{$vo['level_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="layui-inline">
                                <button class="layui-btn search" type="button" lay-skin="switch" lay-filter="search"><i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-body" style="overflow: auto;">
                        <table class="layui-table layui-form" id="list" lay-filter="list">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--头部导航--}}
    <script type="text/html" id="toolbarHeader">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="add"><i class="layui-icon">&#xe654;</i>添加会员</button>
        </div>
    </script>
<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //执行一个laydate实例
        var startDate = laydate.render({
            elem: '#register_time_start', //指定元素
            done: function (value, date) {
                if (value !== '') {
                    endDate.config.min.year = date.year;
                    endDate.config.min.month = date.month - 1;
                    endDate.config.min.date = date.date;
                } else {
                    endDate.config.min.year = '';
                    endDate.config.min.month = '';
                    endDate.config.min.date = '';
                }
            }
        });
        //执行一个laydate实例
        var endDate = laydate.render({
            elem: '#register_time_end',
            done: function (value, date) {
                if (value !== '') {
                    startDate.config.max.year = date.year;
                    startDate.config.max.month = date.month - 1;
                    startDate.config.max.date = date.date;
                } else {
                    startDate.config.max.year = '';
                    startDate.config.max.month = '';
                    startDate.config.max.date = '';
                }
            }
        });
    });
    layui.use('table', function(){
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var jsonParam = getFormJson('listSearchForm');
        var tableList = table.render({
            elem: '#list',
            id: 'list',
            url:'{{url('admin/member/ajaxList')}}',
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
                {field:'username', width:150, title: '会员昵称'},
                {field:'level_name', width:150, title: '等级'},
                {field:'total_amount', width:150, title: '累计消费'},
                {field:'email',width:250, title: '邮箱', templet: function (d) {
                    if(d.email_validated == 0){
                        return d.email + '(未验证)';
                    }
                    return d.email;
                }},
                {field:'mobile', width:250, title: '手机号', sort: true,templet: function (d) {
                    if(d.mobile_validated == 0){
                        return d.mobile + '(未验证)';
                    }
                    return d.mobile;
                }},
                {field:'member_money',width:150, title: '余额'},
                {field:'pay_points',width:150, title: '消费积分'},
                {field:'register_time',width:250, title: '注册日期',templet: formatDate},
                {fixed: 'right',width:250, title:'操作', align: 'center', templet: function () {
                    return '<a class="layui-btn layui-btn-xs" lay-event="detail">详情</a>\
                         <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="money">资金列表</a>\
                         <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="address">收获地址</a>';
                    }
                }
            ]],
            page: true
        });
        //监听头部导航
        table.on('toolbar(list)',function(obj){
            switch(obj.event){
                case 'add':
                        xadmin.open('添加会员','{{url('admin/member/addMember')}}');
                    break;
            }
        });
        //监听行工具事件(操作)导航
        table.on('tool(list)', function(obj){
            var data = obj.data;
            switch(obj.event){
                case 'detail':
                    location.href='{{url('admin/member/detail?id=')}}'+data.id;
                    break;
                case 'money':
                    location.href='{{url('admin/member/accountLog?member_id=')}}'+data.id;
                    break;
                case 'address':
                    location.href='{{url('admin/member/address?member_id=')}}'+data.id;
                    break;
            }
        });
        /**
         * 处理 排序 sort(table属性lay-filter的值)
         **/
        table.on('sort(list)', function(obj){
            $('input[name="sortfield"]').val(obj.field);
            $('input[name="sorttype"]').val(obj.type);
            //重新加载页面的数据
            table.reload('list', {
                where: {
                    'sortfield': obj.field,
                    'sorttype': obj.type
                }
            });
        });
        $('.search').on('click', function(){
            reloadTable();
        });
        function reloadTable(){
            table.reload('list', {
                where: getFormJson('listSearchForm')
            });
        }

        /**
         * 格式化时间
         * @param value
         * @returns {*}
         */
        function formatDate(val) {
            var date = new Date(val.register_time*1000);
            Y = date.getFullYear() + '-';
            M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
            D = date.getDate() + ' ';
            h = date.getHours() + ':';
            m = (date.getMinutes() < 10 ? '0'+(date.getMinutes()) : date.getMinutes()) + ':';
            s = (date.getSeconds() < 10 ? '0'+(date.getSeconds()) : date.getSeconds());
            return Y+M+D+h+m+s;
        }
    });
</script>
@endsection