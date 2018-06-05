<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
/**
 * 编辑选择题页
 */
class Reloadfill extends Controller{
    /**
     * 获取视图
     */
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $select=new \app\api\controller\Fill();
                $data=$select->getreloaddata($id);
                if($data){
                    $userdata=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                    $this->assign("user",$userdata);
                    $this->assign('data',$data);
                    setcookie('reloadfillid',$id);
                    return $this->fetch('reloadfill');
                }
            }
        }
        return $this->error('请先登录');
    }
    /**
     * 编辑方法
     */
    public function edit($name,$answer,$score,$id){
        $select=new \app\api\controller\Fill();
        $select->edit($name,$answer,$score,$id);
        return json(['status'=>1]);
    }
}