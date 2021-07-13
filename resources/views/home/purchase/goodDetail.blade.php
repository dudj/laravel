<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Email: dudongjiangphp@163.com
 * Date: 2021/7/8
 * Time: 17:59
 * Summary: 购物车页面
 */
?>
@extends('layouts.home')
@extends('layouts.home_nav')
@section('home_content')
    <div class="layui-container home-detail">
        <p class="title">
            <a href="http://pay.dedehtml.com/">首页</a> &gt;
            <a href="/home/">居家用品</a> &gt;
            <span>产品详情</span>
        </p>
        <div class="layui-row price">
            <div class="layui-col-sm6">
                <div class="intro-img">
                    <img src="{{$data['original_img']}}">
                </div>
            </div>
            <form id="formcar" name="formcar" method="post" action="/plus/posttocar.php">
                <input type="hidden" name="id" value="3">
                <div class="layui-col-sm6 shopChoose">
                    <div class="title">
                        <p><span class="layui-badge">新品</span>时尚瓷碗7件套</p>
                        好评率 <span>90%</span>
                    </div>
                    <p>
                        <span>￥<big><b id="GOODS_AMOUNT">97.00</b></big></span>原价￥<big><del>99</del></big>
                    </p>
                    <dl>
                        <dt>颜色：</dt>
                        <dd class="active" onclick="changeAtt(this)" title="¥ ">
                            白色<input style="display:none" id="spec_value_251" name="spec_211" value="|251" type="radio" checked="">
                            <i class="layui-icon layui-icon-ok active"></i>
                        </dd>
                        <dd onclick="changeAtt(this)" title="¥ ">
                            灰色<input style="display:none" id="spec_value_252" name="spec_211" value="|252" type="radio">
                        </dd>
                        <dd onclick="changeAtt(this)" title="¥ ">
                            黑色<input style="display:none" id="spec_value_253" name="spec_211" value="|253" type="radio">
                        </dd>
                    </dl>
                    <dl>
                        <dt>尺寸：</dt>
                        <dd class="active" onclick="changeAtt(this)" title="¥ ">1.2米<input style="display:none" id="spec_value_254" name="spec_212" value="|254" type="radio" checked="">
                            <i class="layui-icon layui-icon-ok active"></i>
                        </dd>
                        <dd onclick="changeAtt(this)" title="¥ ">0.5米
                            <input style="display:none" id="spec_value_255" name="spec_212" value="|255" type="radio">
                        </dd>
                    </dl>
                    <div class="number layui-form">
                        <label>数量</label>
                        <div class="layui-input-inline btn-input">
                            <button class="layui-btn layui-btn-primary sup" type="button" onclick="goods_cut(),changePrice()">-</button>
                            <input type="text" class="layui-input" name="buynum" id="number" value="1" onblur="changePrice()">
                            <button class="layui-btn layui-btn-primary sub" type="button" onclick="goods_add(),changePrice()">+</button>
                        </div>
                        <p class="inputTips">已超出库存数量！</p>
                    </div><div class="shopBtn">
                        <button class="layui-btn layui-btn-primary sale" type="submit">立即购买</button>
                        <button class="layui-btn shop addcar" type="button">
                            <i class="layui-icon layui-icon-home-shop"></i>
                            加入购物车
                        </button>
                        <button class="layui-btn layui-btn-primary collect" type="button">
                            <i class="layui-icon layui-icon-rate" id="collect"></i>
                            收藏
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="layui-row layui-col-space30">
            <div class="layui-col-sm8 detailTab">
                <div class="layui-tab layui-tab-brief">
                    <ul class="layui-tab-title">
                        <li class="layui-this">详情</li>
                        <li>评论 <span>(120)</span></li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <img src="{{$data['original_img']}}">
                        </div>
                        <div class="layui-tab-item">
                            <div class="comment">
                                <ul>
                                    <li>
                                        <div class="img">
                                            <img src="/res/static/img/person.png">
                                        </div>
                                        <p class="txt">质量还可以！纯棉的，盖着挺舒服的，对皮肤也好。</p>
                                        <p class="time">2018年05月02日 11:20</p>
                                    </li>
                                </ul>
                                <div id="detailList">
                                    <div class="layui-box layui-laypage layui-laypage-molv" id="layui-laypage-1">
                                        <span class="layui-laypage-curr">
                                            <em class="layui-laypage-em" style="background-color:#daba91;"></em>
                                            <em>1</em>
                                        </span>
                                        <a href="javascript:;" data-page="2">2</a>
                                        <a href="javascript:;" data-page="3">3</a>
                                        <a href="javascript:;" data-page="4">4</a>
                                        <a href="javascript:;" data-page="5">5</a>
                                        <a href="javascript:;" class="layui-laypage-next" data-page="2">下一页</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm4 detailCom">
                <p class="title">热销推荐</p>
                <ul class="detailCom-content hot-sell">
                    @foreach($recommend['data'] as $vo)
                        <li>
                            <a href="/purchase/goodDetail/{{$vo['goods_id']}}">
                                <div>
                                    <img src="{{$vo['original_img']}}">
                                </div>
                                <p>{{$vo['goods_name']}}</p>
                                <p class="price">￥{{$vo['shop_price']}}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.home_footer')