<?php

namespace view;

class RegisterView{
	
	private static $postUsernameString;
	private static $postPasswordString = "registerView::password";
	private static $postRepeatPasswordString = "registerView::repeatPassword";
	private static $registerString ="RegisterNewAccount";
	
	private static $errors = array();
	
	/**
	 * @param String to bind username to post variable between classes
	 */
	public function __construct($postUsernameString){
		self::$postUsernameString = $postUsernameString;
	}
	
	/**
	 * @return bool if user wants to register a new account.
	 */
	public function UserWantsToRegister(){
		return isset($_GET[self::$registerString]);
	}
	
	/**
	 * @return bool of user tried to register a new account
	 */
	 public function UserHavePostedRegisterInformation(){
	 	return isset($_POST[self::$postPasswordString]); // User have posted something, empty boxes will be "" so we can check any of the fields
	 }
	 
	 /**
	  * @return Array with posted information if information have been posted, else return null
	  */
	 public function GetPostedRegisterData(){
	 	if($this->UserHavePostedRegisterInformation()){
			return array(
					self::$postUsernameString => $_POST[self::$postUsernameString],
					self::$postPasswordString => $_POST[self::$postPasswordString],
					self::$postRepeatPasswordString => $_POST[self::$postRepeatPasswordString]
					);		
	 	}
		else{
			return null;
		}
	 }
	 
	 ///SUMMARY///
	 /**
	  * Used to identify what in the array is what
	  */
	 public function GetPostRegisterUsernameString(){
	 	return self::$postUsernameString;
	 }
	 
	 public function GetPostRegisterPasswordString(){
	 	return self::$postPasswordString;
	 }
	 
	 public function GetPostRegisterRepeatPasswordString(){
	 	return self::$postRepeatPasswordString;
	 }
	 ///END SUMMARY///
	 
	 /**
	  * @return String HTML with register new account form
	  */
	 public function GetRegisterPageHTML($username = ""){
	 	$errors = "";
		if(count(self::$errors) > 0){
	 		foreach(self::$errors as $error){
	 			$errors .= "<p>" . $error . "</p>";
	 		}
		}
	 	return "
	 			<p><a href='index.php'>Tillbaka</a></p>
	 			<h2> Ej Inloggad, Registrerar användare</h2>
	 			<p> " . $errors . "</p>
	 			<div id='registerNewAccount'>
					<form action='index.php?". self::$registerString . "' method='post'>
						<label> Register new account </label><br>
						<label>Username</label><br>
						<input name='" . self::$postUsernameString . "' value='" . $username ."' type='text' ></input><br>
						<label>Password</label><br>
						<input type='password' name='". self::$postPasswordString . "' >
						</input>	<br>
						<label>Repeat Password</label><br>
						<input type='password' name='". self::$postRepeatPasswordString . "' >
						</input>	<br>
						<button class='.button-link' type='submit'>Register</button>
					</form>
				</div>";
	 }
	 
	 /**
	  * @return a stirng HTML witha register new account link
	  */
	 public function GetRegisterLink(){
	 	return "<p><a href='index.php? " . self::$registerString . "'>Registrera ny användare</a></p>";
	 }
	 
	 /**
	  * Adds an error
	  */
	 public function AddUsernameToShortError(){
	 	array_push(self::$errors, "Användarnamnet har för få tecken. Minst 3 tecken");
	 }
	 
	 /**
	  * Adds an error
	  */
	 public function AddPasswordToShortError(){
	 	array_push(self::$errors, "Lösenorden har för få tecken. Minst 6 tecken");
	 }
	 
	  /**
	  * Adds an error
	  */
	 public function AddPasswordNoMatchtError(){
	 	array_push(self::$errors, "Lösenorden matchar inte.");
	 }
		 
	 /**
	  * Adds an error
	  */
	 public function AddUsernameCointainsIllegalCharsError(){
	 	array_push(self::$errors, "Användarnamnet innehåller ogiltiga tecken");
	 }
	 
	  /**
	  * Adds an error
	  */
	 public function AddUsernameExistsError(){
	 	array_push(self::$errors, "Användarnamnet är redan upptaget");
	 }
	 
	 /**
	  * Adds an error
	  */
	 public function AddErrorWhenRegistringError(){
	 	array_push(self::$errors, "Kunde inte skapa en envändare just nu, försök igen senare.");
	 }
	 
	 /**
	  * @return HTML with all errors that exists when trying to register a new account
	  */
	 public function GetErrorsHTML(){
	 	if(count(self::$errors) > 0){
	 		$ret = "";
	 		foreach(self::$errors as $error){
	 			$ret .= "<p>" . $error . "</p>";
	 		}
	 		return $ret;
	 	}
		else{
			return "";
		}
	 }
	 
	 /**
	  * Used when a registration completes
	  * @return String message
	  */
	 public function GetRegistrationCompleteFeedback(){
	 	return "Registreringen lyckades";
	 }
}
