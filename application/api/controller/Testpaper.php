<?php 
    namespace app\api\controller;
    use think\Controller;
    /**
     * 试卷控制类
     * 
     */
    class Testpaper extends Controller{
        /**
         * 新建试卷
         */
        public function add($name,$class,$subject,$school,$uploader,$headquestion,$score){
            $testpaper=new \app\api\model\Testpaper();

            $price = new  \app\api\controller\Defaultprice();
            $defaultprice = $price->getdefaultprice();
            $testpaper->data([
                "Name"=>$name,
                "Uploader"=>(int)$uploader,
                "Class"=>$class,
                "Subject"=>$subject,
                'School'=>$school,
                'HeadQuestion'=>json_encode($headquestion),
                'State'=>0,
                'Score'=>$score,
                'Price'=>$defaultprice['uploaderprice'],
                'Auditorprice'=>$defaultprice['auditorprice']
            ]);
            $testpaper->save();
            \app\api\controller\Timetable::add('Newpaper');
            return $testpaper->ID;
        }
        /**
         * 获取大题目录
         */
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
                            break;
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
        /**
         * 获取大题下所有数据
         */
        public function getQuestionValue($id,$titleid){
            $data1=\app\api\model\Select::all(['Belong'=>$id,'BelongTitle'=>$titleid]);
            $data2=\app\api\model\Fill::all(['Belong'=>$id,'BelongTitle'=>$titleid]);
            $data3=\app\api\model\Shortanswer::all(['Belong'=>$id,'BelongTitle'=>$titleid]);
            return count(array_merge($data1,$data2,$data3));
        }
        /**
         * 获取特定大题头信息
         */
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
        /**
         * 获取正在录入的试卷
         */
        public function getworkingtestpaper($userid){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$userid,'State'=>0]);
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
        /**
         * 获取等待该审核人审核的试卷
         */
        public function getwaitingtestpaper($userid){
            $list=\think\Db::query("select * from testpaper where Auditorlist like '%,".$userid.",%'");
            $data=[];
            foreach($list as $value){
                if($value['State']==1){
                    $log=\app\api\model\Log::get(['Name'=>'催单1','Testpaper'=>$value['ID'],'State'=>0]);
                    $alert=0;
                    if($log){
                        $alert=1;
                    }
                    $item=[
                        "id"=>$value['ID'],
                        "name"=>$value['Name'],
                        'class'=>$value['Class'],
                        'subject'=>$value['Subject'],
                        'school'=>$value['School'],
                        'time'=>$this->gettimebefore($value['Uploaddate']),
                        'alert'=>$alert
                    ];
                    array_push($data,$item);
                }
            }
            return $data;
        }
        /**
         * 获取等待该审核人审核的试卷数量
         */
        public function getwaitingtestpapernumberforauditor($userid){
            return count($this->getwaitingtestpaper($userid));
        }
        /**
         * 获取该审核员被催单的试卷数量
         */
        public function getalertwaitngtestpapernumberforauditor($userid){
            $list=\think\Db::query("select * from testpaper where Auditorlist like '%,".$userid.",%'");
            $alert=0;
            foreach($list as $value){
                if($value['State']==1){
                    $log=\app\api\model\Log::get(['Name'=>'催单1','Testpaper'=>$value['ID'],'State'=>0]);
                    if($log){
                        $alert++;
                    }
                }
            }
            return $alert;
        }
        /**
         * 获取等待管理员分配人员的试卷数量
         */
        public function getwaitingtestpapernumberforadmin(){
            return count($this->getwaitingauditordata());
        }
        /**
         * 获取管理员被催单的试卷数量
         */
        public function getalertwaitngtestpapernumberforadmin(){
            $list=\app\api\model\Testpaper::all(['State'=>1]);
            $alert=0;
            foreach($list as $value){
               
                    $log=\app\api\model\Log::get(['Name'=>'催单0','Testpaper'=>$value->ID,'State'=>0]);
                    if($log){
                        $alert++;
                    }
                
            }
            return $alert;
        }
        /**
         * 获取试卷预览数据
         */
        public function gettestpaper($id){
            $testpaper=\app\api\model\Testpaper::get(['ID'=>$id]);
            if($testpaper){
                $list=json_decode($testpaper->HeadQuestion,true);
                $data=[];
                $select=new \app\api\controller\Select();
                $fill=new \app\api\controller\Fill();
                $shortanswer=new \app\api\controller\Shortanswer();
                foreach($list as $key=>$value){
                    switch($value['type']){
                        case '选择题':
                            $child=$select->getdata($id,$key+1);
                            break;
                        case '填空题':
                            $child=$fill->getdata($id,$key+1);
                            break;
                        case '简答题':
                            $child=$shortanswer->getdata($id,$key+1);
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
                $user=new \app\api\controller\User();
                return [
                    'name'=>$testpaper->Name,
                    'class'=>$testpaper->Class,
                    'subject'=>$testpaper->Subject,
                    'school'=>$testpaper->School,
                    'children'=>$data,
                    'state'=>$testpaper->State,
                    'score'=>$testpaper->Score,
                    'uploader'=>$user->getname($testpaper->Uploader),
                    'auditor'=>$user->getname($testpaper->Auditor),
                    'unexpectscore'=>$this->getunexpectscore($id)
                ];
            }
        }
        /**
         * 录入完成
         */
        public function commit($id){
            $testpaper=\app\api\model\Testpaper::get(['ID'=>$id]);
            $testpaper->data([
                'Uploaddate'=>date('Y-m-d H:s'),
                'State'=>1
            ]);
            $testpaper->save();
            \app\api\controller\Timetable::add('Upload');
        }
        /**
         * 获取正在录入的试卷数量
         */
        public function getworkingtestpapernumber($userid){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$userid,'State'=>0]);
            return count($list);
        }
        /**
         * 获取等待审核的试卷数量
         */
        public function getwaitingtestpapernumber($userid){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$userid,'State'=>1]);
            return count($list);
        }
        /**
         * 获取被打回的试卷数量
         */
        public function geterrortestpapernumber($userid){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$userid,'State'=>3]);
            return count($list);
        }
        /**
         * 获取等待审核的试卷信息
         */
        public function getwaitingpaper($upid){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$upid,"State"=>1]);
            $data = [];
            foreach ($list as $value) {
                $log=\app\api\model\Log::get(['Testpaper'=>$value->ID,'Name'=>'催单0']);
                $log1=\app\api\model\Log::get(['Testpaper'=>$value->ID,'Name'=>'催单1']);
                $progress='';
                if($log1){
                    $state=1;
                }else if($log){
                    if($value->Auditorlist==''){
                        $state=1;
                    }else{
                        $state=0;
                    }
                }else{
                    $state=0;
                }
                if($value->Auditorlist==''){
                    $progress='等待分配人员';
                }else{
                    $progress='等待审核人审核';
                }
                $item=[
                    "id"=>$value->ID,
                    "name"=>$value->Name,
                    'class'=>$value->Class,
                    'subject'=>$value->Subject,
                    'school'=>$value->School,
                    'state'=>$state,
                    'progress'=>$progress
                ];
                array_push($data,$item);
            }
            return $data;
        }
        /**
         * 获取审核通过的试卷
         */
        public function getpasspaper($upid){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$upid,"State"=>2]);
            $data = [];
            foreach ($list as $value) {
                $item=[
                    "id"=>$value->ID,
                    "name"=>$value->Name,
                    'class'=>$value->Class,
                    'subject'=>$value->Subject,
                    'school'=>$value->School,
                    'isPay'=>$value->isPay,
                    'price'=>$value->Price
                ];
                array_push($data,$item);
            }
            return $data;
        }
        /**
         * 获取审核通过的试卷（审核人）
         */
        public function getpasspaperforauditor($auditorid){
            $list=\app\api\model\Testpaper::all(['Auditor'=>$auditorid,"State"=>2]);
            $data = [];
            foreach ($list as $value) {
                $item=[
                    "id"=>$value->ID,
                    "name"=>$value->Name,
                    'class'=>$value->Class,
                    'subject'=>$value->Subject,
                    'school'=>$value->School,
                    'isPay'=>$value->Auditorispay,
                    'price'=>$value->Auditorprice
                ];
                array_push($data,$item);
            }
            return $data;
        }
        /**
         * 获取审核通过的试卷（管理员）
         */
        public function getpasspaperforadmin(){
            $list=\app\api\model\Testpaper::all(["State"=>2]);
            $data = [];
            $user=new \app\api\controller\User();
            foreach ($list as $value) {
                $item=[
                    "id"=>$value->ID,
                    "name"=>$value->Name,
                    'class'=>$value->Class,
                    'subject'=>$value->Subject,
                    'school'=>$value->School,
                    'uploader'=>$user->getname($value->Uploader),
                    'auditor'=>$user->getname($value->Auditor),
                    'auditorispay'=>$value->Auditorispay,
                    'auditorprice'=>$value->Auditorprice,
                    'isPay'=>$value->isPay,
                    'price'=>$value->Price,
                    'upid'=>$value->Uploader,
                    'auid'=>$value->Auditor
                ];
                array_push($data,$item);
            }
            return $data;
        }
        /**
         * 获取被打回的试卷
         */
        public function getbackpaper($upid){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$upid,"State"=>3]);
            $data = [];
            foreach ($list as $value) {
                $item=[
                    "id"=>$value->ID,
                    "name"=>$value->Name,
                    'class'=>$value->Class,
                    'subject'=>$value->Subject,
                    'school'=>$value->School,
                    'note'=>$value->Note
                ];
                array_push($data,$item);
            }
            return $data;
        }
        /**
         * 审核通过
         */
        public function confirm($id,$auditorid){
            $testpaper=\app\api\model\Testpaper::get(['ID'=>$id]);
            $user=new \app\api\controller\User();
            $user->addnum($testpaper->Uploader);
            $user->addnum($auditorid);
            $testpaper->data([
                'Auditor'=>$auditorid,
                'Audittime'=>date('Y-m-d H:s'),
                'State'=>2
            ]);
            $testpaper->save();
            \app\api\controller\Timetable::add('Access');
            \app\api\controller\Log::set($id,1);
        }
        /**
         * 审核不通过
         */
        public function cancel($id,$auditorid,$note){
            $testpaper=\app\api\model\Testpaper::get(['ID'=>$id]);
            $testpaper->data([
                'Auditor'=>$auditorid,
                'Audittime'=>date('Y-m-d H:s'),
                'State'=>3,
                'Note'=>$note
            ]);
            $testpaper->save();
            \app\api\controller\Timetable::add('Back');
            \app\api\controller\Log::set($id,1);
        }
        /**
         * 获取等待分配人员的试卷
         */
        public function getwaitingauditordata(){
            $testpaper=\app\api\model\Testpaper::all(['Auditorlist'=>'','State'=>1]);
            $data=[];
            foreach($testpaper as $value){
                $log=\app\api\model\Log::get(['Name'=>'催单0','Testpaper'=>$value['ID'],'State'=>0]);
                $alert=0;
                if($log){
                    $alert=1;
                }
                $item=[
                    "id"=>$value->ID,
                    "name"=>$value->Name,
                    'class'=>$value->Class,
                    'subject'=>$value->Subject,
                    'school'=>$value->School,
                    'time'=>$this->gettimebefore($value->Uploaddate),
                    'alert'=>$alert
                ];
                array_push($data,$item);
            }
            return $data;
        }
        /**
         * 分配试卷的审核人
         */
        public function setauditor($id,$auditorlist){
            $testpaper=\app\api\model\Testpaper::get(["ID"=>$id]);
            if($testpaper){
                $testpaper->data([
                    'Auditorlist'=>$auditorlist
                ]);
                $testpaper->save();
                \app\api\controller\Timetable::add('Set');
                \app\api\controller\Log::set($id,0);
            }
        }
        /**
         * 判断试卷是否录入完全的函数
         * 0未知试卷，1完成录入，-1录入不完全，-2录入总分不一致
         */
        public function iscomplete($id){
            $testpaper=\app\api\model\Testpaper::get(["ID"=>$id]);
            if($testpaper){
                $list=json_decode($testpaper->HeadQuestion,true);
                $select=new \app\api\controller\Select();
                $fill=new \app\api\controller\Fill();
                $shortanswer=new \app\api\controller\Shortanswer();
                $score=0;
                foreach($list as $key=>$value){
                    switch($value['type']){
                        case '选择题':
                            $child=$select->getdata($id,$key+1);
                            break;
                        case '填空题':
                            $child=$fill->getdata($id,$key+1);
                            break;
                        case '简答题':
                            $child=$shortanswer->getdata($id,$key+1);
                            break;
                        default:
                            $child=[];
                    }
                    if(count($child)!=$value['number']){
                        return -1;
                    }
                    foreach($child as $value){
                        $score+=$value['score'];
                    }
                }
            }
            if($score==$testpaper->Score){
                return 1;
            }else{
                return -2;
            }
            return 0;
        }
        /**
         * 获取试卷录入分值与设定分值的偏差值
         */
        public function getunexpectscore($id){
            $testpaper=\app\api\model\Testpaper::get(["ID"=>$id]);
            if($testpaper){
                $list=json_decode($testpaper->HeadQuestion,true);
                $select=new \app\api\controller\Select();
                $fill=new \app\api\controller\Fill();
                $shortanswer=new \app\api\controller\Shortanswer();
                $score=0;
                foreach($list as $key=>$value){
                    switch($value['type']){
                        case '选择题':
                            $child=$select->getdata($id,$key+1);
                            break;
                        case '填空题':
                            $child=$fill->getdata($id,$key+1);
                            break;
                        case '简答题':
                            $child=$shortanswer->getdata($id,$key+1);
                            break;
                        default:
                            $child=[];
                    }
                    foreach($child as $value){
                        $score+=$value['score'];
                    }
                }
            }
            if($score==$testpaper->Score){
                return 0;
            }else{
                return $score-$testpaper->Score;
            }
            return 0;
        }
        /**
         * uploader确认收款
         */
        public function confirmpaying($uid,$id){
            $testpaper=\app\api\model\Testpaper::get(["ID"=>$id]);
            if($testpaper)
            {
                $testpaper->isPay = 1;
                $testpaper->save();
                $money = new \app\uploader\controller\Historypaper();
                $money->addmoney($uid,$testpaper->Price);
                return 1;
            }
            else return 0;
        }
        /**
         * auditor确认收款
         */
        public function auditorconfirmpaying($uid,$id){
            $testpaper=\app\api\model\Testpaper::get(["ID"=>$id]);
            if($testpaper)
            {
                $testpaper->Auditorispay = 1;
                $testpaper->save();
                $money = new \app\uploader\controller\Historypaper();
                $money->addmoney($testpaper->Auditorprice);
                return 1;
            }
            else return 0;
        }
        /**
         * 内部方法 获取上传时间距离现在过去几分钟
         * 已实装
         * 2018-3-3 张煜
         */
        private function gettimebefore($time){//获取距离现在时间
            $nowtime=date('Y-m-d H:i');
            $now = strtotime($nowtime);
            $old = strtotime($time);
            $durlingtime=round((($now-$old))/60);
            if($durlingtime>=60 && $durlingtime<1440){
                return round($durlingtime/60).'小时';
            }else if($durlingtime>=1440){
                return round($durlingtime/1440).'天';
            }else{
                return $durlingtime.'分钟';
            }  
        }
        /**
         * 获取所有正在运行的试卷
         */
        public function getallworkinglist(){
            $list=\app\api\model\Testpaper::all();
            $data=[];
            $user=new User();
            foreach($list as $value){
                if($value->State!=2){
                    $progress='';
                    $uploadtime='';
                    switch($value->State){
                        case 0:
                            $progress='正在录入';
                            $uploadtime='未完成';
                            break;
                        case 1:
                            if($value->Auditorlist==''){
                                $progress='正在等待人员分配';
                            }else{
                                $progress='正在等待审核';
                            }
                            $uploadtime=$this->gettimebefore($value->Uploaddate);
                            break;
                        case 3:
                            $progress='被打回';
                            $uploadtime=$this->gettimebefore($value->Uploaddate);
                            break;
                        default:
                    }
                    $item=[
                        "id"=>$value->ID,
                        "name"=>$value->Name,
                        'class'=>$value->Class,
                        'subject'=>$value->Subject,
                        'school'=>$value->School,
                        'uploader'=>$user->getname($value->Uploader),
                        'uploaddate'=>$value->Uploaddate,
                        'progress'=>$progress,
                        'uploadtime'=>$uploadtime
                    ];
                    array_push($data,$item);
                }
            }
            return $data;
        }
        /**
         * 获取正在运行的试卷比例数据
         */
        public function getpandata(){
            $working=count(\app\api\model\Testpaper::all(['State'=>0]));
            $waiting1=count(\app\api\model\Testpaper::all(['State'=>1,'Auditorlist'=>'']));
            $waiting2=count(\app\api\model\Testpaper::all(['State'=>1]))-count(\app\api\model\Testpaper::all(['State'=>1,'Auditorlist'=>'']));
            $back=count(\app\api\model\Testpaper::all(['State'=>3]));
            return [['value'=>$working,'name'=>'正在录入'],['value'=>$waiting1,'name'=>'等待分配'],['value'=>$waiting2,'name'=>'等待审核'],['value'=>$back,'name'=>'被打回'],];
        }
        /**
         * 获取该上传人的正在运行的试卷信息
         */
        public function getworkinglist($id){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$id]);
            $data=[];
            $user=new User();
            foreach($list as $value){
                if($value->State!=2){
                    $progress='';
                    $uploadtime='';
                    switch($value->State){
                        case 0:
                            $progress='正在录入';
                            $uploadtime='未完成';
                            break;
                        case 1:
                            if($value->Auditorlist==''){
                                $progress='正在等待人员分配';
                            }else{
                                $progress='正在等待审核';
                            }
                            $uploadtime=$this->gettimebefore($value->Uploaddate);
                            break;
                        case 3:
                            $progress='被打回';
                            $uploadtime=$this->gettimebefore($value->Uploaddate);
                            break;
                        default:
                    }
                    $item=[
                        "id"=>$value->ID,
                        "name"=>$value->Name,
                        'class'=>$value->Class,
                        'subject'=>$value->Subject,
                        'school'=>$value->School,
                        'uploader'=>$user->getname($value->Uploader),
                        'uploaddate'=>$value->Uploaddate,
                        'progress'=>$progress,
                        'uploadtime'=>$uploadtime
                    ];
                    array_push($data,$item);
                }
            }
            return $data;
        }
        /**
         * 获取该上传人正在运行的试卷比例数据
         */
        public function getpandataforuser($id){
            $working=count(\app\api\model\Testpaper::all(['State'=>0,'Uploader'=>$id]));
            $waiting1=count(\app\api\model\Testpaper::all(['State'=>1,'Auditorlist'=>'','Uploader'=>$id]));
            $waiting2=count(\app\api\model\Testpaper::all(['State'=>1,'Uploader'=>$id]))-count(\app\api\model\Testpaper::all(['State'=>1,'Auditorlist'=>'','Uploader'=>$id]));
            $back=count(\app\api\model\Testpaper::all(['State'=>3,'Uploader'=>$id]));
            return [['value'=>$working,'name'=>'正在录入'],['value'=>$waiting1,'name'=>'等待分配'],['value'=>$waiting2,'name'=>'等待审核'],['value'=>$back,'name'=>'被打回'],];
        }
        /**
         * 获取该上传人的历史上传效率柱状图
         */
        public function getlinedataforuser($id){
            $list=\app\api\model\Testpaper::all(['Uploader'=>$id]);
            $x=[];
            $upload=[];
            $access=[];
            foreach($list as $value){
                if($value->Audittime!=''){
                    $auditortime=\explode(' ',$value->Audittime)[0];
                    if(in_array($auditortime,$x)){
                        $key=array_keys($x,$auditortime)[0];
                        $access[$key]++;
                    }else{
                        array_push($x,$auditortime);
                        array_push($access,1);
                        array_push($upload,0);
                    }
                }
                if($value->Uploaddate!=''){
                    $uploadtime=\explode(' ',$value->Uploaddate)[0];
                    if(in_array($uploadtime,$x)){
                        $key=array_keys($x,$uploadtime)[0];
                        $upload[$key]++;
                    }else{
                        array_push($x,$uploadtime);
                        array_push($upload,1);
                        array_push($access,0);
                    }
                }
            }
            for($i=0;$i<count($x);$i++){
                $k=$i;
                for($j=$i;$j<count($x);$j++){
                    if($x[$k]>$x[$j]){
                        $k=$j;
                    }
                }
                if($k!=$i){
                    $temp=$x[$i];
                    $x[$i]=$x[$k];
                    $x[$k]=$temp;

                    $temp=$upload[$i];
                    $upload[$i]=$upload[$k];
                    $upload[$k]=$temp;

                    $temp=$access[$i];
                    $access[$i]=$access[$k];
                    $access[$k]=$temp;
                }
            }
            return [
                'xAxis'=>[
                    'data'=>$x
                ],
                'series'=>[
                    [
                        'name'=>'录入量',
                        'data'=>$upload
                    ],
                    [
                        'name'=>'审核量',
                        'data'=>$access
                    ],
                ]
            ];
        }
    }