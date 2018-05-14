<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Shortanswer extends Controller{
    public function index($belong,$belongid){
        if(isset($_COOKIE['userid'])){
            setcookie("belong",$belong);
            setcookie("belongid",$belongid);
            return $this->fetch('Shortanswer');
        }
        return $this->error('请先登录');
    }
}