<?php
namespace App\Http\Controllers\Home;

use App\Http\Controllers\HomeController;
use App\Logic\Admin\GoodsLogic;
use App\Logic\Common\CartLogic;
use Illuminate\Http\Request;

/**
 * Class PurchaseController
 * Created by dudj
 * User: dudj
 * Email: dudongjiangphp@163.com
 * Date: 2021-07-12
 * Summary: 产品相关
 */
class PurchaseController extends HomeController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * param carts 购物车的数量和总计 list 购物车列表
     */
    public function cart(Request $request){
        if($request->isMethod('post')){
            $cartLogic = new CartLogic();
            if($request->type == 'carts'){
                $cartData = $cartLogic->getCarts($request);
                return $this->success($cartData);
            }else if($request->type == 'editGoodsNum'){
                $res = $cartLogic->editGoodsNum($request);
                if($res){
                    return $this->success($res);
                }else{
                    return $this->error($res,'库存不足');
                }
            }else if($request->type == 'delGoods'){
                $res = $cartLogic->delGoods($request);
                if($res){
                    return $this->success($res);
                }else{
                    return $this->error($res,'移除失败');
                }
            }else if($request->type == 'list'){
                $cartList = $cartLogic->getCartList($request);
                $data = [
                    'code' => 0,
                    'data' => $cartList['data'],
                    'count' => $cartList['total'],
                    'msg' => '查询成功'
                ];
                return response()->json($data);
            }
        }
        $request->limit = 5;
        $request->sortfield = 'goods_id';
        $request->sorttype = 'desc';
        request()->offsetSet('is_recommend',1);
        $goods = new goodsLogic();
        $goodsList['recommend'] = $goods->getGoodsList($request);
        return view('home.purchase.cart',[
            'data'=>$goodsList
        ]);
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 返回购物车列表
     */
    public function cartInfo(){
        return view('home.purchase.cart');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 商品详情
     */
    public function goodDetail($id){
        $cartLogic = new CartLogic();
        if(intval($id)>0){
            $goodData = $cartLogic->getGoodDetail($id);
            $request = new Request();
            $request->limit = 3;
            $request->sortfield = 'goods_id';
            $request->sorttype = 'desc';
            request()->offsetSet('is_recommend',1);
            $goods = new goodsLogic();
            $recommendList = $goods->getGoodsList($request);
            return view('home.purchase.goodDetail',[
                'data' => $goodData,
                'recommend' => $recommendList
            ]);
        }else{
            return redirect('/');
        }
    }
}
