<?php
namespace app\admin\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Paydetail extends Controller{
    public function index($unpaid_id){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $data=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                if($data){
                    $unpaid_c=new \app\api\controller\User();
                    $price = new \app\api\controller\Defaultprice();
                    $unpaiduser=$unpaid_c->getunpaiduser();
                    if($unpaid_id >=count($unpaiduser['userinfo']) || $unpaid_id<0 )return $this->error('已无待付款人员','admin/index/index');
                    //未支付的试卷id
                    $paper_id = [];
                    //待支付的用户id
                    $user_id = $unpaiduser['userinfo'][$unpaid_id]['ID'];

                    foreach( $unpaiduser['datainfo'][$unpaid_id] as $each){
                        array_push($paper_id,$each['id']);
                    }
                    $this->assign('unpaiduser',$unpaiduser['userinfo'][$unpaid_id]);
                    $this->assign('unpaiduserid',$user_id);
                    $this->assign('unpaiddata',$unpaiduser['datainfo'][$unpaid_id]);
                    $paper_id = implode(',',$paper_id);
                    $this->assign('ids',$paper_id);
                    $this->assign("user",$data);
                    return $this->fetch('paydetail');
                }
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}