<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Created by PhpStorm.
 * User: dudj
 * Email: dudongjiangphp@163.com
 * Date: 2020/5/21
 * Time: 14:49
 * Summary: 公共文件类
 */
/**
 * @return string
 * 获取当前请求节点的url
 */
function getTemplatePath()
{
    $url = '';
    list($class, $method) = explode('@', request()->route()->getActionName());
    # 模块名
    $modules = strtolower(str_replace(
        '\\',
        '.',
        str_replace(
            'App\\Http\\Controllers\\',
            '',
            trim(
                implode('\\', array_slice(explode('\\', $class), 0, -1)),
                '\\'
            )
        )
    ));
    # 控制器名称
    $controller = strtolower(str_replace(
        'Controller',
        '',
        substr(strrchr($class, '\\'), 1)
    ));
    # 方法名
    $url = $modules.'/'.$controller.'/'.$method;
    return $url;
}

/**
 * @return array
 * 直接可以访问的控制器 不受权限控制
 */
function getCommonController(){
    return [
        "common"
    ];
}
/**
 * @return array
 * 直接可以访问的方法 不受权限控制
 */
function getCommonMethod(){
    return [
        'store',
        'update',
        'index_json'
    ];
}

/**
 * @param $password
 * @param $salt
 * @return string
 * 密码加密规则
 */
function generatePassword($password,$salt){
    return sha1('laravel' .$salt . sha1($salt . sha1($password)));
}

/**
 * @return string
 * 生成salt 规则：小写大写数字小写大写
 */
function generateSalt(){
    return chr(rand(65, 90)).chr(rand(97, 122)).rand(10000,99999).chr(rand(65, 90)).chr(rand(97, 122));
}
/**
 * @param $arr
 * @param $key_name
 * @return array
 * 将数据库中查出的列表以指定的 id 作为数组的键名
 */
function convert_arr_key($arr, $key_name)
{
    $arr2 = array();
    foreach($arr as $key => $val){
        $arr2[$val[$key_name]] = $val;
    }
    return $arr2;
}
/**
 * @param $str
 * @return null|string
 * PHP 获取中文首字母拼音
 */
function getFirstCharter($str){
    if(empty($str))
    {
        return '';
    }
    $fchar=ord($str{0});
    if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
    $s1=iconv('UTF-8','gb2312//TRANSLIT//IGNORE',$str);
    $s2=iconv('gb2312','UTF-8//TRANSLIT//IGNORE',$s1);
    $s=$s2==$str?$s1:$str;
    $asc=ord($s{0})*256+ord($s{1})-65536;
    if($asc>=-20319&&$asc<=-20284) return 'A';
    if($asc>=-20283&&$asc<=-19776) return 'B';
    if($asc>=-19775&&$asc<=-19219) return 'C';
    if($asc>=-19218&&$asc<=-18711) return 'D';
    if($asc>=-18710&&$asc<=-18527) return 'E';
    if($asc>=-18526&&$asc<=-18240) return 'F';
    if($asc>=-18239&&$asc<=-17923) return 'G';
    if($asc>=-17922&&$asc<=-17418) return 'H';
    if($asc>=-17417&&$asc<=-16475) return 'J';
    if($asc>=-16474&&$asc<=-16213) return 'K';
    if($asc>=-16212&&$asc<=-15641) return 'L';
    if($asc>=-15640&&$asc<=-15166) return 'M';
    if($asc>=-15165&&$asc<=-14923) return 'N';
    if($asc>=-14922&&$asc<=-14915) return 'O';
    if($asc>=-14914&&$asc<=-14631) return 'P';
    if($asc>=-14630&&$asc<=-14150) return 'Q';
    if($asc>=-14149&&$asc<=-14091) return 'R';
    if($asc>=-14090&&$asc<=-13319) return 'S';
    if($asc>=-13318&&$asc<=-12839) return 'T';
    if($asc>=-12838&&$asc<=-12557) return 'W';
    if($asc>=-12556&&$asc<=-11848) return 'X';
    if($asc>=-11847&&$asc<=-11056) return 'Y';
    if($asc>=-11055&&$asc<=-10247) return 'Z';
    return null;
}

/**
 * @param $cat_id
 * @return array|mixed
 * 获取某个商品分类的子节点(无限极)
 */
function getCatGrandson ($cat_id)
{
    $GLOBALS['catGrandson'] = array();
    $GLOBALS['category_id_arr'] = array();
    // 先把自己的id 保存起来
    $GLOBALS['catGrandson'][] = $cat_id;
    // 把整张表找出来
    $GLOBALS['category_id_arr'] = DB::table('goods_category')->select('id','parent_id')->get()->toArray();
    // 先把所有儿子找出来
    $son_id_arr = DB::table('goods_category')->where("parent_id", '=', $cat_id)->select('id')->get()->toArray();
    foreach($son_id_arr as $k => $v)
    {
        getCatGrandson2($v);
    }
    return $GLOBALS['catGrandson'];
}

/**
 * @param $cat_id
 * 递归调用子节点
 */
function getCatGrandson2($cat_id){
    $GLOBALS['catGrandson'][] = $cat_id;
    foreach($GLOBALS['category_id_arr'] as $k => $v)
    {
        if($v == $cat_id)
        {
            getCatGrandson2($k); // 继续找孙子
        }
    }
}

/**
 * @param $data
 * @param $model
 * @return array
 * 去除多个不想关的字段
 */
function filterFields($data,$model)
{
    $fields = Schema::getColumnListing($model->getTable());
    $tableColumn = array_keys($data);
    $columns = array_intersect($tableColumn, $fields);
    $return = [];
    //值不为空的
    array_walk($data, function($value,$key) use (&$return, $columns){
        if(in_array($key, $columns) && $value != ''){
            $return[$key] = $value;
        }
    });
    return $return;
}

/**
 * @return string
 * 获取商品货号
 */
function getGoodsSn(){
    if(isset($basic['goods_sn']) && !empty($basic['goods_sn'])){
        $goods_sn_pre = $basic['goods_sn'];
    }else{
        $goods_sn_pre = 'LD';
    }
    return $goods_sn_pre.time().rand(1,999);
}

/**
 * @param int $memberId 用户id
 * @param int $memberMoney 可用余额变动
 * @param int $payPoints 消费积分变动
 * @param string $desc 描述
 * @param int $orderId 订单id
 * @param string $orderSn 订单号
 * @param bool $recharge false不操作$user_total_money ,true则$user_total_money记录充值累计金额
 * @param int $withdrawn 用户提现金额 0不操作$withdrawal_total_money ,大于0则$withdrawal_total_money记录提现累计金额
 * @return bool
 * 记录积分
 */
function accountLog($memberId, $memberMoney = 0,$payPoints = 0, $desc = '',$orderId = 0 ,$orderSn = '',$recharge = false,$withdrawn = 0){
    $account_log = array(
        'member_id'       => $memberId,
        'member_money'    => $memberMoney,
        'pay_points'    => $payPoints,
        'change_time'   => time(),
        'desc'   => $desc,
        'order_id' => $orderId,
        'order_sn' => $orderSn
    );
    $member = DB::table('member')->where('id', '=', $memberId)->first();
    $updateData = [
        'member_money'        => $member['member_money'] + $memberMoney,
        'pay_points'        => $member['pay_points'] + $payPoints
    ];
    if($recharge) $updateData['member_total_money'] = $member['member_total_money'] + $memberMoney;  //用户充值累计金额
    if($withdrawn) $updateData['withdrawal_total_money'] = $member['withdrawal_total_money'] + $withdrawn;  //用户提现累计金额
    if(($memberMoney+$payPoints) == 0)return false;
    $update = DB::table('member')->where('id', '=', $memberId)->update($updateData);
    if($update){
        DB::table('account_log')->insert($account_log);
        return true;
    }else{
        return false;
    }
}
/**
 * @param $key intergal.is_reg => 查询
 * @param $data
 * @return array
 * @throws Exception
 * 缓存一些数据
 */
function LaravelRedisCache($key,$data){
    try{
        $redis = new \Predis\Client();
        $param = explode('.',$key);
        if(empty($data)){
            $res = $redis->get($param[0]);
            if(!$res){
                $cacheData = DB::table('config')->where('inc_type',$param[0])->get()->toArray();
                $arr = [];
                foreach($cacheData as $val){
                    $arr[$val['name']] = $val['value'];
                }
                $redis->set($param[0],serialize($arr));
                return $arr;
            }else{
                return unserialize($res);
            }
        }else{
            $result =  DB::table('config')->where("inc_type", $param[0])->get()->toArray();
            //有数据 更新 或者 插入
            if($result){
                foreach($data as $k=>$v){
                    $flag = true;
                    foreach($result as $key=>$val){
                        if($val['name'] == $k){
                            $flag = false;
                            DB::table('config')->where('name',$k)->where('inc_type',$param[0])->update(['value'=>$v]);
                            break;
                        }
                    }
                    if($flag){
                        DB::table('config')->insert(['inc_type'=>$param[0],'name'=>$k,'value'=>$v]);
                    }
                }
            }else{
                //没有数据插入
                $newArr = [];
                foreach($data as $k=>$v){
                    $newArr[] = array('name'=>$k,'value'=>trim($v),'inc_type'=>$param[0]);
                }
                DB::table('config')->insert($newArr);
            }
            $redis->set($param[0],serialize($data));
            return $data;
        }
    }catch (Exception $e){
        throw new Exception($e->getMessage());
    }
}
?>