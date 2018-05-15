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
                'Children'=>$child
            ]);
            if($shortans->save())return 1;
            else return 0;
            
        }
        public function addchild($smallData,$belong,$belongid)
        {
            $childid = [];
            for($i = 0;$i<count($smallData);$i++)
            {
                $shortans=new \app\api\model\Shortanswer();
                $shortans->data([
                    'Name'=>$smallData[$i]["name"],
                    'Answer'=>$smallData[$i]["answer"],
                    'Belong'=>$belong,
                    'BelongTitle'=>$belongid,
                    'Score'=>(int)$smallData[$i]["score"],
                    'Children'=>0
                ]);
                $shortans->save();
                $newdata = \app\api\model\Shortanswer::get(['Name'=>$smallData[$i]["name"]]);
                array_push($childid,$newdata["ID"]);
            }
            return $childid;
        }
        public function getdata($id,$belongid){
            return [];
        }
    }