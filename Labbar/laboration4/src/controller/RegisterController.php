<?php

namespace controller;

class RegisterController{
	/**@var \view\RegisterView */
	private $registerView;
	/**@var \model\RegisterModel */
	private $registerModel;
	
	/**
	 * @param \view\RegisterView
	 * @param \model\RegitserModel
	 */
	public function __construct($view, $model){
		$this->registerView = $view;
		$this->registerModel = $model;
	}
	
	/**
	 * Used to regiser an account
	 * @return String HTML
	 * @param \view\Login
	 */
	public function RegisterAccountHTML($loginView){
		// Validate information

		$error = false;
		$info = $this->registerView->GetPostedRegisterData();

		if($this->registerModel->DoesUsernameExists($info[$this->registerView->GetPostRegisterUsernameString()]) ){
			$this->registerView->AddUsernameExistsError();
			$error = true;
		}
		if($this->registerModel->IsUsernameValid($info[$this->registerView->GetPostRegisterUsernameString()])){
			$this->registerView->AddUsernameCointainsIllegalCharsError();
			$error = true;
		}
		if($this->registerModel->IsUsernameTooSmal($info[$this->registerView->GetPostRegisterUsernameString()])){
			$this->registerView->AddUsernameToShortError();
			$error = true;
		}
		if($this->registerModel->IsPasswordTooSmal($info[$this->registerView->GetPostRegisterPasswordString()])){
			$this->registerView->AddPasswordToShortError();
			$error = true;
		}
		if(!$this->registerModel->DoesPassordMatch($info[$this->registerView->GetPostRegisterPasswordString()], $info[$this->registerView->GetPostRegisterRepeatPasswordString()])){
			$this->registerView->AddPasswordNoMatchtError();
			$error = true;
		}
		
		if(!$error){
			// Validation complete and ok
			try{
				$this->registerModel->RegisterAccount(	$info[$this->registerView->GetPostRegisterUsernameString()],
														$info[$this->registerView->GetPostRegisterPasswordString()]
												  );
				// Return login form with feedback
				$html = $this->registerView->GetRegisterLink(); // Add register link
				$html .= $loginView->showLoginForm($this->registerView->GetRegistrationCompleteFeedback()); // Add login form
				return  $html;
			}
			catch(Exception $e){
				$this->registerView->AddErrorWhenRegistringError();
			}
			
		}
		else{
			// Validation failed!
			$uname = $this->registerModel->RemoveTags( $info[$this->registerView->GetPostRegisterUsernameString()] );
			return $this->registerView->GetRegisterPageHTML($uname);
		}
	}
	
}
