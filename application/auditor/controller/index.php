<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Index extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $data=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
            if($data){
                $this->assign("user",$data);
                return $this->fetch();
            }
        }
        return $this->error('请先登录');
    }
}