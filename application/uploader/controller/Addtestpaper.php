<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Addtestpaper extends Controller{
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $testpaper=new \app\api\controller\Testpaper();
            $data=$testpaper->getheadquestion($id);
            if($data){
                $this->assign('id',$id);
                $this->assign('data',$data);
                return $this->fetch('addtestpaper');
            }
            return $this->error('未知的试卷');
        }
        return $this->error('请先登录');
    }
    public function commit($id){
        $testpaper=new \app\api\controller\Testpaper();
        $testpaper->commit($id);
        return json(['status'=>1]);
    }
}