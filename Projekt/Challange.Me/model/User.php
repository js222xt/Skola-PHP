<?php

namespace model;

class User{
	
	/** @var int User ID*/
	private $ID;
	/** @var String User username*/
	private $username;
	/** @var String User password*/
	private $password;
	/** @var String User e-mail*/
	private $email;
	/** @var String User first name*/
	private $fName;
	/** @var String User last name*/
	private $lName;
	/** @var int User ammount of challenge points*/
	private $challengePoints;
	/** @var bool User is admin*/
	private $isAdmin;
	/** @var bool User is banned*/
	private $isBanned;
	
	public function __construct($id, $uname, $pass , $email , $fname, $lname, $chp, $isAdmin, $isBanned){
		$this->ID = $id;
		$this->userName = $uname;
		$this->password = $pass;
		$this->email = $email;
		$this->fName = $fname;
		$this->lName = $lname;
		$this->challengePoints = $chp;
		$this->isAdmin = $isAdmin;
		$this->isBanned = $isBanned;
	}
	
	// GETTERS //
	public function GetID(){
		return $this->ID;
	}
	
	public function GetUsername(){
		return $this->userName;
	}
	
	public function GetPassword(){
		return $this->password;
	}
	
	public function GetEmail(){
		return $this->email;
	}
	
	public function GetFirstname(){
		return $this->fName;
	}
	
	public function GetLastname(){
		return $this->lName;
	}
	
	public function GetChallengePoints(){
		return $this->challengePoints;
	}
	
	public function GetIsAdmin(){
		return $this->isAdmin;
	}
	
	public function GetIsBanned(){
		return $this->isBanned;
	}
	
}
