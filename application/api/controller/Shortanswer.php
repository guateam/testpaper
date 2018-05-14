<?php 
    namespace app\api\controller;
    use think\Controller;
    /**
     * 试卷控制类
     * 
     */
    class Shortanswer extends Controller{
        public function add($belong,$belongid,$name,$ans,$score,$child){
            $shortans=new \app\api\model\Shortanswer();
            $answer=[];
            $option=[];
            $shortans->data([
                'Name'=>$name,
                'Answer'=>json_encode($ans),
                'Belong'=>$belong,
                'BelongTitle'=>$belongid,
                'Score'=>(int)$score,
                'child'=>$child
            ]);
            $select->save();
        }
    }