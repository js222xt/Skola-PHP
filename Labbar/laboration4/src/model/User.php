<?php

namespace model;

/**
 * Should contain code that validates credentials. 
 * For example password length.
 */
class User {

	private $userName;
	private $userPassword;
	
	/**
	 * @param string $userName
	 * @param string $userPassword
	 */
	public function __construct($userName, $userPassword) {
		$this->userName = $userName;
		$this->userPassword = $userPassword;
	}

	/**
	 * @return string $userName
	 */
	public function getUserName() {
		return $this->userName;
	}

	/**
	 * @return string $userPassword
	 */
	public function getUserPassword() {
		return $this->userPassword;
	}
}