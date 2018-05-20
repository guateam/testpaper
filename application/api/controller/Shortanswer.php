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
                'Answer'=>$ans,
                'Belong'=>$belong,
                'BelongTitle'=>$belongid,
                'Score'=>(int)$score,
                'Children'=>$child
            ]);
            if($shortans->save())return 1;
            else return 0;
            
        }
        public function count($belong,$belongid){
            $list=\app\api\model\Shortanswer::all(['Belong'=>$belong,"belongTitle"=>$belongid]);
            return count($list);
        }
        public function update($id,$name,$answer,$score)
        {
            $shortans=\app\api\model\Shortanswer::get(['ID'=>$id]);
            $shortans->Name = $name;
            $shortans->Answer = $answer;
            $shortans->Score = $score;
            $shortans->save();
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
                    'Belong'=>0,
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
        public function getDatabyid($id)
        {
            $item=\app\api\model\Shortanswer::get(['ID'=>$id]);
            if($item)
            {
                $item->Name = str_replace('<br>',"\n",$item->Name);
                return $item;
            }
            else return null;
        }
        public function getchildstring($id){
            $item=\app\api\model\Shortanswer::get(['ID'=>$id]);
            if($item){
                return $item->Children;
            }
            else return null;
        }
        public function getdata($id,$belongid){
            $list=\app\api\model\Shortanswer::all(['Belong'=>$id,'BelongTitle'=>$belongid]);
            $data=[];
            foreach($list as $value){
                $item = [];
                if($value->Children !=0)
                {
                    //保存子题目的数组信息
                    $childDataList = [];
                    //保存子题目的id
                    $childid = explode(",",$value->Children);
                    foreach($childid as $id)
                    {
                        //保存子题目对象
                        $child = self::getDatabyid($id);
                        //赋值
                        if($child)
                        {
                            $childData = [
                            "name"=>str_replace('<br>',"\n",$child->Name),
                            "answer"=>$child->Answer,
                            "score"=>$child->Score,
                            "child"=>null,
                            'id'=>$value->ID
                            ];
                            //添加到子题目数组信息
                            array_push($childDataList,$childData);
                        }
                    }
                    $item = [
                        "name"=>str_replace('<br>',"\n",$value->Name),
                        "answer"=>$value->Answer,
                        "score"=>$value->Score,
                        "child"=>$childDataList,
                        'id'=>$value->ID
                    ];
                }
                else{
                    $item = [
                        "name"=>str_replace('<br>',"\n",$value->Name),
                        "answer"=>$value->Answer,
                        "score"=>$value->Score,
                        "child"=>null,
                        'id'=>$value->ID
                    ];
                }
                array_push($data,$item);
            }
            return $data;
        }
    }