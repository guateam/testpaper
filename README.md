考试题目录入系统
===============

[![Total Downloads]]

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

##更新内容
 + 形验证码
 + 大题名称用信息标记不能为空
 + 其他题型更加明确，简答题的代码放在其他题型中去
 + “大题“给出更加清晰的解释
 + 添加客服qq的显示在页面顶部
 + 管理员要有能更改客服qq的能力
 + 弹窗的按钮颜色改变
 + 帮助前面的符号改成问号
 + 审核时要显示试卷的分数
 + Jquery.cookie文件要导入全部必要的html中
 + 管理员 预览界面，可以看到所有已通过审核的试卷的审核人信息，上传者信息


## 更新与需求

1.	图片验证码加上时间限制
2.	添加大题的时候，题目数量为0的情况没有排除
3.	客服qq的显示变得更加良好，文字和号码同时出现
4.	改qq界面的标题错误，确认修改移动到panel底部
5.	帮助的颜色和符号位置统一
6.	“总分偏差”  改成  “总分偏 多/少”
7.	人员角色和账号在更加醒目的地方明确标出
8.	管理员能查阅每个上传者某个时间段上传试卷的效率
9.	提供按钮来提醒审核员有哪些试卷需要审核
10.	为试卷提供从完成录入到当前为止的时间段信息
11.	注册提醒的窗口的优先级改变

