<?php
namespace CnsPHP\Controller\Admin;

use CnsPHP\Controller\Controller as Controller;
use CnsPHP\Model\Model as Model;

class UserModel extends Model {
    function __construct(){
        parent::__construct('user');
    }
}

class User extends Controller {
    function __construct(){
        parent::__construct();
    }

    function Iframe($args=[]){
        if(!isset($_POST['htmldata']))
        {
            // {"code":2, "msg":"invalid request", "data":[]}
            return $this->msg(2,'invalid request');
        }

        $f="statics/htdocs/iframecanvas.html";
        $c=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$f);

        if($c===FALSE)
        {
            return $this->msg(3,'open file failed');
        }

        $c=str_replace('<div id="start_interposition"></div>','<div id="start_interposition"></div>'.$_POST['htmldata'],$c);
        if(file_put_contents($f,$c) !== FALSE)
        {
            return $this->msg(1,'success');
        }
        else
        {
            return $this->msg(4,'write file failed');
        }
    }

    function Info($args=[]) {
        //database
        $arr = (new Model('user'))->getOne();
        $arr = (new Model())->table('user')->getOne();
        var_dump($arr);

        //or

        $arr = (new UserModel())->getOne();
        $arr = (new UserModel())->table('user')->getOne();
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

        $result =(new UserModel())->update(['age'=>55,'gender'=>'F','name'=>'vvvv'],['name'=>'baaaaa','age'=>30]);
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

        //$this->show(); //default is  CnsPHP/html/admin/user/info.html
        //$this->show($this->rootdir."/tpl/admin/user/info.html");
    }
}
