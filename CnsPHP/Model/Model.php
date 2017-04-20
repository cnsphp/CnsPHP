<?php
namespace CnsPHP\Model;

class Model extends CnsDB {
    public static $tableName = '';

    private static function init()
    {
        if(self::$tableName=="")
            self::$tableName=strtolower(basename(str_replace('\\','/',get_called_class())));
        self::conn();
    }

    public static function table($tableName=""){
        if(strlen(trim($tableName)) > 0)
            self::$tableName = $tableName;
        return get_called_class();
    }

    public static function getOne($arr=[],$sql="") {
        self::init();
        if(strlen($sql) == 0){
            $sql = "select * from ".self::$tableName; 
            $sqlsub="";
            foreach($arr as $k=>$v){
                if(strlen($sqlsub)>0)
                    $sqlsub.=" and $k=:$k";
                else
                    $sqlsub.=" $k=:$k"; 
            }
            if(strlen($sqlsub)>0)
                $sql .= " where $sqlsub";
        }

        $query=self::query($sql,$arr);
        if($query)
            return $query->fetch();
        else
            return [];
    }

    public static function getAll($arr=[],$sql="") {
        self::init();

        if(strlen($sql) == 0){
            $sql = "select * from ".self::$tableName; 
            $sqlsub="";
            foreach($arr as $k=>$v){
                if(strlen($sqlsub)>0)
                    $sqlsub.=" and $k=:$k";
                else
                    $sqlsub.=" $k=:$k"; 
            }
            if(strlen($sqlsub)>0)
                $sql .= " where $sqlsub";
        }

        $query=self::query($sql,$arr);
        if($query)
            return $query->fetchAll();
        else
            return [];
    }

    public static function insert($arr,$sql=""){
        self::init();
        if(self::$tableName=="")
            self::$tableName=strtolower(str_replace('Controller','',get_called_class()));

        if(strlen($sql) == 0){
            $sql = "insert into ".self::$tableName." set "; 
            $sqlsub="";
            foreach($arr as $k=>$v){
                if(strlen($sqlsub)>0)
                    $sqlsub.=" , $k=:$k";
                else
                    $sqlsub.=" $k=:$k"; 
            }
            if(strlen($sqlsub)>0)
            {
                $sql .= " $sqlsub";
                return self::query($sql,$arr);
            }
            else
            {
                return false;
            }
        }
        else
            return self::query($sql,$arr);
    }

    public static function delete($arr,$sql=""){
        self::init();
        if(strlen($sql) == 0){
            $sql = "delete from ".self::$tableName; 
            $sqlsub="";
            foreach($arr as $k=>$v){
                if(strlen($sqlsub)>0)
                    $sqlsub.=" and $k=:$k";
                else
                    $sqlsub.=" where $k=:$k"; 
            }

            if(strlen($sqlsub)>0)
            {
                $sql .= " $sqlsub";
                return self::query($sql,$arr);
            }
            else
            {
                return false;
            }
        }
        else
            return self::query($sql,$arr);
    }

    public static function update($arr,$arr2=[],$sql=""){
        self::init();
        if(strlen($sql) == 0){
            $sql = "update ".self::$tableName." set "; 
            $sqlsub="";
            foreach($arr as $k=>$v){
                if(strlen($sqlsub)>0)
                    $sqlsub.=" , $k=:$k";
                else
                    $sqlsub=" $k=:$k"; 
            }

            $sqlsub2="";

            $keys=array_keys($arr);
            $keys2=array_keys($arr2);
            $arr_inter = array_intersect($keys,$keys2);

            foreach($arr2 as $k=>$v){
                if(strlen($sqlsub2)>0)
                    $sqlsub2.=" and $k=:".(in_array($k,$arr_inter)?$k.'_new_intersect':$k);
                else
                    $sqlsub2=" where $k=:".(in_array($k,$arr_inter)?$k.'_new_intersect':$k); 
            }

            if(strlen($sqlsub)>0)
            {
                $sql .= " $sqlsub $sqlsub2";
                if(count($arr_inter)==0)
                    return self::query($sql,array_merge($arr,$arr2));
                else
                {
                    $arr3=[];
                    foreach($arr2 as $k=>$v)
                    {                         
                        $arr3[(in_array($k,$arr_inter)?$k.'_new_intersect':$k)]=$v;
                    }
                    return self::query($sql,array_merge($arr,$arr3));
                }
            }
            else
            {
                return false;
            }
        }
        else
            return self::query($sql,array_merge($arr,$arr2));
    }
}

class CnsDB {
    public static $conn = null;

    public static $error_code=0;
    public static $error_msg="";
    public static $lastInsertId=0;
    public static $affectedRows=0;

    private static function error($code,$msg) {
        self::$error_code=$code;
        self::$error_msg=$msg;
    }

    private static function init() {
        self::$error_code=0;
        self::$error_msg='';
        self::$lastInsertId=0;
        self::$affectedRows=0;
        return get_called_class();
    }

    //return true / false
    public static function conn($host="",$dbname="",$user="",$pass="",$port=3306) {
        self::init();
        try {
            if($host=="" && $dbname=="" && $user=="" && $pass==""){
                $host   = $GLOBALS['CnsPHP_db_host'];
                $dbname = $GLOBALS['CnsPHP_db_name'];
                $port   = $GLOBALS['CnsPHP_db_port'];
                $user   = $GLOBALS['CnsPHP_db_user'];
                $pass   = $GLOBALS['CnsPHP_db_pass'];
            }
            self::$conn = new \PDO('mysql:host='.$host.';port='.$port.';dbname='.$dbname,$user,$pass) or die(get_called_class().':'.__METHOD__);
            return get_called_class();
            //return true;
        } catch(PDOException $e){
            self::error(1,"Database connect error");
            return null;
            //return false;
        }
    }

    //$sql = 'SELECT name, colour, calories  FROM fruit WHERE calories < :calories AND colour = :colour';
    //$arr = [':calories' => $calories, ':colour' => $colour];

    // return true/false
    public static function query($sql,$arr=array()) {
        self::init();
        $sql=trim($sql);
        $stmt = (self::$conn)->prepare($sql);
        $result=$stmt->execute($arr);

        if($stmt->errorCode()=="00000")
        {
            self::error(0,'');
            if(preg_match('/^select\s+/si', $sql))
            {
                return $stmt;
            }
            else if(preg_match('/^insert\s+/si',$sql))
            {
                self::$lastInsertId=self::$conn->lastInsertId();
                self::$affectedRows=$stmt->rowCount();
                return get_called_class();
            }
            else if(preg_match('/^delete\s+/si', $sql))
            {
                self::$affectedRows=$stmt->rowCount();
                return get_called_class();
            }
            else if(preg_match('/^update\s+/si', $sql))
            {
                self::$affectedRows=$stmt->rowCount();
                return get_called_class();
            }
            else if(preg_match('/^create\s+/si', $sql))
            {
                return get_called_class();
            }
        }
        else
        {
            self::error($stmt->errorCode(),$stmt->errorInfo()[2]);
            return false;
        }
    }
}
