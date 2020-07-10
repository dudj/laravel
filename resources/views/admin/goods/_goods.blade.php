@extends('layouts.admin')
@section('content')
    <style>
        .type-cont{ padding-left:80px; overflow:hidden;}
        .type-cont span{ display:inline-block; font-size:12px; line-height:12px; color:#333; text-align:center; border:1px solid #d0d0d0; padding:12px 20px; border-radius:6px; float:left; margin-right:20px; cursor:pointer;}
        .type-cont span b{ display:block; margin-bottom:8px;}
        .type-cont span i{ font-style:normal;}
        .type-cont span:hover,.type-cont span.curtab{ background-color:#148eff; border:1px solid #148eff; color:#fff;}
    </style>
    <!--以下是在线编辑器 代码 -->
    <script src="{{asset("plugins/Ueditor/ueditor.config.js")}}"></script>
    <script src="{{asset("plugins/Ueditor/ueditor.all.min.js")}}"></script>
    <script src="{{asset("plugins/Ueditor/lang/zh-cn/zh-cn.js")}}"></script>
    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">商品管理</a>
            <a><cite>商品基本信息设置</cite></a>
            <a><cite>添加商品</cite></a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <form method="post" id="addEditGoodsForm" class="layui-form">
        <input type="hidden" name="goods_id" value="@if(isset($goods['goods_id'])){{$goods['goods_id']}}@endif">
        <input type="hidden" name="goods_type" value="0">
        <input type="hidden" value="@if(isset($level_cat[1])){{$level_cat[1]}}@endif" name="level_cat_1" disabled="disabled"/>
        <input type="hidden" value="@if(isset($level_cat[2])){{$level_cat[2]}}@endif" name="level_cat_2" disabled="disabled"/>
        <input type="hidden" value="@if(isset($level_cat[3])){{$level_cat[3]}}@endif" name="level_cat_3" disabled="disabled"/>
        <div class="layui-tab layui-tab-card">
            <ul class="layui-tab-title">
                <li class="layui-this">通用信息</li>
                <li>商品相册</li>
                {{--<li>商品模型</li>--}}
                <li>积分折扣</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><h3>商品类型</h3></label>
                        <div class="layui-input-block type-cont">
                            <span class="type-btn @if((isset($goods['is_virtual']) && $goods['is_virtual'] == 0) || (!isset($goods['is_virtual']))) curtab @endif" data-index="0"><b>实物商品</b><i>（物流发货）</i></span>
                            @if(request('param.behavior') != 'audit' && request('param.behavior') != 'editSupplierGoods')
                                <span class="type-btn @if(isset($goods['is_virtual']) && $goods['is_virtual'] == 1) curtab @endif" data-index="1"><b>电子卡券</b><i>（无需物流）</i></span>
                                {{--<span class="type-btn @if(isset($goods['is_virtual']) && $goods['is_virtual'] == 2) curtab @endif" data-index="1"><b>预约商品</b><i>（无需物流）</i></span>--}}
                            @endif
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><h3>基本信息</h3></label>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品名称 <span class="x-red">*</span></label>
                        <div class="layui-input-inline">
                            <input type="text" name="goods_name" value="{{isset($goods['goods_name'])?$goods['goods_name']:''}}" lay-verify="goods_name" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品分类 <span class="x-red">*</span></label>
                        <div class="layui-input-inline">
                            {{--联动搜索--}}
                            <select name="cat_id" id="cat_id" class="layui-select" lay-filter="cat_id">
                                <option value="">请选择分类</option>
                                @foreach($categoryList as $vo)
                                    @if(isset($level_cat['1']) && $vo['id'] == $level_cat['1'])
                                        <option value="{{$vo['id']}}" selected>{{$vo['name']}}</option>
                                    @else
                                        <option value="{{$vo['id']}}">{{$vo['name']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-input-inline">
                            <select name="cat_id_2" id="cat_id_2" class="layui-select" lay-filter="cat_id_2">
                                <option value="">请选择分类</option>
                            </select>
                        </div>
                        <div class="layui-input-inline">
                            <select name="cat_id_3" id="cat_id_3" class="layui-select">
                                <option value="">请选择分类</option>
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">商品简介</label>
                            <div class="layui-input-inline">
                                <textarea name="goods_remark" class="layui-textarea">{{isset($goods['goods_remark'])?$goods['goods_remark']:''}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">商品货号</label>
                            <div class="layui-input-inline">
                                <input type="text" name="goods_sn" value="{{isset($goods['goods_sn'])?$goods['goods_sn']:''}}" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux"><span class="x-red">商品货号不填会默认生成</span></div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">SPU</label>
                            <div class="layui-input-inline">
                                <input type="text" value="{{isset($goods['spu'])?$goods['spu']:''}}" name="spu" class="layui-input"/>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">SKU</label>
                            <div class="layui-input-inline">
                                <input type="text" value="{{isset($goods['sku'])?$goods['sku']:''}}" name="sku" class="layui-input"/>
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">商品品牌</label>
                        <div class="layui-input-inline">
                            <select name="brand_id" id="brand_id" class="layui-select">
                                <option value="">所有品牌</option>
                                @foreach($brandList as $vo)
                                    @if(isset($goods['brand_id']) && $vo['id'] == $goods['brand_id'])
                                        <option value="{{$vo['id']}}" selected>{{$vo['name']}}</option>
                                    @else
                                        <option value="{{$vo['id']}}">{{$vo['name']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item" id="supplier">
                        <label class="layui-form-label">供应商</label>
                        <div class="layui-input-inline">
                            <select name="suppliers_id" id="suppliers_id" class="layui-select">
                                <option value="0">不指定供应商属于本店商品</option>
                                @foreach($suppliersList as $vo)
                                    @if(isset($goods['suppliers_id']) && $vo['suppliers_id'] == $goods['suppliers_id'])
                                        <option value="{{$vo['suppliers_id']}}" selected>{{$vo['suppliers_name']}}</option>
                                    @else
                                        <option value="{{$vo['suppliers_id']}}">{{$vo['suppliers_name']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">本店售价 <span class="x-red">*</span></label>
                        <div class="layui-input-inline">
                            <input type="text" lay-verify="shop_price" value="{{isset($goods['shop_price'])?$goods['shop_price']:''}}" name="shop_price" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux"><span class="x-red">本店售价必填</span></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">市场售价 <span class="x-red">*</span></label>
                        <div class="layui-input-inline">
                            <input type="text" lay-verify="market_price" value="{{isset($goods['market_price'])?$goods['market_price']:''}}" name="market_price" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux"><span class="x-red">市场售价必填</span></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">成本价(供货价)</label>
                        <div class="layui-input-inline">
                            <input type="text" @if(request('behavior') == 'audit' || request('behavior') == 'editSupplierGoods') readonly="readonly" @endif value="{{isset($goods['cost_price'])?$goods['cost_price']:''}}" name="cost_price" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">佣金</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{isset($goods['commission'])?$goods['commission']:''}}" name="commission" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux"><span class="x-blue">用于分销的分成金额</span></div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">图片上传</label>
                        <div class="input-file-show">
                            <span class="show">
                                    <a id="img_a" target="_blank" class="nyroModal" rel="gal" href="{{isset($goods['original_img'])?$goods['original_img']:''}}">
                                        <i id="img_i" class="iconfont"
                                           onMouseOver="layer.tips('<img src={{isset($goods['original_img'])?$goods['original_img']:''}}>',this,{tips: [1, '#fff']});" onMouseOut="layer.closeAll();">&#xe6a8;</i>
                                    </a>
                            </span>
                            <span class="type-file-box">
                                <input type="text" id="imagetext" name="original_img" value="{{isset($goods['original_img'])?$goods['original_img']:''}}" class="type-file-text">
                                <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
                                <input class="type-file-file" onClick="GetUploadify(1,'','goods','img_call_back')" size="30"
                                       title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
                            </span>
                        </div>
                        <p class="notic">请上传图片格式文件，建议图片尺寸800*800像素</p>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">视频上传</label>
                        <div class="input-file-show">
                            <span class="type-file-box">
                                <input type="text" id="videotext" name="video" value="{{isset($goods['video'])?$goods['video']:''}}" class="type-file-text">
                                @if(isset($goods['video']) && $goods['video'])
                                    <input type="button" onclick="delupload()" value="删除视频" class="type-file-button">
                                @else
                                    <input type="button" name="button" id="videobutton1" value="选择上传..." class="type-file-button">
                                    <input class="type-file-file" onClick="GetUploadify(1,'','goods','video_call_back','Flash')" size="30" title="点击按钮选择文件并提交表单后上传生效">
                                @endif
                            </span>
                        </div>
                        <p class="notic">请上传视频格式文件</p>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品标签</label>
                        <div class="layui-input-inline">
                            <select name="label_id" id="label_id" class="layui-select">
                                <option value="">请选择标签</option>
                                @foreach($goodsLabel as $vo)
                                    @if(isset($goods['label_id']) && $vo['label_id'] == $goods['label_id'])
                                        <option value="{{$vo['label_id']}}" selected>{{$vo['label_name']}}</option>
                                    @else
                                        <option value="{{$vo['label_id']}}">{{$vo['label_name']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品重量 <span class="x-red">*</span></label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{isset($goods['weight'])?$goods['weight']:''}}" name="weight" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux"><span class="x-blue">用于计算物流费,克为单位</span></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品体积 <span class="x-red">*</span></label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{isset($goods['volume'])?$goods['volume']:''}}" name="volume" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux"><span class="x-blue">用于计算物流费,单位立方米</span></div>
                    </div>
                    <div class="layui-form-item goods_shipping">
                        <label class="layui-form-label">是否包邮 <span class="x-red">*</span></label>
                        <div class="layui-input-inline">
                            @if(isset($goods['is_free_shipping']) && $goods['is_free_shipping'] == 1)
                                <input type="checkbox" name="is_free_shipping" lay-filter="is_free_shipping" checked lay-skin="switch" value="1" lay-text="是|否">
                            @else
                                <input type="checkbox" name="is_free_shipping" lay-filter="is_free_shipping" lay-skin="switch" value="0" lay-text="是|否">
                            @endif
                        </div>
                        <div class="layui-input-inline" id="template_id">
                            <select name="template_id" id="template_id" class="layui-select">
                                <option value="">请选择运费模板</option>
                                @foreach($freight_template as $vo)
                                    @if(isset($goods['template_id']) && $vo['template_id'] == $goods['template_id'])
                                        <option value="{{$vo['template_id']}}" selected>{{$vo['template_name']}}</option>
                                    @else
                                        <option value="{{$vo['template_id']}}">{{$vo['template_name']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">总库存 <span class="x-red">*</span></label>
                        <div class="layui-input-inline">
                            <input type="text" lay-verify="store_count" @if(request('behavior') == 'audit' || request('behavior') == 'editSupplierGoods') readonly="readonly" @endif value="{{isset($goods['store_count'])?$goods['store_count']:''}}" name="store_count" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">商品关键词</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{isset($goods['keywords'])?$goods['keywords']:''}}" name="keywords" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux"><span class="x-blue">多个关键字使用,(英文符合)分隔</span></div>
                    </div>
                    <div class="layui-form-item virtual" style="display:none;">
                        <label class="layui-form-label">电子卡券购买上限</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{isset($goods['virtual_limit'])?$goods['virtual_limit']:''}}" name="virtual_limit" class="layui-input" onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onblur="checkInputNum(this.name,1,10,'',1);" />
                        </div>
                        <div class="layui-form-mid layui-word-aux"><span>请填写1~10之间的数字，电子卡券最高购买数量不能超过10个。</span></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">虚拟销售量</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{isset($goods['virtual_sales_sum'])?$goods['virtual_sales_sum']:''}}" name="virtual_sales_sum" class="layui-input" onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onblur="checkInputNum(this.name,0,9999999,'',1);" />
                        </div>
                        <div class="layui-form-mid layui-word-aux"><span>虚拟销售量（请输入0~9999999）：虚拟销售量 + 真实销量</span></div>
                    </div>
                    <input class="is_virtual" id="is_virtual0" name="is_virtual" value="{{isset($goods['is_virtual'])?$goods['is_virtual']:''}}" hidden>
                    <div class="layui-form-item">
                        <label class="layui-form-label">虚拟收藏量</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{isset($goods['virtual_collect_sum'])?$goods['virtual_collect_sum']:''}}" name="virtual_collect_sum" class="layui-input" onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onblur="checkInputNum(this.name,0,9999999,'',1);" />
                        </div>
                        <div class="layui-form-mid layui-word-aux"><span>虚拟收藏量（请输入0~9999999）：虚拟收藏量 + 真实收藏量</span></div>
                    </div>
                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">商品详情</label>
                        <div class="layui-input-block">
                            <textarea id="goods_content" name="goods_content" placeholder="请输入内容" class="layui-textarea">{{isset($goods['goods_content'])?$goods['goods_content']:''}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="layui-tab-item">
                    <div class="layui-input-block">
                        <table class="layui-table layui-table-bordered">
                            <tr>
                                <td>
                                    @if(isset($goods['goods_images']))
                                        @foreach($goods['goods_images'] as $vo)
                                            <div class="goods_xc" style="width:100px; text-align:center; margin: 5px; display:inline-block;">
                                                <input type="hidden" value="{{$vo['image_url']}}" name="goods_images[]">
                                                <a onClick="" href="{{$vo['image_url']}}" target="_blank">
                                                    <img width="100" height="100" src="{{$vo['image_url']}}">
                                                </a>
                                                <br>
                                                <a href="javascript:void(0)" onClick="ClearPicArr2(this,'{{$vo['image_url']}}')">删除</a>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="goods_xc" style="width:100px; text-align:center; margin: 5px; display:inline-block;">
                                        <input type="hidden" name="goods_images[]" value="" />
                                        <a href="javascript:void(0);" onClick="GetUploadify(10,'','goods','call_back2');">
                                            <img src="/images/add-button.jpg" width="100" height="100" />
                                        </a>
                                        <br/>
                                        <a href="javascript:void(0)">&nbsp;&nbsp;</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><p style="color:#AAA">请上传图片格式文件，建议图片尺寸800*800像素</p></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="layui-tab-item">
                    <div class="layui-form-item price">
                        <label class="layui-form-label">价格货梯</label>
                        @if(isset($goods['price_ladder']) && $goods['price_ladder'])
                            @foreach(json_decode($goods['price_ladder'],true) as $key=>$vo)
                                <div class="layui-form-item" style="margin-left:8%">
                                    <label class="layui-form-label">单次购买个数达到</label>
                                    <div class="layui-input-inline" style="width:10%" >
                                        <input type="text" class="layui-input" name="ladder_amount[]" value="{{$vo['amount']}}" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')"/>
                                    </div>
                                    <label class="layui-form-label">价格</label>
                                    <div class="layui-input-inline" style="width:10%">
                                        <input type="text" class="layui-input" name="ladder_price[]" value="{{$vo['price']}}" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')"/>
                                    </div>
                                    @if($key == 0)
                                        <a class="p_plus" href="javascript:;" style="line-height: 38px"><strong>[+]</strong></a>
                                    @else
                                        <a class="p_plus" href="javascript:;" onclick="$(this).parent().remove()" style="line-height: 38px"><strong>[-]</strong></a>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="layui-form-item" style="margin-left:8%">
                                <label class="layui-form-label">单次购买个数达到</label>
                                <div class="layui-input-inline" style="width:10%" >
                                    <input type="text" class="layui-input" name="ladder_amount[]" value="" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')">
                                </div>
                                <label class="layui-form-label">价格</label>
                                <div class="layui-input-inline" style="width:10%" >
                                    <input type="text" class="layui-input" name="ladder_price[]" value="" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')">
                                </div>
                                <a class="p_plus" href="javascript:;" style="line-height: 38px"><strong>[+]</strong></a>
                            </div>
                        @endif
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">赠送积分</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{isset($goods['give_integral'])?$goods['give_integral']:''}}" name="give_integral" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux"><span class="x-blue">订单完成后赠送积分</span></div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">兑换积分</label>
                        <div class="layui-input-inline">
                            <input type="text" value="{{isset($goods['exchange_integral'])?$goods['exchange_integral']:''}}" name="exchange_integral" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux"><span class="x-blue">不得高于商品最低价格与兑换比的积，如果设置0，则不支持积分抵扣</span></div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="goodsSave">确认提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
<script>
    $(document).ready(function(){
        initIsVirtual();
        initCategory();
        initFreight();
        $('.type-cont span').click(function(){
            $(this).addClass("curtab").siblings().removeClass("curtab");
        });
        $('.p_plus').click(function() {
            var html =
                '<div class="layui-form-item" style="margin-left:8%;">'+
                    '<label class="layui-form-label">单次购买个数达到</label>'+
                    '<div class="layui-input-inline" style="width:10%">'+
                        '<input type="text" class="layui-input" name="ladder_amount[]" value="" onpaste="this.value=this.value.replace(/[^\d.]/g,\"\")" onkeyup="this.value=this.value.replace(/[^\d.]/g,\"\")">'+
                    '</div>'+
                    '<label class="layui-form-label">价格</label>'+
                    '<div class="layui-input-inline" style="width:10%">'+
                        '<input type="text" class="layui-input" name="ladder_price[]" value="" onpaste="this.value=this.value.replace(/[^\d.]/g,\"\")" onkeyup="this.value=this.value.replace(/[^\d.]/g,\"\")">' +
                    '</div>'+
                    '<a class="p_plus" href="javascript:;"  onclick=\'$(this).parent().remove();\' style="line-height: 38px"><strong>[-]</strong></a>' +
                '</div>';
            $('.price').after(html);
        });
    });
    $(document).on("click",'.type-btn',function(){
        initIsVirtual();
    });
    //初始化商品类型设置
    function initIsVirtual() {
        var is_virtual = $(".type-btn.curtab").data('index');
        $('#is_virtual0').val(is_virtual);
        $('input[name="goods_type"]').val(is_virtual);
        switch (Number(is_virtual)){
            case 0:
                $('.virtual').hide();
                $(".goods_shipping").show();
                $('#supplier').show();
                $("input[name='exchange_integral']").removeAttr('disabled');
                break;
            case 1:
                $('.virtual').show();
                $('#is_free_shipping_label_1').trigger('click');
                initFreight();
                $(".goods_shipping").hide();
                $('#supplier').hide();
                $('#suppliers_id').val(0);
                $("input[name='exchange_integral']").val('');
                $("input[name='exchange_integral']").attr('disabled','disabled');
                break;
        }
    }
    /**
     * 初始化类别选择
     **/
    function initCategory(){
        var level_cat_1 = $.trim($("input[name='level_cat_1']").val());
        var level_cat_2 = $.trim($("input[name='level_cat_2']").val());
        var level_cat_3 = $.trim($("input[name='level_cat_3']").val());
        if(level_cat_2 > 0 || level_cat_1 > 0){
            get_category('{{url('api/goods/getCategory')}}',level_cat_1,'cat_id_2',level_cat_2);
        }
        if(level_cat_3 > 0){
            get_category('{{url('api/goods/getCategory')}}',level_cat_2,'cat_id_3',level_cat_3 );
        }
    }
    /**
     * 初始化 是否包邮的数据
     **/
    function initFreight(){
        var is_free_shipping = $("input[name='is_free_shipping']:checked").val();
        if(is_free_shipping == 0){
            $("#template_id").show();
        }else{
            $("#template_id").hide();
        }
    }
    /**
     * 上传图片回调事件
     * @param fileurl_tmp
     */
    function img_call_back(fileurl_tmp) {
        $("#imagetext").val(fileurl_tmp);
        $("#img_a").attr('href', fileurl_tmp);
        $("#img_i").attr('onmouseover', "layer.tips('<img src=" + fileurl_tmp + ">',this,{tips: [1, '#fff']});");
    }
    // 上传商品相册回调函数
    function call_back2(paths) {
        var last_div = $(".goods_xc:last").prop("outerHTML");
        for (var i = 0; i < paths.length; i++) {
            $(".goods_xc:eq(0)").before(last_div);	// 插入一个 新图片
            $(".goods_xc:eq(0)").find('a:eq(0)').attr('href', paths[i]).attr('onclick', '').attr('target', "_blank");// 修改他的链接地址
            $(".goods_xc:eq(0)").find('img').attr('src', paths[i]);// 修改他的图片路径
            $(".goods_xc:eq(0)").find('a:eq(1)').attr('onclick', "ClearPicArr2(this,'" + paths[i] + "')").text('删除');
            $(".goods_xc:eq(0)").find('input').val(paths[i]); // 设置隐藏域 要提交的值
        }
    }
    //上传之后删除组图
    function ClearPicArr2(obj, path) {
        $.ajax({
            type: 'GET',
            url: "{{url('admin/uploadify/delupload')}}",
            data: {action: "del", filename: path},
            success: function () {
                $(obj).parent().remove();
            }
        });
        // 删除数据库记录
        $.ajax({
            type: 'GET',
            url: "{{url('admin/goods/delGoodsImages')}}",
            data: {filename: path},
            success: function () {
            }
        });
    }
    //上传视频回调事件
    function video_call_back(fileurl_tmp) {
        $("#videotext").val(fileurl_tmp);
        $("#video_a").attr('href', fileurl_tmp);
        $("#video_i").attr('onmouseover', "layer.tips('<img src=" + fileurl_tmp + ">',this,{tips: [1, '#fff']});");
        if (typeof(fileurl_tmp) != 'undefined') {
            $('#video_button').html('<input type="button" onclick="delupload()" value="删除视频" class="type-file-button" >');
        }
    }
    layui.use(['table','form','laydate'], function(){
        var table = layui.table;
        var form = layui.form;
        var laydate = layui.laydate;
//        商品分类联动
        form.on('select(cat_id)',function(data){
            var cat_id = data.value;
            get_category('{{url('api/goods/getCategory')}}',cat_id,'cat_id_2','0');
            $('#cat_id_3').html("<option value=''>请选择分类</option>");
        });
        form.on('select(cat_id_2)',function(data){
            var cat_id = data.value;
            get_category('{{url('api/goods/getCategory')}}',cat_id,'cat_id_3','0');
        });
        form.on('switch(is_free_shipping)',function(data){
            var res = 0;
            if(data.value == 0){
                res = 1;
            }
            $("input[name='is_free_shipping']").val(res);
            layer.tips('温馨提示：不包邮的情况下，一定选择配送方式', data.othis)
            if(res == 0){
                $("#template_id").show();
            }else{
                $("#template_id").hide();
            }
        });
        form.verify({
            goods_name: function(value){
                if(value.length == ''){
                    return '商品不能为空';
                }
            },
            shop_price: function(value){
                if(value.length == ''){
                    return '本店售价不能为空';
                }
            },
            market_price: function(value){
                if(value.length == ''){
                    return '市场售价不能为空';
                }
            },
            store_count: function(value){
                if(value.length == ''){
                    return '库存不能为空';
                }
            },
            content: function(value){
                layedit.sync(editIndex);
            }
        });
        form.on('submit(goodsSave)', function(data){
            var param = getFormJson('addEditGoodsForm');
            param.is_free_shipping = param.is_free_shipping?param.is_free_shipping:0;
            var resStatus = commonAjax('{{url('admin/goods/save')}}', 'post', Base64.encode(JSON.stringify(param)), 'json',false);
            //发异步，把数据提交给php
            if(resStatus > 0){
                $('#submit').attr('disabled', true);
                var msg = '添加成功';
                if($("input[name='goods_id']").val()){
                    msg = '修改成功';
                }
                layer.alert(msg, {icon: 6},function () {
                    window.parent.location.reload();
                    //刷新父页面
                    // 获得frame索引
                    var index = parent.layer.getFrameIndex(window.name);
                    //关闭当前frame
                    parent.layer.close(index);
                });
            }
            return false;
        });
    });
    var url="{{urlConnect('admin/ueditor/index',['savePath'=>'goods'])}}";
    UE.getEditor('goods_content', {
        toolbars: [[
            'fullscreen', 'source', '|', 'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
            'directionalityltr', 'directionalityrtl', 'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', 'link','|',
            'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
            'simpleupload', 'insertimage', 'emotion', 'scrawl', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
            'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
            'print', 'preview', 'searchreplace', 'drafts', 'help'
        ]],
        serverUrl :url,
        zIndex: 999,
        initialFrameWidth: "100%", //初化宽度
        initialFrameHeight: 300, //初化高度
        focus: false, //初始化时，是否让编辑器获得焦点true或false
        maximumWords: 99999, removeFormatAttributes: 'class,style,lang,width,height,align,hspace,valign',//允许的最大字符数 'fullscreen',
        pasteplain:false, //是否默认为纯文本粘贴。false为不使用纯文本粘贴，true为使用纯文本粘贴
        autoHeightEnabled: true
    });
</script>
@endsection