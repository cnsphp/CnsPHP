<?php
namespace CnsPHP\Common;

/*
<?php
//...
class UserController extends Controller {

    public static function Login($args=[]){
       if(!isset($_SESSION['auth_login_check_code']) || $_SESSION['auth_login_check_code'] != $_POST['checkcode'])
       {
           return self::msg(2,'invalid check code');
       }
       else
       {
           $_SESSION['auth_login_check_code']='';
       }

       //...
    }

    public static function LoginCheckcode($args=[]){
       $act = $args['act'];

       if($act == 'new'){
          $_SESSION['auth_login_check_code']=CheckCode::create(90,25);
       }
    }
}
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
