<?php 
    namespace app\api\controller;
    use think\Controller;
    use \app\api\model\User as UserModel;
    /**
     * 账户控制类
     * 
     */
    class User extends Controller{
        public function checkuser($cookie){
            $user=\app\api\model\User::get(['Cookie'=>$cookie]);
            if($user){
                return $user->ID;
            }
        }
        /**
         * 内部方法，生成一个不重复的cookie
        */
        private function getcookie(){
            $cookie = $this->makekeys();
            if (UserModel::get(["Cookie" => $cookie])) {
                $cookie = $this->getcookie();
            }
            return $cookie;
        }
        /**
         * 内部方法，生成随机的一串代码
         */
        private function makekeys($length = 8){

            // 密码字符集，可任意添加你需要的字符
            $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
                'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
                't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
                'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
                'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

            // 在 $chars 中随机取 $length 个数组元素键名

            $keys = '';
            for ($i = 0; $i < $length; $i++) {
                // 将 $length 个数组元素连接成字符串
                $keys .= $chars[mt_rand(0, 61)];
            }
            return $keys;
        }
        /**
        * 注册方法
        */
        public function register(){
            $data=new UserModel($_POST);
            try{
                $data->allowField(true)->save();
                return json(['status'=>1]);
            }catch(Exception $e){
                return json(['status'=>0]);
            }
        }

        /**
         * 登陆方法
         */
        public function login(){
            $name = $_POST['Username'];
            $data=UserModel::get(["Username"=>$name]);//从数据库调取此用户信息
            if($data){
                if($data->Password == $_POST['Password']){
                    $data->Cookie=$this->getcookie();
                    $data->save();
                    return json(['status'=>1,'type'=>$data->Type,'cookie'=>$data->Cookie]);
                }else return json(['status'=>0]);
            }else return json(['status'=>-1]);
        }
        public function addnum($id){
            $user=UserModel::get(['ID'=>$id]);
            if($user){
                $user->Num++;
                $user->save();
            }
        }
    }