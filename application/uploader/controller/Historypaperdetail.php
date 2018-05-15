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
    public function index($id){
        //试卷信息
        $paper = PaperModel::All(["ID"=>$id]);
        $paper[0]["HeadQuestion"] = json_decode($paper[0]["HeadQuestion"],true);
        //选择题
        $select = SelectModel::All(["Belong"=>$id]);
        //将选项分成数组
        for($i = 0;$i<count($select);$i++)
        {
            $select[$i]['Option'] = explode("  ",$select[$i]['Option']);
        }
        //填空题
        $fill = FillModel::All(["Belong"=>$id]);
        //对空位的处理，暂时不知道怎么写
        for($i = 0;$i<count($fill);$i++){
        }

        //简答题
        $answer =AnswerModel::All(["Belong"=>$id]);

        $this->assign("paper",$paper);
        $this->assign("select",$select);
        $this->assign("fill",$fill);
        $this->assign("answer",$answer);
        return $this->fetch("historypaperdetail");
    }
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