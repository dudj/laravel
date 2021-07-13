@section('home_nav')
    <header>
        <div class="home-header">
            <div class="layui-container">
                <div class="home-nav">
                    <span class="layui-breadcrumb memberinfo" lay-separator="|" style="visibility: visible;">
                        @if(auth('home')->check())
                            <a style="margin-right:5px;color:#5CE1E6;" href="{{url('member/center')}}">用戶中心</a><span lay-separator="">|</span>
                            <a href="/member/">我的订单</a><span lay-separator="">|</span>
                            <a style="margin-right:5px;color:#5CE1E6;" href="{{url('logout')}}">退出</a><span lay-separator="">|</span>
                        @else
                            <a style="margin-right:5px;color:#5CE1E6;" href="{{url('login')}}">登录</a><span lay-separator="">|</span>
                            <a href="{{url('register')}}">注册</a><span lay-separator="">|</span>
                        @endif
                        <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=858265175&amp;site=qq&amp;menu=yes" target="_blank">在线客服</a>
                    </span>
                    <span class="layui-breadcrumb home-breadcrumb-icon" lay-separator=" " style="visibility: visible;">
                        <a id="search"><i class="layui-icon layui-icon-home-find"></i></a>
                        <span lay-separator=""> </span>
                        <a href="/member/">
                            <i class="layui-icon layui-icon-username"></i>
                        </a>
                        <span lay-separator=""> </span>
                        <a href="/plus/car.php">
                            <i class="layui-icon layui-icon-home-shop"></i>
                        </a>
                    </span>
                </div>
                <div class="home-banner layui-form">
                    <a class="banner" href="/">
                        <img src="/home/images/Ld.png" style="width:160px;height:80px">
                    </a>
                    <div class="layui-input-inline">
                        <input type="text" placeholder="搜索好物" class="layui-input">
                        <i class="layui-icon layui-icon-home-find"></i>
                    </div>
                    <a class="shop" href="/purchase/cart">
                        <i class="layui-icon layui-icon-home-shop"></i>
                        <span class="layui-badge totalNum">0</span>
                    </a>
                </div>
                <ul class="layui-nav close">
                    <li class="layui-nav-item layui-this">
                        <a href="/">首页</a>
                    </li>
                    <li class="layui-nav-item ">
                        <a href="/home/">居家用品</a>
                    </li>
                    <li class="layui-nav-item ">
                        <a href="/device/">小家电</a>
                    </li>
                    <li class="layui-nav-item ">
                        <a href="/wash/">洗护</a>
                    </li>
                    <li class="layui-nav-item ">
                        <a href="/kitchen/">厨具</a>
                    </li>
                    <li class="layui-nav-item ">
                        <a href="/supplies/">日用品</a>
                    </li>
                    <span class="layui-nav-bar" style="left: 949px; top: 60px; width: 0px; opacity: 0;"></span>
                </ul>
                <button id="switch">
                    <span></span>
                    <span class="second"></span>
                    <span class="third"></span>
                </button>
            </div>
        </div>
    </header>
@endsection