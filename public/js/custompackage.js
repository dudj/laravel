/**
 * 自定义封装js文件
 **/
function commonAjax(url,type,data,datatype){
    data = Base64.decode(data);
    if (typeof data == 'string') {
        try {
            data = JSON.parse(data);
        } catch(e) {
        }
    }
    var resStatus = 0;
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
            console.log('success-result:'+result.code+";result.msg"+result.msg);
            if(result.code == 1){
                layer.msg(result.msg,{icon: 5,time:1000});
                resStatus = 1;
            }else{
                layer.alert(result.msg,{icon: 5,time:1000});
                resStatus = -1;
            }
        },
        error:function(result){
            console.log('result:'+result.code+";result.msg"+result.msg);
            layer.alert(result.msg,{icon: 5,time:500});
            resStatus = -1;
        }
    });
    return resStatus;
}