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
      public function GetInfo($args=[]) {
           ...
      }
   
   3)
   http://www.a.com/blog/user-admin/get-info/a/b/c/d/e/f
   
   Controller/Blog/UserAdmin.php
      public function GetInfo($args=[]) {
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
