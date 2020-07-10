<?php
/**
 * Author: dudj
 * Date: 2020-05-27
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Logic\Common\EditorLogic;
use Illuminate\Http\Request;

/**
 * Class UeditorController
 * @package Admin\Controller
 */
error_reporting(E_ERROR | E_WARNING);
class UeditorController extends Controller
{
    private $sub_name = array('date', 'Y/m-d');
    private $savePath = 'temp/';

    public function __construct()
    {
        $savePath = request('savepath') ?: request('savePath');
        $this->savePath = $savePath ? $savePath . '/' : 'temp/';
        header("Content-Type: text/html; charset=utf-8");
    }
	public function index(Request $request){
        $CONFIG2 = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("./plugins/Ueditor/php/config.json")), true);
        $action = $_GET['action'];
        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG2);
                break;
            /* 上传图片 */
            case 'uploadimage':
		        //$fieldName = $CONFIG2['imageFieldName'];
		        $result = $this->imageUp($request);
		        break;
            /* 上传涂鸦 */
            case 'uploadscrawl':
		        $config = array(
		            "pathFormat" => $CONFIG2['scrawlPathFormat'],
		            "maxSize" => $CONFIG2['scrawlMaxSize'],
		            "allowFiles" => $CONFIG2['scrawlAllowFiles'],
		            "oriName" => "scrawl.png"
		        );
		        $fieldName = $CONFIG2['scrawlFieldName'];
		        $base64 = "base64";
		        $result = $this->upBase64($config,$fieldName);
		        break;
            /* 上传视频 */
            case 'uploadvideo':
		        $fieldName = $CONFIG2['videoFieldName'];
		        $result = $this->upFile($fieldName);
		        break;
            /* 上传文件 */
            case 'uploadfile':
		        $fieldName = $CONFIG2['fileFieldName'];
		        $result = $this->upFile($fieldName);
                break;
            /* 列出图片 */
            case 'listimage':
			    $allowFiles = $CONFIG2['imageManagerAllowFiles'];
			    $listSize = $CONFIG2['imageManagerListSize'];
			    $path = $CONFIG2['imageManagerListPath'];
			    $get =$_GET;
			    $result =$this->fileList($allowFiles,$listSize,$get);
                break;
            /* 列出文件 */
            case 'listfile':
			    $allowFiles = $CONFIG2['fileManagerAllowFiles'];
			    $listSize = $CONFIG2['fileManagerListSize'];
			    $path = $CONFIG2['fileManagerListPath'];
			    $get = $_GET;
			    $result = $this->fileList($allowFiles,$listSize,$get);
                break;
            /* 抓取远程文件 */
            case 'catchimage':
		    	$config = array(
			        "pathFormat" => $CONFIG2['catcherPathFormat'],
			        "maxSize" => $CONFIG2['catcherMaxSize'],
			        "allowFiles" => $CONFIG2['catcherAllowFiles'],
			        "oriName" => "remote.png"
			    );
			    $fieldName = $CONFIG2['catcherFieldName'];
			    /* 抓取远程图片 */
			    $list = array();
			    isset($_POST[$fieldName]) ? $source = $_POST[$fieldName] : $source = $_GET[$fieldName];
				
			    foreach($source as $imgUrl){
			        $info = json_decode($this->saveRemote($config,$imgUrl),true);
			        array_push($list, array(
			            "state" => $info["state"],
			            "url" => $info["url"],
			            "size" => $info["size"],
			            "title" => htmlspecialchars($info["title"]),
			            "original" => htmlspecialchars($info["original"]),
			            "source" => htmlspecialchars($imgUrl)
			        ));
			    }
			    $result = json_encode(array(
			        'state' => count($list) ? 'SUCCESS':'ERROR',
			        'list' => $list
			    ));
                break;
            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }
        /* 输出结果 */
        if(isset($_GET["callback"])){
            if(preg_match("/^[\w_]+$/", $_GET["callback"])){
                echo htmlspecialchars($_GET["callback"]).'('.$result.')';
            }else{
                echo json_encode(array(
                    'state' => 'callback参数不合法'
                ));
            }
        }else{
            echo $result;
        }
	}
	//上传文件
	private function upFile($fieldName)
    {
		$file = request()->file('file');
		if(empty($file)){
			$file = request()->file('upfile');
		}
		if (empty($file)) {
			$state = "ERROR";
            return json_encode(['state' =>$state]);
		} elseif (strtolower(pathinfo($file->getInfo('name'), PATHINFO_EXTENSION)) == 'php') {
            return json_encode(['state' =>'ERROR'.'后缀不允许']);
        }
        // 移动到框架应用根目录/public/uploads/ 目录下
        $this->savePath = $this->savePath.date('Y').'/'.date('m-d').'/';
        // 使用自定义的文件保存规则
        $info = $file->rule(function ($file) {
            return  md5(mt_rand());
        })->move(config('system.UPLOAD_PATH').$this->savePath);

		if($info){
			$data = array(
				'state' => 'SUCCESS',
				'url' => '/'.config('system.UPLOAD_PATH').$this->savePath.$info->getSaveName(),
				'title' => $info->getFilename(),
				'original' => $info->getFilename(),
				'type' => '.' . $info->getExtension(),
				'size' => $info->getSize(),
			);
		}else{
			$data = array('state' => 'ERROR'.$file->getError());
		}
		return json_encode($data);
	}

    //列出图片
	private function fileList($allowFiles,$listSize,$get)
    {
	    $savePath = '';
	    if ($this->savePath && $this->savePath != 'temp/') {
	        $savePath = $this->savePath;
        }
		$dirname = './'.config('system.UPLOAD_PATH').$savePath;
		$allowFiles = substr(str_replace(".","|",join("",$allowFiles)),1);
		/* 获取参数 */
		$size = isset($get['size']) ? htmlspecialchars($get['size']) : $listSize;
		$start = isset($get['start']) ? htmlspecialchars($get['start']) : 0;
		$end = $start + $size;
		/* 获取文件列表 */
		$path = $dirname;
		$files = $this->getFiles($path,$allowFiles);
		if(!count($files)){
		    return json_encode(array(
		        "state" => "no match file",
		        "list" => array(),
		        "start" => $start,
		        "total" => count($files)
		    ));
		}
		/* 获取指定范围的列表 */
		$len = count($files);
		for($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
		    $list[] = $files[$i];
		}

		/* 返回数据 */
		$result = json_encode(array(
		    "state" => "SUCCESS",
		    "list" => $list,
		    "start" => $start,
		    "total" => count($files)
		));

		return $result;
	}

   	/*
	 * 遍历获取目录下的指定类型的文件
	 * @param $path
	 * @param array $files
	 * @return array
	*/
    private function getFiles($path,$allowFiles,&$files = array()){
	    if(!is_dir($path)) return null;
	    if(substr($path,strlen($path)-1) != '/') $path .= '/';
	    $handle = opendir($path);
			
	    while(false !== ($file = readdir($handle))){
	        if($file != '.' && $file != '..'){
	            $path2 = $path.$file;
	            if(is_dir($path2)){
	                $this->getFiles($path2,$allowFiles,$files);
	            }else{
		            if(preg_match("/\.(".$allowFiles.")$/i",$file)){
		                $files[] = array(
		                    'url' => substr($path2,1),
		                    'mtime' => filemtime($path2)
		                );
		            }
	            }
	        }
	    }		
	    return $files;
    }

    //抓取远程图片
	private function saveRemote($config,$fieldName){
	    $imgUrl = htmlspecialchars($fieldName);
	    $imgUrl = str_replace("&amp;","&",$imgUrl);

	    //http开头验证
	    if(strpos($imgUrl,"http") !== 0){
	        $data=array(
		        'state' => '链接不是http链接',
		    );
	        return json_encode($data);
	    }
	    //获取请求头并检测死链
	    $heads = get_headers($imgUrl);
	    if(!(stristr($heads[0],"200") && stristr($heads[0],"OK"))){
	        $data=array(
		        'state' => '链接不可用',
		    );
	        return json_encode($data);
	    }
	    //格式验证(扩展名验证和Content-Type验证)
	    $fileType = strtolower(strrchr($imgUrl,'.'));
	    if(!in_array($fileType,$config['allowFiles']) || stristr($heads['Content-Type'],"image")){
	        $data=array(
		        'state' => '链接contentType不正确',
		    );
	        return json_encode($data);
	    }

	    //打开输出缓冲区并获取远程图片
	    ob_start();
	    $context = stream_context_create(
	        array('http' => array(
	            'follow_location' => false // don't follow redirects
	        ))
	    );
	    readfile($imgUrl,false,$context);
	    $img = ob_get_contents();
	    ob_end_clean();
	    preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/",$imgUrl,$m);

	    $dirname = config('system.UPLOAD_PATH').'remote/';
	    $file['oriName'] = $m ? $m[1] : "";
	    $file['filesize'] = strlen($img);
	    $file['ext'] = strtolower(strrchr($config['oriName'],'.'));
	    $file['name'] = uniqid().$file['ext'];
	    $file['fullName'] = $dirname.$file['name'];
	    $fullName = $file['fullName'];

	    //检查文件大小是否超出限制
	    if($file['filesize'] >= ($config["maxSize"])){
  		    $data=array(
			    'state' => '文件大小超出网站限制',
		    );
		    return json_encode($data);
	    }

	    //创建目录失败
	    if(!file_exists($dirname) && !mkdir($dirname,0777,true)){
  		    $data=array(
			    'state' => '目录创建失败',
		    );
		    return json_encode($data);
	    }else if(!is_writeable($dirname)){
  		    $data=array(
			    'state' => '目录没有写权限',
		    );
		    return json_encode($data);
	    }
		// 文件格式判断
		strstr(strtolower($fullName),'.php') && exit('文件格式不对');

	    //移动文件
	    if(!(file_put_contents($fullName, $img) && file_exists($fullName))){ //移动失败
  		    $data=array(
			    'state' => '写入文件内容错误',
		    );
		    return json_encode($data);
	    }else{ //移动成功
	        $data=array(
			    'state' => 'SUCCESS',
			    'url' => substr($file['fullName'],1),
			    'title' => $file['name'],
			    'original' => $file['oriName'],
			    'type' => $file['ext'],
			    'size' => $file['filesize'],
		    );
	    }
		
	    return json_encode($data);
	}

    /*
	 * 处理base64编码的图片上传
	 * 例如：涂鸦图片上传
	*/
	private function upBase64($config,$fieldName){
	    $base64Data = $_POST[$fieldName];
	    $img = base64_decode($base64Data);

	    $dirname = config('system.UPLOAD_PATH').'scrawl/';
	    $file['filesize'] = strlen($img);
	    $file['oriName'] = $config['oriName'];
	    $file['ext'] = strtolower(strrchr($config['oriName'],'.'));
	    $file['name'] = uniqid().$file['ext'];
	    $file['fullName'] = $dirname.$file['name'];
	    $fullName = $file['fullName'];

 	    //检查文件大小是否超出限制
	    if($file['filesize'] >= ($config["maxSize"])){
  		    $data=array(
			    'state' => '文件大小超出网站限制',
		    );
		    return json_encode($data);
	    }

	    //创建目录失败
	    if(!file_exists($dirname) && !mkdir($dirname,0777,true)){
	        $data=array(
			    'state' => '目录创建失败',
		    );
		    return json_encode($data);
	    }else if(!is_writeable($dirname)){
	        $data=array(
			    'state' => '目录没有写权限',
		    );
		    return json_encode($data);
	    }
		// 文件格式判断
		strstr(strtolower($fullName),'.php') && exit('文件格式不对');

	    //移动文件
	    if(!(file_put_contents($fullName, $img) && file_exists($fullName))){ //移动失败
            $data=array(
		        'state' => '写入文件内容错误',
		    );
	    }else{ //移动成功	       
	        $data=array(
			    'state' => 'SUCCESS',
			    'url' => substr($file['fullName'],1),
			    'title' => $file['name'],
			    'original' => $file['oriName'],
			    'type' => $file['ext'],
			    'size' => $file['filesize'],
		    );
	    }
		
	    return json_encode($data);
	}

    /**
     * @function imageUp
     */
    public function imageUp(Request $request)
    {       
        // 上传图片框中的描述表单名称，
        $pictitle = $request->pictitle;
        $dir = $request->dir;
        $title = htmlspecialchars($pictitle , ENT_QUOTES);        
        $path = htmlspecialchars($dir, ENT_QUOTES);
        $file = $request->file('file');
        $editor = new EditorLogic();
        if (empty($file)) {
            $file = $request->file('upfile');
        }
        if (true !== $file->isValid() || !$file) {
            $state = $file->getErrorMessage();
        } else {
            $return = $editor->saveUploadImage($file, $this->savePath);
            $state = $return['state'];
            $data['url'] = $return['url'];
        }
        $data['title'] = $title;
        $data['original'] = '';
        $data['state'] = $state;
        $data['path'] = $path;
        return response()->json($data);
    }
    
    /**
     * app文件上传
     */
    public function appFileUp()
    {      
        $path = config('system.UPLOAD_PATH').'appfile/';
        if (!file_exists($path)) {
            mkdir($path);
        }

        //$input_file  ['upfile'] = $info['Filedata'];  一个是上传插件里面来的, 另外一个是 文章编辑器里面来的
        // 获取表单上传文件
        $file = request()->file('Filedata');
        if (empty($file)) {
            $file = request()->file('upfile');    
        }
        
        $result = $this->validate(
            ['file2' => $file], 
            ['file2'=>'fileSize:30000000|fileExt:apk,ipa,pxl,deb'],
            ['file2.fileSize' => '上传文件过大', 'file2.fileExt' => '上传文件后缀不正确']                    
           );
        if (true !== $result || empty($file)) {            
            $state = "ERROR" . $result;
        } else {
            $info = $file->rule(function ($file) {    
                return date('YmdHis_').input('Filename'); // 使用自定义的文件保存规则
            })->move($path);
            if ($info) {
                $state = "SUCCESS";                         
            } else {
                $state = "ERROR" . $file->getError();
            }
            $return_data['url'] = $path.$info->getSaveName();            
        }
        
        $return_data['title'] = 'app文件';
        $return_data['original'] = ''; // 这里好像没啥用 暂时注释起来
        $return_data['state'] = $state;
        $return_data['path'] = $path;        

        $this->ajaxReturn($return_data);
    }

    /**
     * 微信公众号图片素材列表
     * @param $listSize int 拉取多少
     * @param $get array get数组
     * @return string
     */
    public function wechatImageList($listSize, $get)
    {
        $size = isset($get['size']) ? intval($get['size']) : $listSize;
        $start = isset($get['start']) ? intval($get['start']) : 0;

        $logic = new \app\common\logic\WechatLogic;
        return $logic->getPluginImages($size, $start);
    }

    /**
     * 上传视频
     */
    public function videoUp(Request $request)
    {
        $pictitle = $request->pictitle;
        $dir = $request->dir;
        $title = htmlspecialchars($pictitle , ENT_QUOTES);
        $path = htmlspecialchars($dir, ENT_QUOTES);
        // 获取表单上传文件
        $file = $request->file('file');
        if (empty($file)) {
            $file = $request->file('upfile');
        }
         $upload_max_filesize = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
        if (true !== $file->isValid() || !$file) {
            $state = "ERROR 视频过大, 最大不能超过: $upload_max_filesize";
        } else {
            // 移动到框架应用根目录/public/uploads/ 目录下
            $new_path = $this->savePath.date('Y').'/'.date('m-d').'/';
            // 使用自定义的文件保存规则
            $uploadName = sha1($file->getClientOriginalName().time().rand(1000, 9999)). '.'. $file->extension();
            $info = $file->move(config('system.UPLOAD_PATH').$new_path, $uploadName);
            $data['url'] = '/'.config('system.UPLOAD_PATH').$new_path.$uploadName;
            if ($info) {
                $state = "SUCCESS";
				$img_url = $this->setVideoImg($data['url']);
            } else {
                $state = "ERROR" . $file->getError();
				$img_url = '';
            }
            $data['img_url'] = $img_url;
        }
        $data['title'] = $title;
        $data['original'] = ''; // 这里好像没啥用 暂时注释起来
        $data['state'] = $state;
        $data['path'] = $path;
        return response()->json($data);
    }
	/**
	 * 视频截图
	 * https://ffmpeg.zeranoe.com/builds/ ffmpeg.exe下载地址
	 * @param $file |视频路径 /public/upload/goods/name.mp4
	 * @return bool | name.jpg
	 */
	public function setVideoImg($file){
		$pre = dirname(dirname(dirname(__DIR__)));
		if(IS_WIN) {
			$ffmpeg = $pre . '/public/plugins/ffmpeg/bin/ffmpeg.exe';
			if(!file_exists($ffmpeg))	return $ffmpeg.' /no ffmpeg';
		}else{
			$ffmpeg = '/monchickey/ffmpeg/bin/ffmpeg';

			if(!file_exists($ffmpeg)){
				//$ffmpeg = '/usr/bin/ffmpeg';
				$ffmpeg = 'ffmpeg';
			}
		}
		//if(!file_exists($ffmpeg))	return $ffmpeg.' /no ffmpeg';
		$arr = explode('.', $file);
		$jpg = $pre . $arr[0] . '.jpg';
		$path = $pre . $file;
		if(file_exists($path)){
			// exec system
			exec("$ffmpeg -i $path -ss 2 -vframes 1 $jpg",$re);
			return $re;
		}else{
			return $path.' /no path';
		}
	}
}