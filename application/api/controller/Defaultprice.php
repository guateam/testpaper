<?php 
    namespace app\api\controller;
    use think\Controller;
    use \app\api\model\Defaultprice as priceModel;

    class Defaultprice extends Controller{
        /**
        * 获取现在的单价情况
        */
        public function getdefaultprice(){
            $price = priceModel::get(["ID"=>1]);
            if($price){
                return ["uploaderprice"=>$price->Uploaderprice,"auditorprice"=>$price->Auditorprice];
            }else{
                return null;
            }
        }

        /**
         * 修改目前的单价情况
         */
        public function setdefaultprice($uploaderprice,$auditorprice){
            $price = priceModel::get(["ID"=>1]);
            if($price){
                $price->Uploaderprice = $uploaderprice;
                $price->Auditorprice = $auditorprice;
                $price->save();
                return 1;
            }else{
                return 0;
            }
        }
    }
?>