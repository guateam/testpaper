<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Notpassyet extends Controller{
    public function index(){
        return $this->fetch("notpassyet");
    }
}