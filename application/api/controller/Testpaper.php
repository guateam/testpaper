<?php 
    namespace app\api\controller;
    use think\Controller;
    /**
     * 试卷控制类
     * 
     */
    class Testpaper extends Controller{
        public function add($name,$class,$subject,$school,$uploader,$headquestion){
            $testpaper=new \app\api\model\Testpaper();
            $testpaper->data([
                "Name"=>$name,
                "Uploader"=>(int)$uploader,
                "Class"=>$class,
                "Subject"=>$subject,
                'School'=>$school,
                'HeadQuestion'=>json_encode($headquestion)
            ]);
            $testpaper->save();
            return $testpaper->ID;
        }
        public function getheadquestion($id){
            $testpaper=\app\api\model\Testpaper::get(['ID'=>$id]);
            if($testpaper){
                $data=[];
                $list=\json_decode($testpaper->HeadQuestion);
                foreach($list as $key=>$value){
                    $active=$this->getQuestionValue($id,$key+1);
                    switch($value->type){
                        case '选择题':
                            $link='select';
                            break;
                        case '填空题':
                            $link='fill';
                            break;
                        case '简答题':
                            $link='shortanswer';
                            $break;
                        default:
                            $link='shortanswer';
                    }
                    $item=[
                        'name'=>$value->name,
                        'type'=>$value->type,
                        'number'=>$value->number,
                        'active'=>$active,
                        'progress'=>"style='width:".round($active/$value->number*100)."%;'",
                        'link'=>$link
                    ];
                    \array_push($data,$item);
                }
                return $data;
            }
        }
        public function getQuestionValue($id,$titleid){
            $data1=\app\api\model\Select::all(['Belong'=>$id,'BelongTitle'=>$titleid]);
            $data2=\app\api\model\Fill::all(['Belong'=>$id,'BelongTitle'=>$titleid]);
            $data3=\app\api\model\Shortanswer::all(['Belong'=>$id,'BelongTitle'=>$titleid]);
            return count(array_merge($data1,$data2,$data3));
        }
        public function getTitle($id,$belongid){
            $testpaper=\app\api\model\Testpaper::get(['ID'=>$id]);
            if($testpaper){
                $list=\json_decode($testpaper->HeadQuestion);
                return [
                    'name'=>$list[$belongid-1]->name,
                    'number'=>$list[$belongid-1]->number
                ];
            }
        }
        public function getworkingtestpaper($userid){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$userid]);
            $data=[];
            foreach ($list as $value) {
                $item=[
                    "id"=>$value->ID,
                    "name"=>$value->Name,
                    'class'=>$value->Class,
                    'subject'=>$value->Subject,
                    'school'=>$value->School
                ];
                array_push($data,$item);
            }
            return $data;
        }
        public function gettestpaper($id){
            $testpaper=\app\api\model\Testpaper::get(['ID'=>$id]);
            if($testpaper){
                $list=json_decode($testpaper->HeadQuestion,true);
                $data=[];
                $select=new \app\api\controller\Select();
                $fill=new \app\api\controller\Fill();
                $shortanswer=new \app\api\controller\Shortanswer();
                foreach($list as $value){
                    switch($value['type']){
                        case '选择题':
                            $child=$select->getdata($id,$value['ID']);
                            break;
                        case '填空题':
                            $child=$fill->getdata($id,$value['ID']);
                            break;
                        case '简答题':
                            $child=$shortanswer->getdata($id,$value['ID']);
                            break;
                        default:
                            $child=[];
                    }
                    $item=[
                        'name'=>$value['name'],
                        'type'=>$value['type'],
                        'children'=>$child,
                    ];
                    array_push($data,$item);
                }
                return [
                    'name'=>$testpaper->Name,
                    'class'=>$testpaper->Class,
                    'subject'=>$testpaper->Subject,
                    'school'=>$testpaper->School,
                    'children'=>$data
                ];
            }
        }

        public function getwaitingpaper($upid){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$upid,"State"=>1]);
            $data = [];
            foreach ($list as $value) {
                $item=[
                    "id"=>$value->ID,
                    "name"=>$value->Name,
                    'class'=>$value->Class,
                    'subject'=>$value->Subject,
                    'school'=>$value->School
                ];
                array_push($data,$item);
            }
            return $data;
        }
        public function getbackpaper($upid){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$upid,"State"=>2]);
            $data = [];
            foreach ($list as $value) {
                $item=[
                    "id"=>$value->ID,
                    "name"=>$value->Name,
                    'class'=>$value->Class,
                    'subject'=>$value->Subject,
                    'school'=>$value->School
                ];
                array_push($data,$item);
            }
            return $data;
        }

        public function getpasspaper($upid){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$upid,"State"=>3]);
            $data = [];
            foreach ($list as $value) {
                $item=[
                    "id"=>$value->ID,
                    "name"=>$value->Name,
                    'class'=>$value->Class,
                    'subject'=>$value->Subject,
                    'school'=>$value->School
                ];
                array_push($data,$item);
            }
            return $data;
        }
    }