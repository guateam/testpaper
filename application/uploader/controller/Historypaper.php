<?php
namespace app\uploader\controller;
use think\Controller;
use think\Db;
use \app\api\model\User as UserModel;
use \app\api\model\Testpaper as PaperModel;
/**
 * 历史记录页
 */
class Historypaper extends Controller{
    /**
     * 获取视图
     */
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
            if($user){
                $paper=new \app\api\controller\Testpaper();
                $pass = $paper->getpasspaper($user->ID);
                $this->assign("user",$user);
                $this->assign("paper",$pass);
                return $this->fetch("historypaper");
            }
        }
        return $this->error('请先登录','index/index/index');
    }
    /**
     * 添加金钱
     * 弃用
     */
    public function addmoney($id,$amount){
                $user_c =new \app\api\controller\User();
                $user_c->addmoney($id,$amount);
                return 1;
    }
}