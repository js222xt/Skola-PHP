<?php

namespace controller;

require_once("./src/model/UserList.php");

/**
 * Has 2 different methods for login and 1 for logging out.
 */
class LoginController {
	private $loginView;
	private $userList;
	private $session;
	
	public function __construct($view, $session) {
		$this->loginView = $view;
		$this->session = $session;
		$this->userList = new \model\UserList();
	}

	public function login() {
		try {
			$user = $this->loginView->getUser();
			$this->userList->validateUser($user);
			$this->session->loginUser($user);

			if ($this->loginView->userWantsToStayLoggedIn()) {
				$this->loginView->saveCredentials($user);
				return $this->loginView->getLoggedInHTML($user, "Inloggning lyckades och vi kommer ihåg dig nästa gång.");
			}

			return $this->loginView->getLoggedInHTML($user, "Inloggning lyckades.");
		} catch (\Exception $e) {
			return $this->loginView->showLoginForm($e->getMessage());
		}
	}

	public function loginByCookie() {
		try {
			$user = $this->loginView->getSavedCredentials();
			$this->userList->validateSavedUser($user);
			$this->loginView->saveCredentials($user);
			return $this->loginView->getLoggedInHTML($user, "Inloggning lyckades via cookies.");
		} catch (\Exception $e) {
			$this->loginView->removeSavedCredentials();
			return $this->loginView->showLoginForm($e->getMessage());
		}
	}

	public function logOut() {
		$this->loginView->removeSavedCredentials();
		session_destroy();
		return $this->loginView->showLoginForm("Du har nu loggat ut.");
	}
}