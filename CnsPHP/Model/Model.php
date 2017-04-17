<?php
namespace CnsPHP;

class Model extends CnsDB {
    public $tableName = '';

    function __construct($tableName=""){
        $this->tableName = $tableName;
        $this->conn(); 
    }

     
    function tableName($tableName=""){
        if(strlen(trim($tableName)) > 0)
            $this->tableName = $tableName;
        return $this;
    }
     

    function getOne($arr=[],$sql="") {
        if(strlen($sql) == 0){
             $sql = "select * from ".$this->tableName; 
             $sqlsub="";
             foreach($arr as $k=>$v){
                if(strlen($sqlsub)>0)
                   $sqlsub.=" , $k=:$k";
                else
                   $sqlsub.=" $k=:$k"; 
             }
             if(strlen($sqlsub)>0)
                 $sql .= " where $sqlsub";
        }

         $query=$this->query($sql,$arr);
         if($query)
             return $query->fetch();
         else
             return [];
    }


    function getAll($arr=[],$sql="") {
        if(strlen($sql) == 0){
             $sql = "select * from ".$this->tableName; 
             $sqlsub="";
             foreach($arr as $k=>$v){
                if(strlen($sqlsub)>0)
                   $sqlsub.=" , $k=:$k";
                else
                   $sqlsub.=" $k=:$k"; 
             }
             if(strlen($sqlsub)>0)
                 $sql .= " where $sqlsub";
        }

         $query=$this->query($sql,$arr);
         if($query)
             return $query->fetchAll();
         else
             return [];
    }

    function insert($arr,$sql=""){
        if(strlen($sql) == 0){
             $sql = "insert into ".$this->tableName." set "; 
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
                 return $this->query($sql,$arr);
             }
             else
             {
                 return false;
             }
        }
        else
           return $this->query($sql,$arr);
    }

    function delete($arr,$sql){
        if(strlen($sql) == 0){
             $sql = "delete from ".$this->tableName; 
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
                 return $this->query($sql,$arr);
             }
             else
             {
                 return false;
             }
        }
        else
           return $this->query($sql,$arr);
    }

    function update($arr,$arr2=[],$sql=""){
        
        if(strlen($sql) == 0){
             $sql = "update ".$this->tableName." set "; 
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
                   $sqlsub2.=" and $k=:".in_array($k,$arr_inter)?$k.'_new_intersect':$k;
                else
                   $sqlsub2=" where $k=:".in_array($k,$arr_inter)?$k.'_new_intersect':$k; 
             }

             if(strlen($sqlsub)>0)
             {
                 $sql .= " $sqlsub $sqlsub2";
                 if(count($arr_inter)==0)
                     return $this->query($sql,array_merge($arr,$arr2));
                 else
                 {
                     $arr3=[];
                     foreach($arr2 as $k=>$v)
                     {                         
                         $arr3[in_array($k,$arr_inter)?$k.'_new_intersect':$k]=$v;
                     }
                     return $this->query($sql,array_merge($arr,$arr3));
                 }
             }
             else
             {
                 return false;
             }
        }
        else
           return $this->query($sql,array_merge($arr,$arr2));
    }
}

class CnsDB {
     public $conn = null;

     public $error_code=0;
     public $error_msg="";
     public $lastInsertId=0;
     public $affectedRows=0;

     function __structure() {
     }

     private function error($code,$msg) {
         $this->error_code=$code;
         $this->error_msg=$msg;
     }

     private function init() {
         $this->error_code=0;
         $this->error_msg='';
         $this->lastInsertId=0;
         $this->affectedRows=0;
         return $this;
     }

     //return true / false
     public function conn($host="",$dbname="",$user="",$pass="",$port=3306) {
         $this->init();
         try {
             if($host=="" && $dbname=="" && $user=="" && $pass==""){
                 $host   = $GLOBALS['CnsPHP_db_host'];
                 $dbname = $GLOBALS['CnsPHP_db_name'];
                 $port   = $GLOBALS['CnsPHP_db_port'];
                 $user   = $GLOBALS['CnsPHP_db_user'];
                 $pass   = $GLOBALS['CnsPHP_db_pass'];
             }
             $this->conn = new \PDO('mysql:host='.$host.';port='.$port.';dbname='.$dbname,$user,$pass) or die(__CLASS__.':'.__METHOD__);
             return $this;
             //return true;
         } catch(PDOException $e){
             $this->error(1,"Database connect error");
             return null;
             //return false;
         }
     }

     //$sql = 'SELECT name, colour, calories  FROM fruit WHERE calories < :calories AND colour = :colour';
     //$arr = [':calories' => $calories, ':colour' => $colour];

     // return true/false
     public function query($sql,$arr=array()) {
         $this->init();
         $sql=trim($sql);
         $stmt = $this->conn->prepare($sql);

         $result=$stmt->execute($arr);

         if($stmt->errorCode()=="00000")
         {
             $this->error(0,'');
             if(preg_match('/^select\s+/si', $sql))
             {
                 return $stmt;
             }
             else if(preg_match('/^insert\s+/si',$sql))
             {
                 $this->lastInsertId=$this->conn->lastInsertId();
                 $this->affectedRows=$stmt->rowCount();
                 return $this;
             }
             else if(preg_match('/^delete\s+/si', $sql))
             {
                 $this->affectedRows=$stmt->rowCount();
                 return $this;
             }
             else if(preg_match('/^update\s+/si', $sql))
             {
                 $this->affectedRows=$stmt->rowCount();
                 return $this;
             }
             else if(preg_match('/^create\s+/si', $sql))
             {
                 return $this;
             }
         }
         else
         {
             $this->error($stmt->errorCode(),$stmt->errorInfo()[2]);
             return false;
         }
     }
}
