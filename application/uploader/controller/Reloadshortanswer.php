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
                $this->assign("childrennumber",count($childrendata));
                $this->assign('childrendata',$childrendata);
                return $this->fetch('reloadshortanswer');
            }
        }
        return $this->error('请先登录');
    }
}