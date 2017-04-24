<?php
namespace CnsPHP\Controller\Admin;

use CnsPHP\Controller\Controller as Controller;
use CnsPHP\Model\Model as Model;
use CnsPHP\Controller\Auth as Auth;
use CnsPHP\Common\CheckCode as CheckCode;

//默认表格为Class Model名字的小写,即为user

class User extends Model {}

class UserController extends Controller {
    public static function Register($args=[]) {
       if(!isset($_SESSION['auth_register_check_code']) || $_SESSION['auth_register_check_code'] != $_POST['checkcode'])
       {
           return self::msg(2,'invalid check code');
       }
       else
       {
           $_SESSION['auth_login_check_code']='';
       }


       User::insert(['name'=>$_POST['username'],'passwd'=>Auth::passwd($_POST['passwd']),'gender'=>$_POST['gender'],'age'=>$_POST['age'], 'time'=>time()]);  
       if(User::$affectedRows == 1)
           self::msg(1,'register successfully');
       else
           self::msg(2,'register failed: '.User::error_msg);
    }

    public static function Login($args=[]){
       if(!isset($_SESSION['auth_login_check_code']) || $_SESSION['auth_login_check_code'] != $_POST['checkcode'])
       {
           return self::msg(2,'invalid check code');
       }
       else
       {
           $_SESSION['auth_login_check_code']='';
       }

       $username = $_POST['username'];
       $passwd   = $_POST['passwd'];
       $arr      = User::getOne(['name'=>$username]);   

       if(Auth::passwd_verify($_POST['passwd'],$arr['passwd']))
       {
           $_SESSION['auth_login_in'] = true;
           return self::msg(1,'login seccuessfully');
       }
       else
       {
           $_SESSION['auth_login_in'] = true;
           return self::msg(3,'login failed; passwd invalid');
       }
    }

    public static function LoginCheckcode($args=[]){
       $act = $args['act'];

       if($act == 'new'){
          $_SESSION['auth_login_check_code']=CheckCode::create(90,25);
       }
    }

    public static function RegisterCheckcode($args=[]){
       $act = $args['act'];

       if($act == 'new'){
          $_SESSION['auth_register_check_code']=CheckCode::create(90,25);
       }
    }

    public static function Logout($args=[]) {
        if(Auth::is_login())
        {
            $_SESSION['auth_login_in'] = false;
            return self::msg(1,'logout successfully');
        }
        else
        {
            return self::msg(2,'logout failed; not login or other error');
        }
    }

    public static function Iframe($args=[]){
        if(!isset($_POST['htmldata']))
        {
            // {"code":2, "msg":"invalid request", "data":[]}
            return self::msg(2,'invalid request');
        }

        $f="statics/htdocs/iframecanvas.html";
        $c=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$f);

        if($c===FALSE)
        {
            return self::msg(3,'open file failed');
        }

        $c=str_replace('<div id="start_interposition"></div>','<div id="start_interposition"></div>'.$_POST['htmldata'],$c);
        if(file_put_contents($f,$c) !== FALSE)
        {
            return self::msg(1,'success');
        }
        else
        {
            return self::msg(4,'write file failed');
        }
    }

    public static function Info($args=[]) {
        /*
        //database
        $arr = Model::table('user')::getOne();
        //var_dump($arr);

        $arr = User::getOne();
        //var_dump($arr);

        $arr = User::getOne(["name"=>"aaa","age"=>50]);
        //var_dump($arr);

        $arr = User::getOne([],"select * from user order by id desc");
        //var_dump($arr);
        //$arr = User::getALl(['name'=>'%a%','age'=>20],"select * from user where name like :name and age>:age");
        //var_dump($arr);

        //$result =User::insert(['name'=>'beci92','age'=>30,'gender'=>'M','time'=>time()]);
        //$result =User::insert(['name'=>'b8090','age'=>30,'gender'=>'M','time'=>time()],"insert into user set name=:name,age=:age,gender=:gender,time=:time");
        //if($result !== false)
        //echo $result::$lastInsertId;

        //$result = User::delete(['name'=>'b8888']);
        //if($result !== false)
        //echo $result::$affectedRows;

        //$result = User::delete(['name'=>'bccbaa','age'=>49],"delete from user where name=:name or age<:age");
        //if($result !== false)
        //echo $result::$affectedRows;

        $result =User::update(['age'=>55,'gender'=>'F','name'=>'vvvvv'],['name'=>'vvvv','age'=>55]);
        if($result !== false)
            echo $result::$affectedRows;

        $result =User::update(['age'=>45,'gender'=>'F','name'=>'baaaaa'],[],"update user set age=:age, gender=:gender where name=:name");
        if($result !== false)
            echo $result::$affectedRows;
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

        //$this->show(); //default is  CnsPHP/html/admin/user/info.html
        //$this->show($this->rootdir."/tpl/admin/user/info.html");
         */
    }
}
