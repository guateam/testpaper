<?php
namespace app\auditor\controller;
use think\Controller;
use \app\api\model\User as UserModel;
/**
 * 审核试卷页
 */
class Audit extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $testpaper=new \app\api\controller\Testpaper();
                $userdata=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                $userid=$user->checkuser($_COOKIE['userid']);
                if($userid){
                    $data=$testpaper->getwaitingtestpaper($userid);
                    $this->assign("user",$userdata);
                    $this->assign('data',$data);
                    $this->assign('empty','<h1 class="text-center success">没有等待审核的试卷<h1>');
                    return $this->fetch('audit');
                }
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}