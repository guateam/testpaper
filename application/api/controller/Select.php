<?php 
    namespace app\api\controller;
    use think\Controller;
    use \app\api\model\User as UserModel;
    /**
     * 选择题控制类
     * 
     */
    class Select extends Controller{
        /**
         * 获取进度条
         * 获取当前大题录入进度
         */
        public function getprogress($belong,$belongid){
            $list=\app\api\model\Select::all(['Belong'=>$belong,'BelongTitle'=>$belongid]);
            $testpaper=\app\api\model\Testpaper::get(['ID'=>$belong]);
            $list1=\json_decode($testpaper->HeadQuestion);
            $number=$list1[$belongid-1]->number;
            return ['progress'=>round(((count($list)+1)/$number)*100),'now'=>count($list)+1];
        }
        /**
         * 添加填空题
         */
        public function add($belong,$belongid,$name,$answerlist,$score){
            $select=new \app\api\model\Select();
            $answer=[];
            $option=[];
            foreach ($answerlist as $key=>$value) {
                array_push($option,$value['answer']);
                if($value['type']=="true"){
                    array_push($answer,$key);
                }  
            }
            $select->data([
                'Name'=>$name,
                'Answer'=>json_encode($answer),
                'Option'=>json_encode($option),
                'Belong'=>$belong,
                'BelongTitle'=>$belongid,
                'Score'=>(int)$score
            ]);
            $select->save();
        }
        /**
         * 获取填空题数据
         */
        public function getdata($id,$belongid){
            $list=\app\api\model\Select::all(['Belong'=>$id,'BelongTitle'=>$belongid]);
            $data=[];
            foreach($list as $value){
                $answer=json_decode($value->Answer,true);
                $answerlist=[];
                foreach($answer as $v){
                    if($v<26){
                        array_push($answerlist,chr(ord('A')+$v));
                    }else{
                        array_push($answerlist,'第'.($v+1).'项');
                    }
                    
                }
                $item=[
                    'name'=>$value->Name,
                    'answer'=>$answerlist,
                    'option'=>\json_decode($value->Option,true),
                    'score'=>$value->Score,
                    'id'=>$value->ID
                ];
                array_push($data,$item);
            }
            return $data;
        }
        /**
         * 获取填空题修改时所需题干信息与分数信息
         */
        public function getreloaddata($id){
            $select=\app\api\model\Select::get(['ID'=>$id]);
            if($select){
                $data=[
                    'name'=>$select->Name,
                    'score'=>$select->Score
                ];
                return $data;
            }
        }
        /**
         * 获取选择题修改时所需选项信息
         */
        public function getoptiondata($id){
            $select=\app\api\model\Select::get(['ID'=>$id]);
            if($select){
                $option=\json_decode($select->Option,true);
                $answer=\json_decode($select->Answer,true);
                $list=[];
                foreach($option as $key=>$value){
                    $item=[
                        "ID"=>$key,
                        'answer'=>$value,
                        'type'=>in_array($key,$option)
                    ];
                    array_push($list,$item);
                }
                return $list;
            }
        }
        /**
         * 根据参数修改选择题
         */
        public function edit($name,$answerlist,$score,$id){
            $select=\app\api\model\Select::get(['ID'=>$id]);
            $answer=[];
            $option=[];
            foreach ($answerlist as $key=>$value) {
                array_push($option,$value['answer']);
                if($value['type']=="true"){
                    array_push($answer,$key);
                }  
            }
            $select->data([
                'Name'=>$name,
                'Answer'=>json_encode($answer),
                'Option'=>json_encode($option),
                'Score'=>(int)$score
            ]);
            $select->save();
        }
    }