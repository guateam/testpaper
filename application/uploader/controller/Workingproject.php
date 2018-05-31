<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Workingproject extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $testpaper=new \app\api\controller\Testpaper();
                $user=new \app\api\controller\User();
                $userid=$user->checkuser($_COOKIE['userid']);
                if($userid){
                    $data=$testpaper->getworkingtestpaper($userid);
                    $userdata=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                    $this->assign("user",$userdata);
                        $this->assign('data',$data);
                        $this->assign('empty','<h1 class="text-center success">没有正在录入的试卷<h1>');
                        return $this->fetch('workingproject');
                }
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}