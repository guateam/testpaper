<?php
namespace app\api\model;
use think\Model;
/**
 * 账户模型
 */
class User extends Model{
    //数据库表单接包人员
    protected $table = 'user';

    // 设置当前模型的数据库连接
    protected $connection = [
        // 数据库类型
        'type'        => 'mysql',
        // 服务器地址
        'hostname'    => '127.0.0.1',
        // 数据库名
        'database'    => 'testpaper',
        // 数据库用户名
        'username'    => 'root',
        // 数据库密码
        'password'    => 'smhhyyz508234',
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => '',
        // 数据库调试模式
        'debug'       => false,
    ];
}