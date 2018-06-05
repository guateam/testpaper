<?php
namespace app\auditor\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Auditdetail extends Controller{
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $testpaper=new \app\api\controller\Testpaper();
                $userdata=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                $data=$testpaper->gettestpaper($id);
                if($data){
                    $this->assign("user",$userdata);
                    $this->assign('id',$id);
                    $this->assign('data',$data);
                    return $this->fetch('auditdetail');
                }
            }
        }
        return $this->error('请先登录','index/index/index');
    }
    public function confirm($id,$auditorid){
        $testpaper=new \app\api\controller\Testpaper();
        $user=new \app\api\controller\User();
        $userid=$user->checkuser($auditorid);
        if($userid){
            $testpaper->confirm($id,$userid);
            $paper_price = $testpaper->getprice($id);
            if($paper_price)
            {
                $uploaderid = $testpaper->getuploader($id);
                //审核者增加未发放工资
                $user->addunpaid($userid,$paper_price['auditorprice']);
                //上传者增加未发放工资
                $user->addunpaid($uploaderid,$paper_price['price']);
            }

            return json(['status'=>1]);
        }
        return json(['status'=>0]);
    }
    public function cancel($id,$auditorid,$note){
        $testpaper=new \app\api\controller\Testpaper();
        $user=new \app\api\controller\User();
        $userid=$user->checkuser($auditorid);
        if($userid){
            $testpaper->cancel($id,$userid,$note);
            return json(['status'=>1]);
        }
        return json(['status'=>0]);
    }
}