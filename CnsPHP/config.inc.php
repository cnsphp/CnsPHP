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
$GLOBALS['CnsPHP_base']    = "CnsPHP";
$GLOBALS['CnsPHP_db_host'] = "localhost";
$GLOBALS['CnsPHP_db_port'] = "3306";
$GLOBALS['CnsPHP_db_name'] = "dbname";
$GLOBALS['CnsPHP_db_user'] = "user";
$GLOBALS['CnsPHP_db_pass'] = "pass";

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
include($GLOBALS['CnsPHP_base']."/Smarty/Smarty.class.php");
