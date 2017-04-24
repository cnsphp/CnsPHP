<?php
//session
ini_set('session.save_handler', 'memcached');
ini_set('session.save_path',    '127.0.0.1:11211');
session_start();

//debug
ini_set('display_errors','on');
error_reporting(E_ALL);

//timezone
date_default_timezone_set("Asia/Shanghai");

//config
//CnsPHP相对于index.php的目录位置
$GLOBALS['CnsPHP']['base']    = "CnsPHP";
$GLOBALS['CnsPHP']['db']['host'] = "localhost";
$GLOBALS['CnsPHP']['db']['port'] = "3306";
$GLOBALS['CnsPHP']['db']['name'] = "dbname";
$GLOBALS['CnsPHP']['db']['user'] = "user";
$GLOBALS['CnsPHP']['db']['pass'] = "pass";

//autoload class
spl_autoload_register( function ($class) {
        if(strpos($class,"Smarty") !== FALSE)
            return;

        $f=str_replace("\\",'/',$class);

        $loaded=false;
        foreach([".php",".class.php","Class.php"] as $ext)
        {
            if(file_exists($f.$ext))
            {
               include $f.$ext;
               $loaded = true;
               break;
            }
        }

        if(!$loaded)
        {
            echo " Warn: not load class $f ";
        }
});

//include tpl smarty
include($GLOBALS['CnsPHP']['base']."/Smarty/Smarty.class.php");

//referer check
if(!referer_check())
    die('invalid request!!!');

function referer_check()
{
    if($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index.php')
        return true;

    if(!isset($_SERVER['HTTP_REFERER']))
        return false;

    $arr = [
       'http://ppt.yiyaozg.com'
    ];

    $valid = false;
    foreach($arr as $refer)
    {
       if(strpos($_SERVER['HTTP_REFERER'], $refer) === 0)
       {
           return true;
       }
    }
    return false;
}
