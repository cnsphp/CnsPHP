<?php
namespace CnsPHP\Common;
class File {

    //upload($_FILES['file'],$_SERVER['DOCUMENT_ROOT']."/upload/xxxx")
    // $types = array(
    //      "application/gnutar",
    //      "application/mspowerpoint",
    //      "application/msword", 
    //      "application/powerpoint",
    //      "application/rar",
    //      "application/x-bzip2",
    //      "application/x-excel",
    //      "application/x-gzip",
    //      "application/x-rar",
    //      "application/x-shockwave-flash",
    //      "application/x-zip",
    //      "application/zip",
    //      "image/gif",
    //      "image/jpeg",
    //      "image/jpg",
    //      "image/pjpeg",
    //      "image/png",
    //      "image/x-png",
    //      "multipart/x-gzip",
    //      "multipart/x-zip",
    // );
    //
    // include("class/File.inc.php");
    // $exts = array("gif","jpeg","jpg","png","bmp","ppt","pptx","zip","rar","tar","gz","bz2","docx","xls");
    // $exts = array("gif","jpeg","jpg","png","bmp","ppt","pptx","zip","rar","tar","gz","bz2","docx","xls");
    // \CnsPHP\File::upload($_FILES['file'],$_SERVER['DOCUMENT_ROOT'].'/upload/xxx',$types,$exts,2048000);
    //
    // <form action="test.php" method="post" enctype="multipart/form-data">
    //     <label for="file">文件名：</label>
    //     <input type="file" name="file" id="file"><br>
    //     <input type="submit" name="submit" value="提交">
    // </form>
    //

    public static function upload($upfiles,$dst,$types,$exts,$maxsize=204800) {
        $info = pathinfo($upfiles["name"]);
        $extension = $info['extension'];
        $dst=$dst.'.'.$extension;

        if(in_array($upfiles["type"],$types) && in_array($extension, $exts) && ($upfiles["size"] < $maxsize)  )
        {
            if ($upfiles["error"] > 0)
            {
                echo "错误：: " . $upfiles["error"] . "<br>";
            }
            else
            {
                echo "上传文件名: " . $upfiles["name"] . "<br>";
                echo "文件类型: " . $upfiles["type"] . "<br>";
                echo "文件大小: " . ($upfiles["size"] / 1024) . " kB<br>";
                echo "文件临时存储的位置: " . $upfiles["tmp_name"];
            }

            if (file_exists($dst))
            {
                echo "$dst 文件已经存在";
            }
            else
            {
                move_uploaded_file($upfiles["tmp_name"], $dst);
                echo "文件存储在: $dst";
                return true;
            }
        }
        else
        {
            echo "非法的文件格式";
        }
        return false;
    }

    public static function log($file,$msg){
        file_put_contents($file,$msg."\n",FILE_APPEND | LOCK_EX);
    }
}
