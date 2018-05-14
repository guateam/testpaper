<?php 
    namespace app\api\controller;
    use think\Controller;
    use \app\api\model\User as UserModel;
    /**
     * 填空题控制类
     * 
     */
    class Fill extends Controller{
        public function getprogress($belong,$belongid){
            $list=\app\api\model\Fill::all(['Belong'=>$belong,'BelongTitle'=>$belongid]);
            $testpaper=\app\api\model\Testpaper::get(['ID'=>$belong]);
            $list1=\json_decode($testpaper->HeadQuestion);
            $number=$list1[$belongid-1]->number;
            return ['progress'=>round(((count($list)+1)/$number)*100),'now'=>count($list)+1];
        }
        public function add($belong,$belongid,$name,$answerlist,$score){
            $select=new \app\api\model\Fill();
            $select->data([
                'Name'=>json_encode($name),
                'Answer'=>json_encode($answerlist),
                'Belong'=>$belong,
                'BelongTitle'=>$belongid,
                'Score'=>(int)$score
            ]);
            $select->save();
        }
    }