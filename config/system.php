<?php
/**
 * Created by PhpStorm.
 * User: dudj
 * Email: dudongjiangphp@163.com
 * Date: 2020/5/27
 * Time: 9:43
 * Summary: 系统配置全局变量
 */
return [
    'UPLOAD_PATH' => 'upload/',
    'oss_switch' => false,
    'erasable_type' =>['.gif','.jpg','.jpeg','.bmp','.png','.mp4','.3gp','.flv','.avi','.wmv'],
    //自定义公共验证函数
    'common_check_prefix' => ['captcha'],
];