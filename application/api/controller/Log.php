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
                $list=\app\api\model\Log::all(['To'=>$userid,'State'=>0]);
                $data=[];
                foreach($list as $value){
                    $item=[
                        'name'=>$value->Note,
                        'style'=>$value->Type
                    ];
                    $value->State=1;
                    $value->save();
                    array_push($data,$item);
                }
                return json(['status'=>1,'data'=>$data]);
            }
            return json(['status'=>0]);
        }
        /**
         * 添加日志
         */
        public function add($name,$from,$to,$type,$note,$testpaper){
            
        }
    }


