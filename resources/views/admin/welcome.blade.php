<!doctype html>
<html  class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>管理中心</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link href="{{ asset('xadmin/css/font.css') }}" rel="stylesheet">
    <link href="{{ asset('xadmin/css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('xadmin/css/xadmin.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('xadmin/lib/layui/layui.js') }}"></script>
</head>
<body>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <blockquote class="layui-elem-quote">欢迎管理员：
                            <span class="x-red"><?=auth()->guard('admin')->user()->name?></span>！
                            <div class="sj">
                                当前时间:
                                <span></span>年
                                <span></span>月
                                <span></span>日
                                <span></span>时
                                <span></span>分
                                <span></span>秒
                            </div>
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">数据统计</div>
                    <div class="layui-card-body ">
                        <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <h3>文章数</h3>
                                    <p>
                                        <cite>66</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <h3>会员数</h3>
                                    <p>
                                        <cite>12</cite></p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">系统信息</div>
                    <div class="layui-card-body ">
                        <table class="layui-table">
                            <tbody>
                            <tr>
                                <th>laravel框架版本</th>
                                <td>5.3.0</td>
                            </tr>
                            <tr>
                                <th>运行环境</th>
                                <td>Apache/OpenSSL/Curl</td></tr>
                            <tr>
                                <th>PHP版本</th>
                                <td>>=5.6.4</td></tr>
                            <tr>
                                <th>PHP运行方式</th>
                                <td>cgi-fcgi</td></tr>
                            <tr>
                                <th>MYSQL版本</th>
                                <td>5.6</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">开发团队</div>
                    <div class="layui-card-body ">
                        <table class="layui-table">
                            <tbody>
                            <tr>
                                <th>版权所有</th>
                                <td>杜栋江(dudongjiang)</td>
                            </tr>
                            <tr>
                                <th>开发者</th>
                                <td>杜栋江(2045686313@qq.com)</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <style id="welcome_style"></style>
    </div>
    </div>
</body>
<script>
    $(document).ready(function(){
        function time() {
            var date = new Date();
            var n = date.getFullYear();
            var y = date.getMonth()+1;
            var t = date.getDate();
            var h = date.getHours();
            var m = date.getMinutes();
            var s = date.getSeconds();
            $('.sj span').eq(0).html(n);
            $('.sj span').eq(1).html(y);
            $('.sj span').eq(2).html(t);
            $('.sj span').eq(3).html(h);
            $('.sj span').eq(4).html(m);
            $('.sj span').eq(5).html(s);
            for (var i = 0; i < $('div').length; i++) {
                if ($('div').eq(i).text().length == 1) {
                    $('div').eq(i).html(function(index, html) {
                        return 0 + html;
                    });
                }
            }
        }
        time();
        setInterval(time, 1000);
    })
</script>
</html>

