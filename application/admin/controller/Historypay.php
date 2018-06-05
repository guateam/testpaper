<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use \app\api\model\User as UserModel;
/**
 * 管理员历史记录页面控制器
 */
class Historypay extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
            if($user){
                $bill_c=new \app\api\controller\Bill();
                $bill = $bill_c->getallbill();
                $this->assign("bill",$bill);
                return $this->fetch("historypay");
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}