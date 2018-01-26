<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 2017/5/25
 * Time: 10:10
 */

namespace Cnsyao\CnsPHP\Lib\Common;
//use CnsPHP\Common\File;

class Str
{
   public static function c() {
      return $GLOBALS['config'];
   }

    /**
     * @param $s
     */
    public static function echoln($s)
    {
        echo "$s\n";
    }

    public static function msg($code = 1, $msg = '', $data = array(), $token = '')
    {
        return json_encode(["code" => $code, "msg" => $msg, "data" => $data, "token" => $token]);
    }


    public static function caseCade($str, $arrPos)
    {
        $newstr='';
        for($i=0;$i<strlen($str);$i++) {
            $c = $str[$i];
            if (in_array($i, $arrPos)) {
                if (preg_match('/[a-z]/', $c) == 1) {
                    $c = chr(ord($c)-32);
                } else if (preg_match('/[A-Z]/', $c) == 1) {
                    $c = chr(ord($c)+32);
                }
            }
            $newstr.=$c;
        }
        return $newstr;
    }

    /**
     * var_dump(valid(11));                     11
     * var_dump(valid(11,23));                false
     * var_dump(valid(11,false,99));       11
     * var_dump(valid(11,8,99));            11
     * var_dump(valid(11,28,99));          false
     */
    public static function validIint($val, $min = false, $max = false)
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
     * @param $arr
     * @return bool
     *
     * @usage Str::validate(['"xxxx", ''int'])
     *              Str::validate(['"xxxx", ''other', ''/^\d+$/' ])
     */
    public static function validate($arr)
    {
        if (!is_array($arr)) {
            return false;
        }

        $items = count($arr);
        if ($items != 2 && $items != 3) {
            return false;
        }

        $val = $arr[0];
        $type = $arr[1];

        if (count($arr) == 3 && $arr[1] == 'other') {
            $regex = $arr[2];
        }

        $arrTypes = [
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
            if ($var === false) {
                return false;
            } else {
                return true;
            }
        };

        if ($type == 'other') {
            if (preg_match($regex, $val, $match) == 1) {
                return true;
            }
            return false;
        } else if (is_array($arrTypes[$type])) {
            //[FILTER_VALIDATE_INT]
            if (count($arrTypes[$type]) == 1) {
                list($filter) = $arrTypes[$type];
                return $rtv(filter_var($val, $filter));
            }
            // [FILTER_VALIDATE_INT, ['flags' => FILTER_FLAG_ALLOW_OCTAL]]
            list($filter, $opt) = $arrTypes[$type];
            return $rtv(filter_var($val, $filter, $opt));
        } // ' /^[a-zA-Z]+$/',
        else if (is_string($arrTypes[$type])) {
            if (preg_match($arrTypes[$type], $val, $match) == 0) {
                return false;
            }
            return true;
        }
    }

    /**
     * @param int $len
     * @param string $format
     * @param string $special
     * @return string
     */
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

    /**
     *
     *  $s = '12B456A890';
     * $hunxiao = PassMixed($s); // 1SBn56A890
     * $fan_hunxiao = PassMixed($hunxiao); // 1SBn56A890
     *
     * @param $hash
     * @param array $arr_positions
     * @param array $arr_chars
     * @return mixed
     */

    public static function passMixed($hash, $arr_positions = [1, 3, 10, 20, 30, 44, 55, 80, 99], $arr_chars = ['a', 'Z', 'K', 'g', 'X', 'n', '9', 'P', 'j'])
    {
        $len = strlen($hash);

        for ($i = 0; $i < count($arr_positions); $i++) {
            $pos = $arr_positions[$i];
            if ($pos < $len) {
                $hash = (substr_replace($hash, ($hash[$pos]) ^ ($arr_chars[$i]), $pos, 1));
            } else {
                break;
            }
        }
        return $hash;
    }


    /**
     * 加密与解密
     * $enc = encrypt($pass,'E','iloveyou');
     * $x=encrypt($enc,'D','iloveyou');
     *
     * if($pass==$x){
     *   echo "enced: ".$enc;
     *   echo "\n";
     * }
     */
    function encrypt($string, $operation, $key = '')
    {
        $key = md5($key);
        $key_length = strlen($key);
        $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', ",", base64_encode($result));
        }
    }


    /**
     * @param $s
     * @return array
     *
     * @usage str2arr("单田芳汉语拼love音相关参考资料")
     */
    public static function str2arr($s)
    {
        $arr = array();
        for ($i = 0, $len = mb_strlen($s); $i < $len; $i++) {
            $arr[] = mb_substr($s, $i, 1);
        }
        return $arr;
    }
}

