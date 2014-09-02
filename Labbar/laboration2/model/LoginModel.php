<?php

namespace model;

class LoginModel{
	

	private $getLoginString = "login";
	private $userName = "Admin"; 
	private $password = "Password";
	private $loggedInUserName = "";
	private static $saltString = "CookieMonster";

	public function SetLoggedInUser($userName){
		$this->loggedInUserName = $userName;
	}
	
	/**
	 * @return String Returns the username
	 */
	public function GetUsername(){
		return $this->loggedInUserName;
	}
	
	/**
	 * @param String $string
	 * @Return String that is encrypted with md5 + salt string
	 */
	public function GetEncryptedString($string){
		return md5($string . self::$saltString);
	}
	
	/**
	 * @param String $inputuserName
	 * @param String $inputPassword
	 * @return bool Return true if username and password are correct 
	 */
	public function CheckUsernameAndPassword($inputuserName,$inputPassword){
		if($inputuserName == $this->userName && 
		   $inputPassword == $this->GetEncryptedString($this->password)){
				return true;
		}
		return false;
	}
}
