<?php
namespace app\admin\controller;
use think\Controller;
use \app\api\model\User as UserModel;
/**
 * 支付页面
 */
class Pay extends Controller{
    public function index(){
        if(isset($_COOKIE['userid'])){
            $user=new \app\api\controller\User();
            if($user->checkuser($_COOKIE['userid'])){
                $data=UserModel::get(["Cookie"=>$_COOKIE['userid']]);//从数据库调取此用户信息
                if($data){
                    $unpaid_c=new \app\api\controller\User();
                    $price = new \app\api\controller\Defaultprice();
                    $unpaiduser=$unpaid_c->getunpaiduser();
                    //总价
                    $total_price = [];
                    //欠款时间
                    $not_pay_time = [];
                    //目前的时间
                    $now_time = strtotime(date("y-m-d H:i"));

                    for($j = 0;$j<count($unpaiduser['datainfo']);$j++){
                        array_push($total_price,0);
                        array_push($not_pay_time,0);
                        for($i = 0;$i<count($unpaiduser['datainfo'][$j]);$i++)
                        {
                            if($unpaiduser['userinfo'][$j]['Type']==0)
                            {
                                $total_price[$j]+=$unpaiduser['datainfo'][$j][$i]['price'];
                            }
                            else  $total_price[$j]+=$unpaiduser['datainfo'][$j][$i]['auditorprice']; 
                            $timeCut = ceil(($now_time - strtotime($unpaiduser['datainfo'][$j][$i]['audittime']) )/86400);
                            if($timeCut >$not_pay_time[$j])
                            {
                                $not_pay_time[$j] = $timeCut;
                            }
                        }

                    }
                    $this->assign('unpaid',$unpaiduser);
                    $this->assign("user",$data);
                    $this->assign('total',$total_price);
                    $this->assign('time',$not_pay_time);
                    return $this->fetch('pay');
                }
            }
        }
        return $this->error('请先登录','index/index/index');
    }
}