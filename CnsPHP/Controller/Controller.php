<?php
namespace CnsPHP\Controller;
use CnsPHP\Common\CnsToken;
use CnsPHP\Common\CheckCode as CheckCode;

class Controller {
    public static $rootdir="/var/www/php/ppt.yiyaozg.com";
    public static $rootdir_html="/var/www/html/ppt.yiyaozg.com";

    public static $uri = "";

    public static $module = "";
    public static $controller = "";
    public static $method = "";
    public static $args = [];

    public static $view = null;

    public static function msg($code=1,$msg='',$data=array(),$token=array())
    {
        $str = json_encode(["code"=>$code, "msg"=>$msg, "data"=>$data,"token"=> $token]);
        echo $str;
        return $str;
    }

    public static function Checkcode($key)
    {
        if (isset($key) && strlen($key)>0 )
            $_SESSION['auth_check_code'][$key] = CheckCode::create(90, 25);
    }

    public static function CheckcodeValid($key,$code) {
        if(isset($_SESSION['auth_check_code'][$key]) ) {
            if ($_SESSION['auth_check_code'][$key] === addslashes($code)) {
                unset($_SESSION['auth_check_code'][$key]);
                return true;
            }
        }
        return false;
    }

    public static function log($msg,$f="")
    {
        if($f==="")
            $f=self::$rootdir."/logs/logs.txt";
        file_put_contents($f,$msg."\n",FILE_APPEND | LOCK_EX);
    }

    /**
     * 默认目录为: tpl/Modulename/ControllerName/MethodName.html
     */
    public static function show($tpl="") {
        if($tpl === "")
            self::$view->display(strtolower(self::$module.'/'.self::$controller.'/'.self::$method.'.html'));
        else
            self::$view->display($tpl);
    }

    // 初始化
    public static function init()
    {
        self::$rootdir=getcwd();
        self::$rootdir_html="/var/www/html/ppt.yiyaozg.com";

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
            if(!in_array(self::$method, $GLOBALS['CnsPHP']['UnAUthMethod']  ))
            {
                $arr_token=self::TokenVerify();
                self::$args = array_merge_recursive(self::$args,$arr_token);
            }
            $cname::{self::$method}(self::$args,$_POST,$_GET);
        }
        else
        {
            die("invalid request");
        }
    }


    /**
     * @param array ['_tk'=>,'_m'=>,'_d'=>,'_f'=>]
     * @return array ['iat'=>, 'exp'=>, 'nbf'=>, '_tk_uid'=>]
     *  curl  -H 'QCTKA: { "_tk": " ...", "_tk_d": "...", "_tk_m": " ...","_tk_f": "..."}' -d "uid=7"  http://ppt.yiyaozg.com/user/info
      * curl  -H 'QCTKA: {"token": {"_tk": "...", "_tk_d": "...", "_tk_m": "...", "_tk_f": "..."}}' -d "uid=7"  http://ppt.yiyaozg.com/user/info
     */
    public static function TokenVerify($exp=60*24*7) {
        $token=[];

        // HEADER Token
        if(isset($_SERVER['HTTP_QCTKA'])) {
            $arrjson = (array)json_decode($_SERVER['HTTP_QCTKA'],true);
            if (isset($arrjson) && is_array($arrjson) && isset($arrjson['token']['_tk']) && isset($arrjson['token']['_tk_m']) && isset($arrjson['token']['_tk_d']) && isset($arrjson['token']['_tk_f']))
            {
                $token = $arrjson['token'];
            }
            else if(isset($arrjson) && is_array($arrjson) && isset($arrjson['_tk']) && isset($arrjson['_tk_m']) && isset($arrjson['_tk_d']) && isset($arrjson['_tk_f']))
            {
                $token =$arrjson;
            }
        }
        else
            die(self::msg(2,'invalid authentication 1'));

        $md5 = CnsToken::combinMD5($token);

        $arr = CnsToken::verify($md5,$exp);
        if($arr === false )
            die(self::msg(2,'invalid authentication'));

        unset($arr['iat']);
        unset($arr['nbf']);

        return array_merge($arr, ['token'=> $token] );
    }

    private static function controller_exists($controller_file,$method) {
        return (file_exists($controller_file) && preg_match('/ public\s+static\s+function\s+'.$method.'\s*\(/',file_get_contents($controller_file)) == 1 );
    }

    private static function parseURI() {
        self::$uri=trim(preg_replace('/\?.*$/','',$_SERVER['REQUEST_URI']),'/');
        if(preg_match('/^checkcode\/([^\/]+)$/', self::$uri, $match_checkcode) == 1)
        {
            die(self::Checkcode($match_checkcode[1]));
        }

        $arr=preg_split('/\//',self::$uri);
        
        if(self::$uri == '' || self::$uri == "index.php")
        {
            self::$module='Front';
            self::$controller='Index';
            self::$method = 'Index';
        }
        else if(count($arr)==2)
        {
            self::$module=$GLOBALS['CnsPHP']['default']['module'];
            self::$controller=self::replaceGap($arr[0]);
            self::$method = self::replaceGap($arr[1]);
            
            array_shift($arr);
            array_shift($arr);
        }
        else if(count($arr) >=3)
        {
            $module     = self::$module=$GLOBALS['CnsPHP']['default']['module'];
            $controller = self::replaceGap($arr[0]);
            $method     = self::replaceGap($arr[1]);

            $controller_file = self::$rootdir.'/'.$GLOBALS['CnsPHP']['base'].'/Controller/'.$module.'/'.$controller.'Controller.php';
            if(self::controller_exists($controller_file,$method))
            {
                self::$module=$GLOBALS['CnsPHP']['default']['module'];
                self::$controller=self::replaceGap($arr[0]);
                self::$method = self::replaceGap($arr[1]);

                array_shift($arr);
                array_shift($arr);
            }
            else
            {
            self::$module=self::replaceGap($arr[0]);
            self::$controller=self::replaceGap($arr[1]);
            self::$method = self::replaceGap($arr[2]);
            array_shift($arr);
            array_shift($arr);
            array_shift($arr);
        }
        }
        else
        {
            die("invalid request 1");
        }

        //check whether module / controller / method all ok
        $controller_file = self::$rootdir.'/'.$GLOBALS['CnsPHP']['base'].'/Controller/'.self::$module.'/'.self::$controller.'Controller.php';

        if(!self::controller_exists($controller_file,self::$method))
        {
            die("invalid request 2");
        }

        while(@list($arg,$val) = $arr)
        {
            array_shift($arr);
            array_shift($arr);
            self::$args[$arg] =$val;
        }
    }

    private static function replaceGap($str) {
        if(strpos($str,'-') !== FALSE)
            return ucfirst(preg_replace_callback('/\-([a-z])/', function($matches) {return strtoupper($matches[1]); },$str));
        else
            return ucfirst($str);
    }
}
