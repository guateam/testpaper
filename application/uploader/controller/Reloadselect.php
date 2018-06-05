<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
/**
 * 编辑选择题页
 */
class Reloadselect extends Controller{
    /**
     * 获取视图
     */
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $select=new \app\api\controller\Select();
                $data=$select->getreloaddata($id);
                if($data){
                    $userdata=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                    $this->assign("user",$userdata);
                    $this->assign('data',$data);
                    setcookie('reloadselectid',$id);
                    return $this->fetch('reloadselect');
                }
            }
        }
        return $this->error('请先登录');
    }
    /**
     * 获取选项信息
     */
    public function getoption($id){
        $select=new \app\api\controller\Select();
        $data=$select->getoptiondata($id);
        if($data){
            return json(['status'=>1,'data'=>$data]);
        }else{
            return json(['status'=>1]);
        }
    }
    /**
     * 编辑方法
     */
    public function edit($name,$answerlist,$score,$id){
        $select=new \app\api\controller\Select();
        $select->edit($name,$answerlist,$score,$id);
        return json(['status'=>1]);
    }
}