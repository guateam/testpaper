<?php 
    namespace app\api\controller;
    use think\Controller;
    use \app\api\model\QQ as QQModel;
    /**
     * 时间表控制类
     * 
     */
    class Timetable extends Controller{
        /**
         * 使当天特定种类的数据加一
         */
        public static function add($type){
            $time=\app\api\model\Timetable::get(['Time'=>date('Y-m-d')]);
            if($time){
                switch($type){
                    case 'Newpaper':
                        $time->Newpaper++;
                        break;
                    case 'Upload':
                        $time->Upload++;
                        break;
                    case 'Access':
                        $time->Access++;
                        break;
                    case 'Back':
                        $time->Back++;
                        break;
                    case 'Set':
                        $time->Set++;
                        break;
                }
                $time->save();
            }else{
                $time=new \app\api\model\Timetable();
                $time->data([
                    'Time'=>date('Y-m-d'),
                    $type=>1
                ]);
                $time->save();
            }
        }
        /**
         * 获取全平台的历史柱状图数据
         */
        public function getlinedata(){
            $list=\app\api\model\Timetable::all();
            $access=[];
            $upload=[];
            $back=[];
            $newpaper=[];
            $xAxis=[];
            $set=[];
            foreach($list as $value){
                array_push($xAxis,$value->Time);
                array_push($access,$value->Access);
                array_push($upload,$value->Upload);
                array_push($back,$value->Back);
                array_push($newpaper,$value->Newpaper);
                array_push($set,$value->Set);
            }
            return [
                'xAxis'=>[
                    'data'=>$xAxis
                ],
                'series'=>[
                    [
                        'name'=>'新建量',
                        'data'=>$newpaper
                    ],
                    [
                        'name'=>'录入/修改量',
                        'data'=>$upload
                    ],
                    [
                        'name'=>'通过量',
                        'data'=>$access
                    ],
                    [
                        'name'=>'打回量',
                        'data'=>$back
                    ],
                    [
                        'name'=>'分配量',
                        'data'=>$set
                    ],

                ]
            ];
        }
    }