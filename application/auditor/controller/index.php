<?php
namespace app\auditor\controller;
use think\Controller;
use \app\api\model\User as UserModel;
/**
 * 审核员主页
 */
class Index extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $data=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                if($data){
                    $testpaper=new \app\api\controller\Testpaper();
                    $this->assign("user",$data);
                    $this->assign('working',$testpaper->getwaitingtestpapernumberforauditor($data->ID));
                    $this->assign('alert',$testpaper->getalertwaitngtestpapernumberforauditor($data->ID));
                    return $this->fetch();
                }
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}