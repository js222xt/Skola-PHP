<?php 

namespace model;

class LoginModel{
	/** @var String containing the Username */
	private static $userName = "Admin"; 
	/** @var String containing the Password */
	private static $password = "Password";
	/** @var Bool if user already is logged in */
	private $alreadyLoggedin = false;
	/** @var String */
	private static $saltString = "CookieMonster";
	
	/**
	 * @param String $inputuserName
	 * @param String $inputPassword
	 * @return bool Return true if username and password are correct 
	 */
	public function checkAuthentication($inputuserName,$inputPassword){
		if($inputuserName == self::$userName && 
		   $inputPassword == self::$password ||
		   $inputuserName == self::$userName && 
		   $inputPassword == $this->getEncryptedString(self::$password)){
		   		$this->alreadyLoggedin = true;
				return true;
		}
		return false;
	}
	
	/**
	 * @param String $string
	 * @Return String that is encrypted with md5 + salt string
	 */
	public function GetEncryptedString($string){
		return md5($string . self::$saltString);
	}
}
