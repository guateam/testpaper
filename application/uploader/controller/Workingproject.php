<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Workingproject extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $testpaper=new \app\api\controller\Testpaper();
            $user=new \app\api\controller\User();
            $userid=$user->checkuser($_COOKIE['userid']);
            if($userid){
                $data=$testpaper->getworkingtestpaper($userid);
                if($data){
                    $this->assign('data',$data);
                    return $this->fetch('workingproject');
                }
                return $this->error('未找到试卷');
            }
        }
        return $this->error('请先登录');
    }
}