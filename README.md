<pre>
<b>.nginx</b>
#/etc/nginx/sites-enabled/cnsphp.yiyaozg.com 
server {
    listen 80;
    server_name cnsphp.yiyaozg.com;
    root /data/webs/cnsphp.yiyaozg.com/public;
    index index.htm index.html index.php ;

    error_log /var/log/nginx/error.log;
    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    charset utf-8;
    
    add_header X-Frame-Options "SAMEORIGN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";
    index index.html index.php; 
	
    location = /favicon.ico {
        access_log off;
        log_not_found off;
    }       

    location = /robots.txt {
        access_log off;
        log_not_found off;
    }

    location ~ \.php$ {
        include    fastcgi_params;
        fastcgi_index index.php;
        fastcgi_pass    127.0.0.1:9006;
        add_header Access-Control-Allow-Origin *;
        add_header 'Access-Control-Allow-Credentials' 'true';
        add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
        add_header 'Access-Control-Allow-Headers' 'DNT,QCTKA,X-CustomHeader,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type';
        fastcgi_param  SCRIPT_FILENAME  $document_root/$fastcgi_script_name;
    }
}

<b>.php</b>
;/etc/php/7.0/fpm/pool.d/cnsphp.yiyaozg.com.conf
[cnsphp]
;prefix = /data/webs/cnsphp.yiyaozg.com/public
;chroot = $prefix
;chdir = /
user = www-data
group = www-data
listen.owner = www-data
listen.group = www-data
listen = 127.0.0.1:9006
pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
php_value[upload_max_filesize]= 100m
php_value[post_max_size] = 100m
php_value[max_execution_time] = 1800
php_flag[display_errors] = on
php_value[date.timezone] = asia/shanghai
;php_value[session.save_handler] = memcached
;php_value[session.save_path] = tcp://127.0.0.1:11211



# <b>CnsPHP</b>
  a simple php web framework based on smarty ^_^

# <b>Structure</b>
/cnsphp.yiyaozg.com
├── Application 
│   ├── config.inc.php (配置文件)
│   ├── route.inc.php
│   ├── init.inc.php
│   ├── Controller (主要业务编程区)
│   │   ├── AppController.php (Application用户Controller的基类)
│   │   ├── Common
│   │   │     └── CommonController.php
│   │   └─ Admin
│   │   │    ├── IndexController.php
│   │   │    ├── UserController.php
│   │   │    ├── ...
│   │   │    └── WxpayController.php
│   │   └─ User
│   │       ├── IndexController.php
│   │       ├── UserController.php
│   │       ├── ...
│   │       └── WxpayController.php
│   │
│   ├── Model
│   │    ├── AppModel.php
│   │    └── Front
│   │         └── IndexModel.php
│   ├── View
│   │     ├── cache
│   │     ├── compile
│   │     │    ├── 117e3ee986632ea32414f51c36f33c9b36cd144e_0.file.index.html.php
│   │     │    └── f9aa2f04ca5ef71405378acc0b07b8b087648838_0.file.index.html.php
│   │     ├── config
│   │     └── html
│   │         └── job
│   │             ├── p
│   │             │   └── center.html
│   │             └── u
│   │                 ├── login.html
│   │                 └── register.html
│   └── Common
│     ├── CnsAntiXSS.php
│     ├── CnsMail.php
│     ├── CnsSMS.php
│     ├── CnsToken.php
│     ├── token.php
│     ├── third
│     │   ├── alimail
│     │   │   ├── AliMail.php
│     │   │   └── aliyun
│     │   │       ├── aliyun-php-sdk-alidns
│     │   │       ...
│     │   │       ├── aliyun-php-sdk-green
│     │   │       └── README.md
│     │   ├── alipay
│     │   │   ├── config.php
│     │   │   ...
│     │   │   └── return_url.php
│     │   ├── sms
│     │   │   ├── smsapi.class.php
│     │   │   └── SMSCN.php
│     │   └── wxpay
│     │       ├── cert
│     │       ├── index.php
│     │        ..
│     │       └── logs
│     │           └── 2017-09-17.log
│     └── vendor
│         ├── firebase
│         │   └── php-jwt
│         ├── ircmaxell
│         │   └── password-compat
│         ├── paragonie
│         │   └── random_compat
│         └── voku
│            └─anti-xs
├── CnsPHP (基本不用修改)
│   ├── Common
│   │   ├── CheckCode.php
│   │   ├── CnsMemcached.php
│   │   ├── cookie.txt
│   │   ├── FileCache.php
│   │   ├── File.php
│   │   ├── Ftp.php
│   │   ├── Img.php
│   │   ├── ImgUploadEffect.php
│   │   ├── Net.php
│   │   ├── QRCode.php
│   │   ├── Spider.php
│   │   ├── String.php
│   │   ├── Str.php
│   │   └── SVG.php
│   ├── Controller
│   │   ├── Auth.php
│   │   ├── Controller.php
│   │   └── Route.php
│   ├── Model
│   │   └── Model.php
│   ├── Route.php
│   └── View
│       ├── Autoloader.php
│       ├── debug.tpl
│       ├── plugins
│       ├── SmartyBC.class.php
│       ├── Smarty.class.php
│       └── sysplugins
│           ├── smarty_cacheresource_custom.php
│           ├ ....
│           ├── smarty_undefined_variable.php
│           └── smarty_variable.php
├── Docs
│   └──API.docx
└── public
    ├── css
    ├── images
    ├── index.php
    ├── js
    │   └── jquery.form.js
    └── uploads 

# <b>Example</b>							
   http://www.a.com/module/controller/method/arg1/val1/arg2/val2/arg3/val3
   
   1)
   http://www.a.com/admin/user/info/a/b/c/d/e/f
   
   Controller/Admin/User.php
      public function Info($args,$post,$get) {
          ...
      }
   
   2)
   http://www.a.com/admin/user/get-info/a/b/c/d/e/f
   
   Controller/Admin/User.php
      public function GetInfo($args,$post,$get) {
           $a=$args['a'];
           $x=$post['x'];
           $y=$get['y'];
      }
   
   3)
   http://www.a.com/blog/user-admin/get-info/a/b/c/d/e/f
   
   Controller/Blog/UserAdmin.php
      public function GetInfo($args,$post,$get) {
           ...
      }   
   
# <b>Controller and Smarty TPL</b> 
&lt;?php
namespace Application\Controller\Job;

use Application\Controller\AppController;
use Application\Model\AppModel;

use CnsPHP\Common\FileCache;
use CnsPHP\Common\Str;
use CnsPHP\Common\Net;
use CnsPHP\Common\File;
use CnsPHP\Common\CnsMemcached;
use CnsPHP\Common\QRCode;

use CnsPHP\Controller\Auth as Auth;
use Application\Common\CnsSMS as CnsSMS;
use CnsPHP\Common\CheckCode;
use Application\Common\CnsToken;
use Application\Common\CnsMail;

class Users extends AppModel{}

class UController extends AppController
{
     public static function Authcode($args,$post,$get){
         $_SESSION['auth_check_code'][$args['act']]=CheckCode::create(90,25);
     }

     public static function Register($args, $post, $get){
         self::show();
     }

     public static function Registered($args,$post,$get){
         if (!CheckCode::Verify('register', $post['checkcode'])){
            return Net::redirect('/u/register','验证码错误',3);
         }

         $eastr = md5(Str::random(60));
         Users::insert([
             'gid' => 'P',
             'username'=>$post['username'],
             'passwd' => Auth::passwd($post['passwd']),
             'email' => $post['email'],
             'eastr' => $eastr,
             'regtime'=>date('U')
         ]);

         if (Users::$affectedRows == 1) {
             //普通程序
	     return Net::redirect('/',"注册成功",3);

             //API返回  {"code": "1", "msg": "注册成功", "data":{}, "token": ""}
             return Str::msg(1, '注册成功'); 
         } else
             return Net::redirect(-1,"注册失败",3);
	     
	     //API返回   {"code": "1", "msg": "注册失败...", "data":{}, "token": ""}
             //return Str::msg(-1, '注册失败: ' . Users::$error_msg);
     }

    
     public static function Login($args,$post,$get){
           //显示模板
           self::show();
     }

     public static function Unreg($args,$post,$get){
          unset($_SESSION['HTTP_QCTKA']);
          Net::redirect('/','注销成功',3);
     }

     public static function Logined($args,$post,$get){
         //验证码检测
         if (!CheckCode::Verify('login', $post['checkcode']))
             return Str::msg(-1, '验证码错误');

         $arr = Users::getOne("", ['username'=>$post['username'],'email' => $post['username']], "select uid,gid,username,email,passwd,mobile,eastr,eastatus from users where (email=:username or username=:username)");

         //如果邮箱存在 且已经激活
         if($arr['eastatus'] == 'Y'){
             //验证密码是否正确
             if (auth::passwd_verify($post['passwd'], $arr['passwd'])) {
                 //generate token
                 $token = CnsToken::token($arr['uid'], ["_tk_uid" => $arr['uid'], "_tk_gid" => $arr['gid'], '_tk_ip' => Net::clientip()], 30);

                 Users::update(['lastlogin' => date('U')], ['uid' => $arr['uid']]);
                 return Net::redirect('/', '登陆成功',3);
             } else {
                 return Net::redirect(-1, '邮箱或密码错误');
             }
         } else {
                 return Net::redirect(-1, '帐号还未激活');
                     $url = Net::host() . "/user/valid-email/valid/" . $arr['eastr'];
                     $str = <<<_EOF_ 
尊敬的用户 您好： 
    欢迎您注册 a.com，点击链接接来验证您的 Email：.... 安全起见，该链接将于发送后 30分钟失效。 
_EOF_;
                     //$send = CnsMail::Send($post['email'], 'a.com 邮箱激活', $str);

                     //if ($send)
                     //    Str::msg(-3, '您的邮箱尚未激活,请去邮箱' . $post['email'] . '激活');
                     //else
                     //    Str::msg(-4, '您的邮箱尚未激活,但发送激活连接失败,请联系在线客服');
             }
     }

     public static function Center($args,$post,$get){
         self::show();
     }
}
    
1)CnsPHP/Controller/Admin/User.php
&lt;?php
        namespace CnsPHP;
        class Users extends AppModel {
        }
        class User extends AppController {

             function Info($args=[]) {
               //database
                   $arr = Users::getOne();
                   //or
                   $arr = Users::getOne();
                   $arr = Users::getOne(['name'=>'aaa','age'=>20]);
                   $arr = Users::getOne([],"select * from user order by id desc");
                   
                   $arr = Users::getALl(['name'=>'%a%','age'=>20],"select * from user where name like :name and age>:age");
                   
                   $result =Users::insert(['name'=>'beccbaa0','age'=>30,'gender'=>'M','time'=>time()]);
                   $result =Users::insert(['name'=>'beccbaa0','age'=>30,'gender'=>'M','time'=>time()],"insert into user set name=:name,age=:age,gender=:gender,time=:time");
                   if($result !== false)
                       echo Users::lastInsertId;
                   
                   $result =Users::delete(['name'=>'beccbaa0']);
                   $result =Users::delete(['name'=>'beccbaa0','age'=>20],"delete from user where name=:name or age<:age");
                   if($result !== false)
                       echo Users::affectedRows;
                       
                   $result =Users::update(['age'=>55,'gender'=>'F'],['name'=>'baaaaa']);
                   $result =Users::update(['age'=>45,'gender'=>'F','name'=>'bccbaa'],[],"update user set age=:age, gender=:gender where name=:name");
                   if($result !== false)
                       echo Users::affectedRows;
               //~database
        		

                //self::$view->assign("title","xxxx");
        		 
                //self::show(); //default is  tpl/html/admin/user/info.html
        	//self::show($this->rootdir."/tpl/admin/user/info.html");
             }
        }
    
     <b>2) tpl/admin/user/info.html</b>
       &lt;!DOCTYPE html&gt;
       &lt;html&gt;
       &lt;head&gt;
           &lt;meta charset="UTF-8"&gt;
           &lt;title&gt;&lt;!--{$title}--&gt;&lt;/title&gt;
       &lt;/head&gt;

       &lt;body&gt;
             Content of the document......
       &lt;/body&gt;
      &lt;/html&gt; 
</pre>
