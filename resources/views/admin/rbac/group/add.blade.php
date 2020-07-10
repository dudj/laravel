@extends('layouts.admin')
@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form action="" method="post" class="layui-form layui-form-pane">
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>角色名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" required="" lay-verify="name" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">
                    权限
                </label>
                <div class="layui-form-mid layui-word-aux"><span class="x-red">*</span>层级必须明确，选择二级必须选择其父类，选择三级必须选择一二级的父类 例子：选择管理员删除，必须选择管理员管理(二级)和系统管理(一级)</div>
                <table  class="layui-table layui-input-block">
                    <thead>
                        <tr>
                            <td>查看全部</td>
                            <td>
                                <input name="isall" lay-skin="primary" lay-verify="isall" type="radio" title="是" value="1"/>
                                <input name="isall" lay-skin="primary" lay-verify="isall" checked type="radio" value="0" title="否"/>
                            </td>
                        </tr>
                        <tr>
                            <td>一级</td>
                            <td>二级|三级</td>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($menus as $val)
                        <tr>
                            <td>
                                <input name="nodestr" class="ids" lay-skin="primary" lay-filter="father" type="checkbox" title="{{$val['name']}}" value="{{$val['id']}}">
                            </td>
                            <td>
                                <div class="layui-input-block">
                                    @if(isset($val['list']))
                                        @foreach($val['list'] as $v)
                                            <input name="nodestr" class="ids" lay-skin="primary" type="checkbox" title="{{$v['name']}}" value="{{$v['id']}}">|
                                        {{--三级数据 需要展示赋予权限--}}
                                                @if(isset($v['list']))
                                                    @foreach($v['list'] as $vthree)
                                                        <input name="nodestr" class="ids" lay-skin="primary" type="checkbox" title="{{$vthree['name']}}" value="{{$vthree['id']}}">
                                                    @endforeach
                                                @else
                                                @endif
                                            <br/>
                                        @endforeach
                                    @else
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="layui-form-item layui-form-text">
                <label for="desc" class="layui-form-label">
                    描述
                </label>
                <div class="layui-input-block">
                    <textarea placeholder="描述" id="description" lay-verify="description" name="description" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="add">增加</button>
            </div>
        </form>
    </div>
</div>
<script>
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form,layer = layui.layer;
        form.verify({
            name: function(value) {
                if (value.length < 1) {
                    return '名称必填';
                }
            },
            isall: function(value) {
                var isall = $("input[name='isall']:checked").val();
                if (isall == 0) {
                    var check_val = [];
                    $('.ids:checked').each(function(){
                        check_val.push($(this).val());
                    });
                    if(check_val == ''){
                        return '请选择权限分配的权限节点';
                    }
                }
            },
            description: function(value) {
                if (value.length < 1) {
                    return '描述名称必填';
                }
            }
        });
        //监听提交
        form.on('submit(add)', function(data){
            var resStatus = commonAjax('{{url('admin/group/store')}}', 'post', Base64.encode(JSON.stringify(data.field)), 'json',false);
            //发异步，把数据提交给php
            if(resStatus > 0){
                layer.alert("增加成功", {icon: 6},function () {
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
        //form.render(); 重新渲染
        form.on('radio', function(data){
            if(data.value == 1){
                $('.ids').attr('checked',true);
                form.render();
            }else{
                $('.ids').attr('checked',false);
                form.render();
            }
        });
        form.on('checkbox(father)', function(data){
            if(data.elem.checked){
                $(data.elem).parent().siblings('td').find('input').prop("checked", true);
                form.render();
            }else{
                $(data.elem).parent().siblings('td').find('input').prop("checked", false);
                form.render();
            }
        });
    });
</script>
@endsection