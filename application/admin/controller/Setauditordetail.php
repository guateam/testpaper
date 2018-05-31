<?php
namespace app\admin\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Setauditordetail extends Controller{
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $testpaper=new \app\api\controller\Testpaper();
                $data=$testpaper->gettestpaper($id);
                if($data){
                    $userlist=$user->getauditorlist();
                    $this->assign('userlist',$userlist);
                    $this->assign("data",$data);
                    $this->assign('empty','<h4 class="text-center">没有数据</h4>');
                    setcookie('paperid',$id);
                    return $this->fetch('setauditordetail');
                }
                return $this->error('未知的试卷');
            }
        }
        return $this->error('请先登录','index/index/index');
    }
    public function add($id,$auditorlist){
        $testpaper=new \app\api\controller\Testpaper();
        $testpaper->setauditor($id,$auditorlist);
        return json(['status'=>1]);
    }
}