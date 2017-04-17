<?php
namespace CnsPHP;

class User extends Controller {
     function __construct(){
         parent::__construct();
     }

     function Iframe($args=[]) {
         //var_dump($_POST);
         //var_dump($args);
         //var_dump($this->view);

        // $this->dbClass(); $this->db
        // $db=$this->dbClass();
        //
         $db=$this->dbClass();
         $db->conn();

        // $this->fileClass(); $this->file
        // $file=$this->fileClass();

        // $this->imgClass();  $this->img
        // $img=$this->imgClass(); 

        // $this->strClass();  $this->str
        // $str=$this->imgClass(); 

        //var_dump($this);

         if(true){
             echo json_encode(['code'=>1,'msg'=>'success','data'=>[]]);
         }
         else
         {
             echo json_encode(['code'=>2,'msg'=>'error','data'=>[]]);
         }

         //$this->view->assign("title","xxxx");
         //$this->show();
     }
}
