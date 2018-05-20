<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Reloadselect extends Controller{
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $select=new \app\api\controller\Select();
                $data=$select->getreloaddata($id);
                if($data){
                    $this->assign('data',$data);
                    setcookie('reloadselectid',$id);
                    return $this->fetch('reloadselect');
                }
            }
        }
        return $this->error('请先登录');
    }
    public function getoption($id){
        $select=new \app\api\controller\Select();
        $data=$select->getoptiondata($id);
        if($data){
            return json(['status'=>1,'data'=>$data]);
        }else{
            return json(['status'=>1]);
        }
    }
    public function edit($name,$answerlist,$score,$id){
        $select=new \app\api\controller\Select();
        $select->edit($name,$answerlist,$score,$id);
        return json(['status'=>1]);
    }
}