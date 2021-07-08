<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\HomeController;
use App\Logic\Admin\GoodsLogic;
use Illuminate\Http\Request;
use thrift\Hello;

class IndexController extends HomeController
{
    private $goodsLogic;
    public function __construct()
    {
        $this->goodsLogic = new GoodsLogic();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 热销产品、新品、推荐产品 后台有设置，根据设置查询
     *  request()->offsetUnset('is_hot',1);
     *  request()->offsetSet('is_hot',1);
     * 设置值和取消值
     */
    public function index(Request $request){
        //查询几个品牌和几个商品
        $request->limit = 4;
        $request->sortfield = 'goods_id';
        $request->sorttype = 'desc';
        $request->page = 0;
        request()->offsetSet('is_hot',1);
        $goodsList['hot'] = $this->goodsLogic->getGoodsList($request);
        request()->offsetUnset('is_hot',1);
        request()->offsetSet('is_new',1);
        $goodsList['new'] = $this->goodsLogic->getGoodsList($request);
        request()->offsetUnset('is_new',1);
        request()->offsetSet('is_recommend',1);
        $goodsList['recommend'] = $this->goodsLogic->getGoodsList($request);
        return view('home.index.index',[
            'goodsList' => $goodsList
        ]);
    }
}
