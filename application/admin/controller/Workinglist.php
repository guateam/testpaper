<?php
namespace app\admin\controller;
use think\Controller;
use \app\api\model\User as UserModel;
/**
 * 工作情况页面
 */
class Workinglist extends Controller{
    /**
     * 全局工作情况页面
     */
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
    /**
     * 获取全局历史信息柱状图
     */
    public function getlinedata(){
        $timetable=new \app\api\controller\Timetable();
        $data=$timetable->getlinedata();
        return json(['status'=>1,'data'=>$data]);
    }
    /**
     * 获取全局饼状图
     */
    public function getpandata(){
        $testpaper=new \app\api\controller\Testpaper();
        $data=$testpaper->getpandata();
        return json(['status'=>1,'data'=>$data]);
    }
    /**
     * 获取单个用户饼状图
     */
    public function getpandataforuser($id){
        $testpaper=new \app\api\controller\Testpaper();
        $data=$testpaper->getpandataforuser($id);
        return json(['status'=>1,'data'=>$data]);
    }
    /**
     * 获取单个用户柱状图
     */
    public function getlinedataforuser($id){
        $testpaper=new \app\api\controller\Testpaper();
        $data=$testpaper->getlinedataforuser($id);
        return json(['status'=>1,'data'=>$data]);
    }
    /**
     * 单个用户工作情况页面
     */
    public function user($id){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $testpaper=new \app\api\controller\Testpaper();
                $data=$testpaper->getworkinglist($id);
                $this->assign('name',$user->getname($id));
                $this->assign('data',$data);
                setcookie('id',$id);
                return $this->fetch('workinglistforuser');
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}