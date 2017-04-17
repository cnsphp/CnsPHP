<?php
namespace CnsPHP;

class CnsDB {
     public $conn = null;

     public $error_code=0;
     public $error_msg="";
     public $lastInsertId=0;
     public $affectedRows=0;
     public $rows=array();

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
         $this->rows=array();
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
             return true;
         } catch(PDOException $e){
             $this->error(1,"Database connect error");
             return false;
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
             if(preg_match('/^select\s+/si', $sql))
             {
                 return $stmt;
                 //$this->rows=$stmt->fetchAll();            
             }
             else if(preg_match('/^insert\s+/si',$sql))
             {
                 $this->lastInsertId=$this->conn->lastInsertId();
                 $this->affectedRows=$stmt->rowCount();
             }
             else if(preg_match('/^delete\s+/si', $sql))
             {
                 $this->affectedRows=$stmt->rowCount();
             }
             else if(preg_match('/^update\s+/si', $sql))
             {
                 $this->affectedRows=$stmt->rowCount();
             }
             else if(preg_match('/^create\s+/si', $sql))
             {
             }

             $this->error(0,'');
             return true;
         }
         else
         {
             $this->error($stmt->errorCode(),$stmt->errorInfo()[2]);
             return false;
         }
     }
}

class Model extends CnsDB {
    function __construct(){
        $this->db->conn(); 
    }

    function tableName($tableName=""){
        if(strlen(trim($tableName)) >= 0)
            $this->tableName = $tableName;
        return $this;
    }

    function primaryKey($id=""){
        if(strlen($trim($id) >= 0 )
            $this->id=$id;
        return $this;
    }

    function getOne($sql,$arr){
        $this->db->query($sql,$arr)->fetch();
    }

    function getAll($sql,$arr) {
        $this->db->query($sql,$arr)->fetchAll();
    }

    function insert($sql,$arr){
        return $this->db->query($sql,$arr);
    }

    function del($sql,$arr){
        return $this->db->query($sql,$arr);
    }

    function update($sql,$arr){
        return $this->db->query($sql,$arr);
    }

    function create($sql,$arr){
        return $this->db->query($sql,$arr);
    }
}
