<?php
namespace app\uploader\controller;
use think\Controller;
use think\Db;
use \app\api\model\User as UserModel;
use \app\api\model\Testpaper as PaperModel;
class Historypaper extends Controller{
    public function index(){
        $user=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
        if($user){
            $paper=new \app\api\controller\Testpaper();
            $pass = $paper->getpasspaper($user->ID);
            $this->assign("user",$user);
            $this->assign("paper",$pass);
            return $this->fetch("historypaper");
        }
        return $this->error('请先登录');
    }
}