<?php
namespace app\uploader\controller;
use think\Controller;
class Newtestpaper extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                return $this->fetch('newtestpaper');
            }
        }
        return $this->error('请先登录','index/index/index');
    }
    public function add($name,$class,$subject,$school,$uploader,$headquestion){
        $user=new \app\api\controller\User();
        $uploaderid=$user->checkuser($uploader);
        if($uploaderid){
            $testpaper=new \app\api\controller\Testpaper();
            $paperlist1 = \app\api\model\Testpaper::all(['Name'=>$name]);
            $paperlist2 = \app\api\model\Testpaper::all(['Class'=>$class]);
            $paperlist3 = \app\api\model\Testpaper::all(['Subject'=>$subject]);
            $paperlist4 = \app\api\model\Testpaper::all(['School'=>$school]);
            if($paperlist1){
                return json(['status'=>-1]);
            }
            $id=$testpaper->add($name,$class,$subject,$school,$uploaderid,$headquestion);
            return json(['status'=>1,'id'=>$id]);
        }
        return json(['status'=>0]);
    }
}