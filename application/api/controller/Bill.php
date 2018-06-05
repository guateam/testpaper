<?php 
    namespace app\api\controller;
    use think\Controller;
    use \app\api\model\Bill as BillModel;
    /**
     * 填空题控制类
     * 
     */
    class Bill extends Controller{
        public function getbill($id){
            $bill = BillModel::get(['ID'=>$id]);
            if($bill)return $bill;
            else return 0;
        }
        public function getallbill(){
            $bill = BillModel::all();
            if($bill)return $bill;
            else return 0;
        }
        public function addbill($val,$type,$paperid){
            $bill = new BillModel();
            $bill->PayValue = $val;
            $bill->PayTime = date("Y-m-d  H:i:s");
            $bill->PayType = $type;
            $paper = \app\api\Model\Testpaper::get(['ID'=>$paperid]);
            if($paper){
                $bill->PaperID = $paperid;
                $bill->PaperName = $paper->Name;
                $bill->PaperClass = $paper->Class;
                $bill->PaperSubject = $paper->Subject;
                $bill->PaperSchool = $paper->School;
                if($type == 0){
                    $bill->PayTargetID = $paper->Uploader;
                }else{
                    $bill->PayTargetID = $paper->Auditor;
                }
                $target =\app\api\Model\User::get(['ID'=> $bill->PayTargetID]);
                $bill->PayTargetName = $target->Username;
                $bill->PayTargetPhoneNumber = $target->PhoneNumber;
                $bill->save();
                return 1;
            }else{
                return 0;
            }
        }
    }