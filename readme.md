laravel项目运行或者配置

1.下载laravel的代码

2.配置host和apache

3.安装composer,加入系统命令（环境变量）

4.在laravel源码目录下执行 composer update

5.在源码目录下配置 config/app.php APP_DEBUG设置为true 代表debug模式开启

6.确保源码根目录下有.env文件，没有则复制.env.example一份，重命名为.env

7.生成key,命令行执行：php artisan key:generate
生成key之后配置到.env文件

8.关于Apache和nginx的配置
创建一个.htaccess文件 apache的
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    RewriteEngine On

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)/$ /$1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>

如果 Laravel 附带的 .htaccess 文件在 Apache 中无法使用的话，尝试下方的做法：

Options +FollowSymLinks

RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-d

RewriteCond %{REQUEST_FILENAME} !-f

RewriteRule ^ index.php [L]


Nginx
如果使用 Nginx ，网站配置中加入下述代码将会转发所有的请求到 index.php 前端控制器。

location / {
    try_files $uri $uri/ /index.php?$query_string;
}

9.自定义了一些方法，并且修改了一些源码
Illuminate.zip 解压到vendor\laravel\framework\src下

10.特别需要注意的点，需要将PHP的禁用函数putenv解禁

此项目集成了layui+layer方式整合到laravel框架,目前做了简要的RBAC

项目注意事项：

1.视图层使用JSON.stringify(data.field)将form表单的字段进行转化后，在ajax传递的时候一定加上content-type:"application/json;charset=UTF-8",然后转义data = JSON.parse(data);

2.权限规则中，index和common(控制器)不进行限制，store和update以及index_json方法不进行限制(add和edit代表了添加和修改的权限，index_json也属于列表)

3.修改权限或者从新分配用户权限后需要清除缓存

4.表单统一提交，取表单值的时候，swich开关关闭状态，无值 需要默认给switch操作的name一个值 

登录相关：

1.使用了自定义的密码

普通功能：

1.管理员页面中，使用了上传头像，集成了layer的切图功能

2.管理员列表采用了动态加载，监听了头工具栏和工具条的功能

3.管理员页面动态查询和角色页面表单查询的功能

4.权限页面eleTree使用了树进行数据展示

5.多个页面进行了form表单提交验证

6.layouts是公共页面

7.dump(DB::getQueryLog());

