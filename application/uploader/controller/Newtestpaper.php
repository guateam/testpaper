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
    public function add($name,$class,$subject,$school,$uploader,$headquestion,$score){
        $user=new \app\api\controller\User();
        $uploaderid=$user->checkuser($uploader);
        if($uploaderid){
            $testpaper=new \app\api\controller\Testpaper();
            if(!check_paper_reupload($name,$class,$subject,$school)){
                return json(['status'=>-1]);
            }
            $id=$testpaper->add($name,$class,$subject,$school,$uploaderid,$headquestion,$score);
            return json(['status'=>1,'id'=>$id]);
        }
        return json(['status'=>0]);
    }

    //检测试卷是否重复
    public function check_paper_reupload($name,$class,$subject,$school){
        $paperlist1 = \app\api\model\Testpaper::get(['Name'=>$name]);
            $paperlist2 = \app\api\model\Testpaper::get(['Class'=>$class]);
            $paperlist3 = \app\api\model\Testpaper::get(['Subject'=>$subject]);
            $paperlist4 = \app\api\model\Testpaper::get(['School'=>$school]);
            if($paperlist1 || $paperlist2 || $paperlist3 || $paperlist4){
                return 0;
            }else return 1;
    }
}