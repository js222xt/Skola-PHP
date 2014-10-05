<?php

namespace controller;

require_once("LoginController.php");
require_once("RegisterController.php");
require_once("./src/view/Login.php");
require_once("./src/view/RegisterView.php");
require_once("./src/model/RegisterModel.php");
require_once("./src/model/db/ApplicationDAL.php");

class MasterController {
	private $loginView;
	private $registerView;
	private $registerModel;
	private $registerController;
	private $appDAL;
	private $loginController;
	private $session;
	
	public function __construct($session) {
		$this->loginView = new \view\Login();
		$this->registerView = new \view\RegisterView($this->loginView->getUsernamePostString());
		$this->appDAL = new \model\db\ApplicationDAL();
		$this->registerModel = new \model\RegisterModel($this->appDAL);
		$this->registerController = new RegisterController($this->registerView, $this->registerModel);
		$this->session = $session;
		$this->loginController = new \controller\LoginController($this->loginView, $this->session);
	}

	/**
	 * Handles state of application.
	 * @return string HTML
	 */
	public function run() {
		//If user is logged in by session.
		if ($this->session->userIsLoggedIn() && $this->session->isSessionValid()) {
			if ($this->loginView->userWantsToLogOut()) {
				return $this->loginController->logOut();
			}

			$user = $this->session->userIsLoggedIn();
			
			if ($this->loginView->userIsSaved()) {
				$this->loginView->saveCredentials($user);
			}

			return $this->loginView->getLoggedInHTML($user);
		}
		//If user logs in by cookies.
		if ($this->loginView->userIsSaved() && $this->session->isSessionValid()) {
			if ($this->loginView->userWantsToLogOut()) {
				return $this->loginController->logOut();
			}
			
			return $this->loginController->loginByCookie();
		}
		//If user wants to login.
		if ($this->loginView->userWantsToLogin()) {
			return $this->loginController->login();
		}
		
		// If user wants to be registered
		if($this->registerView->UserWantsToRegister()){
			
			// If user already tried to submit
			if($this->registerView->UserHavePostedRegisterInformation()){
				// Calls register controller
				return $this->registerController->RegisterAccountHTML($this->loginView);
			}
			else{
				return $this->registerView->GetRegisterPageHTML();
			}
		}
		
		// No login method worked, return loginform
		$html = $this->registerView->GetRegisterLink(); // Add register link
		$html .= $this->loginView->showLoginForm(); // Add login form
		return  $html;
	}
}