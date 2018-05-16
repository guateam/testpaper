<?php
namespace app\uploader\controller;
use think\Controller;
class Overview extends Controller{
    /**
     * 新的历史记录登录
     */
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $testpaper=new \app\api\controller\Testpaper();
            $data=$testpaper->gettestpaper($id);
            if($data){
                $this->assign('id',$id);
                $this->assign('data',$data);
                return $this->fetch('overview');
            }
            return $this->error('未知的试卷');
        }
        return $this->error('请先登录');
    }
}