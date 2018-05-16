<?php
namespace app\auditor\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Auditdetail extends Controller{
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $testpaper=new \app\api\controller\Testpaper();
            $data=$testpaper->gettestpaper($id);
            if($data){
                $this->assign('id',$id);
                $this->assign('data',$data);
                return $this->fetch('auditdetail');
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