<?php
namespace CnsPHP\Common;

class Str {
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

    public static function echoln($msg) {
        echo "$msg\n";
    }

    public static function randstr($len = 8, $format = 'ALL', $special='~!@#$%^&*_-+=,.?;:')
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
}
