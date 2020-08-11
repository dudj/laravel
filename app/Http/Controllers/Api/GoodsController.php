<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


/**
 * Class GoodsController
 * Created by dudj
 * Email: dudongjiangphp@163.com
 * Date: 2020-05-26
 * Summary: 商品相关数据接口
 */
class GoodsController extends Controller
{
    /**
     * 获取商品分类
     */
    public function getCategory(Request $request){
        $parent_id = $request->parent_id; // 商品分类 父id
        $data = DB::table('goods_category')->select('id','name')->where("parent_id", '=', $parent_id)->get()->toArray();
        return $this->success($data);
    }
}
