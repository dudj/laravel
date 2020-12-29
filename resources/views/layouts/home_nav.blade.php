@section('home_nav')
    <header>
        <div class="wrap-header">
            {{--nav--}}
            <div class="nav">
                <span class="nologin">
                    @if(auth('home')->check())
                        <a style="margin-right:5px;color:#5CE1E6;" href="{{url('member/center')}}">用戶中心</a>
                    @else
                        <a style="margin-right:5px;color:#5CE1E6;" href="{{url('login')}}">登录</a>
                        <a href="{{url('register')}}">注册</a>
                    @endif
                </span>
                <ul>
                    <li><a href="{{url('/')}}"><span>我的订单</span></a></li>
                    <li><a href="{{url('common/contact')}}"><span>我的浏览</span></a></li>
                    <li><a href="{{url('common/contact')}}"><span>我的收藏</span></a></li>
                </ul>
            </div>
            <!---Main Header--->
            <div class="main-header">
                <div class="zerogrid">
                    <div class="row">
                        <div class="col-1-4">
							<a href="/Home/index/index.html">
                                <img src="/home/images/Ld.png" style="width:160px;height:80px">
                            </a>
                        </div>
                        <div class="col-2-4">
                            <form id="searchForm" name="" method="get" action="/Home/Goods/search.html" class="home-index-search-form">
                                <input autocomplete="off" name="name" id="name" type="text" value="" class="home-index-search-input" placeholder="请输入搜索关键字...">
                                <button type="submit" class="home-index-search-button">搜索</button>
                            </form>
                            <ul id="searchTopList">
                                {{--一小时更新一次数据--}}
                                <li><a href="{{url('/')}}">我的订单</a></li>
                                <li><a href="{{url('/')}}">我的浏览</a></li>
                                <li><a href="{{url('/')}}">我的收藏</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!---Top Menu--->
            <div id="cssmenu">
                <ul>
                    <li class="active"><a href="{{url('/')}}"><span>首页</span></a></li>
                    <li class="last"><a href="{{url('common/contact')}}"><span>联系我们</span></a></li>
                </ul>
            </div>
        </div>

    </header>
    <script>
        $(document).ready(function(){
            var url = window.location.href;
            $("#cssmenu>ul>li").removeClass('active');
            $("#cssmenu>ul>li").each(function(){
                if($(this).children('a').attr('href') == url){
                    $(this).addClass('active');
                }
            });
        })
    </script>
@endsection