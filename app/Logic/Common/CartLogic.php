<?php
namespace App\Logic\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Predis\Client;
use Illuminate\Support\Facades\Log;
/**
 * 购物车逻辑 - 前后台都会使用，就放在了公共方法
 * Class CartLogic
 * @package App\Logic\Common
 */
class CartLogic extends Model
{
    /**
     * @return mixed
     * 获取当前用户的购物车商品数量以及总价
     */
    public function getCarts(Request $request){
        $cartData = DB::table('cart')->select(DB::raw('count(1) AS cart_count','sum(goods_num * member_goods_price) as price_total'))->where(['member_id'=>auth('home')->user()->id,'status'=>1])->first();
        return $cartData;
    }

    /**
     * @param Request $request
     * @return mixed
     * 修改商品数量 购物车中存在、商品存在并且库存满足就可以
     */
    public function editGoodsNum(Request $request){
        $cartData = DB::table('cart')->select()->where(['member_id'=>auth('home')->user()->id,'status'=>1,'goods_id'=>$request->goods_id])->first();
        $goodsData = DB::table('goods')->select('store_count')->where(['goods_id'=>$request->goods_id])->first();
        Log::info('编辑商品数量，查看商品信息：',$goodsData);
        if(isset($cartData) && $cartData && isset($goodsData) && ($goodsData['store_count']-$request->goods_num)>0){
            return DB::table('cart')
                ->where(['member_id'=>auth('home')->user()->id,'status'=>1,'goods_id'=>$request->goods_id])
                ->update(['goods_num' => $request->goods_num]);
        }
        return false;
    }

    /**
     * @param Request $request
     * @return mixed
     * 移除商品
     */
    public function delGoods(Request $request){
        return DB::table('cart')
            ->whereIn('id',$request->ids)
            ->update(['status' => 2]);
    }

    /**
     * @param $request
     * @return mixed
     * 根据查询条件获取数据
     */
    public function getCartList(Request $request){
        if(!isset($request->sortfield)){
            $request->sortfield = 'add_time';
        }
        if(!isset($request->sorttype)){
            $request->sorttype = 'desc';
        }
        $param = [
            'paramWhere' => ['member_id','goods_id','status'],
            'paramLike' => ['goods_sn','goods_name'],
            'paramDate' => [],
            'paramIn' => [
            ],
        ];
        $where = function($query) use($request,$param){
            //=
            foreach($param['paramWhere'] as $val){
                if ($request->has($val) && $request->$val != '') {
                    $query->where('c.'.$val, '=', $request->$val);
                }
            }
            //like
            foreach($param['paramLike'] as $val){
                if ($request->has($val) && $request->$val != '') {
                    $search = "%" . trim($request->$val) . "%";
                    $query->where(function ($sonQuery) use ($search){
                        $sonQuery->where('g.goods_name', 'LIKE', $search);
                        $sonQuery->orWhere('g.goods_sn', 'LIKE', $search);
                    });
                }
            }
            //in
            foreach($param['paramIn'] as $key=>$val){
                if($request->has($key) && $request->$key > 0){
                    $query->whereIn($key, $val($request->$key));
                }
            }
            //查询正常的商品
            $query->where('c.status', '=', $request->status?$request->status:1);
            //查询某用户对应的产品
            if(auth('home')->check()){
                $query->where('c.member_id', '=', auth('home')->user()->id);
            }
        };
        $pagesize = $request->limit;
        $offset = ($request->page - 1) * $pagesize;
        $cartList = DB::table('cart as c')
            ->leftjoin('goods as g',function($join){
                $join->on('g.goods_id','=','c.goods_id');
            })->select('g.goods_id','c.goods_num','c.id','g.goods_name','g.market_price','g.shop_price','c.member_goods_price','g.original_img')->where($where)->orderBy($request->sortfield, $request->sorttype)->offset($offset)->paginate($pagesize)->toArray();
        return $cartList;
    }
    public function getGoodDetail($id){
        $goodData = DB::table('goods as g')
            ->leftjoin('goods_category as gc',function($join){
                $join->on('g.cat_id','=','gc.id');
            })->leftjoin('suppliers as s',function($join){
                $join->on('g.suppliers_id','=','s.suppliers_id');
            })->select('g.*','gc.name as cat_name','s.suppliers_name')->where(['g.goods_id'=>$id])->first();
        return $goodData;
    }
}