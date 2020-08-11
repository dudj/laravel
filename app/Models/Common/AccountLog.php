<?php
namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
/**
 * Class Member
 * Created by PHP strom
 * User: dudj
 * Email: dudongjiangphp@163.com
 * Date: 2020-07-30
 * Summary: 会员记录资金积分管理模型
 */
class AccountLog extends Model
{
    const UPDATED_AT = null;
    // 数据信息
    protected $data = [];
    protected $table = 'account_log';
    const exec_success = '执行成功';
    const exec_fail = '执行失败';
    // 验证规则
    public $rule = [
        'desc' => 'required',
    ];
    //错误信息
    protected $message = [
        'desc.required' => '描述必填',
    ];
    /**
     * @param $request
     * @param $type
     * @return array
     * 验证表单提交的内容 增加和修改均需要验证 因此作为公共方法
     * type 作为区分前后端认证的标识
     */
    public function checkForm($request,$type='admin'){
        try{
            $model = new AccountLog();
            $validator = Validator::make($request->all(), $model->rule,$model->message);
            if($validator->fails()){
                foreach ($validator->errors()->all() as $message) {
                    return ['code'=>-1,'msg'=>$message];
                }
            }
            $data = $request->all();
            return self::valJudge($data);
        }catch (ValidationException $e){
            return ['code'=>-1, 'data'=>'', 'msg'=>$e->getMessage()];
        }
    }

    /**
     * @param $data
     * @return array
     * 验证会员各项数据是否正常
     * 操作会员的冻结金额
     */
    public function valJudge($data){
        $member = DB::table('member')->select("*")->where('id',$data['member_id'])->first();
        if($data['money_type'] < 1){
            if($data['member_money'] > $member['member_money']){
                return '用户剩余资金不足！';
            }
            $data['member_money'] = - $data['member_money'];
        }
        if($data['frozen_type'] == 1){
            //增加冻结金额
            if($data['frozen_money'] > $member['member_money']){
                return '用户剩余资金不足！';
            }
            $data['frozen_money'] = - $data['frozen_money'];
        }else if($data['frozen_type'] < 1){
            //减少冻结金额
            if($data['frozen_money'] > $member['frozen_money']){
                return '冻结的资金不足！';
            }
        }
        $frozen_money = $member['frozen_money'] + $data['frozen_money'];
        if($data['points_type'] < 1){
            if($data['pay_points'] > $member['pay_points']){
                return '积分剩余不足';
            }
            $data['pay_points'] = - $data['pay_points'];
        }
        DB::table('member')->where('id', $data['member_id'])->update(['frozen_money' => $frozen_money]);
        $res = accountLog($data['member_id'], $data['member_money'], $data['pay_points'], $data['desc'], 0,0,'',$data['frozen_money'] != 0?false:true);
        if ($res) {
            return ['code' => 1, 'msg' => "操作成功"];
        } else {
            return ['code' => -1, 'msg' => "操作失败"];
        }
    }
}
