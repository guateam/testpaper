<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
/**
 * 尚未通过试卷页
 */
class Notpassyet extends Controller{
    public function index(){
        if(isset($_COOKIE['userid']))
        {
            $user=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
            if($user)
            {
                $paper=new \app\api\controller\Testpaper();
                $wait = $paper->getwaitingpaper($user->ID);
                $danger = $paper->getbackpaper($user->ID);
                $userdata=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                $this->assign("user",$userdata);
                $this->assign("wait",$wait);
                $this->assign("danger",$danger);
                return $this->fetch("notpassyet");
            }
        }
        
        else return $this->error('请先登录','index/index/index');
    }
}