<?php
namespace app\auditor\controller;
use think\Controller;
use think\Db;
use \app\api\model\User as UserModel;
/**
 * 历史记录详情页
 */
class Historypaperdetail extends Controller{
    /**
     * 新的历史记录登录
     */
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $testpaper=new \app\api\controller\Testpaper();
                $userdata=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                $data=$testpaper->gettestpaper($id);
                if($data){
                    $this->assign("user",$userdata);
                    $this->assign('data',$data);
                    return $this->fetch('nindex');
                }
                return $this->error('未知的试卷');
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}