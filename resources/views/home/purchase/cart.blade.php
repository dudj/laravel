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
    <div class="layui-container home-usershop">
        <table id="home-usershop-table" lay-filter="home-usershop-table"></table>
        <div class="layui-form layui-border-box layui-table-view" lay-filter="LAY-table-1" lay-id="home-usershop-table" style=" ">
            <div class="layui-table-box">
                <div class="layui-table-header">
                    <table cellspacing="0" cellpadding="0" border="0" class="layui-table" lay-skin="line">
                        <thead>
                        <tr>
                            <th data-field="0" data-key="1-0-0" data-unresize="true" class=" layui-table-col-special"><div class="layui-table-cell laytable-cell-1-0-0 laytable-cell-checkbox">
                                    <input type="checkbox" name="layTableCheckbox" lay-skin="primary" lay-filter="layTableAllChoose"><div class="layui-unselect layui-form-checkbox" lay-skin="primary">
                                        <i class="layui-icon layui-icon-ok"></i>
                                    </div>
                                </div>
                            </th>
                            <th data-field="1" data-key="1-0-1" data-minwidth="260" class=" layui-table-col-special">
                                <div class="layui-table-cell laytable-cell-1-0-1" align="center">
                                    <span>商品</span>
                                </div>
                            </th>
                            <th data-field="2" data-key="1-0-2" data-minwidth="160" class=" layui-table-col-special">
                                <div class="layui-table-cell laytable-cell-1-0-2" align="center">
                                    <span>单价</span>
                                </div>
                            </th>
                            <th data-field="3" data-key="1-0-3" class=" layui-table-col-special">
                                <div class="layui-table-cell laytable-cell-1-0-3" align="center">
                                    <span>数量</span>
                                </div>
                            </th>
                            <th data-field="4" data-key="1-0-4" class=" layui-table-col-special">
                                <div class="layui-table-cell laytable-cell-1-0-4" align="center">
                                    <span>小计</span>
                                </div>
                            </th>
                            <th data-field="5" data-key="1-0-5" class=" layui-table-col-special">
                                <div class="layui-table-cell laytable-cell-1-0-5" align="center">
                                    <span>操作</span>
                                </div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="layui-table-body layui-table-main">
                    <table cellspacing="0" cellpadding="0" border="0" class="layui-table" lay-skin="line">
                    </table>
                </div>
            </div>
            <style>
                .laytable-cell-1-0-0{ width: 50px; }
                .laytable-cell-1-0-1{  }
                .laytable-cell-1-0-2{  }
                .laytable-cell-1-0-3{ width: 150px; }
                .laytable-cell-1-0-4{ width: 120px; }
                .laytable-cell-1-0-5{ width: 100px; }
            </style>
        </div>
        <div class="home-usershop-table-num layui-form">
            <input type="checkbox" lay-skin="primary">
            <div class="layui-unselect layui-form-checkbox" lay-skin="primary">
                <i class="layui-icon layui-icon-ok"></i>
            </div>
            <span class="numal">已选 0 件</span>
            <a id="batchDel">批量删除</a>
            <p id="total">
                合计: ￥<span>0.00</span>
            </p>
            <div id="toCope">
                <p>应付：<big>￥0.00</big>
                </p>
                <span>满减￥20，包邮</span>
            </div>
            <button class="layui-btn" onclick="location=carbuyaction.php">结算</button>
        </div>
        {{--推荐产品--}}
        <p>猜你喜欢</p>
        <ul class="home-usershop-like">
            @foreach($data['recommendList']['data'] as $vo)
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
    <script type="text/html" id="goodsTpl">
        <div>
            <img src="@{{d.original_img}}">
            <div style="display: inline-block; text-align: left; vertical-align: top;padding-left: 10px;">
                <p>@{{d.goods_name}}</p>
            </div>
        </div>
    </script>
    <script type="text/html" id="priceTpl">
        <p>
            <span class="price">￥@{{d.member_goods_price}}</span>
            <del>￥@{{d.shop_price}}</del>
        </p>
    </script>
    <script type="text/html" id="numTpl">
        <div class="numVal">
            <button class="layui-btn layui-btn-primary sup">-</button>
            <input type="text" class="layui-input" data-num="@{{ d.goods_num }}" data-val="@{{ d.goods_id }}" value="@{{d.goods_num}}">
            <button class="layui-btn layui-btn-primary sub">+</button>
        </div>
    </script>
    <script type="text/html" id="totalTpl">
        <span class="total" style="color: #cd2d15;">￥@{{(d.member_goods_price*d.goods_num).toFixed(2)}}</span>
    </script>
    <script type="text/html" id="shopTpl">
        <a lay-event="del">删除</a>
    </script>
@endsection
@extends('layouts.home_footer')