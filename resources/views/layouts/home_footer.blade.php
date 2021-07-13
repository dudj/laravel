@section('home_footer')
    <footer>
        <div class="home-footer">
            <div class="layui-container">
                <div class="intro">
                    <span class="first">
                        <i class="layui-icon layui-icon-home-shield"></i>
                        7天无理由退换货
                    </span>
                    <span class="second">
                        <i class="layui-icon layui-icon-home-car"></i>
                        满199元全场包邮
                    </span>
                    <span class="third">
                        <i class="layui-icon layui-icon-home-diamond"></i>
                        100%品质保证
                    </span>
                    <span class="last">
                        <i class="layui-icon layui-icon-home-tel"></i>
                        客服400-8888-888
                    </span>
                </div>
                <div class="about">
                    <span class="layui-breadcrumb" lay-separator="|" style="visibility: visible;">
                        <a href="/about.html">关于我们</a>
                        <span lay-separator="">|</span>
                        <a href="/help.html">帮助中心</a>
                        <span lay-separator="">|</span>
                        <a href="/service.html">售后服务</a>
                        <span lay-separator="">|</span>
                        <a href="/delivery.html">配送服务</a>
                        <span lay-separator="">|</span>
                        <a href="/supply.html">关于货源</a>
                    </span>
                    <p>版权 @ <?=config('app.frontName')?> - 更多信息 © 2012-2021</p>
                </div>
            </div>
        </div>
    </footer>
@endsection