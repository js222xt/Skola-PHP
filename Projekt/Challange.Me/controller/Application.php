<?php

namespace controller;

// Common
require_once("/common/HTMLPage.php");

// Model
require_once("/model/LoginModel.php");
require_once("/model/db/Connect.php");
require_once("/model/db/ApplicationDAL.php");

// View
require_once("/view/ApplicationView.php");
require_once("/view/HeaderView.php");
require_once("/view/FooterView.php");

// Controller
require_once("LoginController.php");
require_once("ChallengeController.php");
require_once("CommentController.php");
require_once("UserController.php");

class Application{
	/** @var String with title */
	private $title;
	/** @var String with html for the header*/
	private $header;
	/** @var String with messages for the header*/
	private $messages;
	/** @var String with html for the body */
	private $body;
	/** @var String with html for the footer */
	private $footer;
	/** @var String with login status */
	private $loggedStatus;
	/** @var \controller\LoginController */
	private $loginController;
	/** @var \controller\ChallengeController */
	private $challengeController;
	/** @var \controller\CommentController */
	private $commentController;
	/** @var \controller\FriendsController */
	private $userController;
	/** @var \view\ApplicationView */
	private $applicationView;
	/** @var \view\HeaderView */
	private $headerView;
	/** @var \view\FooterView */
	private $footerView;
	/** @var \model\db\Connection */
	private $connection;
	/** @var DB Connection from \model\db\Connect  (mysqli_connect)*/
	private $dbConnection;
	/** @var \model\db\ApplicationDAL */
	private $applicationDAL;
	/** @var Array containing user information */
	private $loggedInUser;
	
	/**
	 * start the aplication
	 * @return String HTML
	 */
	public function startApplication(){
		try{
			
			// INIT CLASSES
			
			// Application DAL
			$this->applicationDAL = new \model\db\ApplicationDAL();
			// Application View for the application
			$this->applicationView = new \view\ApplicationView($this->applicationDAL);
			// Login Controller for login
			$this->loginController = new \controller\LoginController($this->applicationDAL, $this->applicationView);
			// Challenge Controller for challenges
			$this->challengeController = new \controller\ChallengeController($this->applicationView, $this->applicationDAL);
			// Comment Controller for comments
			$this->commentController = new \controller\CommentController($this->applicationView, $this->applicationDAL);
			// Friends Controller for friends
			$this->userController = new \controller\UserController($this->applicationView, $this->applicationDAL);
			// Header View
			$this->headerView = new \view\HeaderView();
			// Footer View
			$this->footerView = new \view\FooterView();
			

			// Handle Login			
			$this->checkLoginEvents();
			
			// If user is logged in, should not display anything if not
			if($this->loginController->isUserLoggedIn()){
				
				
				// Update user
				$this->loggedInUser = $this->loginController->GetLoggedInUser();
				
				$isAdmin = $this->loggedInUser[0]['IsAdmin'];
				$isBanned = $this->loggedInUser[0]['Banned'];
					
				if(!$isBanned){						
											
					if($this->loggedInUser[0]['IsAdmin']){
						$this->body .= $this->challengeController->ShowAddChallengeHTML();	
					}
					
					// Show Header
					$this->header .= $this->headerView->GetHeaderHTML();
					
					// Check where we are
					// If Challenges
					if($this->applicationView->IsAtChallenges()){
						
						$this->body .= $this->challengeController->GetChallengesHTML($this->loggedInUser, $this->userController->GetAllFriends($this->loggedInUser));
							
					}			
					// If user want sto see a specific challenge
					else if($this->applicationView->UserWantsToSeeAChallenge()){				
						
						// Check if user wanted to post a comment
						if($this->applicationView->UserWantsToPostComment()){
							
							$this->commentController->PostComment($this->loggedInUser);
													
						}
						
						// If user want to remove a comment
						if($this->applicationView->UserWantsToRemoveAComment()){
							
							$this->commentController->RemoveComment($this->loggedInUser);							
						}											
						
						// Show the challenge
						$this->body .= $this->challengeController->ShowChallengeHTML($this->loggedInUser, $this->userController->GetAllFriends($this->loggedInUser));
						
					}
					// If MyStuff
					else if($this->applicationView->IsAtMyStuff()){
						// Show my stuff
						
						// Show all active challenges
						
						$this->body .= $this->challengeController->GetActiveAndCompletedChallengesHTML(null,$this->loggedInUser[0]['ID'], $this->loggedInUser, $this->userController->
																																								GetAllFriends($this->loggedInUser));						
						
						// Show Account information
						$this->body .= $this->applicationView->GetAccountInformationHTML($this->loggedInUser);
						
					}
					// If Friends
					else if($this->applicationView->IsAtFriends()){
						
						// Show Friends						
						$this->body .= $this->userController->GetFriendsHTML($this->loggedInUser);						
						
					}
					else if($this->applicationView->UserWantsToSeeAllUsers()){
						// Show all users
						$this->body .= $this->userController->GetAllUsersHTML($this->loggedInUser);
									
					}
					// If user wants to add a user
					if($this->applicationView->UserWantsToAddUser()){
						
						// Add
						$this->userController->AddFriendForUser($this->loggedInUser);
						
					}
					// User wants to remove a friend
					else if($this->applicationView->UserWantsToRemoveUser()){
						
						// Remove
						$this->userController->RemoveFriendFromUser($this->loggedInUser);
						
					}  
					// If use wants to remove a challenge
					if($this->applicationView->UserWantsToRemoveAChallenge()){
						
						// Remove
						$this->challengeController->RemoveChallengeFromUser($this->loggedInUser);
									
					}
					// If use wants to finish a challenge
					if($this->applicationView->UserWantsToFinishAChallenge()){
						
						// Finish
						$this->challengeController->FinishAChallenge($this->loggedInUser);
						
					}
					// If user wants to take a challenge
					if($this->applicationView->UserWantsToTakeAChallenge()){
						
						// Add
						$this->challengeController->TakeChallenge($this->loggedInUser);		
					}
					else if($this->applicationView->AdminWantsToRemoveAChallenge()){
						
						// Try to remove
						$this->challengeController->AdminRemoveChallenge($isAdmin);
							
					}
					if($this->applicationView->UserWantsToChallengeAUser()){
						if($this->applicationView->DidUserChoseAnyFriendsToChallenge()){
							
							// Challenge them
							$this->challengeController->ChallengeFriends($this->loggedInUser[0]['ID']);
						}
					}
					// Admin wants to add a challenge
					else if($this->applicationView->AdminWantsToAddAChallenge()){
						
						// Get html
						$this->body .= $this->challengeController->AdminAddChallengeToDB($isAdmin);
						
					}
					// Admin wants to ban a user
					else if($this->applicationView->AdminWantsToBanAUser()){
						
						// Ban
						$this->userController->AdminBanUser($isAdmin, $this->loggedInUser);
										
					}
					// Admin wants to unban a user
					else if($this->applicationView->AdminWantsToUnBanAUser()){
						
						//Unban
						$this->userController->AdminUnbanUser($isAdmin);
					}
					// If user wants to see a user
					if($this->applicationView->UserWantsToSeeAUser()){
	
						// Show user
						$this->body .= $this->userController->ShowUserHTML($this->loggedInUser, $this->challengeController);
						
					}
					if($this->applicationView->UserWantsToSeeNewChallenges()){
						// Get all the challenges that user has ben challenged with
						$challenged = $this->applicationDAL->GetAllChallengesUserChallnegedWith($this->loggedInUser[0]['ID']);
						
						// Get HTML
						$this->body .= $this->applicationView->ShowNewChallengesStart();
						for ($i=0; $i < count($challenged); $i++) { 
							$this->body .= $this->applicationView->ShowNewChallenges($challenged[$i], $this->applicationDAL->GetChallenge($challenged[$i]['CID']), $this->applicationDAL->
																																								GetUser($challenged[$i]['CBYID']));
						}
						$this->body .= $this->applicationView->ShowNewChallengesEnd();
						
						// Mark them as read
						$this->applicationDAL->MarkChallengedAsRead($this->loggedInUser[0]['ID']);
					}
				}
				else{
					$this->applicationView->Banned();
				}		
			}
			else {
			// User wants to register new account
				if($this->applicationView->UserWantsToRegisterNewAccount()){
					
					// Get HTML
					$this->body .= $this->loginController->RegisterNewAccountHTML();
				}
			}

			if(!$this->loggedInUser[0]['Banned']){
				$this->WriteLoginHTML();
			}
				

			//================= HTML OUTPUT =====================//
			
			// Errors?
			if($this->applicationView->AnyErrors()){
				$this->messages.= $this->applicationView->GetErrorsHTML();
			}			
			
			// Success?
			if($this->applicationView->AnySuccess()){
				$this->messages.= $this->applicationView->GetSuccededHTML();
			}
			
			// BODY
			$this->body .= $this->applicationView->getDateHTML();
			
			// FOOTER
			$this->footer = $this->footerView->GetFooterHTML();
			
			if($this->loginController->isUserLoggedIn()){
				// See if user has any unread notification
				$this->CheckNotifications();	
			}
			
			
			// Return HTML
			$pageView = new \view\HTMLPage();
			return  $pageView->getPageHTML($this->title, "" . $this->loggedStatus . "" . $this->header, $this->messages, $this->body, $this->footer);
			
			
		}
		// Database exception is thrown!
		catch(\model\db\DBConnectionException $dbex){

			$this->applicationView->DataBaseError();
			$htmlError = $this->applicationView->GetErrorsHTML();

			return $htmlError;
		}
		// Comething else is wrong!
		catch (Exception $ex){
			$this->applicationView->UnexpectedError();
			$htmlError = $this->applicationView->GetErrorsHTML();
			
			return $htmlError;			
		}	
	}
	
	public function CheckLoginEvents(){
		
		$this->loginController->CheckLoginEvents();
		
		
	}
	
	private function CheckNotifications(){
		// Have user got challenged?
		$unreadChallengesNotifications = $this->applicationDAL->GetUnreadChallengesNotifications($this->loggedInUser[0]['ID']);
		
		// Add HTML
		$this->body .= $this->applicationView->GetNotificationBarHTML($unreadChallengesNotifications);
	}
	
	public function WriteLoginHTML(){
		// If user is logged in
		if($this->loginController->IsUserLoggedIn()){
			// HTML
			$this->body .= $this->loginController->initializeLoggedIn();
			
			$this->userIsLoggedIn();
		}
		else{
			// HTML
			$this->body .= $this->loginController->initializeLoggedOut();
			
			$this->userIsLoggedOut();
		}
	}
	
	/**
	 * Try to login after user registered
	 */
	public function TryLoginAfterRegister($username, $password){
		$this->isLoggedInWithPost = $this->loginController->TryLoginAfterRegister($username, $password);
	}

	
	/**
	 * user is confirmed as logged in.
	 */
	public function UserIsLoggedIn(){
		$this->loggedStatus = $this->applicationView->getLoggedInUserMessage();
		$this->title = $this->applicationView->getLoggedInTitle();
	}
	
	/**
	 * user is confirmed as not logged in.
	 */
	public function UserIsLoggedOut(){
		$this->loggedStatus = $this->applicationView->getNotLoggedInStatus();
		$this->title = $this->applicationView->getNotLoggedInTitle();
	}	
	
	
	
	
}
