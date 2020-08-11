<?php
/**
 * Author: dudj
 * Date: 2020-05-09
 */
namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Goods extends Model
{
    public function specImage(){
        return $this->hasMany('SpecImage', 'goods_id', 'goods_id');
    }
    public function specGoodsPrice()
    {
        return $this->hasMany('specGoodsPrice', 'goods_id', 'goods_id')->order('item_id');
    }
    public function goodsImages()
    {
        return $this->hasMany('GoodsImages', 'goods_id', 'goods_id');
    }

    public function FlashSale()
    {
        return $this->hasOne('FlashSale', 'id', 'prom_id');
    }

    public function teamActivity()
    {
        return $this->hasOne('TeamActivity', 'goods_id', 'goods_id')->where('deleted', 0);
    }
    public function PromGoods()
    {
        return $this->hasOne('PromGoods', 'id', 'prom_id');
    }

    public function GroupBuy()
    {
        return $this->hasOne('GroupBuy', 'id', 'prom_id');
    }

    public function brand()
    {
        return $this->hasOne('brand', 'id', 'brand_id');
    }
    public function BespeakTemplate()
    {
        return $this->hasOne('BespeakTemplate', 'template_id', 'bespeak_template_id');
    }
    public function suppliers()
    {
        return $this->hasOne('suppliers', 'suppliers_id', 'suppliers_id');
    }

    public function BespeakTemplateUnit()
    {
        return $this->hasMany('BespeakTemplateUnit', 'template_id', 'bespeak_template_id')->where(['deleted'=>0])->order('sort desc');
    }
    public function getDiscountAttr($value, $data)
    {
        if ($data['market_price'] == 0) {
            $discount = 10;
        } else {
            $discount = round($data['shop_price'] / $data['market_price'], 2) * 10;
        }
        return $discount;
    }

    public function goodsAttr()
    {
        return $this->hasMany('GoodsAttr', 'goods_id' ,'goods_id')->join('__GOODS_ATTRIBUTE__ b', '__PREFIX__goods_attr.attr_id = b.attr_id')->order('b.order desc');
    }

    /**
     * 获取商品评价
     * 好评数差评数中评数及其百分比,和总评数
     * @param $value
     * @param $data
     * @return array|false|\PDOStatement|string|Model
     */
    public function getCommentStatisticsAttr($value, $data)
    {
        $comment_where = ['is_show' => 1, 'goods_id' => $data['goods_id'], 'username' => ['neq', 'admin']]; //公共条件
        $field = "sum(case when img !='' and img not like 'N;%' then 1 else 0 end) as img_sum,"
            . "sum(case when ceil((deliver_rank+goods_rank+service_rank)/3)= 4 or ceil((deliver_rank+goods_rank+service_rank)/3)= 5 then 1 else 0 end) as high_sum," .
            "sum(case when ceil((deliver_rank+goods_rank+service_rank)/3)= 3  then 1 else 0 end) as center_sum," .
            "sum(case when ceil((deliver_rank+goods_rank+service_rank)/3)<=2  then 1 else 0 end) as low_sum,count(comment_id) as total_sum";
        $comment_statistics = Db::name('comment')->field($field)->where($comment_where)->group('goods_id')->find();
        if ($comment_statistics) {
            $comment_statistics['high_rate'] = ceil($comment_statistics['high_sum'] / $comment_statistics['total_sum'] * 100); // 好评率
            $comment_statistics['center_rate'] = ceil($comment_statistics['center_sum'] / $comment_statistics['total_sum'] * 100); // 中评率
            $comment_statistics['low_rate'] = ceil($comment_statistics['low_sum'] / $comment_statistics['total_sum'] * 100); // 差评率
        } else {
            $comment_statistics = ['img_sum' => 0, 'high_sum' => 0, 'high_rate' => 100, 'center_sum' => 0, 'center_rate' => 0, 'low_sum' => 0, 'low_rate' => 0, 'total_sum' => 0];

        }
        // 如果有虚拟，
        if($data['virtual_comment_count'] > 0){
            $comment_statistics['high_rate'] = ceil(($comment_statistics['high_sum']+$data['virtual_comment_count']) / ($comment_statistics['total_sum']+$data['virtual_comment_count']) * 100);
            if(empty($comment_statistics['high_sum']) || $comment_statistics['high_rate'] < 90){
                $comment_statistics['high_rate'] = 99;
            }
            $comment_statistics['high_sum'] += $data['virtual_comment_count'];
            $comment_statistics['total_sum'] += $data['virtual_comment_count'];
        }
        return $comment_statistics;
    }

    public function getPriceLadderAttr($value)
    {
        if (!empty($value)) {
            return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function setVirtualIndateAttr($value)
    {
        return strtotime($value);

    }
    public function setExchangeIntegralAttr($value, $data){
        if($data['is_virtual'] == 1){
            return 0;
        }else{
            return $value;
        }
    }
    public function setCatIdAttr($value, $data){
        if($data['cat_id_3']){
            return $data['cat_id_3'];
        }
        if($data['cat_id_2']){
            return $data['cat_id_2'];
        }
        return $value;
    }

    public function setExtendCatIdAttr($value, $data){
        if($data['extend_cat_id_3']){
            return $data['extend_cat_id_3'];
        }
        if($data['extend_cat_id_2']){
            return $data['extend_cat_id_2'];
        }
        return $value;
    }
    public function setSpecTypeAttr($value, $data){
       return $data['goods_type'];
    }

    public function setPriceLadderAttr($value, $data){
        if ($data['ladder_amount'][0] > 0) {
            $price_ladder = array();
            foreach ($data['ladder_amount'] as $key => $value) {
                $price_ladder[$key]['amount'] = intval($data['ladder_amount'][$key]);
                $price_ladder[$key]['price'] = floatval($data['ladder_price'][$key]);
            }
            $price_ladder = array_values(array_sort($price_ladder, 'amount', 'asc'));
            return json_encode($price_ladder);
        }else{
            return '';
        }
    }

    //获取商品规格
    public function getSpecAttr($value, $data)
    {
        $spec_goods_price_key = db('spec_goods_price')->where("goods_id", $data['goods_id'])->column('key');
        if($spec_goods_price_key){
            $spec_goods_price_key_str = implode('_', $spec_goods_price_key);
            $spec_goods_price_key_arr = explode('_', $spec_goods_price_key_str);
            $spec_goods_price_key_arr = array_unique($spec_goods_price_key_arr);
            $spec_item_list = db('spec_item')->where('id', 'IN', $spec_goods_price_key_arr)->order('order_index asc')->select();
            $spec_ids = get_arr_column($spec_item_list, 'spec_id');
            $spec_list = db('spec')->where('id', 'IN', $spec_ids)->order('`order` desc, id asc')->select();
            foreach($spec_list as $spec_key=>$spec_val){
                foreach($spec_item_list as $spec_item_key=>$spec_item_val){
                    if($spec_val['id'] == $spec_item_val['spec_id']){
                        $spec_list[$spec_key]['spec_item'][] = $spec_item_val;
                    }
                }
            }
            return $spec_list;
        }
        return [];
    }

    public function goodsCategory()
    {
        return $this->hasOne('GoodsCategory', 'id', 'cat_id');
    }

    public function getExchangeIntegralPriceAttr($value, $data)
    {
        $point_rate = tpCache('integral.point_rate');
        if ($point_rate) {
            return $price = round($data['shop_price'] - $data['exchange_integral'] / $point_rate, 2);
        } else {
            return $price = round($data['shop_price'] - $data['exchange_integral'] / 10, 2);
        }
    }
    /**
     * @param $value
     * @param $data
     * @return mixed
     * 获取预约人数
     */
    public function getOrderBespeakCountAttr($value, $data)
    {
        //拿出已付款的预约订单（估计还要练模板查）
        $count = db('shop_order')
            ->alias('s')
            ->join('order o','o.order_id = s.order_id','LEFT')
            ->where(['s.goods_id'=>$data['goods_id'],'o.pay_status'=>1,'o.order_status'=>['neq',3]])
            ->where(['o.order_status'=>['neq',5]])
            ->count();
        return $count;
    }

    /**
     * 获取销量（包含虚拟销量）
     * @param $value
     * @param $data
     */
    public function getSalesSumAttr($value,$data){
        return $value + $data['virtual_sales_sum'];
    }


    public function getLabelIdAttr($value,$data){
        //TODO 可兼容多标签
        if($value>0){
            $label_name = Db::name('goods_label')->where('label_id',$value)->value('label_name');
            return $label_name ?: '';
        }
        return '';
    }

}
