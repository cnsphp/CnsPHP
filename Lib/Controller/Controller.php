<?php
namespace Cnsyao\CnsPHP\Lib\Controller;

use Cnsyao\CnsPHP\Lib\Common\Net;
use Cnsyao\CnsPHP\Lib\Common\Str;
use Cnsyao\CnsPHP\Lib\Controller\Route;

class Controller
{
    public static $uri = "";

    public static $module = "";
    public static $controller = "";
    public static $method = "";
    public static $args = [];

    public static $view = null;
    public static $isapi = 'n';

    public static function hello() {
      echo "hello controller\n";
      echo Str::msg(1,"hello api");
      echo "\n";
    }

    /**
     * 默认目录为: tplpath/Modulename/ControllerName/MethodName.html
     */
    public static function show($tpl = "")
    {
        if ($tpl === "")
            self::$view->display(strtolower(self::$module . '/' . self::$controller . '/' . self::$method . '.html'));
        else
            self::$view->display($tpl);
    }

    // 初始化
    public static function init(\Closure $func)
    {
        //路径解析成module controller method args
        self::parseURI();

        //回调函数,处理登陆认证, 通过认证则返回$token
        $argsToken = $func();
        //安全防护，防止使用用户输入的_tk_uid
        if(isset($args['_tk_uid']))
            unset(self::$args['_tk_uid']);

        if(isset($args['_tk_gid']))
            unset(self::$args['_tk_gid']);

        if(isset($args['_tk_ip']))
            unset(self::$args['_tk_ip']);

        self::$args = array_merge_recursive(self::$args, $argsToken);

        //smarty
        self::$view = new \Smarty();
        self::$view->setTemplateDir(\C::get()->root_path . '/Application/View/html/');
        self::$view->setCompileDir(\C::get()->root_path . '/Application/View/compile/');
        self::$view->setConfigDir(\C::get()->root_path . '/Application/View/config/');
        self::$view->left_delimiter = '<!--{';
        self::$view->right_delimiter = '}-->';

        //调用 xxxController::Funcxxx()
        $f = \C::get()->root_path . '/Application/Controller/' . self::$module . '/' . self::$controller . 'Controller.php';
        $cname = "Application\\Controller\\" . (self::$module) . "\\" . (self::$controller) . "Controller";

        $cname::{self::$method}(self::$args, $_POST, $_GET);
    }

    private static function controller_exists($controller_file, $method)
    {
        return (file_exists($controller_file) && preg_match('/ public\s+static\s+function\s+' . $method . '\s*\(/', file_get_contents($controller_file)) == 1);
    }

    private static function parseURI()
    {
        //去掉 ?querystring
        self::$uri = preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']);

        //去掉尾部/
        if (self::$uri !== '/')
            self::$uri = rtrim(self::$uri, '/');

        foreach (\C::get()->route_map as $RouteKey => $val) {
            if (preg_match('|^' . $RouteKey . '$|', self::$uri) === 1) {
                self::$uri = preg_replace('|^' . $RouteKey . '$|', Route::get($RouteKey), self::$uri);
                break;
            }
        }

        //解析uri成 module controller method
        self::$uri = trim(self::$uri, '/');
        $arr = preg_split('/\//', self::$uri);

        //uri为空时,默认为/front/index/index
        if (self::$uri == '') {
            self::$module = \C::get()->default_module;
            self::$controller = 'Index';
            self::$method = 'Index';
        } //当uri为/user/info时,默认为/front/user/info
        else if (count($arr) == 2) {
            self::$module = \C::get()->default_module;
            self::$controller = self::replaceGap($arr[0]);
            self::$method = self::replaceGap($arr[1]);

            array_shift($arr);
            array_shift($arr);
        } else if (count($arr) >= 3) {
            //如果uri为 /user/info/uid/28/age/39, 判断默认模块/front/user/info是否存在
            $module = self::$module = \C::get()->default_module;
            $controller = self::replaceGap($arr[0]);
            $method = self::replaceGap($arr[1]);

            $controller_file = \C::get()->root_path . '/Application/Controller/' . $module . '/' . $controller . 'Controller.php';
            if (self::controller_exists($controller_file, $method)) {
                self::$module = \C::get()->default_module;
                self::$controller = self::replaceGap($arr[0]);
                self::$method = self::replaceGap($arr[1]);

                array_shift($arr);
                array_shift($arr);
            } //默认模块不存在,则严格分解为/module/controller/method/arg1/val1/arg2/val2
            else {
                self::$module = self::replaceGap($arr[0]);
                self::$controller = self::replaceGap($arr[1]);
                self::$method = self::replaceGap($arr[2]);
                array_shift($arr);
                array_shift($arr);
                array_shift($arr);
            }
        } else {
            die(basename(__FILE__) . ":" . __LINE__ . ": invalid request 1");
        }
        //判断模块 控制器 方法是否存在
        $controller_file = \C::get()->root_path . '/Application/Controller/' . self::$module . '/' . self::$controller . 'Controller.php';
        if (!self::controller_exists($controller_file, self::$method)) {
            die(basename(__FILE__) . ":" . __LINE__ . ": Controller: invalid request controller or method ");
        }

        //生成/module/controller/method/arg1/val1/arg2/val2中的 ['arg1'=>'val1', 'arg2'=>'val2' ...]数组
        while (@list($arg, $val) = $arr) {
            array_shift($arr);
            array_shift($arr);
            self::$args[$arg] = $val;
        }

        if (\C::get()->isapi == "y" || isset(self::$args['isapi']) && self::$args['isapi'] == 'y') {
            self::$isapi = "y";
        }
    }

    /**
     * 修改uri中的-[a-z]为[A-Z]
     */
    private static function replaceGap($str)
    {
        if (strpos($str, '-') !== FALSE)
            return ucfirst(preg_replace_callback('/\-([a-z])/', function ($matches) {
                return strtoupper($matches[1]);
            }, $str));
        else
            return ucfirst($str);
    }
}
