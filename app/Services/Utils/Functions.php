<?php
/**
 * @return string
 * 获取当前请求节点的url
 */
function getTemplatePath()
{
    $url = '';
    list($class, $method) = explode('@', request()->route()->getActionName());
    # 模块名
    $modules = strtolower(str_replace(
        '\\',
        '.',
        str_replace(
            'App\\Http\\Controllers\\',
            '',
            trim(
                implode('\\', array_slice(explode('\\', $class), 0, -1)),
                '\\'
            )
        )
    ));
    # 控制器名称
    $controller = strtolower(str_replace(
        'Controller',
        '',
        substr(strrchr($class, '\\'), 1)
    ));
    # 方法名
    $url = $modules.'/'.$controller.'/'.$method;
    return $url;
}

/**
 * @return array
 * 直接可以访问的控制器 不受权限控制
 */
function getCommonController(){
    return [
        "common"
    ];
}
/**
 * @return array
 * 直接可以访问的方法 不受权限控制
 */
function getCommonMethod(){
    return [
        'store',
        'update',
        'index_json'
    ];
}

/**
 * @param $password
 * @param $salt
 * @return string
 * 密码加密规则
 */
function generatePassword($password,$salt){
    return sha1('laravel' .$salt . sha1($salt . sha1($password)));
}

/**
 * @return string
 * 生成salt 规则：小写大写数字小写大写
 */
function generateSalt(){
    return chr(rand(65, 90)).chr(rand(97, 122)).rand(10000,99999).chr(rand(65, 90)).chr(rand(97, 122));
}
?>