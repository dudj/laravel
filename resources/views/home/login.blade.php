<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>LD奢侈品登录</title>
        <link href="{{ asset('xadmin/lib/layui/css/layui.css') }}" rel="stylesheet">
        <link href="{{ asset('home/css/login.css') }}" rel="stylesheet">
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
            <blockquote class="layui-elem-quote"><a href="{{url('/')}}">LD奢侈品</a></blockquote>
        </div>
    </div>
</div>
<div class="layui-row">
    <div class="layui-col-sm12 layui-col-md12">
        <div class="layui-carousel zyl_login_height" lay-anim="fade" lay-indicator="none" lay-arrow="hover" style="width: 100%;">
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
        © 2020 - LD奢侈品登录界面 || LD界面版权所有
    </div>
</div>
<!-- LoginForm -->
<div class="zyl_lofo_main">
    <fieldset class="layui-elem-field layui-field-title zyl_mar_02">
        <legend>欢迎登录 - LD商城</legend>
    </fieldset>
    <div class="layui-row layui-col-space15">
        <form class="layui-form zyl_pad_01" method="post" action="{{ url('/login') }}">
            <div class="layui-col-sm12 layui-col-md12">
                <div class="layui-form-item">
                    <input type="text" name="username" lay-verify="required|username" autocomplete="off" placeholder="账号" class="layui-input">
                    <i class="layui-icon layui-icon-username zyl_lofo_icon"></i>
                </div>
            </div>
            <div class="layui-col-sm12 layui-col-md12">
                <div class="layui-form-item">
                    <input type="password" name="password" lay-verify="required|password" autocomplete="off" placeholder="密码" class="layui-input">
                    <i class="layui-icon layui-icon-password zyl_lofo_icon"></i>
                </div>
            </div>
            <div class="layui-col-sm12 layui-col-md12">
                <div class="layui-row">
                    <div class="layui-col-xs4 layui-col-sm4 layui-col-md4">
                        <div class="layui-form-item">
                            <input type="text" name="captcha" id="captcha" lay-verify="required|captcha" autocomplete="off" placeholder="验证码" class="layui-input" maxlength="6">
                            <i class="layui-icon layui-icon-vercode zyl_lofo_icon"></i>
                        </div>
                    </div>
                    <div class="layui-col-xs6 layui-col-sm6 layui-col-md6">
                        <img src="{{ captcha_src() }}" style="cursor: pointer;margin-left:15px;" onclick="this.src='{{ url('captcha/default') }}?s=' + Math.random()">
                    </div>
                </div>
            </div>
            <div class="layui-col-sm12 layui-col-md12">
                <button class="layui-btn layui-btn-fluid" class="layui-btn" lay-submit="" type="submit"  lay-filter="login">立即登录</button>
                <a class="layui-btn layui-btn-xs" lay-event="edit">免费注册</a>
                <a class="layui-btn layui-btn-xs" lay-event="edit">忘记密码？</a>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('xadmin/lib/layui/layui.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/custompackage.js') }}"></script>
<script src="{{ asset('home/js/jparticle.min.js') }}"></script>
<script>
    layui.use([ 'form'], function(){
        var form = layui.form;
        //自定义验证规则
        form.verify({
            username: function(value){
                if(value.length < 6){
                    return '名称至少得6个字符';
                }
            },
            password: [/^[\S]{6,12}$/,'密码必须6到12位，且不能出现空格']
        });
        var username = '{{$errors->first('username')}}';
        var password = '{{$errors->first('password')}}';
        var captcha = '{{$errors->first('captcha')}}';
        if(username){
            layer.msg(username);
        }
        if(password){
            layer.msg(password);
        }
        if(captcha){
            layer.msg(captcha);
        }
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