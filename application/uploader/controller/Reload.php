<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Reload extends Controller{
    public function index($id){
        $user=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
        if($user)
        {
            $paper=new \app\api\controller\Testpaper();
            $reload = \app\api\model\Testpaper::get(["ID"=>$id]);
            $this->assign("reload",$reload);
            return $this->fetch("reload");
        }
        else return $this->error('请先登录');
    }
}