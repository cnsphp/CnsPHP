<?php
namespace CnsPHP\Controller;

class Controller {
    public static $rootdir="";

    public static $uri = "";

    public static $module = "";
    public static $controller = "";
    public static $method = "";
    public static $args = [];

    public static $view = null;

    private static function parseURI(){
        self::$uri=$_SERVER['REQUEST_URI'];

        if(self::$uri == '' || self::$uri == '/' || self::$uri == "index.php" || self::$uri=='/index.php')
        {
            self::$module='Front';
            self::$controller='Index';
            self::$method = 'Index';
        }
        else
        {
            self::$uri=ltrim(self::$uri,'/');
            $arr=preg_split('/\//',self::$uri);

            self::$module=self::replaceGap($arr[0]);
            self::$controller=self::replaceGap($arr[1]);
            self::$method = self::replaceGap($arr[2]);
         }

        array_shift($arr);
        array_shift($arr);
        array_shift($arr);
        while(@list($arg,$val) = $arr)
        {
            array_shift($arr);
            array_shift($arr);
            self::$args[$arg] =$val;
        }
    }

    private static function replaceGap($str){ 
        if(strpos($str,'-') !== FALSE)
            return ucfirst(preg_replace_callback('/\-([a-z])/', function($matches){return strtoupper($matches[1]); },$str));
        else
            return ucfirst($str);
    }

    public static function msg($code=1,$msg='',$data=[])
    {
        echo json_encode(["code"=>$code, "msg"=>$msg, "data"=>$data]);
    }

    /**
     * 默认目录为: tpl/Modulename/ControllerName/MethodName.html
     */
    public static function show($tpl="") {
        if($tpl == "")
            self::$view->display(strtolower(self::$module.'/'.self::$controller.'/'.self::$method.'.html'));
        else
            self::$view->display($tpl);
    }

    public static function init()
    {
        self::$rootdir=getcwd();

        self::parseURI();

        self::$view = new \Smarty();
        self::$view->setTemplateDir(self::$rootdir.'/tpl/html/');
        self::$view->setCompileDir(self::$rootdir.'/tpl/compile/');
        self::$view->setConfigDir(self::$rootdir.'/tpl/config/');
        self::$view->left_delimiter = '<!--{';
        self::$view->right_delimiter = '}-->';

        $f=self::$rootdir.'/'.$GLOBALS['CnsPHP']['base'].'/Controller/'.self::$module.'/'.self::$controller.'Controller.php';
        if(file_exists($f))
        {
            $cname="CnsPHP\\Controller\\".(self::$module)."\\".(self::$controller)."Controller";
            //(new $cname())->{self::$method}(self::$args);
            $cname::{self::$method}(self::$args);
        }
        else
        {
            die("invalid request");
        }
    }

    public static function log($msg,$f=""){
        if($f=="")
            $f=self::$rootdir."/logs/logs.txt";
        file_put_contents($f,$msg."\n",FILE_APPEND | LOCK_EX);
    }
}
