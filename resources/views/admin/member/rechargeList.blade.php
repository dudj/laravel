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
            <a>充值提现</a>
            <a><cite>充值列表</cite></a>
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
                            <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input" value="{{request('createtime_start')}}"  autocomplete="off" placeholder="开始时间" name="createtime_start" id="createtime_start">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input" value="{{request('createtime_end')}}"  autocomplete="off" placeholder="结束时间" name="createtime_end" id="createtime_end">
                            </div>
                            <div class="layui-inline">
                                <input type="text" name="nickname" value="{{request('nickname')}}" placeholder="会员昵称" autocomplete="off" class="layui-input">
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
<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //执行一个laydate实例
        var startDate = laydate.render({
            elem: '#createtime_start', //指定元素
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
            elem: '#createtime_end',
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
            url:'{{url('admin/member/rechargeList')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            toolbar:'#toolbarHeader',
            defaultToolbar: ['filter', 'exports', 'print'],
            where:jsonParam,
            method:'post',
            sort: true,
            title: '充值列表',
            cols: [[
                {field:'id', title: 'ID', sort: true},
                {field:'nickname', title: '会员昵称'},
                {field:'ordersn', title: '充值单号'},
                {field:'amount', title: '充值金额'},
                {field:'createtime', title: '订单生成时间',templet: formatDate},
                {field:'payname', title: '支付名称'},
                {field:'status', title: '支付状态',templet:function(d){
                    switch(d.status){
                        case 0:
                            return '未支付';
                            break;
                        case 1:
                            return '已支付';
                            break;
                        case 2:
                            return '已关闭';
                            break;
                        default:
                            return '支付方式有误';
                        break;
                    }
                }}
            ]],
            page: true
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
            var date = new Date(val.createtime*1000);
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