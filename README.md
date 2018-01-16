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
│   ├── config.inc.php
│   ├── route.inc.php
│   ├── init.inc.php
│   ├── Controller
│   │   ├── AppController.php
│   │   ├── Common
│   │   │   └── CommonController.php
│   │   └─ Job
│   │     ├── PController.php
│   │     ├── UController.php
│   │     ├── WeixinController.php
│   │     └── WxpayController.php
│   │
│   ├── Model
│   │   ├── AppModel.php
│   │   └── Front
│   │       └── IndexModel.php
│   ├── View
│   │     ├── cache
│   │     ├── compile
│   │     │   ├── 117e3ee986632ea32414f51c36f33c9b36cd144e_0.file.index.html.php
│   │     │   └── f9aa2f04ca5ef71405378acc0b07b8b087648838_0.file.index.html.php
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
├── CnsPHP
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
							

# <b>Install</b>  
  1) modify the nginx config   
     $ sudo vim /etc/nginx/sites-enabled/www.a.com
       location / {
           try_files $uri $uri/ /index.php?$args;
       }

   2) in the document_root directory install CnsPHP
     $ cd /var/www/htdocs/www.a.com 
     $ wget https://raw.githubusercontent.com/cnsphp/CnsPHP/master/install.sh
     $ chmod +x install.sh
     $ sudo ./install.sh


# <b>Example</b>							
   http://www.a.com/module/controller/method/arg1/val1/arg2/val2/arg3/val3
   
   1)
   http://www.a.com/admin/user/info/a/b/c/d/e/f
   
   Controller/Admin/User.php
      public function Info($args=[]) {
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
    Documentation http://www.smarty.net/documentation
    
    default we use:
        $this->view->left_delimiter = '&lt;!--{';
        $this->view->right_delimiter = '}--&gt;';
        
    Example:
       1)CnsPHP/Controller/Admin/User.php
        &lt;?php
        namespace CnsPHP;
        class UserModel extends Model {
             function __construct(){
                 parent::__construct('user');
             }
        }
        class User extends Controller {
             function __construct(){
                 parent::__construct();
             }
             function Info($args=[]) {
               //database
                   $arr = (new Model('user'))->getOne();
                   //or
                   $arr = (new UserModel())->getOne();
                   $arr = (new UserModel())->getOne(['name'=>'aaa','age'=>20]);
                   $arr = (new UserModel())->getOne([],"select * from user order by id desc");
                   
                   $arr = (new UserModel())->getALl(['name'=>'%a%','age'=>20],"select * from user where name like :name and age>:age");
                   
                   $result =(new UserModel())->insert(['name'=>'beccbaa0','age'=>30,'gender'=>'M','time'=>time()]);
                   $result =(new UserModel())->insert(['name'=>'beccbaa0','age'=>30,'gender'=>'M','time'=>time()],"insert into user set name=:name,age=:age,gender=:gender,time=:time");
                   if($result !== false)
                       echo $result->lastInsertId;
                   
                   $result =(new UserModel())->delete(['name'=>'beccbaa0']);
                   $result =(new UserModel())->delete(['name'=>'beccbaa0','age'=>20],"delete from user where name=:name or age<:age");
                   if($result !== false)
                       echo $result->affectedRows;
                       
                   $result =(new UserModel())->update(['age'=>55,'gender'=>'F'],['name'=>'baaaaa']);
                   $result =(new UserModel())->update(['age'=>45,'gender'=>'F','name'=>'bccbaa'],[],"update user set age=:age, gender=:gender where name=:name");
                   if($result !== false)
                       echo $result->affectedRows;
               //~database
               
               //var_dump($_POST);
               //var_dump($args);
               //var_dump($this->view);
        		
        		
               // $this->fileClass();
           	   // $this->file->...
        	   // or
               // $file=$this->fileClass();
        	   // $file-> ...
        		
               // $this->imgClass();  
        	   // $this->img->...
        	   // or
               // $img=$this->imgClass(); 
        	   // $img->...
        		
                // $this->strClass();  
        	    // $this->str->
        	    // or
                // $str=$this->imgClass(); 
        	    // $str->
        		
                //var_dump($this);         
        		
                //$this->view->assign("title","xxxx");
        		 
                //$this->show(); //default is  tpl/html/admin/user/info.html
        	    //$this->show($this->rootdir."/tpl/admin/user/info.html");
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
