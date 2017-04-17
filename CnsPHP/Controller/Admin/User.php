<?php
namespace CnsPHP;

class User extends Controller {
     function __construct(){
         parent::__construct();
     }

     function Info($args=[]) {
        //var_dump($_POST);
        //var_dump($args);
        //var_dump($this->view);
		
		
        // $this->dbClass();
	// $this->db->conn();		
	// or 
        // $db=$this->dbClass();
        // $db->conn();

		
        // $this->fileClass();
   	// $this->file->...
	// or
        // $file=$this->fileClass();
	// $file-> ...

		
        // $this->imgClass();  
	// $this->img->...
	// or
        // $img=$this->imgClass(); 
	// $img->...

		
        // $this->strClass();  
	// $this->str->
	// or
        // $str=$this->imgClass(); 
	// $str->

		
        //var_dump($this);         

		
         //$this->view->assign("title","xxxx");
		 
         //$this->show(); //default is  CnsPHP/html/admin/user/info.html
	 //$this->show($this->rootdir."/tpl/admin/user/info.html");
     }
}
