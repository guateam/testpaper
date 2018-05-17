<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Notpassyet extends Controller{
    public function index(){
        $user=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
        if($user)
        {
            $paper=new \app\api\controller\Testpaper();
            $wait = $paper->getwaitingpaper($user->ID);
            $danger = $paper->getbackpaper($user->ID);
            $this->assign("wait",$wait);
            $this->assign("danger",$danger);
            return $this->fetch("notpassyet");
        }
        else return $this->error('请先登录','index/index/index');
    }
}