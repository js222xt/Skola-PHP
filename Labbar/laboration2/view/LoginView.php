<?php

namespace view;

class LoginView{
	/** @var \model\LoginModel */
	private $loginModel;
	/** @var \model\Logg */
	private $logg;
	/** @var String containing index in $_POST */
	private static $postUsernameString = "NameOfUser";
	/** @var String containing index in $_POST */
	private static $postPasswordString = "Password";
	/** @var String containing index in $_POST after a post because it changes*/
	private static $postPasswordIndex;
	/** @var String containing given UserName */
	private $inputUserName;
	/** @var String containing given Password */
	private $inputPassword;
	/** @var String containing IP adress used by the user */
	private static $IP = "IP";
	/** @var String containing information about userAgent */
	private static $userAgent = "UserAgent";
	/** @var String containing logout string in $_GET */
	private static $logoutString = "logout";
	/** @var String containing title of the website */
	private static $title;
	/** @var String containing index in $_POST */
	private static $rememberMeChecked = "Checked";
	/** @var String containing errormessages */
	private $errorMessage = "";
	/** @var String containing the string that te user tried to login with */
	private $userNameInput = "";
	/** @var String containing the message if login was successfull */
	private $loggedInMessage = "";
	/** @var String containing index */
	private static $pressedlogoutString = "pressedlogout";
	/** @var String containing index */
	private static $reloadString = "reload";
	/** @var String containing cookie endtime filename */ 
	private static $endTimeFileName = "endtime.txt";
	/** @var Int with time in sec */
	private static $timeInSec = 15;
	
	/*
	 * Initialize a new LoginModel obj.
	 * Set self::$postPasswordIndex
	 */
	public function __construct(){
		try{
			// Set vars that is needed.
			$this->loginModel = new \model\LoginModel();	
			self::$postPasswordIndex = self::$postPasswordString . "_";
			$this->logg = new \model\Logg();
		}
		catch(\Exception $ex){
			$this->logg->WriteErrorMessage($ex);
		}
		
	}
	
	/**
	 * @return bool
	 */
	private function UserHavePosted(){
		return (isset($_POST[self::$postUsernameString]) &&
				isset($_POST[self::$postPasswordIndex]) &&
				$_POST[self::$postUsernameString] != "" &&
				$_POST[self::$postPasswordIndex] != "");
	}
	
	/**
	 * @return bool
	 */
	private function UserHavePostedName(){
		return isset($_POST[self::$postUsernameString]);
	}
	
	
	private function InitializePostVar(){
		if(isset($_POST[self::$postUsernameString]) && 
		   isset($_POST[self::$postPasswordIndex])){
			$this->inputUserName = $_POST[self::$postUsernameString];
			$this->inputPassword = $_POST[self::$postPasswordIndex];	
		}	
	}
	
	/**
	 * @return bool
	 */
	private function UserWantsToBeRemembered(){
		return isset($_POST[self::$rememberMeChecked]);
	}
	
	/**
	 * Sets the message that is shown to the user depending on login method
	 */
	private function SetLoggedInMessage(){
		$loggedInMessage = "Inloggning lyckades";
	 	$loggedInMessageRemember = "Inloggning lyckades och vi kommer 
										ihåg dig nästa gång";
		$loggedInWithCookie = "Inloggningen lyckades via cookies.";
		
		if($this->UserWantsToBeRemembered()){
			$this->loggedInMessage = $loggedInMessageRemember;
			$this->MakeCookie();
			$this->MakeReloadSession();
		}		
		elseif ($this->CheckAuthenticationWithCookies() && !$this->MakeReloadSessionIsSet()) {
			$this->loggedInMessage = $loggedInWithCookie;
			$this->inputUserName = $_COOKIE[self::$postUsernameString];
			$this->inputPassword = $_COOKIE[self::$postPasswordIndex];
			$this->MakeSession();
			$this->MakeReloadSession();
		}
		elseif($this->CheckAuthenticationWithInput()){
			$this->loggedInMessage = $loggedInMessage;
			$this->inputUserName = $_POST[self::$postUsernameString];
			$this->inputPassword = $_POST[self::$postPasswordIndex];
			$this->MakeSession();
			$this->MakeReloadSession();
		}
	}
	
	/**
	 * @return bool
	 */
	private function CheckAuthentication(){
		if ($this->CheckAuthenticationWithCookies()) {
			return true;
		}
		if ($this->CheckAuthenticationWithSession()) {
			return true;
		}
		if ($this->CheckAuthenticationWithInput()) {
			return true;
		}
		return false;
	}
	
	/**
	 * @return bool 
	 */
	private function CheckAuthenticationWithCookies(){
		if ($this->CookieExists() && $this->ValidateCookie()) {
			$this->loginModel->SetLoggedInUser(
			$_COOKIE[self::$postUsernameString]);
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * @return bool 
	 */
	private function CheckAuthenticationWithSession(){
		if ($this->SessionExists() && $this->ValidateSession()){
	 		$this->loginModel->SetLoggedInUser(
	 		$_SESSION[self::$postUsernameString]);
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * @return bool 
	 */
	private function CheckAuthenticationWithInput(){
		if($this->UserHavePosted()){
			$this->InitializePostVar();
			if($this->loginModel->
					  CheckUsernameAndPassword(
					  $this->inputUserName,
					  $this->loginModel->GetEncryptedString(
					  $this->inputPassword))){
				$this->loginModel->SetLoggedInUser(
				                   $this->inputUserName);
				return true;
			}
			return false;
		}
		return false;
	}
	
	/**
	 * @return String HTML
	 */
	public function InitializeForm(){
		$loggedInTitle = "Laboration.Inloggad";
		$notLoggedInTitle = "Laboration. Inte inloggad";
		$this->SetErrorMessage($this->GetInputErrors());
		if($this->UserHavePostedName()){
			$this->userNameInput = $_POST[self::$postUsernameString];
		}
		if(!$this->UserInformationValid()){
			
			// Set title
			self::$title = $notLoggedInTitle;
			
			if ($this->UserHavePosted()) {
				// Logg the unsuccessfull login
				$this->logg->WriteUnsuccessfullLogin($this->inputUserName,
				 							   $this->inputPassword,
				 							   $this->GetDateHTML());	
			}			
			return $this->GetFormHTML();
			$this->errorMessage = $this->GetErrorMessage();
		}
		else {
			$this->SetLoggedInMessage();
			if ($this->UserHavePosted()) {
				// Logg the unsuccessfull login
				$logg = new \model\Logg();
			}
			// Set title
			self::$title = $loggedInTitle;		
			return $this->GetLoggedInHTML();
		}
	}
	
	/**
	 * @return bool
	 */
	private function UserInformationValid(){
		try{
			$cookieErrorString = "Felaktig information i cookie";
			$nowLoggedout = "Du har nu loggat ut";
			if(!$this->CheckAuthentication() ||
			 	isset($_GET[self::$logoutString])) {
				if ($this->ValidateCookie() != true) {
				    $this->SetErrorMessage($cookieErrorString);
					$this->ClearCookie();		
				}
				if ($this->ValidateSession() != true) {
					$this->ClearSession();
				}
				if($this->UserWantsToLogOut()){
					$this->ClearCookie();
					$this->userNameInput = "";
					if($this->UserWantsToLogOut() && isset($_SESSION[self::$pressedlogoutString])){
						if ($_SESSION[self::$pressedlogoutString] === false) {
							$this->SetErrorMessage($nowLoggedout);
							$_SESSION[self::$pressedlogoutString] = true;
						}					
					}
					$this->ClearSession();
				}
				return false;
			}
			else {
				return true;
			}
		}
		catch(\Exception $ex){
			$this->logg->WriteErrorMessage($ex);
		}
	}
	
	/**
	 * @return bool
	 */
	private function UserWantsToLogOut(){
		return isset($_GET[self::$logoutString]);
	}
	
	/**
	 * @param $message String with message
	 */
	private function SetErrorMessage($message){
		$this->errorMessage = $message;
	}
	
	/**
	 * @return String HTML
	 */
	private function GetLoggedInHTML(){		
		$returnString = $this->loggedInMessage;

		return $returnString .= 
			   '<p><a href="?' .
			   self::$logoutString . 
			   '">Logga ut</a></p>';
	}
	
	/**
	 * @return String HTML
	 */
	private function GetFormHTML(){
		$button = "button";
		return '<form action="?login" method="post"
				 enctype="multipart/form-data">
					<fieldset>	
						<legend>
						Login - Skriv in användarnamn och lösenord
						</legend>
						<p>'. $this->GetErrorMessage() .'<p>				
						<label for="UserNameID">Användarnamn :</label>
						<input type="text" size="20" name="' 
						. self::$postUsernameString .
						'" id="UserNameID" value="' .
						$this->userNameInput . '">
						<label for="PasswordID">Lösenord  :</label>
						<input type="password" size="20" name="' .
						self::$postPasswordString .
						' " id="PasswordID" value="">
						<label for="AutologinID">Håll mig inloggad  :</label>
						<input type="checkbox" name="' . self::$rememberMeChecked . '" id="AutologinID">
						<input type="submit" name="' .
						$button . 
						' " value="Logga in">
					</fieldset>
				</form>';
	}
	
	/**
	 * @return String 
	 */
	public function GetDateHTML(){
		$today = getdate();
		$dateString = "";
		
		//Get day of the week
		$dateString .= $this->GetDayOfWeek($today);

		// Check day of the month
		$dateString .= "den " . $today['mday'] . " " . $this->GetMonth($today);
		
		// Check year
		$dateString .= "år " . $today["year"] . ".";
		
		// Adds 0 to seconds and/or minutes hours if length of string == 1 (<10)
		$minutes = $this->GetFixedTimeString($today, "minutes");
		$seconds = $this->GetFixedTimeString($today, "seconds");
		$hour = $this->GetFixedTimeString($today, "hours");
		
		// Checking time
		$dateString .= " Klockan är [" .
						 $hour . ":" .
						 $minutes . ":" . 
						 $seconds . "].";
		return $dateString;
	}
	
	/**
	 *@param getdate() $today
	 *@param String $type
	 *@return String
	 */
	private function GetFixedTimeString($today,$type){
		if(strlen($today[$type]) == 1){
			return "0" . $today[$type];
		}
		else {
			return $today[$type];
		}
	}
	
	/**
	 *@param getdate() $today
	 *@Return String
	 */
	private function GetDayOfWeek($today){
		// Check day of the week
		switch ($today["wday"]) {
			case 1:		
				return "Måndag, ";
			case 2:		
				return "Tisdag, ";
			case 3:		
				return  "Onsdag, ";
			case 4:		
				return "Torsdag, ";
			case 5:		
				return  "Fredag, ";
			case 6:		
				return "Lördag, ";
			case 7:		
				return  "Söndag, ";
		}
	}
	
	/**
	 *@param getdate()  $today
	 *@Return String
	 */
	private function GetMonth($today){
		// Check month
		switch ($today["mon"]) {
			case 1:	
				return " Januari ";
			case 2:	
				return " Februari ";
			case 3:	
				return " Mars ";
			case 4:	
				return " April ";
			case 5:	
				return " Maj ";
			case 6:	
				return" Juni ";
			case 7:	
				return " Juli ";	
			case 8:	
				return" Augusti ";
			case 9:	
				return " September ";	
			case 10:	
				return " Oktober ";
			case 11:	
				return " November ";
			case 12:	
				return " December ";
		}	
	}
	
	/**
	 * @return String title 
	 */
	public function GetTitle(){
		return self::$title;
	}
	
	/**
	 * @return String login status
	 */
	public function GetLoggedInStatus(){
		$notLoggedInStatus = "Ej inloggad";
		if(isset($_GET[self::$logoutString])){
			return 	$notLoggedInStatus;
		}
		elseif($this->CheckAuthenticationWithCookies() ||
			   $this->CheckAuthenticationWithSession() ||
			   $this->CheckAuthenticationWithInput()){
			$loggedInStatus;
			$loggedInStatus = $this->loginModel->GetUsername()
													   . " är inloggad";
			return $loggedInStatus;
		}
		return $notLoggedInStatus;
	}
	
	/**
	 * @return String username
	 */
	private function GetUsername(){
		return $this->loginModel->GetUsername();
	}
	
	/**
	 * @return String
	 */
	private function GetInputErrors(){
		$passMissing = "Lösenord saknas";
		$usernameMissing = "Användarnamn saknas";
		$wrongUserOrPass = "Felaktigt användarnamn och/eller lösenord";
		if($this->UserHavePostedName()){
			if ($_POST[self::$postUsernameString] == "") {
				return $usernameMissing;
			}			
		}
		else {
			return "";
		}
		if(isset($_POST[self::$postPasswordIndex])){
			if ($_POST[self::$postPasswordIndex] == "") {
				return $passMissing;
			}			
		}
		return $wrongUserOrPass;
	}
	
	/**
	 *@return String If something is wrong/missing 
	 */
	private function GetErrorMessage(){
		return $this->errorMessage;
	}	
	
	/*
	 * Make session variables
	 */	
	private function MakeSession(){
		try{
			$_SESSION[self::$postUsernameString] = $this->inputUserName;
			$_SESSION[self::$postPasswordIndex] = $this->inputPassword;
			$_SESSION[self::$IP] = $_SERVER['REMOTE_ADDR'];
			$_SESSION[self::$userAgent] = $_SERVER['HTTP_USER_AGENT'];
			$this->MakeLogoutSession();			
		}
		catch(\Exception $ex){
			$this->logg->WriteErrorMessage($ex);
		}
	}  
	
	/**
	 * Make a logout session
	 */
	private function MakeLogoutSession(){
		$_SESSION[self::$pressedlogoutString] = false;
	}
	
	/**
	 * Make reload session
	 */
	private function MakeReloadSession(){
		$_SESSION[self::$reloadString] = false;
	}
	
	/**
	 * @return bool
	 */
	private function MakeReloadSessionIsSet(){
		return isset($_SESSION[self::$reloadString]);
	}
	
	/*
	* Creates a cookie that exists in 1 min
	*/
	private function MakeCookie(){
		try{
			setcookie(self::$postUsernameString,
	 					    $this->inputUserName,
	 					    time() + intval(self::$timeInSec),
	 					    null,
	 						null,
	 					 	null,
	 					 	true);
			setcookie(self::$postPasswordIndex,
						 	$this->loginModel->GetEncryptedString(
							$this->inputPassword),
						 	time() + intval(self::$timeInSec),						
						 	null,
						 	null,
						 	null,
						 	FALSE); 
			file_put_contents(self::$endTimeFileName, time() + intval(self::$timeInSec));
		}
		catch(\Exception $ex){
			$this->logg->WriteErrorMessage($ex);
		}
	}
	
	/*
	* Clears cookies
	*/
	private function ClearCookie(){
		try{
			setcookie(self::$postUsernameString,"",time() - 3600);
			setcookie(self::$postPasswordIndex,"",time() - 3600); 
		}
		catch(\Exception $ex){
			$this->logg->WriteErrorMessage($ex);
		}
	}
	 
	 /*
	* Clears sessions
	*/
	private function ClearSession(){
		try{
			if($this->SessionExists()){
				session_destroy();	
			}
		}
		catch(\Exception $ex){
			$this->logg->WriteErrorMessage($ex);
		}
	}
	

	 
	/**
	* @return bool true if cookie exists
	*/ 
	private function CookieExists(){
		return isset($_COOKIE[self::$postUsernameString]) &&
			   isset($_COOKIE[self::$postPasswordIndex]);
	}
		 
	/**
	* @return bool true if session exists
	*/ 
	private function SessionExists(){
	return isset($_SESSION[self::$postUsernameString]) &
		   isset($_SESSION[self::$postPasswordIndex]);
	}
	 
	 
	 
	/**
	* @return bool true if cookie information is valid
	*/
	private function ValidateCookie(){
	  	if($this->CookieExists()){
	  		if ($this->loginModel->CheckUsernameAndPassword(
	  					$_COOKIE[self::$postUsernameString],
	  					$_COOKIE[self::$postPasswordIndex])) {
	  			if(time() > file_get_contents(self::$endTimeFileName)){
	  				return false; 	
	  			}
				else {
					return true;
				}
 
			 }	  
			else {
				return false;
			}		
	  	}
		else {
			return true;	
		}
	  }
	  
	  
	  /**
	   * @return bool true if session information is valid
	  */
	private function ValidateSession(){
		if($this->SessionExists()){
		  	if ($this->loginModel->
		  				CheckUsernameAndPassword(
		  				$_SESSION[self::$postUsernameString],
		  				$this->loginModel->GetEncryptedString(
		  				$_SESSION[self::$postPasswordIndex])) &&
		  		 		$_SESSION[self::$IP] == $_SERVER['REMOTE_ADDR'] &&
				 		$_SESSION[self::$userAgent] == $_SERVER['HTTP_USER_AGENT']) {
				return true; 
			}	  
			else{
				return false;
			}
		}
		else {
			return true;	
		}		
	  }
}
