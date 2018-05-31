<?php
namespace app\uploader\controller;
use think\Controller;
use \app\api\model\User as UserModel;
class Reloadshortanswer extends Controller{
    public function index($id){
        if(isset($_COOKIE['userid'])){
            $question=new \app\api\controller\Shortanswer();
            $data = $question->getDatabyid($id);
            $childrendata = [];
            if($data){
                $this->assign('data',$data);
                $userdata=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                $this->assign("user",$userdata);
                if($data->Children != 0)
                {
                    $childrenlist = explode(",",$data->Children);
                    foreach($childrenlist as $childid)
                    {
                        $childid = intval($childid);
                        $child = $question->getDatabyid($childid);
                        array_push($childrendata,$child);
                    }
                }
                setcookie("childrennumber",count($childrendata));
                $this->assign("childrennumber",count($childrendata));
                $this->assign('childrendata',$childrendata);
                return $this->fetch('reloadshortanswer');
            }
        }
        return $this->error('请先登录');
    }
}