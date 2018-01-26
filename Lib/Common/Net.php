<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 2017/5/25
 * Time: 10:32
 */

namespace Cnsyao\CnsPHP\Lib\Common;


class Net
{
    /**
     * @return string
     */
    public static function clientip()
    {
        if (isset($_SERVER['REMOTE_ADDR']))
            return $_SERVER['REMOTE_ADDR'];
        return '';
    }

    /**
     * @param  $data   string  "a=1&b=2&c=3"
     * @return  array  [$header,$content]
     */
    public static function curl($url, $data = null, $get_header = false)
    {
        $info = parse_url($url);

        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
        $options = array(
            CURLOPT_VERBOSE => true,
            CURLOPT_USERAGENT => $user_agent,
            CURLOPT_COOKIEFILE => "cookie.txt",
            CURLOPT_COOKIEJAR => "cookie.txt",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => $get_header,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_ENCODING => "gzip",
            CURLOPT_HTTPHEADER => array(
                'Origin: ' . $info['scheme'] . '://' . $info['host'] . (isset($info['port']) ? ':' . $info['port'] : ''),
                'Accept-Encoding: gzip, deflate, br',
                'Accept-Language: zh-CN,zh;q=0.8',
                'Upgrade-Insecure-Requests: 1',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,**;q=0.8',
                'Content-Type: application/x-www-form-urlencoded',
                'Cache-Control: max-age=0',
                'Connection: keep-alive'
            )
        );

        if ($data != null) {
            $options[CURLOPT_CUSTOMREQUEST] = "POST";
            $options[CURLOPT_POST] = true;
            if (gettype($data) == 'string')
                $options[CURLOPT_POSTFIELDS] = $data;
            else if (gettype($data) == 'array') {
                $options[CURLOPT_POSTFIELDS] = http_build_query($data);
            }
        } else {
            $options[CURLOPT_CUSTOMREQUEST] = "GET";
            $options[CURLOPT_POST] = false;
        }
//var_dump($options);
        $ch = curl_init($url);

        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);

        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);
        if ($errmsg) {
            //Str::echoln("$errmsg $err");
            return false;
        }

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        return ['header' => $header, 'content' => str_replace("\r", '', $content)];
    }

    public static function scheme(){
        return ((!empty($_SERVER['HTTPS']) && @$_SERVER['HTTPS'] != 'off') || @$_SERVER['SERVER_PORT'] == 443 || @$_SERVER['HTTP_X_FORWARDED_PORT'] == 443) ? "https://" : "http://";
    }

    public static function host($with_port=false){
        if($with_port)
            return Net::scheme().$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'];
        else
            return Net::scheme().$_SERVER['HTTP_HOST'];
    }

    public static function redirect($url,$msg="",$seconds=0){
        $s='';
        if($seconds) {
            $s = " {$seconds}秒后返回";
        }
        if($url == -1) {
            $url = " window.history.back();";
        }
        else{
                $url="window.location = \"$url\"";
        }
echo <<<_HTML_
<span id="RedirectMsg">{$msg}{$s}</span>
<script>
var count = 5;
setInterval(function(){
    count--;
    
    document.getElementById('RedirectMsg').innerHTML = document.getElementById('RedirectMsg').innerHTML.replace(/\d+秒/,count+'秒');
    if (count == 0) {
        {$url}
    }
},1000);
</script>
_HTML_;
    }
}
