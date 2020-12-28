<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

DB::connection()->enableQueryLog();

/**
 * Class HomeController
 * @package App\Http\Controllers
 * DB::getQueryLog()
 */
class HomeController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * @param $data
     * @param $msg
     * @return mixed
     * json数据 公共处理
     */
    public function success($data,$msg='ok'){
        $res = [
            'code' => 1,
            'data' => $data,
            'msg' => $msg
        ];
        return response()->json($res);
    }
    /**
     * @param $data
     * @param $msg
     * @return mixed
     * json数据 公共处理
     */
    public function error($data,$msg){
        $res = [
            'code' => -1,
            'data' => $data,
            'msg' => $msg
        ];
        return response()->json($res);
    }
}
