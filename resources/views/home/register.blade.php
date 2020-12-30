<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title><?=config('app.frontName')?></title>
        <link href="{{ asset('xadmin/lib/layui/css/layui.css') }}" rel="stylesheet">
        <link href="{{ asset('home/css/register.css') }}" rel="stylesheet">
        <style>
            /* 覆盖原框架样式 */
            .layui-elem-quote{background-color: inherit!important;}
            .layui-input, .layui-select, .layui-textarea{background-color: inherit; padding-left: 30px;}
        </style>
    </head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-sm12 layui-col-md12 zyl_mar_01">
            <blockquote class="layui-elem-quote"><a href="{{url('/')}}"><?=config('app.frontName')?></a></blockquote>
        </div>
    </div>
</div>
<div class="layui-row">
    <div class="layui-col-sm12 layui-col-md12">
        <div class="layui-carousel zyl_login_height" lay-anim="fade" lay-indicator="none" lay-arrow="hover" style="width: 100%;height:100%">
            <div carousel-item="">
                <div class="layui-this">
                    <div class="zyl_login_cont">
                        <canvas style="display: none; background: rgba(0, 0, 0, 0);"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Carousel End -->

<!-- Footer -->
<div class="layui-row">
    <div class="layui-col-sm12 layui-col-md12 zyl_center zyl_mar_01">
        © 2020 - <?=config('app.frontName')?>注册界面 || <?=config('app.frontName')?>界面版权所有
    </div>
</div>
<!-- LoginForm -->
<div class="zyl_lofo_main">
    <fieldset class="layui-elem-field layui-field-title zyl_mar_02">
        <legend>欢迎注册 - <?=config('app.frontName')?></legend>
    </fieldset>
    <div class="layui-row layui-col-space15">
        <form class="layui-form zyl_pad_01" id="register" method="post" action="{{ url('/register') }}">
            {{ csrf_field() }}
            <div class="layui-col-sm12 layui-col-md12">
                <div class="layui-form-item">
                    <input type="text" name="username" value="{{ old('username') }}" lay-verify="username" autocomplete="off" placeholder="账号" class="layui-input">
                    <i class="layui-icon layui-icon-username zyl_lofo_icon"></i>
                </div>
            </div>
            <div class="layui-col-sm12 layui-col-md12">
                <div class="layui-form-item">
                    <input type="text" name="email" value="{{ old('email') }}" lay-verify="email" autocomplete="off" placeholder="邮箱" class="layui-input">
                    <i class="layui-icon layui-icon-email zyl_lofo_icon"></i>
                </div>
            </div>
            <div class="layui-col-sm12 layui-col-md12">
                <div class="layui-form-item">
                    <input type="text" name="mobile" value="{{ old('mobile') }}" lay-verify="mobile" autocomplete="off" placeholder="手机号" class="layui-input">
                    <i class="layui-icon layui-icon-cellphone zyl_lofo_icon"></i>
                </div>
            </div>
            <div class="layui-col-sm12 layui-col-md12">
                <div class="layui-form-item">
                    <input type="password" name="password" lay-verify="password" autocomplete="off" placeholder="密码" class="layui-input">
                    <i class="layui-icon layui-icon-password zyl_lofo_icon"></i>
                </div>
            </div>
            <div class="layui-col-sm12 layui-col-md12">
                <div class="layui-form-item" style="margin-left:25px;">
                    男 <input type="radio" name="sex" value="1" autocomplete="off"  class="layui-input">
                    女 <input type="radio" name="sex" value='2' autocomplete="off"  class="layui-input">
                    保密 <input type="radio" name="sex" value='0' autocomplete="off"  class="layui-input">
                    <i class="layui-icon layui-icon-radio zyl_lofo_icon"></i>
                </div>
            </div>
            <div class="layui-col-sm12 layui-col-md12">
                <div class="layui-form-item">
                    <input type="date" name="birthday" required autocomplete="off" placeholder="生日" class="layui-input">
                    <i class="layui-icon layui-icon-date zyl_lofo_icon"></i>
                </div>
            </div>
            <div class="layui-col-sm12 layui-col-md12">
                <div class="layui-row">
                    <div class="layui-col-xs4 layui-col-sm4 layui-col-md4">
                        <div class="layui-form-item">
                            <input type="text" name="captcha" id="captcha" lay-verify="required" autocomplete="off" placeholder="验证码" class="layui-input" maxlength="6">
                            <i class="layui-icon layui-icon-vercode zyl_lofo_icon"></i>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm6 layui-col-md6">
                        <img src="{{ captcha_src() }}" style="cursor: pointer;margin-left:15px;" onclick="this.src='{{ url('captcha/default') }}?s=' + Math.random()">
                    </div>
                </div>
            </div>
            <div class="layui-col-sm12 layui-col-md12">
                <a class="layui-btn layui-btn-xs" style="margin-left:5%" href="/login">已有账户，前往登录</a>
            </div>
            <div class="layui-col-sm12 layui-col-md12" style="margin-top:10px;">
                <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="register" class="layui-btn" type="submit">立即注册</button>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('xadmin/lib/layui/layui.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('home/js/jparticle.min.js') }}"></script>
<script src="{{ asset('js/custompackage.js') }}"></script>
<script src="{{ asset('js/base64.js') }}"></script>
<script>
    layui.use(['table','form'], function(){
        var form = layui.form;
        //自定义验证规则
        form.verify({
            username: function(value){
                if(value.length == ''){
                    return '用户昵称不能为空';
                }
            },
            password: [/(.+){6,18}$/, '密码必须6到18位'],
            email:[/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,'邮箱不符合要求'],
            mobile :[/^1\d{10}$/,'手机号不符合要求'],
        });
        form.on('submit(register)', function(data){
            var param = getFormJson('register');
            var resStatus = commonAjax('{{url('register')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
            //发异步，把数据提交给php
            if(resStatus > 0){
                layer.alert('注册成功', {icon: 6},function () {
                    window.location.href = '/';
                });
            }
            return false;
        });
        form.on('lay-event(login)',function(){
            alert(123);
            switch(obj.event){
                case 'login':
                    xadmin.open('添加会员','{{url('admin/member/addMember')}}');
                    break;
            }
        });
        var zyl_login_height = $(window).height()/1.3;
        var zyl_car_height = $(".zyl_login_height").css("cssText","height:" + zyl_login_height + "px!important");
        //粒子线条
        $(".zyl_login_cont").jParticle({
            background: "rgba(0,0,0,0)",//背景颜色
            color: "#fff",//粒子和连线的颜色
            particlesNumber:100,//粒子数量
            particle: {
                minSize: 1,//最小粒子
                maxSize: 3,//最大粒子
                speed: 30,//粒子的动画速度
            }
        });
    });
</script>
</body>
</html>