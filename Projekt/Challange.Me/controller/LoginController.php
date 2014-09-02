<?php

namespace controller;

class LoginController{
	/** @var \model\LoginModel */
	private $loginModel;
	/** @var \model\db\ApplicationDAL */
	private $applicationDAL;
	/** @var \view\ApplicationView */
	private $applicationView;	
	/** @var Array containing user information */
	private $loggedInUser;
	/** @var Bool */
	private $isLoggedInWithPost = false;
	/** @var Bool */
	private $isLoggedInWithCookies = false;
	/** @var Bool */
	private $isLoggedInWithSession = false;
	
	public function __construct($appDAL, $appView){
		$this->loginModel = new \model\LoginModel();
		$this->applicationView = $appView;
		$this->applicationDAL = $appDAL;
	}
	
	/**
	 * @param String $username
	 * @param String $password
	 * @return Bool
	 */
	public function SuccessfullLogin($username,$password){

		//@TODO: Add hash		
		// Hash password
		$hPass = $this->loginModel->GetEncryptedString($password);
		
		// Check Database		
		$user =  $this->applicationDAL->LoginUser($username, $password);

		// Can only be one user
		if(count($user) == 1){
			// Save user
			$this->loggedInUser = $user;
			
			return true;
		}
		else {
			return false;
		}
	}
	
		
	/**
	 * Check if user is logged in or not.
	 */
	public function IsUserLoggedIn(){
		if ($this->isLoggedInWithPost || 
		$this->isLoggedInWithSession ||
		$this->isLoggedInWithCookies) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * @return Array with the current user information
	 */
	public function GetLoggedInUser(){
		return $this->loggedInUser;
	}
	
	
	public function UserWantsToLogout(){
		return $this->applicationView->userWantsToLogout();	
	}

	
	/**
	 * @return Bool
	 */
	public function TryLoginWithSession(){
		$isLoggedInWithSession = false;		
		if ($this->applicationView->validateSession()) {
			$username = $this->applicationView->getSessionUsername();
			$password = $this->applicationView->getSessionPassword();
			$isLoggedInWithSession = $this->successfullLogin(
									 $username,$password);
		}
		return $isLoggedInWithSession;
	}	
	
	/**
	 * @return Bool
	 */
	public function TryLoginWithCookies(){
		$isLoggedInWithCookies = false;		
		if ($this->applicationView->validateCookies()) {
			$username = $this->applicationView->getCookieUsername();
			$password = $this->applicationView->getCookiePassword();
			$isLoggedInWithCookies = $this->successfullLoginWithCookies(
									 $username,$password);
		}
		return $isLoggedInWithCookies;
	}
	
	/**
	 * @return Bool
	 */
	public function TryLoginWithPost(){
		$isLoggedInWithPost = false;		
		$username = $this->applicationView->getPostUsername();
		$password = $this->applicationView->getPostPassword();
		$this->applicationView->setInputUsernamePost();
		$isLoggedInWithPost = $this->successfullLogin($username,$password);
		if (!$isLoggedInWithPost) {
			$this->applicationView->setInputErrors();
		}
		return $isLoggedInWithPost;
	}
	
	public function TryLoginAfterRegister($username, $password){
		$isLoggedIn = $this->successfullLogin($username,$password);
	}
	
	/**
	 * @param String $username
	 * @param String $password
	 * @return Bool
	 */
	public function SuccessfullLoginWithCookies($username,$password){
		return $this->loginModel->checkAuthentication($username,$password);
	}			
	
	
	public function LoggedInWithPost(){
		$this->applicationView->setSession();
		$this->applicationView->setLoggedInWithPostMessage();
		$this->applicationView->setUsernamePost();
	}
	

	public function LoggedInWithSession(){
		$this->applicationView->setUsernameSession();
	}	

	
	public function LoggedInWithCookies(){
		$this->applicationView->setLoggedInWithCookieMessage();
		$this->applicationView->setUsernameCookie();
		$this->applicationView->setSessionWithCookies();
	}		
	
	/**
	 * @return String HTML
	 */
	public function InitializeLoggedIn(){
		if ($this->applicationView->userWantsToBeRemembered()) {
			$this->applicationView->setCookies();
			$this->applicationView->setRemeberMessage();
		}
		$this->applicationView->setLoggedInUserMessage($this->loggedInUser[0]['ID']);
		$html = $this->applicationView->getLoggedInHTML();
		return $html;
	}
	
	/**
	 * @return String HTML
	 */
	public function InitializeLoggedOut(){
		if ($this->userWantsTologout()) {
				if($this->applicationView->logoutSessionExists()){
					$this->applicationView->setLoggedOutSuccessMessage();
				}
				$this->applicationView->destroySession();
				$this->applicationView->destroyCookies();
		}
		$html = $this->applicationView->getNotLoggedInHTML();
		return $html;
	}
	
	public function CheckLoginEvents(){
		//==================== LOGIN ===================//
		
		$userWantsTologout = $this->UserWantsToLogout();
		
		if($this->applicationView->UserHaveSession() &&
		   !$userWantsTologout){
		   	$this->UserHaveSession();
		}		
		else if($this->applicationView->UserHaveCookies() &&
		   !$userWantsTologout){
			$this->UserHaveCookies();
		}
		
		if ($this->applicationView->UserHavePostedSomething()&&
		   !$userWantsTologout) {
			$this->UserHavePostedSomething();
		}
	}
	
	/**
	 * user have session. Check if user can login with session.
	 */
	public function UserHaveSession(){
		$this->isLoggedInWithSession = $this->TryLoginWithSession();
		if ($this->isLoggedInWithSession) {
			$this->LoggedInWithSession();
		}
	}
	
	/**
	 * user have cookies. Check if user can login with cookies.
	 */
	public function UserHaveCookies(){
		$this->isLoggedInWithCookies = $this->TryLoginWithCookies();
		if ($this->isLoggedInWithCookies) {
			$this->LoggedInWithCookies();
		}
	}
	
	/**
	 * user have posted. Check if user can login with the posted data.
	 */
	public function UserHavePostedSomething(){
		$this->isLoggedInWithPost = $this->TryLoginWithPost();
		if ($this->isLoggedInWithPost) {
			$this->LoggedInWithPost();
		}
	}
}