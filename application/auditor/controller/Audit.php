<?php
namespace app\auditor\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Audit extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $testpaper=new \app\api\controller\Testpaper();
            $user=new \app\api\controller\User();
            $userid=$user->checkuser($_COOKIE['userid']);
            if($userid){
                $data=$testpaper->getwaitingtestpaper($userid);
                $this->assign('data',$data);
                $this->assign('empty','<h1 class="text-center success">没有等待审核的试卷<h1>');
                return $this->fetch('audit');
            }
        }
        return $this->error('请先登录');
    }
    public function confirm($id,$auditorid){
        $testpaper=new \app\api\controller\Testpaper();
        $user=new \app\api\controller\User();
        $userid=$user->checkuser($auditorid);
        if($userid){
            $testpaper->confirm($id,$userid);
            return json(['status'=>1]);
        }
        return json(['status'=>0]);
    }
    public function cancel($id,$auditorid){
        $testpaper=new \app\api\controller\Testpaper();
        $user=new \app\api\controller\User();
        $userid=$user->checkuser($auditorid);
        if($userid){
            $testpaper->cancel($id,$userid);
            return json(['status'=>1]);
        }
        return json(['status'=>0]);
    }
}