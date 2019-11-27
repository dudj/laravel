此项目集成了layui+layer方式整合到laravel框架,目前做了简要的RBAC

项目注意事项：

1.视图层使用JSON.stringify(data.field)将form表单的字段进行转化后，在ajax传递的时候一定加上content-type:"application/json;charset=UTF-8",然后转义data = JSON.parse(data);

登录相关：

1.使用了自定义的密码

普通功能：

1.管理员页面中，使用了上传头像，集成了layer的切图功能
2.管理员列表采用了动态加载，监听了头工具栏和工具条的功能
3.管理员页面动态查询和角色页面表单查询的功能
4.权限页面eleTree使用了树进行数据展示
5.多个页面进行了form表单提交验证
6.layouts是公共页面