<?php

namespace model;

class RegisterModel{

	/** @var \model\db\ApplicationDAL */
	private $applicationDAL;

	public function __construct($appDAL){
		$this->applicationDAL = $appDAL;
	}
	
	/**
	 * @return bool if given username exists in db
	 */
	public function DoesUsernameExists($username){
		return count($this->applicationDAL->GetUser($username)) == 1;
	}
	
	/**
	 * @return bool if username if valid
	 */
	public function IsUsernameValid($username){
		$pattern ='@<([a-z]+)[^>]*(?<!/)>@';
		return preg_match($pattern, $username);
	}
	
	/**
	 * @return true if username is too smal
	 */
	public function IsUsernameTooSmal($username){
		return strlen($username) < 3;
	}
	
	/**
	 * @return true if password is too smal
	 */
	public function IsPasswordTooSmal($pass){
		return strlen($pass) < 6;
	}
	
	/**
	 * @return true if passwords match
	 */
	public function DoesPassordMatch($pass, $repeatpass){
		return $pass === $repeatpass;
	}
	
	/**
	 * Registers an account
	 */
	public function RegisterAccount($uname, $pass){
		$this->applicationDAL->RegisterAccount($uname, md5($pass));
	}

	/**
	 * @return String without tags
	 */
	public function RemoveTags($string){
		return preg_replace("@(>[\s\S]*?<)|(<[\s\S]*?>)@", "", $string);
	}
	
}