/**
 * Created by lenovo on 2021/7/8.
 */
layui.config({
    base: '/home/js/' //layui自定义layui组件目录
}).define(['element', 'carousel', 'table', 'util'], function(exports){
    var $ = layui.$
        ,element = layui.element
        ,form = layui.form
        ,carousel = layui.carousel
        ,laypage = layui.laypage
        ,util = layui.util
        ,table = layui.table;
    form.verify({
        type: function(value){
            if(!value){
                return '请选择支付方式';
            }
        }
        ,pid: function(value){
            if(!value){
                return '请选择配送方式';
            }
        }
    });
    form.on('submit(orderPay)', function(data){
        $("#checkoutform").submit();
    });
    form.on('submit(LAY-user-login-submit)', function(obj){
        var field = obj.field;
        //请求接口
        $.ajax({
            url:'index_do.php',
            type:'post',
            dataType:'json',
            data: field,
            success:function(res){
                if(res.code == 1){
                    layer.msg(res.msg,{icon:1,time:1000},function(){
                        window.location.href = res.url
                    });
                }else{
                    layer.msg(res.msg,{icon:5,time:2000});return false;
                }
            }
        });
        return false;
    });
    form.on('submit(LAY-user-reg-submit)', function(obj){
        var field = obj.field;
        //确认密码
        if(field.password !== field.repass){
            return layer.msg('两次密码输入不一致');
        }
        //是否同意用户协议
        if(!field.agreement){
            return layer.msg('你必须同意用户协议才能注册');
        }
        //请求接口
        $.ajax({
            url:'reg_new.php',
            type:'post',
            dataType:'json',
            data: field,
            success:function(res){
                if(res.code == 1){
                    layer.msg(res.msg,{icon:1,time:1000},function(){
                        window.location.href = res.url
                    });
                }else{
                    layer.msg(res.msg,{icon:5,time:2000});return false;
                }
            }
        });
        return false;
    });
    // 会员状态和购物车数量
    $(function(){
        //详情页——选中
        var ddDetail = $(".house-detail").find(".shopChoose").find("dl").children("dd");
        ddDetail.each(function(){
            if($(this).hasClass("active")){
                $(this).append('<i class="layui-icon layui-icon-ok active"></i>');
            };
        });
        //详情页——数量
        $(".house-detail").find(".shopChoose").find(".btn-input").children("input").val("1");
        $(".paymentarr button").click(function(){
            $(this).blur().addClass("layui-this").siblings().removeClass("layui-this");
            var paytype = $(this).data('id');
            $("input[name='paytype']").val(paytype);
        });
        $(".deliveryarr button").click(function(){
            $(this).blur().addClass("layui-this").siblings().removeClass("layui-this");
            var pid = $(this).data('id');
            $("input[name='pid']").val(pid);
        });
        $.ajaxSetup({async:false});
        $.get("/plus/car.php?dopost=carts",function(res){$('.totalNum').text(res.cart_count);},'json');
        $.get("/member/ajax_loginsta.php",function(res){$('.memberinfo a:first-child').text(res.userid);},'json');
    });
    // 点击购物车按钮
    $(document).on('click','.addcar',function () {
        $.ajax({
            cache:true,
            type:"POST",
            url:"/plus/posttocar.php?ajax=1",
            data:$('#formcar').serialize(),
            dataType:"json",
            success:function(res){
                if(res.code == 1){
                    $('.totalNum').text(res.msg);
                    layer.confirm('加入购物车成功!是否去结算?', function(index){
                        window.location.href = "/plus/car.php";
                    });
                }else{
                    layer.msg('加入购物车失败!');
                }
            }
        });
    });
    //购物车——表格
    table.render({
        elem: '#house-usershop-table'
        ,url:  '/plus/car.php?dopost=carinfo'
        ,skin: 'line'
        ,cols: [[
            {type:'checkbox', width:50}
            ,{title:'商品', align:'center', minWidth:260, templet: '#goodsTpl'}
            ,{title:'单价', align:'center', minWidth:160, templet: '#priceTpl'}
            ,{title:'数量', align:'center', width:150, templet: '#numTpl'}
            ,{title:'小计', align:'center', width:120, templet: '#totalTpl'}
            ,{title:'操作', align:'center', width:100, templet: '#shopTpl'}
        ]]
        ,done: function(res, curr, count){
            //数字框
            $(".numVal").each(function(){
                //获得小计 单价
                var totalTd = $(this).parents("td").siblings().find(".total")[0]
                    ,totalPrice = $(this).parents("td").siblings().find("span").filter(".price")[0].innerHTML;
                $(this).children("button").each(function(index){
                    //获得数量
                    var numVal = $(this).parent("div").children("input");
                    $(this).on('click', function(){
                        if(index == "1"){
                            numVal.val(Number(numVal.val()) + 1);
                        }else{
                            numVal[0].value = numVal[0].value > 1 ? numVal[0].value - 1 : 1;
                        };
                        totalTd.innerHTML = '￥' + (numVal.val() * totalPrice.slice(1)).toFixed(2)
                    });
                });
                $(this).children("input").on('keydown', function(e){
                    if(e.keyCode === 13){
                        e.preventDefault();
                        this.value = isNaN(this.value) ? 1 : (this.value > 1 ? this.value : 1);
                        totalTd.innerHTML = '￥' + (this.value * totalPrice.slice(1)).toFixed(2)
                    };
                });
            });
            //合计
            // totalVal();
            if($("#house-usershop-table").next("div").find(".layui-none").length != 0){
                $(".house-usershop-table-num").css("display", "none");
            };
        }
        ,text: {
            none: '<div class="house-usershop-table-none"><div><img src="/res/static/img/shopnone.png"></div><p>购物车空空如也</p><a class="layui-btn layui-btn-primary" href="/">去逛逛</a></div>'
        }
        ,id: 'house-usershop-table'
    });
    //合计
    var goodsVal = $(".house-usershop").find("#total").children("span")
        ,copyWith = $(".house-usershop").find("#toCope").children("p").children("big")
        ,copyTips = $(".house-usershop").find("#toCope").children("span");
    //监听复选框选择 获得总数
    table.on('checkbox(house-usershop-table)', function(obj){
        var checkStatus = table.checkStatus('house-usershop-table');
        goodsVal[0].innerHTML = 0;
        $(checkStatus.data).each(function(){
            goodsVal[0].innerHTML = parseFloat(this.buynum * this.trueprice.slice(1)) + Number(goodsVal[0].innerHTML);
        });
        //满减
        if(goodsVal[0].innerHTML > 200){
            copyWith[0].innerHTML = '￥' + (goodsVal[0].innerHTML - 20).toFixed(2)
            copyTips.css("display", "inline-block");
        }else{
            copyWith[0].innerHTML =  '￥' + parseFloat(goodsVal[0].innerHTML).toFixed(2);
            copyTips.css("display", "none");
        };
        //转换格式
        goodsVal[0].innerHTML = parseFloat(goodsVal[0].innerHTML).toFixed(2);
        if(checkStatus.data.length != 0){
            $(".house-usershop-table-num").children("input")[0].checked = true;
            form.render('checkbox');
        }else{
            $(".house-usershop-table-num").children("input")[0].checked = false;
            form.render('checkbox');
        };
        $(".house-usershop-table-num").children(".numal").html('已选 ' + checkStatus.data.length + ' 件');
    });
    table.on('tool(house-usershop-table)', function(obj){
        var data = obj.data;
        if(obj.event === 'del'){
            layer.confirm('确定删除此物品？', function(index){
                $.post('/plus/car.php?dopost=del',{"ids":data.id},function(res){
                    if (res.code == 1) {
                        $('.totalNum').text(res.msg);
                        obj.del();
                        layer.close(index);
                        table.reload('house-usershop-table');
                    }
                },'json');
            });
        }
    });
    $(".house-usershop").find("#batchDel").on('click', function(){
        var checkStatus = table.checkStatus('house-usershop-table')
            ,checkData = checkStatus.data;
        if(checkData.length === 0){
            layer.msg('请选择数据');
        }else{
            var ids=[];
            for (var i=0;i<checkData.length;i++){
                ids.push(checkData[i].id)
            }
            $.post('/plus/car.php?dopost=del',{"ids":ids},function(res){
                if (res.code == 1) {
                    $('.totalNum').text(res.msg);
                    //执行 Ajax 操作之后再重载
                    $(".house-usershop-table-num").children("input")[0].checked = false;
                    form.render('checkbox');
                    $(".house-usershop-table-num").children(".numal").html('已选 0 件')
                    copyWith[0].innerHTML = goodsVal[0].innerHTML = '￥0.00';
                    copyTips.css("display", "none");
                    layer.msg("已成功删除购物车中的商品");
                    table.reload('house-usershop-table');
                }
            },'json');

        }
    });
    //初始化
    var houseNav = $(".house-header").find(".layui-nav");
    //轮播
    var elemBanner = $('#house-carousel'), ins1 = carousel.render({
        elem: elemBanner
        ,width: '100%'
        ,height: elemBanner.height() + 'px'
        ,arrow: 'none'
        ,interval: 5000
    });
    $(window).on('resize', function(){
        var width = $(this).prop('innerWidth');
        ins1.reload({
            height: (width > 768 ? 500 : 150) + 'px'
        });
    });
    //首页——搜索
    $(".house-header").find("#search").on('click', function(){
        layer.open({
            type: 1
            ,title: false
            ,shadeClose: true
            ,area: '300px'
            ,content: '<div id="house-search" class="layui-form"><input type="text" placeholder="搜索好物" class="layui-input"></div>'
            ,success: function(layero, index){
                $("#house-search").find("input").on('keydown', function(e){
                    if(e.keyCode === 13){
                        e.preventDefault();
                        layer.close(index);
                    };
                });
            }
        });
    });
    //首页——点击切换
    $(".house-header").find("#switch").on('click', function(){
        if(houseNav.hasClass("close")){
            $(".house-header").children(".layui-container")[0].style.height = 60 + houseNav[0].offsetHeight + 'px';
            houseNav.removeClass("close");
        }
        else{
            $(".house-header").children(".layui-container")[0].style.height = 50 + 'px';
            houseNav.addClass("close");
        }
    });
    //列表页——点击切换
    $(".house-list").children(".filter").find("ul").each(function(){
        $(this).children("li").on('click', function(){
            $(this).addClass("active").siblings().removeClass("active");
        });
    });
    //详情页——图片选择
    var imgDetail = $(".house-detail").find(".intro-img").children("img")[0]
        ,srcDetail = $(imgDetail).attr("src")
        ,ulDetail = $(".house-detail").find(".thumb");
    ulDetail.children("li").each(function(){
        $(this).on('mouseenter', function(){
            imgDetail.src = $(this).children("img")[0].src;
        }).on("mouseleave", function(){
            imgDetail.src = srcDetail;
        });
    });
    //详情页——点击切换
    $(".house-detail").find(".shopChoose").find("dl").each(function(){
        $(this).children("dd").on('click', function(){
            $(this).addClass("active").siblings().removeClass("active");
            $(this).append('<i class="layui-icon layui-icon-ok active"></i>');
            $(this).siblings().children("i").replaceWith("");
        });
    });
    //详情页——分页
    laypage.render({
        elem: 'detailList'
        ,count: 50
        ,theme: '#daba91'
        ,layout: ['page', 'next']
    });
    //详情页——收藏
    $(".house-detail").find(".shopChoose").find(".collect").on('click', function(){
        $(this).find("#collect").addClass("layui-icon-rate-solid").removeClass("layui-icon-rate");
        $(this).find("#collect")[0].style.color = '#dbbb92';
        layer.msg('已收藏');
    });
    //我的收藏——点击切换
    $(".house-usercol").find(".user-list").children("li").each(function(){
        $(this).on('click', function(){
            $(this).addClass("active").siblings().removeClass("active");
        });
    });
    //我的收藏——分页
    laypage.render({
        elem: 'userList'
        ,count: 50
        ,theme: '#daba91'
        ,layout: ['page', 'next']
    });
    //我的收藏——删除
    $(".house-usercol").find(".layui-tab-content").find(".goods").each(function(){
        $(this).children(".del").on('click', function(){
            $(this).parent("div").parent("div").remove();
        });
    });
    //地址管理——表格
    table.render({
        elem: '#user-address'
        ,url:  'address.php?dopost=list'
        ,skin: 'line'
        ,cols: [[
            {type:'space', width:100, align:'center', templet: '#spaceTpl', width:90}
            ,{field:'username', title:'收货人', align:'center', width:90}
            ,{field:'address', title:'地址', align:'center'}
            ,{field:'tel', title:'联系方式', align:'center', width:120}
            ,{title:'操作', align:'center', templet: '#addressTpl', width:120}
        ]]
    });
    //地址管理——监听工具条
    table.on('tool(user-address)', function(obj){
        var data = obj.data;
        if(obj.event === 'del'){
            layer.confirm('真的删除行么', function(index){
                obj.del();
                layer.close(index);
            });
        }
        else if(obj.event === 'edit'){
            layer.open({
                type: 2
                ,title: '编辑地址'
                ,content: 'iframe.html'
                ,area: ['730px', '420px']
                ,shade: 0.8
                ,skin: 'address-class'
                ,btn: '确定'
                ,yes: function(index, layero){
                    window['layui-layer-iframe'+ index].layui.form.on('submit(useradd-submit)', function(data){
                        layer.close(index);
                    });
                    layero.find('iframe').contents().find("#useradd-submit").trigger('click');
                }
            });
        }
    });
    $(".useradd").find(".address-add").on('click', function(){
        layer.open({
            type: 2
            ,title: '新建地址'
            ,content: 'iframe.html'
            ,area: ['730px', '420px']
            ,shade: 0.8
            ,skin: 'address-class'
            ,btn: '确定'
            ,yes: function(index, layero){
                window['layui-layer-iframe'+ index].layui.form.on('submit(useradd-submit)', function(data){
                    layer.close(index);
                });
                layero.find('iframe').contents().find("#useradd-submit").trigger('click');
            }
        });
    });
    //个人中心——订单
    table.render({
        elem: '#house-user-order'
        ,url:  'shops_orders.php?dopost=order'
        ,skin: 'line'
        ,cols: [[
            {title:'订单信息', align:'center', templet: '#orderTpl'}
            ,{field:'avatar', title:'订购商品', templet: '#imgTpl', align:'center'}
            ,{field:'number', title:'件数', align:'center', width:80}
            ,{title:'价格', align:'center', templet: '#priceTpl', width:100}
            ,{title:'订单状态', align:'center', templet: '#stateTpl', width:100}
            ,{title:'订单操作', align:'center', templet: '#handleTpl', width:120}
        ]]
    });
    table.on('tool(house-user-order)', function(obj){
        var data = obj.data;
        if(obj.event === 'check'){
            layer.open({
                type: 1
                ,content: '查看物流'
                ,area: ['500px', '300px']
            });
        }
        else if(obj.event === 'evaluate'){
            layer.open({
                type: 1
                ,content: '收获并评价'
                ,area: ['500px', '300px']
            });
        }
    });

    //固定 bar
    util.fixbar({
        click: function(type){
            if(type === 'bar1'){
                //
            }
        }
    });
    exports('house', {});
})