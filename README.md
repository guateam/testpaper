考试题目录入系统
===============

*v1.0.0*

本系统主要包含功能：

 + 试卷录入
 + 题目类型分类
 + 试卷类型记录
 + 试卷上传审核

## 目录结构

初始的目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─common             公共模块目录（可以更改）
│  ├─index              登录目录
│  │  ├─controller      控制器目录
│  │  └─view            视图目录
│  ├─api                接口目录
│  │  ├─controller      控制器目录
│  │  └─model           模型目录
│  ├─uploader           上传人目录
│  │  ├─controller      控制器目录
│  │  └─view            视图目录
│  │
│  ├─command.php        命令行工具配置文件
│  ├─common.php         公共函数文件
│  ├─config.php         公共配置文件
│  ├─route.php          路由配置文件
│  ├─tags.php           应用行为扩展定义文件
│  └─database.php       数据库配置文件
│
├─public                WEB目录（对外访问目录）
│  ├─index.php          入口文件
|  ├─static             静态资源文件
|  |  ├─js              js文件
|  |  └─vendor          第三方类库文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  ├─phpunit.xml        phpunit配置文件
│  └─start.php          框架入口文件
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~

## 更新内容
 + 管理员定试卷的工资单价和审核员的工作单价，这些价格应该是可修改的，每张试卷的工资支付状态的显示，日结或多日一结
 + 催单的提醒需要持续存在,直到完成应做的内容，提示具体哪张试卷需要进行操作

## 需求内容

+ 改qq界面输入两遍
+ 注册的时候添加支付宝账号(为了结算工资)
+ 管理员的效率界面 搜索改成下拉框形式//放一下
+ 薪水情况 改成 工资价位，负数工资的情况没有判断
+ 管理员确认付款，付款的时候显示每个人的未付款总额
+ 数据库做成一个excel表格
