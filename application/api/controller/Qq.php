<?php 
    namespace app\api\controller;
    use think\Controller;
    use \app\api\model\QQ as QQModel;
    /**
     * 账户控制类
     * 
     */
    class QQ extends Controller{
        //获取目前的客服qq
        public function getQQnumber(){
           $data = QQModel::all();
            if($data){
                $dt = [];
                for($i = 0;$i<count($data);$i++)
                {
                    array_push($dt,$data[$i]->Number);
                }
                return $dt;
            }else return null;
        }
        
        //重新设置客服qq
        public function setQQnumber($qqnumber){
            $data = QQModel::get(1);
            if($data){
               $data->Number = $qqnumber;
               $data->save();
            }
            return 1;
        }
        
        //添加客服qq
        public function addQQnumber($qqnumber){
            $data =new QQModel();
            if($data){
               $data->Number = $qqnumber;
               $data->save();
            }
            return 1;
        }

    }


