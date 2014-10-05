<?php

namespace view;

require_once("./src/model/User.php");

class Login {
	/**
	 * Names for the input form and cookies.
	 */
	private static $username = "LoginView::username";
	private static $password = "LoginView::password";
	private static $stayLoggedIn = "LoginView::stayLoggedIn";
	private static $login = "login";
	private static $logout = "logout";

	/**
	 * Endtime for cookies.
	 * @var int
	 */
	private $endtime;

	public function __construct() {
		$this->endtime = time() + 60;
	}

	/**
	 * Is true if user wants to login.
	 * @return bool
	 */
	public function userWantsToLogin() {
		return isset($_GET[self::$login]);
	}

	/**
	 * Is true if user wants to logout.
	 * @return bool
	 */
	public function userWantsToLogOut() {
		return isset($_GET[self::$logout]);
	}

	/**
	 * Is true if user wants to stay logged in.
	 * @return bool
	 */
	public function userWantsToStayLoggedIn() {
		return isset($_POST[self::$stayLoggedIn]);
	}

	/**
	 * Is true if cookies for username & password are set.
	 * @return bool
	 */
	public function userIsSaved() {
		return (isset($_COOKIE[self::$username]) && isset($_COOKIE[self::$password]));
	}

	/**
	 * Returns a user with credentials from cookies.
	 * @return \model\User $savedUser
	 * @throws Exception If cookies are not set.
	 */
	public function getSavedCredentials() {
		if ($this->userIsSaved()) {
			$cookieEndtime = file_get_contents("endtime.txt");

			if (time() > $cookieEndtime) {
				$this->removeSavedCredentials();
				throw new \Exception("Felaktig information i cookie.");
			} else {
				return new \model\User($_COOKIE[self::$username], 
					$_COOKIE[self::$password]);
			}
			
		} else {
			throw new \Exception("Cookies are not set.");
		}
	}

	/**
	 * Removes cookies if cookies are set.
	 */
	public function removeSavedCredentials() {
		if ($this->userIsSaved()) {
			setcookie(self::$username, '', time()-3600);
			setcookie(self::$password, '', time()-3600);

			unset($_COOKIE[self::$username]);
			unset($_COOKIE[self::$password]);
		}
	}

	/**
	 * Saves credentials as cookies and expirationtime in txt-file.
	 * @param  modelUser $user
	 */
	public function saveCredentials(\model\User $user) {
		setcookie(self::$username, $user->getUserName(), $this->endtime);
		setcookie(self::$password, $user->getUserPassword(), $this->endtime);

		file_put_contents("endtime.txt", "$this->endtime");
	}

	/**
	 * @param  string $feedback
	 * @return string HTML
	 */
	public function showLoginForm($feedback = '') {

		$feedback = $this->feedbackHTML($feedback);
		
		return '<h2>Ej inloggad</h2>
				<form action="?' . self::$login . '" method="post">
					<fieldset>
						<legend>Login - Skriv in användarnamn och lösenord</legend>
						' . $feedback . '
						<label for="nameID">Användarnamn:</label>
						<input type="text" name="' . self::$username . '" id="nameID" 
						value="' . $this->getNameInputValue() . '" />

						<label for="passwordID">Lösenord:</label>
						<input type="password" name="' . self::$password . '" id="passwordID" 
						value="" />

						<label for="stayLoggedInID">Håll mig inloggad:</label>
						<input type="checkbox" name="' . self::$stayLoggedIn . '" id="stayLoggedInID" 
						value="stayLoggedIn" />

						<input type="submit" value="Logga in" />
					</fieldset>
				</form>';
	}

	/**
	 * @param  \model\User $user
	 * @param  string $feedback
	 * @return string HTML
	 */
	public function getLoggedInHTML(\model\User $user, $feedback = '') {
		$feedback = $this->feedbackHTML($feedback);

		return '<h2>' . $user->getUserName() . ' är inloggad</h2>
				' . $feedback . '
				<p><a href="?' . self::$logout . '">Logga ut</a></p>';
	}

	/**
	 * @return \model\User
	 * @throws  Exception If username/password from client isn't set.
	 */
	public function getUser() {
		$nameFromClient = rtrim($_POST[self::$username]);
		$passwordFromClient = rtrim($_POST[self::$password]);

		if (empty($nameFromClient)) {
			throw new \Exception("Användarnamn saknas.");

		} else if (empty($passwordFromClient)) {
			throw new \Exception("Lösenord saknas.");
		}

		return new \model\User($nameFromClient, md5($passwordFromClient));
	}

	/**
	 * Returns the value of the name-input if it's set.
	 * @return string value
	 */
	private function getNameInputValue() {
		if(!empty($_POST[self::$username])) {
			return $_POST[self::$username];
		} else {
			return "";
		}
	}

	/**
	 * @param  string $feedback
	 * @return string HTML
	 */
	private function feedbackHTML($feedback) {
		if (!empty($feedback)) {
			return "<p>" . $feedback . "</p>";
		}
	}
	
	// ADDED FOR LABB 4!! //
	public function getUsernamePostString(){
		return self::$username;
	}
}