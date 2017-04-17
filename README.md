<pre>
# <b>CnsPHP</b>
  a simple php web framework based on smarty ^_^

# <b>Structure</b>
/index.php
/CnsPHP/
   ├─ Controller
   │    ├─ Controller.php
   │    └─ Admin
   │          └─ User.php
   └─ Smarty
          └─ Smarty.class.php
 /tpl
   ├─ cache
   ├─ compile
   ├─ config
   └─ html
        └─ admin
             └─ user
                  └─ info.html							

# <b>Install</b>  
  1) modify the nginx config   
     $ sudo vim /etc/nginx/sites-enabled/www.a.com
       location / {
           try_files $uri $uri/ /index.php?$args;
       }


     $ cd /var/www/htdocs/www.a.com 
     $ wget https://raw.githubusercontent.com/cnsphp/CnsPHP/master/install.sh
     $ chmod +x install.sh
     $ sudo ./install.sh


# <b>Example</b>							
   http://ppt.yiyaozg.com/module/controller/method/arg1/val1/arg2/val2/arg3/val3
   http://ppt.yiyaozg.com/admin/user/info/a/b/c/d/e/f
</pre>
