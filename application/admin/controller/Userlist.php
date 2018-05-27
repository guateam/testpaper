<?php
namespace app\admin\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Userlist extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $auditlist=$user->getauditorlist();
                $userlist=$user->getuserlist();  
                $this->assign("userlist",$userlist);  
                $this->assign("auditorlist",$auditlist);
                return $this->fetch('userlist');
                
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}