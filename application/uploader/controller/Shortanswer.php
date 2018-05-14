<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Shortanswer extends Controller{
    public function index($belong,$belongid){
        if(isset($_COOKIE['userid'])){
            $testpaper=new \app\api\controller\Testpaper();
            $data=$testpaper->getTitle($belong,$belongid);
            if($data){
                setcookie("belong",$belong);
                setcookie("belongid",$belongid);
                $this->assign('data',$data);
                return $this->fetch('Shortanswer');
            }
        }
        return $this->error('请先登录');
    }
}