项目注意事项：
1.视图层使用JSON.stringify(data.field)将form表单的字段进行转化后，在ajax传递的时候一定加上content-type:"application/json;charset=UTF-8",然后转义data = JSON.parse(data);

登录相关：
