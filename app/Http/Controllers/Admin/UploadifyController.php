<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class UploadifyController
 * Created by idea.
 * User: dudj
 * Email: dudongjiangphp@163.com
 * Date:
 * Summary:上传图片和视频
 */
class UploadifyController extends Controller{
   
    public function upload(Request $request){
        if($request->has('path') && $request->path){
            $path = $request->path;
        }else{
            $path = 'temp';
        }
        $func = $request->func;
		$image_upload_limit_size = 1024 * 1024 * 5;
        $fileType = $request->has('fileType')?$request->fileType:'Images';//上传文件类型，视频，图片
         switch ($fileType)  {
             case 'Flash':
                 $upload = urlConnect('admin/ueditor/videoUp',['savepath'=>$path,'pictitle'=>'banner','dir'=>'video']);
                 $type = 'mp4,3gp,flv,avi,wmv';
                 break;
             default:
                 $upload = urlConnect('admin/ueditor/imageUp',['savepath'=>$path,'pictitle'=>'banner','dir'=>'images']);
                 $type = 'jpg,png,gif,jpeg';
                 break;
         }
        $info = array(
        	'num'=> $request->num,
        	'fileType'=> $fileType,
            'title' => '',
            'upload' => $upload,
        	'fileList'=> urlConnect("admin/uploadify/fileList",['path'=>$path]),
            'size' => $image_upload_limit_size/(1024 * 1024).'M',
            'type' => $type,
            'input' => $request->input,
            'func' => empty($func) ? 'undefined' : $func,
        );
        return view('admin.uploadify.upload',[
            'info' => $info
        ]);
    }



    //自定义海报专用上传图片
    public function poster_upload(){
        $func = I('func');
        $path = I('path','temp');
        $image_upload_limit_size = config('image_upload_limit_size');
        $fileType = I('fileType','Images');  //上传文件类型，视频，图片
        if($fileType == 'Flash'){
            $upload = U('Admin/Ueditor/videoUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'video'));
            $type = 'mp4,3gp,flv,avi,wmv';
        }else{
            $upload = U('Admin/Ueditor/imageUp',array('savepath'=>$path,'pictitle'=>'banner','dir'=>'images'));
            $type = 'jpg,png,gif,jpeg';
        }
        $info = array(
            'num'=> I('num/d'),
            'fileType'=> $fileType,
            'title' => '',
            'upload' =>$upload,
            'fileList'=>U('Admin/Uploadify/fileList',array('path'=>$path)),
            'size' => $image_upload_limit_size/(1024 * 1024).'M',
            'type' =>$type,
            'input' => I('input'),
            'func' => empty($func) ? 'undefined' : $func,
        );
        $this->assign('info',$info);
        return $this->fetch();
    }


    /**
     * 删除上传的图片,视频
     */
    public function delupload(){
        $action = request('action','del');
        $filename= request('filename');
        $filename= empty($filename) ? request('url') : $filename;
        $filename= str_replace('../','',$filename);
        $filename= trim($filename,'.');
        $filename= trim($filename,'/');
        if($action=='del' && !empty($filename) && file_exists($filename)){
            $filetype = strtolower(strstr($filename,'.'));
            $phpfile = strtolower(strstr($filename,'.php'));  //排除PHP文件
            $erasable_type = config('system.erasable_type');  //可删除文件
            if(!in_array($filetype,$erasable_type) || $phpfile){
                exit;
            }
            if(unlink($filename)){
                Log::info("删除文件成功");
                echo 1;
            }else{
                Log::info("删除文件失败");
                echo 0;
            }
            exit;
        }
    }

    /**
     * @param Request $request
     * 文件列表
     */
    public function fileList(Request $request)
    {
    	/* 判断类型 */
        $type = $request->has('type')?$request->type:'Images';//上传文件类型，视频，图片
    	switch ($type){
    		/* 列出图片 */
    		case 'Images' : $allowFiles = 'png|jpg|jpeg|gif|bmp';break;
    		case 'Flash' : $allowFiles = 'mp4|3gp|flv|avi|wmv|flash|swf';break;
    		/* 列出文件 */
    		default : $allowFiles = '.+';
    	}
    	$path = config('system.UPLOAD_PATH').($request->has('path')?$request->path:'temp');
    	$listSize = 100000;
    	$key = request('key','');
        $size = $request->has('size')?htmlspecialchars($request->size):$listSize;
        $start = $request->has('start')?htmlspecialchars($request->start):0;
    	$end = $start + $size;
    	/* 获取文件列表 */
    	$files = $this->getfiles($path, $allowFiles, $key,['public/upload/goods/thumb']);
    	if (!count($files)) {
    		echo json_encode(array(
    				"state" => "没有相关文件",
    				"list" => array(),
    				"start" => $start,
    				"total" => count($files)
    		));
    		exit;
    	}
    	/* 获取指定范围的列表 */
    	$len = count($files);
    	for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
    		$list[] = $files[$i];
    	}
    	$result = json_encode(array(
    			"state" => "SUCCESS",
    			"list" => $list,
    			"start" => $start,
    			"total" => count($files)
    	));
    	echo $result;
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    public function getfiles($path, $allowFiles, $key,$ignore = array(), &$files = array()){
    	if (!is_dir($path)) return null;
		static $step = 0;
		$step++;
		if($step > 100) return $files;
    	if(substr($path, strlen($path) - 1) != '/') $path .= '/';
    	$handle = opendir($path);
    	while (false !== ($file = readdir($handle))) {
    		if ($file != '.' && $file != '..') {
    			$path2 = $path . $file;
    			if (is_dir($path2) && !in_array($path2,$ignore)) {
                    $this->getfiles($path2, $allowFiles, $key,array(), $files);
    			} else {
    				if (preg_match("/\.(".$allowFiles.")$/i", $file) && preg_match("/.*". $key .".*/i", $file)) {
    					$files[] = array(
    						'url'=> '/'.$path2,
    						'name'=> $file,
    						'mtime'=> filemtime($path2)
    					);
    				}
    			}
    		}
    	}
    	return $files;
    }

    /**
     * 展示图片
     */
	public function preview(){
		// 此页面用来协助 IE6/7 预览图片，因为 IE 6/7 不支持 base64
		$DIR = 'preview';
		// Create target dir
		if (!file_exists($DIR)) {
			@mkdir($DIR);
		}
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		if ($cleanupTargetDir) {
			if (!is_dir($DIR) || !$dir = opendir($DIR)) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $DIR . DIRECTORY_SEPARATOR . $file;
				// Remove temp file if it is older than the max age and is not the current file
				if (@filemtime($tmpfilePath) < time() - $maxFileAge) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}
		$src = file_get_contents('php://input');
		if (preg_match("#^data:image/(\w+);base64,(.*)$#", $src, $matches)) {
			$previewUrl = sprintf(
					"%s://%s%s",
					isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
					$_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI']
			);
			$previewUrl = str_replace("preview.php", "", $previewUrl);
			$base64 = $matches[2];
			$type = $matches[1];
			if ($type === 'jpeg') {
				$type = 'jpg';
			}
			$filename = md5($base64).".$type";
			$filePath = $DIR.DIRECTORY_SEPARATOR.$filename;
			if (file_exists($filePath)) {
				die('{"jsonrpc" : "2.0", "result" : "'.$previewUrl.'preview/'.$filename.'", "id" : "id"}');
			} else {
				$data = base64_decode($base64);
				$filePathLower = strtolower($filePath);
				if (strstr($filePathLower, '../') || strstr($filePathLower, '..\\') || strstr($filePathLower, '.php')) {
					die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "文件上传格式错误 error ！"}}');
				}
				// 文件格式判断
				strstr(strtolower($filePath),'.php') && exit('文件格式不对');
				file_put_contents($filePath, $data);
				die('{"jsonrpc" : "2.0", "result" : "'.$previewUrl.'preview/'.$filename.'", "id" : "id"}');
			}
		} else {
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "un recoginized source"}}');
		}
	}
}