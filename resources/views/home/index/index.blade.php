@extends('layouts.home')
@extends('layouts.home_nav')
@section('home_content')
    <section id="container">
        <!-- 焦点图 -->
        <div class="layui-fulid">
            <div class="layui-carousel house-carousel" id="house-carousel" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 500px;">
                <div carousel-item="">
                    <div class="layui-this">
                        <img src="/home/images/slider-1.jpg">
                    </div>
                    <div class="">
                        <img src="/home/images/slider-2.jpg">
                    </div>
                </div>
                <div class="layui-carousel-ind">
                    <ul>
                        <li class="layui-this"></li>
                        <li class=""></li>
                    </ul>
                </div>
                <button class="layui-icon layui-carousel-arrow" lay-type="sub"></button>
                <button class="layui-icon layui-carousel-arrow" lay-type="add"></button>
            </div>
        </div>
        <div class="layui-container">
            <div class="hot-sell">
                <p class="house-title">热销推荐<a href="">更多优品 &gt;</a>
                </p>
                <div class="layui-row layui-col-space20">
                    @foreach($goodsList['hot']['data'] as $vo)
                        <a href="/house/{{$vo['goods_id']}}.html" class="layui-col-xs3 text">
                            <div>
                                <img src="{{$vo['original_img']}}"></div>
                            <p>{{$vo['goods_name']}}</p>
                            <p class="price">￥{{$vo['shop_price']}}</p>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="hot-sell">
                <p class="house-title">新品推荐<a href="">更多新品 &gt;</a>
                </p>
                <div class="layui-row layui-col-space20">
                    @foreach($goodsList['new']['data'] as $vo)
                        <a href="/house/{{$vo['goods_id']}}.html" class="layui-col-xs3 text">
                            <div>
                                <img src="{{$vo['original_img']}}"></div>
                            <p>{{$vo['goods_name']}}</p>
                            <p class="price">￥{{$vo['shop_price']}}</p>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="hot-sell">
                {{--推荐产品--}}
                <p class="house-title">猜你喜欢<a href="">更多 &gt;</a>
                </p>
                <div class="layui-row layui-col-space20">
                    @foreach($goodsList['recommend']['data'] as $vo)
                        <a href="/house/{{$vo['goods_id']}}.html" class="layui-col-xs3 text">
                            <div>
                                <img src="{{$vo['original_img']}}"></div>
                            <p>{{$vo['goods_name']}}</p>
                            <p class="price">￥{{$vo['shop_price']}}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
@extends('layouts.home_footer')