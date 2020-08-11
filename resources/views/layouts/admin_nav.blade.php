@section('nav')
    <nav class="navbar navbar-default navbar-static-top">
        <!-- 顶部开始 -->
        <div class="container">
            <div class="logo" data-status = '1'>
                <a href="{{ url('/') }}">管理中心</a>
            </div>
            <div class="left_open">
                <a class="leftOperate"><i title="展开左侧栏" class="iconfont">&#xe699;</i></a>
            </div>
            <div class="layui-fluid" id="updatePwd" style="display: none">
                <div class="layui-row">
                    <div class="layui-form-item">
                        <label for="newpass" class="layui-form-label">
                            <span class="x-red">*</span>新密码
                        </label>
                        <div class="layui-input-inline">
                            <input type="password" id="newpass" name="newpass" required="" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="repass" class="layui-form-label">
                            <span class="x-red">*</span>确认密码
                        </label>
                        <div class="layui-input-inline">
                            <input type="password" id="repass" name="repass" required="" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <button class="layui-btn" lay-submit="" onclick="updatePwd()">确认</button>
                    </div>
                </div>
            </div>
            <ul class="layui-nav right" lay-filter="">
                <li class="layui-nav-item">
                    {{ auth()->guard('admin')->user()->name }}
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">
                        <img src="{{auth()->guard('admin')->user()->logo}}" height="40px"/>
                    </a>
                    <dl class="layui-nav-child">
                        <!-- 二级菜单 -->
                        <dd>
                            <a onclick="xadmin.open('个人信息','{{ url('/admin/common/user_info')}}',700,350)">个人信息</a>
                        </dd>
                        <dd>
                            <a href="{{ url('/admin/common/clear')}}">清除缓存</a>
                        </dd>
                        <dd>
                            <a onclick="openPwd()">修改密码</a>
                        </dd>
                        <dd>
                            <a href="{{ url('/admin/logout')}}">退出</a>
                        </dd>
                    </dl>
                </li>
                <li class="layui-nav-item to-index">
                    <a href="/">前台首页</a>
                </li>
            </ul>
        </div>
        <!-- 顶部结束 -->
    </nav>
    <script>
        function openPwd() {
            layer.open({
                type: 1,
                title:'修改密码',
                shadeClose: true,
                content: $('#updatePwd')
            });
        }
        function updatePwd(){
            var newpass = $('input[name="newpass"]').val();
            var repass = $('input[name="repass"]').val();
            if (newpass == '') {
                layer.msg('请输入密码！', '{icon:5}');
                return false;
            }
            if (repass == '') {
                layer.msg('请在一次输入密码！', '{icon:5}');
                return false;
            }
            if (newpass != repass) {
                layer.msg('两次密码不一至！请重新输入', '{icon:5}');
                return false;
            }
            var resStatus = commonAjax('{{url('admin/common/updatePwd')}}','post',Base64.encode(JSON.stringify('password='+newpass)),'json',false);
            //发异步，把数据提交给php
            if(resStatus > 0){
                layer.alert("修改成功", {icon: 6},function () {
                    $('input[name="newpass"]').val('');
                    $('input[name="repass"]').val('');
                    layer.closeAll();
                });
            }
        }
        $('.leftOperate').click(function(){
            var status = $('.logo').attr('data-status');
            if(status > 0){
                $('.logo').css('display','none');
                $('.logo').attr('data-status',0);
            }else{
                $('.logo').css('display','block');
                $('.logo').attr('data-status',1);
            }
        })
    </script>
@endsection