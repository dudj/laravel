@extends('layouts.admin')
@section('content')
    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a><cite>账户资金记录</cite></a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <div class="layui-row">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <table class="layui-table layui-form" id="list" lay-filter="list">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="member_id" value="{{isset($member_id)?$member_id:''}}">
    {{--头部导航--}}
    <script type="text/html" id="toolbarHeader">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="add"><i class="layui-icon">&#xe654;</i>资金调整</button>
            <button class="layui-btn layui-btn-sm" onClick="javascript:location.href='{{url('admin/member/list')}}'"><i class="iconfont">&#xe697;</i>返回会员列表</button>
        </div>
    </script>
<script>
    layui.use('table', function(){
        var table = layui.table;
        var form = layui.form;
        var tableList = table.render({
            elem: '#list',
            id: 'list',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            where:{
                'member_id': '{{$member_id}}'
            },
            url:'{{url('admin/member/accountLog')}}',
            method:'post',
            toolbar:'#toolbarHeader',
            defaultToolbar: ['exports'],
            title: '账户资金记录列表',
            cols: [[
                {field:'id',width:'10%', title: 'ID', sort: true},
                {field:'member_money',width:'20%', title: '会员金额'},
                {field:'pay_points',width:'20%', title: '积分'},
                {field:'desc',width:'20%', title: '描述'},
                {field:'change_time',width:'30%', title: '生成时间',templet: formatDate}
            ]]
        });
        //监听头部导航
        table.on('toolbar(list)',function(obj){
            switch(obj.event){
                case 'add':
                        xadmin.open('管理会员资金','{{url('admin/member/editAccount?member_id='.$member_id)}}');
                    break;
            }
        });
        /**
         * 格式化时间
         * @param value
         * @returns {*}
         */
        function formatDate(val) {
            var date = new Date(val.change_time*1000);
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