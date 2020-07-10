/**
 * 自定义封装js文件 返回状态 无结果集
 * data 参数值
 * Base64.encode('goods_id='+obj.data.goods_id+'&type=sort&value='+this.value+'&status=one')
 * ajax 的时候 需要注意spin的样式是否被阻塞掉 如果是同步执行的话 样式设置被阻塞了
 **/
function commonAjax(url,type,data,datatype,async){
    data = Base64.decode(data);
    if (typeof data == 'string') {
        try {
            data = JSON.parse(data);
        } catch(e) {
        }
    }
    var resStatus = 0;
    var loadingFlag;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:url,
        data:data,
        type:type,
        dataType:datatype,
        async:async,
        beforeSend: function () {
            loadingFlag = layer.msg('正在读取数据，请稍候……', { icon: 16, shade: 0.01,shadeClose:false });
        },
        success:function(result){
            if(result.code == 1){
                resStatus = 1;
            }else{
                layer.alert(result.msg,{icon: 5,time:2000});
                resStatus = -1;
            }
        },
        error:function(result){
            layer.alert("服务器繁忙, 请联系管理员!"+result.msg,{icon: 5,time:2000});
            resStatus = -1;
        },
        complete:function () {
            layer.close(loadingFlag);
        }
    });
    return resStatus;
}
/**
 * 改变某个表中 某个字段的值
 * @param url
 * @param data
 */
function changeTableVal(url,data){
    var loadingFlag = '';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:url,
        data:data,
        type:'post',
        beforeSend: function () {
            loadingFlag = layer.msg('正在操作，请稍候……', { icon: 16, shade: 0.01,shadeClose:false });
        },
        success:function(){
        },
        error:function(){
            layer.alert("服务器繁忙, 请联系管理员!",{icon: 5,time:2000});
        },
        complete:function () {
            layer.close(loadingFlag);
        }
    });
}
/**
 * 公共操作（删，改）
 * @param url
 * @param ids
 * @param type
 * @param refreshList
 * @returns {boolean}
 */
function publicHandle(url,ids,type,refreshList){
    layer.confirm('确认当前操作？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            var loadingFlag;
            // 确定
            $.ajax({
                url: url,
                type:type,
                data:{ids:ids},
                dataType:'JSON',
                beforeSend: function () {
                    loadingFlag = layer.msg('正在读取数据，请稍候……', { icon: 16, shade: 0.01,shadeClose:false });
                },
                success: function (result) {
                    layer.closeAll();
                    if (result.code == 1){
                        layer.msg(result.msg, {icon: 1, time: 1000},function(){
                            location.href = refreshList;
                        });
                    }else{
                        layer.msg(result.msg, {icon: 2, time: 2000});
                    }
                }
            });
        }, function (index) {
            layer.close(index);
        }
    );
}
/**
 * 返回结果集 接口中带有数据的 比如：列表、集合等
 * @param url
 * @param type
 * @param data
 * @param datatype
 * data 参数值
 * Base64.encode('goods_id='+obj.data.goods_id+'&type=sort&value='+this.value+'&status=one')
 */
function commonAjaxData(url,type,data,datatype){
    data = Base64.decode(data);
    if (typeof data == 'string') {
        try {
            data = JSON.parse(data);
        } catch(e) {
        }
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:url,
        data:data,
        type:type,
        dataType:datatype,
        async:false,
        success:function(result){
            return result;
        }
    });
}
/*
form表单 转化json数据
*/
/**
 * form表单 转化json数据
 * @param formName
 * @returns {{}}
 */
function getFormJson(formName){
    var fields = $('#' + formName).serializeArray();
    var jsonParam = {}; //声明一个对象
    $.each(fields, function(index, field) {
        jsonParam[field.name] = field.value; //通过变量，将属性值，属性一起放到对象中
    });
    return jsonParam;
}
/**
 * 获取多级联动的商品分类
 * @param url url地址
 * @param id 父类id
 * @param next 下一个的位置
 * @param select_id 下一个位置中默认的id
 * @returns {boolean}
 */
function get_category(url,id,next,select_id){
    if(id == ''){
        var html = "<option value=''>请选择分类</option>";
        $('#'+next).html(html);
        return false;
    }
    var data = {'parent_id':id};
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:url,
        data:data,
        type:'POST',
        dataType:'json',
        success: function(data) {
            var html = "<option value=''>请选择分类</option>";
            if(data.code == 1){
                for (var i=0 ;i<data.data.length;i++){
                    html+= "<option value='"+data.data[i].id+"'>"+data.data[i].name+"</option>";
                }
            }
            $("#"+next).empty().html(html);
            (select_id > 0) && $('#'+next).val(select_id);//默认选中
        },complete: function(){
            layui.form.render();
        }
    });
}
/*
 * 上传图片 后台专用
 * @access  public
 * @null int 一次上传图片张图
 * @elementid string 上传成功后返回路径插入指定ID元素内
 * @path  string 指定上传保存文件夹,默认存在public/upload/temp/目录
 * @callback string  回调函数(单张图片返回保存路径字符串，多张则为路径数组 )
 */
function GetUploadify(num,elementid,path,callback,fileType)
{
    var upurl = '/admin/uploadify/upload?num='+num+'&input='+elementid+'&path='+path+'&func='+callback+'&fileType='+fileType;
    var title = '上传图片';
    if(fileType == 'Flash'){
        title = '上传视频';
    }
    layer.open({
        type: 2,
        title: title,
        shadeClose: true,
        shade: false,
        maxmin: true, //开启最大化最小化按钮
        area: ['100%', '100%'],
        content: upurl
    });
}

/**
 * 设置用户输入数字合法性
 * @param name 表单name
 * @param min 范围最小值
 * @param max 范围最大值
 * @param keep 保留多少位小数 可不填
 * @param def   不在范围返回的默认值 可不填
 */
function checkInputNum(name,min,max,keep,def){
    var input = $('input[name='+name+']');
    var inputVal = parseInt(input.val());
    var a = parseInt(arguments[3]) ? parseInt(arguments[3]) : 0;//设置第四个参数的默认值
    var b = parseInt(arguments[4]) ? parseInt(arguments[4]) : '';//设置第四个参数的默认值
    if(isNaN(inputVal)){
        input.val('');
    }else{
        if(inputVal < min || inputVal > max){
            if(a > 0){
                input.val(number_format(b,a));
            }else{
                input.val(b);
            }
        }else{
            if(a > 0){
                input.val(number_format(inputVal, a));
            }else{
                input.val(inputVal);
            }
        }
    }
}