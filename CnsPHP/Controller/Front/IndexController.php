<?php
namespace CnsPHP\Controller\Front;

use CnsPHP\Controller\Controller as Controller;
use CnsPHP\Model\Model as Model;
use CnsPHP\Controller\Auth as Auth;

//默认表格为Class Model名字的小写,即为user
class IndexController extends Controller {
    public static function Index($args, $post, $get) {
//        var_dump(Auth::is_login());
    }
}
