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
         <?php
           namespace CnsPHP;
           
           class User extends Controller {
                function __construct(){
                    parent::__construct();
                }
           
                function Info($args=[]) {
                   //var_dump($_POST);
                   //var_dump($args);
                   //var_dump($this->view);
           		
           		
                   // $this->dbClass();
           		     // $this->db->conn();		
           	       // or 
                   // $db=$this->dbClass();
                   // $db->conn();
           
           		
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
           		 
                   //$this->show(); //default is  CnsPHP/html/admin/user/info.html
           		     //$this->show($this->rootdir."/tpl/admin/user/info.html");
                }
           } 
    
     <b>2) tpl/admin/user/info.html</b>
       <!DOCTYPE html&gt;
       <html&gt;
       <head&gt;
           <meta charset="UTF-8"&gt;
           <title><!--{$title}--&gt;</title&gt;
       </head&gt;

       <body&gt;
             Content of the document......
       </body&gt;
      </html&gt; 
</pre>
