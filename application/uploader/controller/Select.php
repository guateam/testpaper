<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
/**
 * 选择题录入页
 */
class Select extends Controller{
    public function index($belong,$belongid){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $testpaper=new \app\api\controller\Testpaper();
                $data=$testpaper->getTitle($belong,$belongid);
                if($data){
                    $userdata=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                    $this->assign("user",$userdata);
                    setcookie("belong",$belong);
                    setcookie("belongid",$belongid);
                    $this->assign('id',$belong);
                    $this->assign('data',$data);
                    return $this->fetch("select");
                }
                return $this->error('未知大题');
            }
        }
        return $this->error('请先登录','index/index/index');
    }
    public function getprogress($belong,$belongid){
        $select=new \app\api\controller\Select();
        return json(\array_merge(['status'=>1], $select->getprogress($belong,$belongid)));
    }
    public function add($belong,$belongid,$name,$answerlist,$score){
        $select=new \app\api\controller\Select();
        $select->add($belong,$belongid,$name,$answerlist,$score);
        return json(['status'=>1]);
    }
}