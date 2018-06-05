<?php 
    namespace app\api\controller;
    use think\Controller;
    use \app\api\model\User as UserModel;
    /**
     * 填空题控制类
     * 
     */
    class Fill extends Controller{
        /**
         * 获取进度条
         * 获取当前大题录入进度
         */
        public function getprogress($belong,$belongid){
            $list=\app\api\model\Fill::all(['Belong'=>$belong,'BelongTitle'=>$belongid]);
            $testpaper=\app\api\model\Testpaper::get(['ID'=>$belong]);
            $list1=\json_decode($testpaper->HeadQuestion);
            $number=$list1[$belongid-1]->number;
            return ['progress'=>round(((count($list)+1)/$number)*100),'now'=>count($list)+1];
        }
        /**
         * 添加填空题
         */
        public function add($belong,$belongid,$name,$answerlist,$score){
            $fill=new \app\api\model\Fill();
            $fill->data([
                'Name'=>json_encode($name),
                'Answer'=>json_encode($answerlist),
                'Belong'=>$belong,
                'BelongTitle'=>$belongid,
                'Score'=>(int)$score
            ]);
            $fill->save();
        }
        /**
         * 获取填空题
         */
        public function getdata($id,$belongid){
            $list=\app\api\model\Fill::all(['Belong'=>$id,'BelongTitle'=>$belongid]);
            $data=[];
            foreach($list as $value){
                $item=[
                    'name'=>\json_decode($value->Name,true),
                    'answer'=>\json_decode($value->Answer,true),
                    'score'=>$value->Score,
                    'end'=>count(json_decode($value->Name,true)),
                    'id'=>$value->ID
                ];
                array_push($data,$item);
            }
            return $data;
        }
        /**
         * 获取修改填空题时需要的信息
         */
        public function getreloaddata($id){
            $fill=\app\api\model\Fill::get(['ID'=>$id]);
            if($fill){
                $option=json_decode($fill->Name,true);
                $answer=json_decode($fill->Answer,true);
                $k=0;
                $i=0;
                $name='';
                while($k<count($answer)){
                    $name.=$option[$i].'__'.$answer[$k].'__';
                    $i++;
                    $k++;
                }
                if($i<count($option)){
                    $name.=$option[$i];
                }
                $data=[
                    'name'=>$name,
                    'score'=>$fill->Score
                ];
                return $data;
            }
        }
        /**
         * 编辑填空题
         * 根据传入参数更新填空题
         */
        public function edit($name,$answerlist,$score,$id){
            $fill=\app\api\model\Fill::get(['ID'=>$id]);
            if($fill){
                $fill->data([
                    'Name'=>json_encode($name),
                    'Answer'=>json_encode($answerlist),
                    'Score'=>(int)$score
                ]);
                $fill->save();
            }
        }
    }