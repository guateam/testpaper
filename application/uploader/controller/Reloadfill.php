<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Reloadfill extends Controller{
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $select=new \app\api\controller\Fill();
                $data=$select->getreloaddata($id);
                if($data){
                    $this->assign('data',$data);
                    setcookie('reloadfillid',$id);
                    return $this->fetch('reloadfill');
                }
            }
        }
        return $this->error('请先登录');
    }
    public function edit($name,$answer,$score,$id){
        $select=new \app\api\controller\Fill();
        $select->edit($name,$answer,$score,$id);
        return json(['status'=>1]);
    }
}