<?php
namespace App\Logic\Common;
/**
 * Created by PhpStorm.
 * User: lenovo
 * Email: dudongjiangphp@163.com
 * Date: 2020/5/27
 * Time: 13:35
 * Summary: 编辑器逻辑
 */
class EditorLogic
{
    /**
     * @param $file
     * @param $save_path
     * @return array
     * 保存图片到本地
     */
    public function saveUploadImage($file, $save_path){
        $return_url = '';
        $state = "SUCCESS";
        $new_path = $save_path.date('Y').'/'.date('md').'/';
        $waterPaths = ['goods/', 'water/']; //哪种路径的图片需要放oss
        $fileVal['ext'] = $file->extension();
        $fileVal['path'] = $file->path();
        $fileVal['mine'] = $file->getMimeType();
        //原始文件名
        $fileVal['originName'] = $file->getClientOriginalName();
        $fileVal['size'] = $file->getClientSize();
        $fileVal['uploadName'] = sha1($fileVal['originName'].time().rand(1000, 9999)). '.'. $fileVal['ext'];
        if (in_array($save_path, $waterPaths) && config('system.oss_switch')) {
            //商品图片可选择存放在oss

        } else {
            $info = $file->move(config('system.UPLOAD_PATH').$new_path, $fileVal['uploadName']);
            if (!$info) {
                $state = "ERROR" . $file->getError();
            } else {
                $return_url = '/'.config('system.UPLOAD_PATH').$new_path.$fileVal['uploadName'];
                $pos = strripos($return_url,'.');
                $filetype = substr($return_url, $pos);
            }
        }
        return [
            'state' => $state,
            'url' => $return_url
        ];
    }
}