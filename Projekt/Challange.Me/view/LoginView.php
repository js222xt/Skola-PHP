<?php

namespace view;

class LoginView{
	/** @var String containing index in $_POST */
	private static $postUsernameString = "NameOfUser";
	/** @var String containing index in $_POST */
	private static $postPasswordString = "Password";
	/** @var String containing index in $_POST because it changes*/
	private static $postPasswordIndex;
	/** @var String containing index in $_POST */
	private static $rememberMeChecked = "Checked";
	/** @var String containing errormessages */
	private $errorMessage = "";
	/** @var String containing the string that te user tried to login with */
	private $userNameInput = "";
	/** @var String containing title if user is logged in*/
	private $loggedInTitle =  "Challenge.Me - Logged in";
	/** @var String containing title if user is NOT logged in */
	private $notLoggedInTitle = "Challange.Me";
	/** @var String containing string if user is NOT logged in */
	private $notLoggedInString = "Not logged in"; 
	/** @var String containing the message if login was successfull */
	private $loggedInUserMessage = " is ready to be challenged!";
	/** @var String containing logout string in $_GET */
	private static $logoutString = "logout";
	/** @var String containing IP adress used by the user */
	private static $IP = "IP";
	/** @var String containing information about userAgent */
	private static $userAgent = "UserAgent";
	/** @var String containing index */
	private static $pressedlogoutString = "pressedlogout";
	/** @var String containing the message if login was successfull */
	private $loggedInMessage = "";
	/** @var String containing cookie endtime filename */ 
	private static $endTimeFileName = "endtime.txt";
	/** @var Int with time in sec */
	private static $timeInSec = 25;
	/** @var String with the logged in user */
	private $loggedInUser;
	/** @var String */
	private $registerString = "RegisterNewChallenger";
	
	/**
	 * constructor
	 */
	public function __construct(){
		self::$postPasswordIndex = self::$postPasswordString . "_";
	}
	
	/**
	 * Form that user uses to login
	 * @return String HTML
	 */
	public function getNotLoggedInHTML(){
		$button = "button";
		return '
		<div id="login"><form action="?login" method="post"
				 enctype="multipart/form-data">
					<fieldset>	
						<legend>
						Login - Enter username and password.
						</legend>
						<p>'. $this->GetErrorMessage() .'<p>				
						<label for="UserNameID">Username :</label>
						<input type="text" size="20" name="' 
						. self::$postUsernameString .
						'" id="UserNameID" value="' .
						$this->getInputUsernamePost() . '">
						<label for="PasswordID">Password  :</label>
						<input type="password" size="20" name="' .
						self::$postPasswordString .
						' " id="PasswordID" value="">
						<a href="index.php?' . $this->registerString . '">Register a new Challenger</a><br>
						<label for="AutologinID">Keep me logged in  :</label>
						<input type="checkbox" name="' . 
						self::$rememberMeChecked . 
						'" id="AutologinID"><br>
						<input class="completeChallenge" type="submit" name="' .
						$button . 
						' " value="Login">
					</fieldset>
				</form>
			</div>'
				;
	}
	
	/**
	 * This will be shown if user is logged in
	 * @return String HTML
	 */
	public function getLoggedInHTML(){
		$returnString = $this->getLoggedInMessage();
		
		return $returnString .= 
			   '<p id="logout"><a href="?' .
			   self::$logoutString . 
			   '">Logout</a></p>';
	}
	
	/**
	 * @return String
	 */
	public function getLoggedInMessage(){
		return $this->loggedInMessage;
	}
	
	/**
	 * @param String
	 */
	private function setLoggedInMassage($message){
		$this->loggedInMessage = $message;
	}
	
	public function setLoggedInWithPostMessage(){
		$message = "<p id='loggedInMessage'>Login successful! </p>";
		$this->setLoggedInMassage($message);
	}
	
	public function setLoggedInWithCookieMessage(){
		$message = "Login successful!.";
		$this->setLoggedInMassage($message);
	}
	
	public function setRemeberMessage(){
		$message = "Login successful and you will be remembered next time!";
		$this->setLoggedInMassage($message);
	}
	
	public function setLoggedInUserMessage($userID, $userIDString){
		$this->loggedInUserMessage = "<div id='loggedInMessage'>
									 <label class='username'><a href='index.php?" . $userIDString. "=" . $userID . "'>" .
									 $this->getLoggedInUser() .
									 "</a></label>" .
									 $this->loggedInUserMessage .
									 "</div>";
	}
	
	public function setLoggedOutSuccessMessage(){
		$message = "You have now logged out.";
		$this->setErrorMessage($message);
	}
	
	/**
	 * @return String
	 */
	public function getLoggedInUserMessage(){
		return $this->loggedInUserMessage;
	}
	
	/**
	 *@return String If something is wrong/missing 
	 */
	public function getErrorMessage(){
		return $this->errorMessage;
	}
	
	public function setErrorMessage($message){
		$this->errorMessage = $message;
	}
	
	/**
	 * @return String title
	 */
	public function getLoggedInTitle(){
		$this-> loggedInTitle .= " as " . $this->loggedInUser;
		return $this->loggedInTitle;
	}
	
	/**
	 * @return String title
	 */
	public function getNotLoggedInTitle(){
		return $this->notLoggedInTitle;
	}
	
	
	/**
	 * @return String title
	 */
	public function getNotLoggedInStatus(){
		return $this->notLoggedInString;
	}
	
	/**
	 * @return Bool
	 */
	public function userTriesToLogin(){
		return (isset($_POST[self::$postUsernameString]) &&
				isset($_POST[self::$postPasswordIndex]) &&
				$_POST[self::$postUsernameString] != "" &&
				$_POST[self::$postPasswordIndex] != "");
	}
	
	/**
	 * @return Bool
	 */
	public function userHavePostedSomething(){
		return (isset($_POST[self::$postUsernameString]) ||
				isset($_POST[self::$postPasswordIndex]));
	} 	
	
	/**
	 * @return Bool
	 */
	public function userWantsToBeRemembered(){
		return isset($_POST[self::$rememberMeChecked]);
	}
	
	/**
	 * @return Bool
	 */
	public function userWantsToLogout(){
		return isset($_GET[self::$logoutString]);
	}
	
	/**
	 * @return String
	 */
	public function getPostUsername(){
		return $_POST[self::$postUsernameString];
	}
	
	/**
	 * @return String
	 */
	public function getPostPassword(){
		return $_POST[self::$postPasswordIndex];
	}
	
	/**
	 * @return String
	 */
	public function setInputUsernamePost(){
		$this->userNameInput = $_POST[self::$postUsernameString];
	}

	public function setUsernameSession(){
		$this->loggedInUser = $_SESSION[self::$postUsernameString];
	}
	
	public function setUsernameCookie(){
		$this->loggedInUser = $_COOKIE[self::$postUsernameString];
	}
	
	public function setUsernamePost(){
		$this->loggedInUser = $_POST[self::$postUsernameString];
	}
	
	/**
	 * @return String
	 */
	public function getInputUsernamePost(){
		return $this->userNameInput;
	}
	
	/**
	 * @return String
	 */
	 public function getLoggedInUser(){
	 	return $this->loggedInUser;
	 }
	
	/**
	 * Sets session variables with some "security"
	 * remote_addr = user IP
	 * http_user_agent = what the browser says it is
	 * set session variables
	 */
	public function setSession(){
		$_SESSION[self::$postUsernameString] = $this->getPostUsername();
		$_SESSION[self::$postPasswordIndex] = $this->getPostPassword();
		$_SESSION[self::$IP] = $_SERVER['REMOTE_ADDR'];
		$_SESSION[self::$userAgent] = $_SERVER['HTTP_USER_AGENT'];
		$this->MakeLogoutSession();	
	}
	
	/**
	 * set session variables useing info in cookies to set session variables
	 */
	public function setSessionWithCookies(){
		$_SESSION[self::$postUsernameString] = $this->getCookieUsername();
		$_SESSION[self::$postPasswordIndex] = $this->getCookiePassword();
		$_SESSION[self::$IP] = $_SERVER['REMOTE_ADDR'];
		$_SESSION[self::$userAgent] = $_SERVER['HTTP_USER_AGENT'];
		$this->MakeLogoutSession();	
	}
	
	/*
	* Creates a cookie that exists in 1 min
	*/
	public function setCookies(){
		$loginModel = new \model\LoginModel();
		setcookie(self::$postUsernameString,
 					    $this->getInputUsernamePost(),
 					    time() + intval(self::$timeInSec)
 					    );
		setcookie(self::$postPasswordIndex,
					 	$loginModel->getEncryptedString(
					 	$this->getPostPassword()),
					 	time() + intval(self::$timeInSec)			
					 	);
		file_put_contents(self::$endTimeFileName,
						  time() + intval(self::$timeInSec));
	}
		
	/**
	 * @return Bool
	 */
	 public function userHaveSession(){
	 	return (isset($_SESSION[self::$postUsernameString]) &&
				isset($_SESSION[self::$postPasswordIndex]));
	 }
	
	/**
	 * @return Bool
	 */
	  public function userHaveCookies(){
	 	return (isset($_COOKIE[self::$postUsernameString]) &&
				isset($_COOKIE[self::$postPasswordIndex]));
	 }
	
	/**
	 * @return Bool
	 */
	  public function logoutSessionExists(){
	 	return (isset($_SESSION[self::$pressedlogoutString]));
	 }
	
	/**
	 * @return Bool
	 */
	 public function validateSession(){
	 	return $_SESSION[self::$IP] == $_SERVER['REMOTE_ADDR'] &&
			   $_SESSION[self::$userAgent] == $_SERVER['HTTP_USER_AGENT'];
	 }
	
	public function destroySession(){
		session_destroy();
	}
	 
	 /**
	* @return bool true if cookie information is valid
	*/
	public function validateCookies(){
		if(time() > file_get_contents(self::$endTimeFileName)){
			$this->setCookieError();
			return false; 	
		}
		else {
			return true;
		}
	}
	
	/**
	* Removes cookies
	*/
	public function destroyCookies(){
		setcookie(self::$postUsernameString,"",time() - 3600);
		setcookie(self::$postPasswordIndex,"",time() - 3600); 
	}
	
	/**
	 * @return String
	 */
	 public function getSessionUsername(){
	 	return $_SESSION[self::$postUsernameString];
	 }
	 
	 /**
	 * @return String
	 */
	 public function getSessionPassword(){
	 	return $_SESSION[self::$postPasswordIndex];
	 }
	 
	 /**
	 * @return String
	 */
	 public function getCookieUsername(){
	 	return $_COOKIE[self::$postUsernameString];
	 }
	 
	 /**
	 * @return String
	 */
	 public function getCookiePassword(){
	 	return $_COOKIE[self::$postPasswordIndex];
	 }
	
	/**
	 * Make a logout session
	 */
	private function MakeLogoutSession(){
		$_SESSION[self::$pressedlogoutString] = "";
	}
	
	public function setCookieError(){
		$this->destroyCookies();
		$this->errorMessage = "Cookie corrupted.";
	}
	
	/**
	 * Check if the input data from user is ok, if not set error message
	 */
	public function setInputErrors(){
		$passMissing = "Please enter password.";
		$usernameMissing = "Please enter username.";
		$wrongUserOrPass = "Wrong username and/or password.";
		if (isset($_POST[self::$postUsernameString]) &&
			isset($_POST[self::$postPasswordIndex])){
			if ($_POST[self::$postUsernameString] == "") {
				$this->errorMessage = $usernameMissing;
			}
			else if ($_POST[self::$postPasswordIndex] == "") {
				$this->errorMessage = $passMissing;
			}	
			else {
				$this->errorMessage = $wrongUserOrPass;	
			}		
		}
	}
	
	/**
	 * @return String link
	 */
	public function GetRegisterString(){
		return $this->registerString;
	}
}
