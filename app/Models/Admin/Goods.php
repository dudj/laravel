<?php
namespace App\Models\Admin;

use App\Logic\Admin\GoodsLogic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Predis\Client;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Email: dudongjiangphp@163.com
 * Date: 2020/06/03
 * Time: 15:54
 * Summary: 商品验证模型
 */
class Goods extends Model
{
    const UPDATED_AT = null;
    // 数据信息
    protected $data = [];
    protected $table = 'goods';
    const exec_success = '执行成功';
    const exec_fail = '执行失败';
    // 验证规则
    public $rule = [
        'goods_id' => 'checkGoodsId:\App\Models\Admin\Goods',
        'goods_name' => 'required|min:3|max:150|unique:goods',
        'cat_id' => 'integer|min:0',
        'goods_sn' => 'max:20|unique:goods',
        'shop_price' => ['required', 'regex:([1-9]\d*(\.\d*[1-9])?)','checkShopPrice:\App\Models\Admin\Goods'],
        'market_price' => 'required|regex:/^\d{1,10}(\.\d{1,2})?$/|checkMarketPrice:\App\Models\Admin\Goods',
        'weight' => 'regex:/^\d{1,10}(\.\d{1,2})?$/',
        'give_integral' => 'regex:/^\d+$/',
        'exchange_integral' => 'checkExchangeIntegral:\App\Models\Admin\Goods',
        'is_free_shipping' => 'required|checkShipping:\App\Models\Admin\Goods',
        'bespeak_template_id' => 'checkBespeakTemplate:\App\Models\Admin\Goods',
        'commission' => 'checkCommission:\App\Models\Admin\Goods',
        'ladder_amount' => 'checkLadderAmount:\App\Models\Admin\Goods',
        'ladder_price' => 'checkLadderPrice:\App\Models\Admin\Goods',
        'virtual_limit' => 'checkVirtualLimit:\App\Models\Admin\Goods',
        'virtual_indate'=>'checkVirtualIndate:\App\Models\Admin\Goods'
    ];
    //错误信息
    protected $message = [
        'goods_name.required' => '商品名称必填',
        'goods_name.min' => '名称长度至少3个字符',
        'goods_name.max' => '名称长度至多50个汉字',
        'goods_name.unique' => '商品名称重复',
        'cat_id.integer' => '商品分类必须填写',
        'cat_id.gt' => '商品分类必须选择',
        'goods_sn.unique' => '商品货号重复',
        'goods_sn.max' => '商品货号超过长度限制',
        'goods_num.checkGoodsNum' => '抢购数量不能大于库存数量',
        'shop_price.required' => '本店售价必填',
        'shop_price.regex' => '本店售价格式不对',
        'shop_price.checkShopPrice' => '本店售价格式不对',
        'market_price.required' => '市场价格必填',
        'market_price.regex' => '市场价格式不对',
        'market_price.checkMarketPrice' => '市场价不得小于本店价',
        'weight.regex' => '重量格式不对',
        'give_integral.regex' => '赠送积分必须是正整数',
        'exchange_integral.checkExchangeIntegral' => '积分抵扣金额不能超过商品总额',
        'virtual_indate'=>'虚拟商品有效期不得小于当前时间',
        'is_free_shipping.required' => '请选择商品是否包邮',
        'virtual_limit.checkVirtualLimit' => '虚拟商品购买上限1~10之间的数字',
    ];
    /**
     * @param $request
     * @return array
     * 验证表单提交的内容 增加和修改均需要验证 因此作为公共方法
     */
    public function checkForm($request){
        try{
            $model = new Goods();
            if($request->goods_id){
                $model->rule['goods_name'] = 'required|min:3|max:150|unique:goods,goods_name,'.$request->goods_id.',goods_id';
                $model->rule['goods_sn'] = 'max:20|unique:goods,goods_sn,'.$request->goods_id.',goods_id';
            }
            $validator = Validator::make($request->all(), $model->rule,$model->message);
            if($validator->fails()){
                foreach ($validator->errors()->all() as $message) {
                    return ['code'=>-1,'msg'=>$message];
                }
            }
            $data = $request->all();
            $data['last_update'] = time();
            $data['price_ladder'] = true;
            if (!empty($data['behavior']) && $data['behavior'] == 'audit') {
                $goods['audit'] = 0;
                $goods['is_on_sale'] = 1;
            }
            self::setCatIdAttr($data);
            self::setPriceLadderAttr($data);
            //修改
            $data = filterFields($data, new Goods());
            if(isset($data['goods_id']) && $data['goods_id'] > 0){
                $goods = DB::table($this->table)->where('goods_id','=',$data['goods_id'])->first();
                $store_count_change_num = $goods['store_count'] - $data['store_count'];
                $goods_id = $data['goods_id'];
                unset($data['goods_id']);
                $model->where('goods_id',$goods_id)->update($data);
            }else{
                unset($data['goods_id']);
                $data['goods_sn'] = getGoodsSn();
                $store_count_change_num = $data['store_count'];
                $goods_id = $model->insertGetId($data);
            }
            $GoodsLogic = new GoodsLogic();
            $GoodsLogic->afterSave($goods_id,$request->all());
            $GoodsLogic->saveGoodsAttr($goods_id, $request->all());
            return ['code'=>1,'msg'=>self::exec_success];
        }catch (ValidationException $e){
            return ['code'=>-1,'msg'=>$e->getMessage()];
        }
    }
    //---rule规则验证
    public static function checkShopPrice($value, $rule, $data){
        if ($value < 0.01) {
            return  '售价不能小于0.01元';
        } else {
            return true;
        }
    }
    //检查阶梯价格中的库存
    public function checkLadderAmount($value, $rule, $data)
    {
        if(min($value) != '' && min($value) <= 0){
            return  '您没有输入有效的库存阶梯！';
        }
        return true;
    }

    //检查阶梯价格中的价格
    public function checkLadderPrice($value, $rule, $data)
    {
        if(max($value) >= $data['shop_price']){
            return '价格阶梯最大金额不能大于商品原价！';
        }
        if(min($value) != '' && min($value) <= 0){
            return '您没有输入有效的价格阶梯！';
        }
        if(min($value) != '' && !empty($data['item'])){
            return '价格阶梯商品不允许添加多规格商品';
        }
        return true;
    }
    // 检查积分兑换
    public function checkExchangeIntegral($value, $rule, $data)
    {
        if ($value > 0) {
            $goods = DB::table('goods')->where('goods_id', '=', $data['goods_id'])->first()->toArray();
            if (!empty($goods)) {
                if ($goods['prom_type'] > 0) {
                    return '该商品参与了其他活动。设置兑换积分无效，请设置为零';
                }
            }
        }
        return true;
        //积分规则修改后的逻辑
        $this->redis = new Client();
        $point_rate_value = $this->redis->get('getSortBrands');
        if ($data['item']) {
            $count = count($data['item']);
            $item_arr = array_values($data['item']);
            $minPrice = $item_arr[0]['price'];
            for ($i = 0; $i < ($count - 1); $i++) {
                if ($item_arr[$i + 1]['price'] < $minPrice) {
                    $minPrice = $item_arr[$i + 1]['price'];
                }
            }
            $goods_price = $minPrice;
        } else {
            $goods_price = $data['shop_price'];
        }

        $point_rate_value = empty($point_rate_value) ? 0 : $point_rate_value;
        if ($value > ($goods_price * $point_rate_value)) {
            return '积分抵扣金额不能超过商品总额';
        } else {
            return true;
        }
    }

    //检查是否有商品规格参加活动，若有则不能编辑商品
    public function checkGoodsId($value, $rule, $data)
    {
        $goods = DB::table('goods')->where('goods_id','=',$value)->first();
        if($goods['prom_type'] > 0){
            // 无规格时，只要不添加规格，就允许改，
            if(isset($data['item']))
                return '该商品正在参与活动，不能添加规格';
        }
        return true;
    }

    //检查市场价
    public function checkMarketPrice($value, $rule, $data)
    {
        if ($value < $data['shop_price']) {
            return '市场价不得小于本店价';
        } else if ($value < $data['cost_price']) {
            return '市场价不得小于供货价';
        } else {
            return true;
        }
    }
    public function checkVirtualLimit($value, $rule, $data)
    {
        if ($data['is_virtual'] == 1){
            if($value > 0 && $value < 11){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
    //检查虚拟商品有效时间
    public function  checkVirtualIndate($value, $rule, $data)
    {
        if($data['is_virtual'] == 1 && strtotime($value) < time()){
            return false;
        }
        return true;
    }

    public function checkShipping($value, $rule, $data)
    {
        if ($value == 0 && empty($data['template_id'])) {
            return '请选择运费模板';
        } else {
            return true;
        }
    }
    public function checkCommission($value, $rule, $data)
    {
        if ($value > $data['shop_price']) {
            return '商品分销的分成金额不能大于等于本店售价金额';
        } else {
            return true;
        }
    }
    public function checkBespeakTemplate($value, $rule, $data)
    {
        if ($data['is_virtual']==2) {
            if($value>0){
                if($data['invalid_refund']<=0){
                    return '请选择过期失效或者过期可退款';
                }
                return true;
            }
            return '请选择预约模板';
        }
        return true;
    }

    //---特殊数据拼凑
    /**
     * @param $data
     * 设置商品类别
     */
    public function setCatIdAttr(&$data){
        if(isset($data['cat_id_3']) && $data['cat_id_3']){
            $data['cat_id'] = $data['cat_id_3'];
        }else if(isset($data['cat_id_2']) && $data['cat_id_2']){
            $data['cat_id'] = $data['cat_id_2'];
        }
    }

    /**
     * @param $data
     * 设置价格货梯
     */
    public function setPriceLadderAttr(&$data){
        if ($data['ladder_amount'][0] > 0) {
            $price_ladder = array();
            foreach ($data['ladder_amount'] as $key => $value) {
                $price_ladder[$key]['amount'] = intval($data['ladder_amount'][$key]);
                $price_ladder[$key]['price'] = floatval($data['ladder_price'][$key]);
            }
            $price_ladder = array_values(array_sort($price_ladder, 'amount', 'asc'));
            $data['price_ladder'] = json_encode($price_ladder);
        }else{
            $data['price_ladder'] = '';
        }
    }
}