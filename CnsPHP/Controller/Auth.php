<?php

namespace CnsPHP\Controller;

class Auth {

    /**
    * @return string length of 60, e.x. $2y$12$d7146232d10f8596bb5d2umsgqXn5dQDPorx7fmKQEyIXhZMgNNPC
    */
	public static function passwd($passwd) {
		$options = [
			'cost' => 12
		];

		return  password_hash($passwd, PASSWORD_DEFAULT, $options);
	}

    /**
    * @return boolean  true/false
    */
	public static function passwd_verify($passwd,$hash) {
		return password_verify($passwd, $hash);
	}


   public static function is_login()
   {
       if(isset($_SESSION['auth_login_in']) && $_SESSION['auth_login_in'] === true)
           return true;
       else
           return false;
   }
}
