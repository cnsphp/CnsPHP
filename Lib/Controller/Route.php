<?php
namespace Cnsyao\CnsPHP\Lib\Controller;
use Cnsyao\CnsPHP\Lib\Common\Str;
class Route
{
    public static function get($uri, $controller = '')
    {
        if ($controller === '') {
            if(isset(\C::get()->route_map->$uri))
                return \C::get()->route_map->$uri;
            return '';
        } else {
            \C::get()->route_map->$uri = $controller;
            return $controller;
        }
    }
}
