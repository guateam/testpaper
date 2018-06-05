<?php
namespace app\auditor\controller;
use think\Controller;
use think\Db;
use \app\api\model\User as UserModel;
/**
 * 历史记录页
 */
class Historypaper extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
            if($user){
                $paper=new \app\api\controller\Testpaper();
                $pass = $paper->getpasspaperforauditor($user->ID);
                $this->assign("user",$user);
                $this->assign("paper",$pass);
                return $this->fetch("historypaper");
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}