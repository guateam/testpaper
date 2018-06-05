<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
/**
 * 历史记录详情页面控制器
 */
class Historypaperdetail extends Controller{
    /**
     * 历史记录
     */
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $testpaper=new \app\api\controller\Testpaper();
                $data=$testpaper->gettestpaper($id);
                if($data){
                    $this->assign('data',$data);
                    return $this->fetch('nindex');
                }
                return $this->error('未知的试卷');
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}