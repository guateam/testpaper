<?php 
    namespace app\api\controller;
    use think\Controller;
    use \app\api\model\QQ as QQModel;
    /**
     * 账户控制类
     * 
     */
    class Log extends Controller{
        /**
         * 获取提示信息
         */
        public function getalert($id){
            $user=new User();
            $userid=$user->checkuser($id);
            if($userid){
                $list=\think\Db::query("select * from log where ToU like '%,".$userid.",%'");
                $data=[];
                foreach($list as $value){
                    if($value['State']==0){
                        $item=[
                            'name'=>$value['Note'],
                            'style'=>$value['Type']
                        ];
                        /**$state=\app\api\model\Log::get(['ID'=>$value['ID']]);
                        $state->State=1;
                        $state->save();*/
                        array_push($data,$item);
                    }
                }
                return json(['status'=>1,'data'=>$data]);
            }
            return json(['status'=>0]);
        }
        /**
         * 添加日志
         */
        public function add($name,$from,$to,$type,$note,$testpaper){
            $log=new \app\api\model\Log();
            $log->data([
                'Name'=>$name,
                'From'=>$from,
                'ToU'=>$to,
                'Type'=>$type,
                'Note'=>$note,
                'State'=>0,
                'Testpaper'=>$testpaper,
                'Date'=>date('Y-m-d H:s')
            ]);
            $log->save();
        }
        /**
         * 生成提醒审核提醒
         */
        public function warningauditor($testpaperid,$from){
            $user=new User();
            $userid=$user->checkuser($from);
            if($userid){
                $testpaper=\app\api\model\Testpaper::get(['ID'=>$testpaperid]);
                if($testpaper){
                    if($testpaper->Auditorlist==''){
                        $list=\app\api\model\User::all(['Type'=>2]);
                        $to=',';
                        foreach($list as $value){
                            $to.=$value->ID.',';
                        }
                        $this->add('催单0',$userid,$to,'warning','有一份试卷被提醒分配审核人！',$testpaperid);
                    }else{
                        $this->add('催单1',$userid,$testpaper->Auditorlist,'warning','你有一份试卷被提醒审核！',$testpaperid);
                    }
                    return json(['status'=>1]);
                }
            }
            return json(['status'=>0]);
        }
        /**
         * 标记审核提醒为已读
         */
        public static function set($id,$type=0){
            $log=\app\api\model\Log::get(['Name'=>'催单'.$type,'Testpaper'=>$id,'State'=>0]);
            if($log){
                $log->Name='催单';
                $log->State=1;
                $log->save();
            }
        }
    }


