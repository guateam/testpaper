<?php
namespace app\index\controller;
use think\Controller;
/**
 * 注册页面
 */
class Register extends Controller{
    public function register(){
        return $this->fetch();
    }
}