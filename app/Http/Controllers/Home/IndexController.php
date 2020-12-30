<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\HomeController;
use App\Logic\Admin\GoodsLogic;
use Illuminate\Http\Request;

class IndexController extends HomeController
{
    private $goodsLogic;
    public function __construct()
    {
        $this->goodsLogic = new GoodsLogic();
    }
    public function index(Request $request){
//        var_dump(auth()->guard('home')->user());die;
        //查询几个品牌和几个商品
        $request->limit = 4;
        $request->sortfield = 'goods_id';
        $request->sorttype = 'desc';
        $request->page = 0;
        $goodsList = $this->goodsLogic->getGoodsList($request);
        return view('home.index.index',[
            'goodsList' => $goodsList['data']
        ]);
    }
}
