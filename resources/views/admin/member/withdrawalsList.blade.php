@extends('layouts.admin')
@section('content')
    <style>
        .layui-inline{
            width: 11%;
        }
    </style>
    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a>会员管理</a>
            <a>充值提现</a>
            <a><cite>会员提现申请记录列表</cite><span class="x-red"> 审核失败和作废的数据在列表中不展示</span></a>
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
                                <input type="text" name="username" value="{{request('username')}}" placeholder="会员昵称" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input" value="{{request('applytime_start')}}"  autocomplete="off" placeholder="起始时间" name="applytime_start" id="applytime_start">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input" value="{{request('applytime_end')}}"  autocomplete="off" placeholder="截止时间" name="applytime_end" id="applytime_end">
                            </div>
                            <div class="layui-inline">
                                <select name="status" id="status" lay-filter="status" class="layui-form-selected" lay-search="">
                                    <option value="">所有分类</option>
                                    <option value="-2">审核失败</option>
                                    <option value="0">审核中</option>
                                    <option value="1">审核通过</option>
                                </select>
                            </div>
                            <div class="layui-inline">
                                <input type="text" name="realname" value="{{request('realname')}}" placeholder="真实姓名" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-inline">
                                <input type="text" name="bankcard" value="{{request('bankcard')}}" placeholder="账号" autocomplete="off" class="layui-input">
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
            <button class="layui-btn layui-btn-sm" lay-event="pass"><i class="layui-icon">&#xe6af;</i>审核通过</button>
            <button class="layui-btn layui-btn-sm" lay-event="refuse"><i class="layui-icon">&#xe69c;</i>审核失败</button>
            <button class="layui-btn layui-btn-sm" lay-event="cancel"><i class="iconfont">&#xe6b7;</i>  作废</button>
        </div>
    </script>
<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        //执行一个laydate实例
        var startDate = laydate.render({
            elem: '#applytime_start', //指定元素
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
            elem: '#applytime_end',
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
        var jsonParam = getFormJson('listSearchForm');
        var tableList = table.render({
            elem: '#list',
            id: 'list',
            url:'{{url('admin/member/withdrawalsList')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            toolbar:'#toolbarHeader',
            defaultToolbar: ['filter', 'exports', 'print'],
            where:jsonParam,
            method:'post',
            sort: true,
            title: '提现列表',
            cols: [[
                {type:'checkbox',event:'check'},
                {field:'id', width:60, title: 'ID', sort: true},
                {field:'username', width:150, title: '会员昵称'},
                {field:'money', width:150, title: '申请金额', sort: true},
                {field:'taxfee', width:150, title: '手续费'},
                {field:'bankcard',width:250, title: '账号'},
                {field:'realname', width:250, title: '真实姓名', sort: true},
                {field:'applytime',width:250, title: '申请时间',templet: formatDate},
                {field:'status',width:250, title: '状态',templet: function(d){
                    //审核状态：-1 已删除 -2 审核失败  0 申请中 1 审核通过 2 支付成功 3 支付失败
                    switch(d.status){
                        case 0:
                            return '申请中';
                        break;
                        case 1:
                            return '审核通过';
                            break;
                        case 2:
                            return '支付成功';
                            break;
                        case 3:
                            return '支付失败';
                            break;
                        default:
                            return '出错了';
                            break;
                    }
                }}
            ]],
            page: true,
            done: function(res) {
                var i = 0;
                $(".layui-table-body.layui-table-main").find("input[name='layTableCheckbox']").each(function() {
                    if(i >= res.data.length){
                        return ;
                    }
                    if (res.data[i].status == 1) {
                        $(this).attr("disabled", 'disabled');
                        form.render('checkbox');
                    }
                    i++;
                });
            }
        });
        //监听头部导航
        table.on('toolbar(list)',function(obj){
            //获取选中状态 checkStatus参数是table的id值     
            var data = table.checkStatus('list');
            //获取选中数量
            var selectCount = data.data.length;
            if(selectCount == 0){
                layer.msg('小伙子，至少选中一项数据',function(){});
                return false;
            }
            var ids = "";
            for(var i=0; i<selectCount; i++){
                ids += data.data[i].id + ",";
            }
            switch(obj.event){
                case 'pass':
                    audit(1,ids,'审核通过');
                    break;
                case 'refuse':
                    layer.prompt({title: '请填写备注(必填)', formType: 2}, function(text, index){
                        audit(-2, ids, text);
                        layer.close(index);
                    });
                    break;
                case 'cancel':
                    layer.prompt({title: '请填写备注(必填)', formType: 2}, function(text, index){
                        audit(-1, ids, text);
                        layer.close(index);
                    });
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
        function audit(status,ids,remark){
            var refreshList = '{{url('admin/member/withdrawalsList')}}';
            layer.confirm('确认当前操作？', {
                        btn: ['确定', '取消'] //按钮
                    }, function () {
                        var loadingFlag;
                        // 确定
                        $.ajax({
                            url: '{{url('admin/member/withdrawalsEdit')}}',
                            type:'post',
                            data: {id:ids,status:status,remark:remark},
                            dataType:'JSON',
                            beforeSend: function () {
                                loadingFlag = layer.msg('正在操作，请稍候……', { icon: 16, shade: 0.01,shadeClose:false });
                            },
                            success: function (result) {
                                layer.closeAll();
                                if (result.code == 1){
                                    layer.msg(result.msg, {icon: 1, time: 1000},function(){
                                        location.href = refreshList;
                                    });
                                }else{
                                    layer.msg(result.msg, {icon: 2, time: 2000});
                                }
                            },error:function(){
                                layer.alert('网络异常', {icon: 2,time: 3000});
                            }
                        });
                    }, function (index) {
                        layer.close(index);
                    }
            );
        }
        form.on('checkbox(layTableAllChoose)', function(data){
            $.each($("input[name=layTableCheckbox]"), function (i, value) {
                if($(this).is(':disabled')){
                    $(this).prop('checked',false);
                }else{
                    var status = $(this).prop("checked");
                    if($(this).attr('lay-filter') == 'layTableAllChoose'){
                        $(this).prop('checked',status);
                    }else{
                        $(this).prop('checked',!status);
                    }
                }
            });
            form.render();
        });
        /**
         * 格式化时间
         * @param value
         * @returns {*}
         */
        function formatDate(val) {
            var date = new Date(val.applytime*1000);
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