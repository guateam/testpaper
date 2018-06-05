<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
/**
 * 录入人主页
 */
class Index extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $data=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
            if($data){
                $testpaper=new \app\api\controller\Testpaper();
                $working=$testpaper->getworkingtestpapernumber($data->ID);
                $waiting=$testpaper->getwaitingtestpapernumber($data->ID);
                $error=$testpaper->geterrortestpapernumber($data->ID);
                $this->assign('working',$working);
                $this->assign('waiting',$waiting);
                $this->assign('error',$error);
                $this->assign("user",$data);
                return $this->fetch();
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}