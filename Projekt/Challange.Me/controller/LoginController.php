<?php

namespace controller;

class LoginController{
	/** @var \model\LoginModel */
	private $loginModel;
	/** @var \model\db\ApplicationDAL */
	private $applicationDAL;
	/** @var \view\ApplicationView */
	private $applicationView;	
	/** @var User containing user information */
	private $loggedInUser;
	/** @var Bool */
	private $isLoggedInWithPost = false;
	/** @var Bool */
	private $isLoggedInWithCookies = false;
	/** @var Bool */
	private $isLoggedInWithSession = false;
	/** @var Int with challenge point default value */
	private $challengePointsDefault = 0;
	/** @var Boolean if user already is logge in, used with register */
	
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

		// Hash password
		$hPass = $this->loginModel->GetEncryptedString($password);
		
		// Check Database		
		$user =  $this->applicationDAL->LoginUser($username, $hPass);

		// Can only be one user
		if(count($user) == 1){
			// Save user
			$this->loggedInUser = new \model\User($user[0]['ID'],$user[0]['Username'],$user[0]['APassword'],$user[0]['Email'],
												  $user[0]['FName'],$user[0]['LName'],$user[0]['challengePoints'] ,$user[0]['IsAdmin'],$user[0]['Banned']);
			
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
		$this->applicationView->setLoggedInUserMessage($this->loggedInUser->GetID());
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
	
	/**
	 * @return String HTML
	 */
	public function RegisterNewAccountHTML(){
		$html ="";
		// User tried to register?
		if($this->applicationView->UserTriedToRegister()){

			// Get posted info
			$info = $this->applicationView->GetPostedRegisterNewChallengerInfo();


			// Validate
			if(count($info) != 0){
				
				$username = $info[0];
				$password = $info[1];
				$email = $info[2];
				$fName = $info[3];
				$lName = $info[4];
				
				if($this->SQLInjectionCheck($username+$password+$email+$fName+$lName)){							
				
					// Check if username of email exists
					$userFromDB = $this->applicationDAL->GetUserFromUsername($username);
					$emailFromDB = $this->applicationDAL->GetEmailFromEmail($email);
					
					$errorsFound = false;
					
					if(count( $userFromDB ) == 0){
						if(count( $emailFromDB ) == 0){
							if(strlen($username) > 50 || strlen($username) == 0){
								$this->applicationView->UsernameIsInvalid();
								$errorsFound = true;
							}
							if(strlen($password) > 50 || strlen($password) == 0){
								$this->applicationView->PasswordIsInvalid();
								$errorsFound = true;
							}
							if(strlen($email) > 200 || strlen($email) == 0){
								$this->applicationView->EmailIsInvalid();
								$errorsFound = true;
							}
							if(strlen($fName) > 50 || strlen($fName) == 0){
								$this->applicationView->FNameIsInvalid();
								$errorsFound = true;
							}
							if(strlen($lName) > 50 || strlen($lName) == 0){
								$this->applicationView->LNameIsInvalid();
								$errorsFound = true;
							}
						}
						else {
							$this->applicationView->EmailAlreadyExists();
							$errorsFound = true;
						}
					}
					else {
						$this->applicationView->UsernameAlreadyExists();
						$errorsFound = true;
					}
					
					// No errors found
					if(!$errorsFound){
						// Hash password
						$hPass = $this->loginModel->GetEncryptedString($password);
						
						// Add user to DB
						$this->applicationDAL->RegisterAccount($username, $hPass, 
															   $email, $fName,
															   $lName, $this->challengePointsDefault );
						
						$this->applicationView->AddUserCanNowLogin($username);
					}
					else {
						$html .= $this->applicationView->GetRegisterNewAccountHTML();
					}	
				}
				else {
					$html = $this->applicationView->OneDoesNotTryToInjectHTML();
				}
			}
			else {
				$this->applicationView->PostWentWrong();
			}
		}
		else{
			$html .= $this->applicationView->GetRegisterNewAccountHTML();
		}
		
		return $html;
	}

	/**
	 * @var String
	 * @return bool if match
	 */
	private function SQLInjectionCheck($subject){
		$pattern = "/^('(''|[^'])*')|(;)|(%)|(\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b)$/";
						
		return !preg_match($pattern, $subject);
	}
}