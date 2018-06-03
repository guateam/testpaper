<?php
namespace app\admin\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Workinglist extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $testpaper=new \app\api\controller\Testpaper();
                $data=$testpaper->getallworkinglist();
                    $this->assign('data',$data);
                    return $this->fetch('workinglist');
            }
        }
        return $this->error('请先登录','index/index/index');
    }
    public function getlinedata(){
        $timetable=new \app\api\controller\Timetable();
        $data=$timetable->getlinedata();
        return json(['status'=>1,'data'=>$data]);
    }
    public function getpandata(){
        $testpaper=new \app\api\controller\Testpaper();
        $data=$testpaper->getpandata();
        return json(['status'=>1,'data'=>$data]);
    }
}