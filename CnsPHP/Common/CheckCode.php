<?php
namespace CnsPHP\Common;

/*
//checkcode.php
session_start();
if(isset($_GET['key'])&&isset($_GET['act']))
{
    $key=addslashes($_GET['key']);
    $act=addslashes($_GET['act']);

    if($act == 'check')
    {
       echo $_SESSION[$key];
    }
    elseif($act == 'new')
   {
       include("class/CheckCode.inc.php");
       $code=\QCourse\CheckCode::create(90,25);
       $_SESSION[$key]=$code;
    }
}

//checkcode.html
<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
<script>
$(function(){
    $("input").click(function(){
        $.get("checkcode.php?key=logcode&act=check",function(code){
        if($("input").val() == code) 
            alert("ok");
        else
            alert("false");
        })
    });

    $("img").click(function(){
        $(this).attr("src","checkcode.php?key=logcode&act=check");
    });
});
</script>
<input type="text" value="" ><img src="checkcode.php?act=new&key=logcode" style="cursor:hand" >
*/
class CheckCode {
    public static function create($width = 100, $height = 50, $num = 4, $type = 'jpeg') {
        $img = imagecreate($width, $height);
        $string = '';
        for ($i = 0; $i < $num; $i++) {
            $rand = mt_rand(0, 2);
            switch ($rand) {
                case 0:
                    $ascii = mt_rand(48, 57); //0-9
                    break;
                case 1:
                    $ascii = mt_rand(65, 90); //A-Z
                    break;

                case 2:
                    $ascii = mt_rand(97, 122); //a-z
                    break;
            }
            $string .= sprintf('%c', $ascii);
        }

        //背景颜色
        imagefilledrectangle($img, 0, 0, $width, $height, self::randBg($img));

        //画干扰元素
        for ($i = 0; $i < 50; $i++) {
            imagesetpixel($img, mt_rand(0, $width), mt_rand(0, $height), self::randPix($img));
        }

        //写字
        for ($i = 0; $i < $num; $i++) {
            $x = floor($width / $num) * $i + 2;
            $y = mt_rand(0, $height - 15);

            imagechar($img, 5, $x, $y, $string[$i], self::randPix($img));
        }

        $func = 'image' . $type;
        $header = 'Content-type:image/' . $type;

        if (function_exists($func)) {
            header($header);
            $func($img);
        } else {
            echo '图片类型不支持';
        }
        imagedestroy($img);
        return $string;
    }

    //浅色的背景
    private static function randBg($img) {
        return imagecolorallocate($img, mt_rand(130, 255), mt_rand(130, 255), mt_rand(130, 255));
    }

    //深色的字或者点这些干 扰元素
    private static function randPix($img) {
        return imagecolorallocate($img, mt_rand(0, 120), mt_rand(0, 120), mt_rand(0, 120));
    }
}
