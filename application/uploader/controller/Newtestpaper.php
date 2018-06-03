<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Newtestpaper extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $userdata=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                $this->assign("user",$userdata);
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
            if(!self::check_paper_reupload($name,$class,$subject,$school)){
                return json(['status'=>-1]);
            }
            $id=$testpaper->add($name,$class,$subject,$school,$uploaderid,$headquestion,$score);
            return json(['status'=>1,'id'=>$id]);
        }
        return json(['status'=>0]);
    }

    //检测试卷是否重复
    public function check_paper_reupload($name,$class,$subject,$school){
        $paper= \app\api\model\Testpaper::get(['Name'=>$name]);
        if($paper){
            if($paper->Class==$class && $paper->Subject==$subject && $paper->School==$school){
                return 0;
            }
        }
        return 1;
    }
}