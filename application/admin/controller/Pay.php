<?php
namespace app\admin\controller;
use think\Controller;
use \app\api\model\User as UserModel;
/**
 * 支付页面
 */
class Pay extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $data=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                if($data){
                    $unpaid_c=new \app\api\controller\User();
                    $price = new \app\api\controller\Defaultprice();
                    $unpaiduser=$unpaid_c->getunpaiduser();
                    $this->assign('unpaid',$unpaiduser);
                    $this->assign("user",$data);
                    return $this->fetch('pay');
                }
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}