<?php
namespace CnsPHP\Common;


class Str 
{
    public static function echoln($msg) 
    {
        echo "$msg\n";
    }

    /**
     * var_dump(valid(11));                     11
     * var_dump(valid(11,23));                false
     * var_dump(valid(11,false,99));       11
     * var_dump(valid(11,8,99));            11
     * var_dump(valid(11,28,99));          false
     */
    public static function valid_int($val, $min = false, $max = false)
    {
        if ($min === false && $max === false)
            return filter_var($val, FILTER_VALIDATE_INT);
        else if ($min !== false)
            return filter_var($val, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min]]);
        else if ($max !== false)
            return filter_var($val, FILTER_VALIDATE_INT, ['options' => ['max_range' => $max]]);
        else
            return filter_var($val, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min, 'max_range' => $max]]);
    }


    /**
     * @param string $mobile
     * @return object * stdClass Object (
     *                                     [mts] => 1501878
     *                                     [province] => 广东
     *                                     [catName] => 中国移动
     *                                     [telString] => 15018788111
     *                                     [areaVid] => 30517
     *                                     [ispVid] => 3236139
     *                                     [carrier] => 广东移动
     *                            )
     *
     * $obj = get_mobile_area('15018788111');
     */
    public static function mobile_area($mobile){
        $url = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=".$mobile."&t=".time();
        $json = mb_convert_encoding(file_get_contents($url), 'utf-8','gbk,gb2312');
        $json = preg_replace(['/\'/','/__GetZoneResult_ =/','/\s+([^\s]+):/sU'],['"','','"\1": '],$json);
        $json=json_decode($json);
        return $json;
    }


    /**
     * var_dump(Str::validate(['"xxxx", ''int']));
     * var_dump(Str::validate(['"xxxx", ''other', ''/^\d+$/' ]));
     */

    public static function validate($arrArg)
    {
        if (!is_array($arrArg) )
            return false;
        $items = count($arrArg);
        if($items != 2 && $items != 3)
            return false;

            $val = $arrArg[0];
            $type=$arrArg[1];
    
        if (count($arrArg)==3 && $arrArg[1] == 'other')
        {
            $regex = $arrArg[2];
        }
        
        $arr = [
            'int' => [FILTER_VALIDATE_INT],
            'oct' => [FILTER_VALIDATE_INT, ['flags' => FILTER_FLAG_ALLOW_OCTAL]],
            'hex' => [FILTER_VALIDATE_INT, ['flags' => FILTER_FLAG_ALLOW_HEX]],
            'float' => [FILTER_VALIDATE_FLOAT],
            'boolean' => [FILTER_VALIDATE_BOOLEAN],
            'number' => '/^\d+$/',
        
            'url' => [FILTER_VALIDATE_URL],
            'email' => [FILTER_VALIDATE_EMAIL],
        
            'mac' => [FILTER_VALIDATE_MAC],
            'ip' => [FILTER_VALIDATE_IP],
            'ip_wan' => [FILTER_VALIDATE_IP, ['flags' => [FILTER_FLAG_NO_PRIV_RANGE, FILTER_FLAG_NO_RES_RANGE]]],
            'ipv4_wan' => [FILTER_VALIDATE_IP, ['flags' => [FILTER_FLAG_IPV4, FILTER_FLAG_NO_PRIV_RANGE, FILTER_FLAG_NO_RES_RANGE]]],
            'ipv6_wan' => [FILTER_VALIDATE_IP, ['flags' => [FILTER_FLAG_IPV6, FILTER_FLAG_NO_PRIV_RANGE, FILTER_FLAG_NO_RES_RANGE]]],
        
            'non-neg' => '/^\d+$/',
            'negative' => '/^-[0-9]*[1-9][0-9]*$/',
        
            'date' => '/^(d{2}|d{4})-((0([1-9]{1}))|(1[1|2]))-(([0-2]([1-9]{1}))|(3[0|1]))$/',
            'html' => '/<(.*)>.*<\/\1>|<(.*) \/>/',
            'qq' => '/^[1-9]*[1-9][0-9]*$/',
            'telephone' => '/^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{8}$/',
            //'mobile' => '/^(13[012356789]|17[01235678]|(14|15|18)[0-9])[0-9]{8}|(134[089])[0-9]{7}$/',
            'mobile' => '/^1[34578]\d{9}$/',
            'zipcode' => '/^[1-9]\d{5}$/',
            'areacode' => '/^0\d{2,3}$/',
            'acount' => '/^[a-zA-Z][a-zA-Z0-9_\.]{4,15}$/',
        
            'alpha' => '/^[a-zA-Z]+$/',
            'lowercase' => '|^[a-z]+$|',
            'upercase' => '|^[A-Z]+$|',
            'alpha_num' => '/^[a-zA-Z0-9]+$/',
            'chinese' => '/[\x{4e00}-\x{9fa5}]/u',
            'alpha_num_underline' => '/^\w+$/',
            'alpha_num_underline_chinese' => '/^[\x{4e00}-\x{9fa5}_a-zA-Z0-9]+$/u',
        
            'other' => ''
        ];
        
        $rtv = function ($var) {
            if ($var === false)
                return false;
            else
                return true;
        };
        
        if ($type == 'other') {
            if (preg_match($regex, $val, $match) == 1) {
                return true;
            }
            return false;
        }
        else if (is_array($arr[$type]))
        {
            //[FILTER_VALIDATE_INT]
            if (count($arr[$type]) == 1)
            {
                list($filter) = $arr[$type];
                return $rtv(filter_var($val, $filter));
            }
            // [FILTER_VALIDATE_INT, ['flags' => FILTER_FLAG_ALLOW_OCTAL]]
            else
            {
                list($filter, $opt) = $arr[$type];
                return $rtv(filter_var($val, $filter, $opt));
            }
        }
        // ' /^[a-zA-Z]+$/',
        else if (is_string($arr[$type]))
        {
            if (preg_match($arr[$type], $val, $match) == 0)
            {
                return false;
            }
            return true;
        }
    }
        
    public static function mymail($to,$subject,$text) {
        $query = http_build_query ([
               'to'=>$to,
               'subject'=>$subject,
               'text'=>$text,
               'key'=>'90.............hp'
        ]);
        
        return exec( "/usr/bin/curl -d '".$query."' http://www.a.com/CnsPHP/Common/CnsMail.php");
    }
        
    public static function random($len = 8, $format = 'ALL', $special = '~@#%_+=,.')
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
    
        $chars .= $special;
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
            $password = self::random($len, $format);
        }
        return $password;
    }

    //str2arr("单田芳汉语拼love音相关参考资料")
    public static function str2arr($str)
    {
        $arr = array();
        for ($i = 0,$len=mb_strlen($str); $i < $len; $i++) {
            $arr[] = mb_substr($str, $i, 1);
        }
        return $arr;
    }
}
