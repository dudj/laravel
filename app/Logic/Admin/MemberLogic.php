<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Email: dudongjiangphp@163.com
 * Date: 2020/7/27
 * Time: 16:17
 * Summary: 会员相关逻辑
 */

namespace App\Logic\Admin;


use App\Models\Common\AccountLog;
use App\Models\Common\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Predis\Client;

class MemberLogic extends Model
{
    public $redis;
    public function __construct(){
        $this->redis = new Client();
    }

    /**
     * @param $request
     * @return array
     * 添加用户
     */
    public function addMember($request){
        $model = new Member();
        $res = $model->checkForm($request);
        if($res['code'] >= 1){
            // 会员注册赠送积分
            $isRegIntegral = $this->redis->get('integral.is_reg_integral');
            if ($isRegIntegral == 1) {
                $pay_points = $this->redis->get('integral.reg_integral');
            } else {
                $pay_points = 0;
            }
            if ($pay_points > 0)
                accountLog($res['data'], 0, $pay_points, '会员注册赠送积分');
            return ['code' => 1, 'msg' => '添加成功'];
        }else{
            return $res;
        }
    }

    /**
     * @param $request
     * @return array
     * 编辑会员信息
     */
    public function editMember($request){
        $model = new Member();
        $res = $model->checkForm($request);
        return $res;
    }

    /**
     * @param $request
     * @return array
     * 编辑会员资金记录
     */
    public function editAccount($request){
        $model = new AccountLog();
        $res = $model->checkForm($request);
        return $res;
    }

    /**
     * @param $request
     * @return array
     * 获取会员对应的收货地址
     */
    public function getAddressList($request){
        $data = DB::table('member_address as address')
            ->leftjoin('region as province',function($join){
            $join->on('address.province','=','province.id');
        })->leftjoin('region as city',function($join){
            $join->on('city.id','=','address.city');
        })->leftjoin('region as district',function($join){
            $join->on('district.id','=','address.district');
        })->leftjoin('region as twon',function($join){
                $join->on('twon.id','=','address.twon');
        })->select('address.*','province.name as province_name','city.name as city_name','district.name as district_name','twon.name as twon_name')
        ->where('member_id',$request->member_id)->get()->toArray();
        return [
            'code' => 0,
            'data' => $data,
            'count' => 0,
            'msg' => '查询成功'
        ];
    }
}