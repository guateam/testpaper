<?php
namespace app\uploader\controller;
use think\Controller;
use think\Db;
use \app\api\model\User as UserModel;
use \app\api\model\Testpaper as PaperModel;
use \app\api\model\Select as SelectModel;
use \app\api\model\Fill as FillModel;
use \app\api\model\Shortanswer as AnswerModel;
class Historypaperdetail extends Controller{
    /**
     * 新的历史记录登录
     */
    public function nindex($id){
        if(isset($_COOKIE['userid'])){
            $testpaper=new \app\api\controller\Testpaper();
            $data=$testpaper->gettestpaper($id);
            if($data){
                $this->assign('data',$data);
                return $this->fetch('nindex');
            }
            return $this->error('未知的试卷');
        }
        return $this->error('请先登录');
    }
}