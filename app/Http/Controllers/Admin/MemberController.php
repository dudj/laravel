<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Logic\Admin\MemberLogic;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


/**
 * Class MemberController
 * Created by dudj
 * Email: dudongjiangphp@163.com
 * Date: 2020-07-27
 * Summary: 会员管理操作
 */
class MemberController extends Controller
{
    /**
     * 获取会员列表
     */
    public function indexList(){
        $levelList = DB::table('member_level')->select("*")->get()->toArray();
        return view('admin.member.list',[
            'levelList' => $levelList
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * 会员列表 啊哈小获取
     */
    public function ajaxList(Request $request){
        if(!isset($request->sortfield)){
            $request->sortfield = 'id';
        }
        if(!isset($request->sorttype)){
            $request->sorttype = 'desc';
        }
        $param = [
            'paramWhere' => ['level'],
            'paramLike' => ['nickname','email','mobile'],
            'paramDate' => ['register_time'],
            'paramIn' => [],
        ];
        $where = $this->whereConcat($request, $param);
        $list = DB::table('member as m')->leftjoin('member_level as l',function($join){
            $join->on('m.level','=','l.id');
        })->select('m.id','m.username','m.email','m.member_money','m.frozen_money','m.register_time','m.mobile','m.mobile_validated','m.email_validated','m.level','m.is_lock','l.level_name')->where($where)->orderBy($request->sortfield, $request->sorttype)->paginate(20)->toArray();
        $data = [
            'code' => 0,
            'data' => $list['data'],
            'count' => $list['total'],
            'msg' => '查询成功'
        ];
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * 添加会员
     */
    public function addMember(Request $request){
        if($request->ajax() && $request->isMethod('post')){
            $memberObj = new MemberLogic();
            $res = $memberObj->addMember($request);
            if($res['code'] >= 1){
                return $this->success($res, $res['msg']);
            }else{
                return $this->error([], $res['msg']);
            }
        }
        return view('admin.member._member');
    }

    /**
     * @param Request $request
     * @return mixed
     * 编辑会员信息
     */
    public function editMember(Request $request){
        if($request->ajax() && $request->isMethod('post')){
            $memberObj = new MemberLogic();
            $res = $memberObj->editMember($request);
            if($res['code'] >= 1){
                return $this->success($res, $res['msg']);
            }else{
                return $this->error([], $res['msg']);
            }
        }else{
            return $this->error([], '请求方式不对');
        }
    }
    /**
     * 获取会员详情
     */
    public function detail(Request $request){
        $levelList = DB::table('member_level')->select("*")->get()->toArray();
        $member = DB::table('member')->select("*")->where('id',$request->id)->first();
        return view('admin.member.detail',[
            'levelList' => $levelList,
            'member' => $member
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 资金列表
     */
    public function accountLog(Request $request){
        if(!isset($request->sortfield)){
            $request->sortfield = 'id';
        }
        if(!isset($request->sorttype)){
            $request->sorttype = 'desc';
        }
        if($request->ajax() && $request->isMethod('post')){
            $list = DB::table('account_log')->select('*')->where('member_id',$request->member_id)->orderBy($request->sortfield, $request->sorttype)->paginate(20)->toArray();
            $data = [
                'code' => 0,
                'data' => $list['data'],
                'count' => $list['total'],
                'msg' => '查询成功'
            ];
            return response()->json($data);
        }
        return view('admin.member.account_log',[
            'member_id' => $request->member_id
        ]);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * 账户管理资金
     */
    public function editAccount(Request $request){
        if($request->ajax() && $request->isMethod('post')){
            try{
                $memberObj = new MemberLogic();
                $res = $memberObj->editAccount($request);
                return response()->json($res);
            }catch (\Exception $e){
                return $this->error([],$e->getMessage());
            }
        }
        $member = DB::table('member')->select("*")->where('id',$request->member_id)->first();
        return view('admin.member.edit_account',[
            'member' => $member
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * 获取会员对应的收货地址
     * 地区所有的信息在缓存中存放
     */
    public function address(Request $request){
        if($request->ajax() && $request->isMethod('post')){
            try{
                $memberObj = new MemberLogic();
                $res = $memberObj->getAddressList($request);
                return response()->json($res);
            }catch (\Exception $e){
                return response()->json(['code'=>-1,'msg'=>$e->getMessage()]);
            }
        }
        return view('admin.member.address',[
            'member_id' => $request->member_id
        ]);
    }
    //会员等级
    public function levelList(Request $request){
        if($request->ajax() && $request->isMethod('get')){
            if(!isset($request->sortfield)){
                $request->sortfield = 'id';
            }
            if(!isset($request->sorttype)){
                $request->sorttype = 'desc';
            }
            $list = DB::table('member_level')->select('*')->orderBy($request->sortfield, $request->sorttype)->paginate(20)->toArray();
            $data = [
                'code' => 0,
                'data' => $list['data'],
                'count' => $list['total'],
                'msg' => '查询成功'
            ];
            return response()->json($data);
        }
        return view('admin.member.levellist');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * 添加编辑会员等级
     */
    public function addEditMemberLevel(Request $request){
        try{
            if($request->ajax() && $request->isMethod('post')){
                try{
                    $memberObj = new MemberLogic();
                    $res = $memberObj->addEditMemberLevel($request);
                    return response()->json($res);
                }catch (\Exception $e){
                    return $this->error([],$e->getMessage());
                }
            }
            $member = DB::table('member_level')->select("*")->where('id',$request->id)->first();
            return view('admin.member._member_level',[
                'data'=>$member
            ]);
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 删除会员等级数据
     */
    public function deleteMemberLevel(Request $request){
        try{
            $id = $request->get('ids');
            $res = self::commonDel('member_level',[['key'=>'id','relation'=>'=','val'=>$id]]);
            if($res){
                return $this->success([],'删除成功');
            }
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }
    //签到
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View|mixed
     * 签到列表
     */
    public function signList(Request $request){
        try{
            if($request->ajax() && $request->isMethod('post')){
                if(!isset($request->sortfield)){
                    $request->sortfield = 'id';
                }
                if(!isset($request->sorttype)){
                    $request->sorttype = 'desc';
                }
                $param = [
                    'paramDate' => ['signtime'],
                    'paramIn' => [],
                    'paramWhere' => [],
                    'paramLike' => [],
                ];
                $where = $this->whereConcat($request, $param);
                $list = DB::table('sign as s')->leftjoin('member as m',function($join){
                    $join->on('m.id','=','s.member_id');
                })->select('s.*','m.username')->where($where)->orderBy($request->sortfield, $request->sorttype)->paginate(20)->toArray();
                $data = [
                    'code' => 0,
                    'data' => $list['data'],
                    'count' => $list['total'],
                    'msg' => '查询成功'
                ];
                return response()->json($data);
            }
            return view('admin.member.signList');
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }
    //充值提现
    public function rechargeList(Request $request){
        try{
            if($request->ajax() && $request->isMethod('post')){
                if(!isset($request->sortfield)){
                    $request->sortfield = 'id';
                }
                if(!isset($request->sorttype)){
                    $request->sorttype = 'desc';
                }
                $param = [
                    'paramDate' => ['createtime'],
                    'paramIn' => [],
                    'paramWhere' => [],
                    'paramLike' => ['nickname'],
                ];
                $where = $this->whereConcat($request, $param);
                $list = DB::table('recharge')->select('*')->where($where)->orderBy($request->sortfield, $request->sorttype)->paginate(20)->toArray();
                $data = [
                    'code' => 0,
                    'data' => $list['data'],
                    'count' => $list['total'],
                    'msg' => '查询成功'
                ];
                return response()->json($data);
            }
            return view('admin.member.rechargeList');
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View|mixed
     * 提现列表
     */
    public function withdrawalsList(Request $request){
        try{
            if($request->ajax() && $request->isMethod('post')){
                if(!isset($request->sortfield)){
                    $request->sortfield = 'id';
                }
                if(!isset($request->sorttype)){
                    $request->sorttype = 'desc';
                }
                $param = [
                    'paramDate' => ['applytime'],
                    'paramIn' => [],
                    'paramWhere' => ['status'],
                    'paramLike' => ['username','realname','bankcard'],
                ];
                $where = $this->whereConcat($request, $param);
                $list = DB::table('withdrawals as w')->leftjoin('member as m',function($join){
                    $join->on('w.member_id','=','m.id');
                })->select('w.*','m.username')
                    ->where($where)
                    ->whereNotIn('status',[-1,-2])->orderBy($request->sortfield, $request->sorttype)->paginate(20)->toArray();
                $data = [
                    'code' => 0,
                    'data' => $list['data'],
                    'count' => $list['total'],
                    'msg' => '查询成功'
                ];
                return response()->json($data);
            }
            return view('admin.member.withdrawalsList');
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     * 修改提现记录的审核状态
     */
    public function withdrawalsEdit(Request $request){
        try{
            $memberObj = new MemberLogic();
            $res = $memberObj->withdrawalsEdit($request);
            if($res == 1){
                return $this->success([],'审核完成');
            }
            return $this->error([],'审核失败');
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }

    public function signRules(Request $request){
        try{
            if($request->ajax() && $request->isMethod('post')){
                $data = [];
                foreach($request->all() as $k=>$v){
                    $data[$k] = $v;
                }
                LaravelRedisCache('signRules.all',$data);
                return $this->success([],'成功');
            }
            $data = LaravelRedisCache('signRules.',[]);
            return view('admin.member.sign_rules',[
                'data' => $data
            ]);
        }catch (\Exception $e){
            return $this->error([],$e->getMessage());
        }
    }
}
