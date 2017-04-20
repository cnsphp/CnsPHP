<?php
namespace CnsPHP\Common;

class Str {
    public static function echoln($msg) {
        echo "$msg\n";
    }

    /**
    * Str::validate($var,'int',10,100);
    */
    public static function validate($var,$type,$min=0,$max=0)
    {
        $arr = [
            'int'        => [FILTER_VALIDATE_INT],
            'int_range'  => [FILTER_VALIDATE_INT,['options'=>['min_range'=>$min,'max_range'=>$max]]],
            'oct'        => [FILTER_VALIDATE_INT,['flags'=>FILTER_FLAG_ALLOW_OCTAL]],
            'hex'        => [FILTER_VALIDATE_INT,['flags'=>FILTER_FLAG_ALLOW_HEX]],
            'float'      => [FILTER_VALIDATE_FLOAT],
            'boolean'    => [FILTER_VALIDATE_BOOLEAN],
            'number'     => '/^\d+$/',

            'url'        => [FILTER_VALIDATE_URL],
            'email'      => [FILTER_VALIDATE_EMAIL],

            'mac'        => [FILTER_VALIDATE_MAC],
            'ip'         => [FILTER_VALIDATE_IP],
            'ip_wan'     => [FILTER_VALIDATE_IP,['flags'=>[FILTER_FLAG_NO_PRIV_RANGE,FILTER_FLAG_NO_RES_RANGE]]],
            'ipv4_wan'   => [FILTER_VALIDATE_IP,['flags'=>[FILTER_FLAG_IPV4,FILTER_FLAG_NO_PRIV_RANGE,FILTER_FLAG_NO_RES_RANGE]]],
            'ipv6_wan'   => [FILTER_VALIDATE_IP,['flags'=>[FILTER_FLAG_IPV6,FILTER_FLAG_NO_PRIV_RANGE,FILTER_FLAG_NO_RES_RANGE]]],

            'non-neg'    => '/^\d+$/',
            'negative'   => '/^-[0-9]*[1-9][0-9]*$/',

            'date'       => '/^(d{2}|d{4})-((0([1-9]{1}))|(1[1|2]))-(([0-2]([1-9]{1}))|(3[0|1]))$/',
            'html'       => '/<(.*)>.*<\/\1>|<(.*) \/>/',
            'qq'         => '/^[1-9]*[1-9][0-9]*$/',
            'telephone'  => '/^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{8}$/',
            'mobile'     => '/^((\(\d{2,3}\))|(\d{3}\-))?13\d{9}$/',
            'zipcode'    => '/^[1-9]\d{5}$/',
            'areacode'   => '/^0\d{2,3}$/',
            'acount'     => '/^[a-zA-Z][a-zA-Z0-9_\.]{4,15}$/',

            'alpha'      => '/^[a-zA-Z]+$/',
            'lowercase'  => '|^[a-z]+$|',
            'upercase'   => '|^[A-Z]+$|',
            'alpha_num'  => '/^[a-zA-Z0-9]+$/',
            'chinese'    => '/[\x{4e00}-\x{9fa5}]/u',
            'alpha_num_underline'         => '/^\w+$/',
            'alpha_num_underline_chinese' => '/^[\x{4e00}-\x{9fa5}_a-zA-Z0-9]+$/u',
        ];

        $rtv = function($var){
            if($var === false)
                return false;
            else
                return true;
        };

        if(gettype($arr[$type]) == 'array')
        {

            if(count($arr[$type]) > 1)
            {
                list($filter,$opt) = $arr[$type];
                return $rtv(filter_var($var,$filter,$opt));
            }
            else
            {
                list($filter) = $arr[$type];
                return $rtv(filter_var($var,$filter));
            }
        }
        else
        {
            if(preg_match($arr[$type],$var,$match) == 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }

    public static function random($len = 8, $format = 'ALL', $special='~!@#$%^&*_-+=,.?;:')
    {
        $is_abc = $is_numer = 0;
        $password = $tmp = '';
        switch ($format) {
            case 'ALL':
                $chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
                break;
            case 'CHAR':
                $chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz';
                break;
            case 'NUMBER':
                $chars = '23456789';
                break;
            default :
                $chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
                break;
        }
    
        $chars.=$special;
        mt_srand((double)microtime() * 1000000 * getmypid());
        while (strlen($password) < $len) {
            $tmp = substr($chars, (mt_rand() % strlen($chars)), 1);
            if (($is_numer <> 1 && is_numeric($tmp) && $tmp > 0) || $format == 'CHAR') {
                $is_numer = 1;
            }
            if (($is_abc <> 1 && preg_match('/[a-zA-Z]/', $tmp)) || $format == 'NUMBER') {
                $is_abc = 1;
            }
            $password .= $tmp;
        }
        if ($is_numer <> 1 || $is_abc <> 1 || empty($password)) {
            $password = self::randstr($len, $format);
        }
        return $password;
    }

    //str2arr("单田芳汉语拼love音相关参考资料")
    public static function str2arr($str)
    {
        $arr=array();
        for($i=0;$i<mb_strlen($str);$i++)
        {
            $arr[]=mb_substr($str,$i,1);
        }
        return $arr;
    }
}
