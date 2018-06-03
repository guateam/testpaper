<?php
namespace app\admin\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Workinglist extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $data=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                if($data){
                    $testpaper=new \app\api\controller\Testpaper();
                    $working=$testpaper->getwaitingtestpapernumberforadmin();
                    $this->assign('working',$working);
                    $this->assign("user",$data);
                    return $this->fetch();
                }
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}