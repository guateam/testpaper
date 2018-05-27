<?php
namespace app\admin\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Setauditor extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $testpaper=new \app\api\controller\Testpaper();
                $data=$testpaper->getwaitingauditordata();
                $this->assign("data",$data);
                $this->assign('empty','<h1 class="text-center success">没有等待分配人员的试卷<h1>');
                return $this->fetch('setauditor');
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}