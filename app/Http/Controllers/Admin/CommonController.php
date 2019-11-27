<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

/**
 * Class CommonController
 * @package App\Http\Controllers\Admin
 * 公共控制器，方法不受权限控制
 */
class CommonController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * IconFont 图标公共页面
     */
    public function unicode(){
        return view('admin.common.unicode');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 没有权限提示页面
     */
    public function deny(){
        return view('admin.common.deny');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 404
     */
    public function errors(){
        return view('admin.common.errors');
    }
    public function clear(){
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('clear-compiled');
        $data = [
            'nodestr',//权限串
            'menu',//菜单
        ];
        for($i=0;$i<count($data);$i++){
            request()->session()->forget($data[$i]);
        }
        return redirect()->back();
    }

    /**
     * 上传图片
     */
    public function uploadImg(Request $request){
        //获取文件对象
        if ($request->isMethod('POST')) {
            $tmp = $request->file('file');
            $path = '/static/upload/admin/'; //public下的article
            if ($tmp->isValid()) { //判断文件上传是否有效
                $fileType = $tmp->getClientOriginalExtension(); //获取文件后缀
                if(! in_array($fileType, ['png', 'jpg', 'gif'])) {
                    return $this->error('','上传文件后缀名有误');
                }
                $filePath = $tmp->getRealPath(); //获取文件临时存放位置
                if (filesize($filePath) >= 2048000) {
                    return $this->error('','上传文件大小超过限制');
                }
                $fileName = date('Y_m_d').'/'.md5(time()) .mt_rand(0,9999).'.'. $fileType;
                $res = Storage::disk('admin')->put($fileName, file_get_contents($filePath)); //存储文件
                if($res){
                    return $this->success(['url'=>Storage::url($fileName),'path'=>$path.$fileName],'上传成功');
                }
            }
        }else{
            return $this->error('','上传方式有误');
        }
    }

    /**
     * @return mixed
     * 修改密码
     */
    public function updatePwd(){
        try{
            $password = request()->get('password');
            if(!preg_match('/^[a-zA-Z0-9_-]{6,12}$/',$password)){
                return $this->error([],'密码规则为：6~12位的字母+数字');
            }
            $password = generatePassword($password,auth()->guard('admin')->user()->salt);
            $res = $this->commonUpdate('admins',['password'=>$password],[['key'=>'id','relation'=>'=','val'=>auth()->guard('admin')->user()->id]]);
            if($res){
                return $this->success([],'修改成功');
            }
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }
}
