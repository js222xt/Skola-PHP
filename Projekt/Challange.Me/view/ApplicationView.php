<?php

namespace view;

require_once("LoginView.php");
require_once("ChallengeView.php");
require_once("CommentView.php");

class ApplicationView{
	/**
	 * @var /view/LoginView 
	 */
	private static $loginView;
	/** @var /view/HeaderView */
	private static $headerView;
	/** @var /view/ChallengeView */
	private static $challengeView;
	/** @var /view/CommentView */
	private static $commentView;
	/** @var /model/db/ApplicationDAL */
	private static $applicationDAL;
	/** @var Array with errors */
	private $errors = array();
	/** @var Array with good things */
	private $succeded = array();
	/** @var String */
	private $addUserString = "AddUser";
	/** @var String */
	private $addChallengeString = "AddChallenge";
	/** @var String */
	private $removeFriendString = "RemoveFriend";
	/** @var String */
	private $registerUsername = "registerUsername";
	/** @var String */
	private $registerPassword = "registerPassword";
	/** @var String */
	private $registerEmail = "registerEmail";
	/** @var String */
	private $registerFName = "registerFName";
	/** @var String */
	private $registerLName= "registerLName";
	/** @var String */
	private $newChallengeName = "newChallengeName";
	/** @var String */
	private $newChallengeDescription = "newChallengeDescription";
	/** @var String */
	private $newChallengeWorth = "newChallengeWorth";
	/** @var String */
	private $banUserID = "BanUserID";
	/** @var String */
	private $unbanUserID = "UnBanUserID";
	/** @var String */
	private $showNewChallenges = "ShowNewChallenges";
	
	
	
	/**
	 * constructor
	 * initialize \view\LoginView
	 */
	public function __construct($appDal){
		self::$loginView = new \view\LoginView();
		self::$headerView = new \view\HeaderView();
		self::$challengeView = new \view\ChallengeView();
		self::$commentView = new \view\CommentView();
		self::$applicationDAL = $appDal;
	}
	
	/**
	 * @return String HTML
	 */
	public function getLoggedInHTML(){		
		return self::$loginView->getLoggedInHTML();
	}
	
	/**
	 * @return String HTML
	 */
	public function getNotLoggedInHTML(){		
		return self::$loginView->getNotLoggedInHTML();
	}
	
	
	/**
	 * @return String HTML
	 */
	public function getLoggedInTitle(){
		return self::$loginView->getLoggedInTitle();
	}
	
	/**
	 * @return String
	 */
	public function getNotLoggedInTitle(){
		return self::$loginView->getNotLoggedInTitle();
	}
	
	/**
	 * @return String
	 */
	public function getLoggedInStatus(){
		return self::$loginView->getLoggedInStatus();
	}
	
	/**
	 * @return String
	 */
	public function getNotLoggedInStatus(){
		return self::$loginView->getNotLoggedInStatus();
	}
	
	/**
	 * @return Bool
	 */
	public function userTriesToLogin(){
		return self::$loginView->userTriesToLogin();
	}
	
	/**
	 * @return Bool if user wants to logout
	 */
	public function userWantsToLogout(){
		return self::$loginView->userWantsToLogout();
	}
	
	/**
	 * @return Bool
	 */
	public function userHavePostedSomething(){
		return self::$loginView->userHavePostedSomething();
	}
	
	/**
	 * @return String
	 */
	public function getPostUsername(){
		return self::$loginView->getPostUsername();
	}
	
	/**
	 * @return String
	 */
	public function getPostPassword(){
		return self::$loginView->getPostPassword();
	}
	
	/**
	 * Sets input username from posted data
	 */
	public function setInputUsernamePost(){
		self::$loginView->setInputUsernamePost();
	}
	
	/**
	 * Sets username from posted data
	 */
	public function setUsernamePost(){
		self::$loginView->setUsernamePost();
	}
	
	/**
	 * Sets password from posted data
	 */
	public function setUsernameSession(){
		self::$loginView->setUsernameSession();
	}	
	
	/**
	 * Sets username from cookie
	 */
	public function setUsernameCookie(){
		self::$loginView->setUsernameCookie();
	}	
	
	/**
	 * Sets message that is shown to logged in user
	 */
	public function setLoggedInUserMessage($userID){
		self::$loginView->setLoggedInUserMessage($userID, self::$commentView->GetUserIDString());
	}
	
	/**
	 * Sets message that user logged in with post
	 */
	public function setLoggedInWithPostMessage(){
		self::$loginView->setLoggedInWithPostMessage();
	}
	
	/**
	 * Sets message that user logged in with cookues
	 */
	public function setLoggedInWithCookieMessage(){
		self::$loginView->setLoggedInWithCookieMessage();
	}
	
	/**
	 * Sets message that user wants to be remembered
	 */
	public function setRemeberMessage(){
		self::$loginView->setRemeberMessage();
	}
	
	/**
	 * Sets message that user logged out successfully
	 */
	public function setLoggedOutSuccessMessage(){
		self::$loginView->setLoggedOutSuccessMessage();
	}
	
	/**
	 * @return String HTML
	 */
	public function getLoggedInUserMessage(){
		return self::$loginView->getLoggedInUserMessage();
	}
	
	/**
	 * @return Bool
	 */
	 public function userHaveSession(){
	 	return self::$loginView->userHaveSession();
	 }
	 
	/**
	 * @return Bool
	 */
	 public function userHaveCookies(){
	 	return self::$loginView->userHaveCookies();
	 } 
	
	/**
	 * @return Bool
	 */
	public function logoutSessionExists(){
		return self::$loginView->logoutSessionExists();
	}
	
	/**
	 * Destroy session variables
	 */
	public function destroySession(){
		self::$loginView->destroySession();
	}
	
	/**
	 * Destroy cookie variables
	 */
	public function destroyCookies(){
		self::$loginView->destroyCookies();
	}
	
	/**
	 * @return Bool
	 */
	 public function validateSession(){
	 	return self::$loginView->validateSession();
	 }
	 
	 /**
	 * @return Bool
	 */
	 public function validateCookies(){
	 	return self::$loginView->validateCookies();
	 }
	
	/**
	 * @return String
	 */
	public function getSessionUsername(){
		return self::$loginView->getSessionUsername();
	}
	
	/**
	 * @return String
	 */
	public function getSessionPassword(){
		return self::$loginView->getSessionPassword();
	}
	
	/**
	 * @return String
	 */
	public function getCookieUsername(){
		return self::$loginView->getCookieUsername();
	}
	
	/**
	 * @return String
	 */
	public function getCookiePassword(){
		return self::$loginView->getCookiePassword();
	}
	
	/**
	 * Creates session variables
	 */
	public function setSession(){
		self::$loginView->setSession();
	}
	
	/**
	 * Creates session variables with info from cookies
	 */
	public function setSessionWithCookies(){
		self::$loginView->setSessionWithCookies();
	}
	
	/**
	 * Creates cookies
	 */
	public function setCookies(){
		self::$loginView->setCookies();
	}
	
	/**
	 * Set error that cookies has some error
	 */
	public function setCookieError(){
		self::$loginView->setCookieError();
	}
	
	/**
	 * If any, set error
	 */
	public function setInputErrors(){
		self::$loginView->setInputErrors();
	}
	
	/**
	 * Does the user wants to be remembered
	 * @return Bool
	 */
	public function userWantsToBeRemembered(){
		return self::$loginView->userWantsToBeRemembered();
	}  
	
	/**
	 * Return a string containing the current date including
	 * day, month, year, second, hour, minute
	 * @return String 
	 */
	public function getDateHTML(){
		$today = getdate();
		$dateString = "<div id='date'>";
		
		//Get day of the week
		$dateString .= $this->getDayOfWeek($today);
		
		// Check day of the month
		$dateString .= "the one and only " . $today["mday"];
		
		// Check day of the month
		$dateString .= $this->getMonth($today);
		
		// Check year
		$dateString .= "year " . $today["year"] . ".";
		
		// Adds 0 to seconds and/or minutes hours if length of string == 1 (<10)
		$minutes = $this->getFixedTimeString($today, "minutes");
		$seconds = $this->getFixedTimeString($today, "seconds");
		$hour = $this->getFixedTimeString($today, "hours");
		
		// Checking time
		$dateString .= " Server time: [" .
						 $hour . ":" .
						 $minutes . ":" . 
						 $seconds . "].</div>";
		return $dateString;
	}
	
	/**
	 *@param getdate() $today
	 *@param String $type
	 *@return String
	 */
	private function getFixedTimeString($today,$type){
		if(strlen($today[$type]) == 1){
			return "0" . $today[$type];
		}
		else {
			return $today[$type];
		}
	}
	
	/**
	 * @var Array user info
	 * @return String html
	 */
	public function GetAccountInformationHTML($user){
		$html ="";

		$html .= "
			<div id='accountInfo'>
				<h3>Account Information</h3>
				<p>Username: <label class='accInfoLabel'><a href='index.php?" . self::$commentView->GetUserIDString() ."=" . $user[0]['ID'] ."'>" . $user[0]['Username'] . "</a></label> </p>
				<p>Email: <label class='accInfoLabel'>" . $user[0]['Email'] . "</label> </p>
				<p>First name: <label class='accInfoLabel'>" . $user[0]['FName'] . "</label> </p>
				<p>Last name: <label class='accInfoLabel'>" . $user[0]['LName'] . "</label> </p>
				<p>Challenge points: <label class='accInfoLabel'>" . $user[0]['challengePoints'] . "</label> </p>
			</div>	
		";
		
		return $html;
	}
	
	
	//================= CHALLENGES =============================//
	
	/**
	 * @return String HTML
	 */
	public function GetChallengeHeaderHTML(){
		return "
		<h2> Challenges </h2>
		";
	}	
	
	/**
	 * @return String HTML
	 */
	public function GetMyActiveChallengesHeaderHTML($userName, $isMine, $ID){
		if($isMine){
			return "
			<h2><a href='index.php?" . self::$commentView->GetUserIDString() ."=" . $ID ."'>My</a> Active Challenges </h2>
			";
		}
		else {
			return "
			<h2> <a href='index.php?" . self::$commentView->GetUserIDString() ."=" . $ID ."'>" . $userName . "</a> Active Challenges </h2>
			";
		}		
	}
	
	/**
	 * @return String HTML
	 */
	public function GetMyCompletedChallengesHeaderHTML($userName, $isMine, $ID){
		if($isMine){
			return "
			<h2><a href='index.php?" . self::$commentView->GetUserIDString() ."=" . $ID ."'>My</a> Completed Challenges </h2>
			";
		}
		else {
			return "
			<h2> <a href='index.php?" . self::$commentView->GetUserIDString() ."=" . $ID ."'>" . $userName . "</a> Completed Challenges </h2>
			";
		}		
	}
	
	
	/**
	 * @return String HTML
	 */
	public function UserHasNoChallengesHTML(){
		return "
			<div class='info'>
				<p> You have no active challenges, browse some <a href='index.php?" . self::$headerView->GetChallengeString() . "'>here</a> :) </p>
			</div>
		";		
	}	
	
	/**
	 * @return String HTML
	 */
	public function UserHasNoCompletedChallengesHTML(){
		return "
			<div class='info'>
				<p> Hmm, no completed challenges yet :0, browse some <a href='index.php?" . self::$headerView->GetChallengeString() . "'>here</a> and get to work :D </p>
			</div>
		";		
	}
	
	/**
	 * @return String HTML
	 */
	public function UserHasNoFriendsHTML(){
		return "
			<div class='info'>
				<p> You have no friends! Browse some users <a href='index.php?" . self::$headerView->GetUsersString() . "'>here!</a> </p>
			</div>
		";		
	}
		
	/**
	 * @var Array
	 * @var User ID
	 * Return HTML for the challenges given
	 */
	public function ShowChallenges($challenges, $hasChallenge, $isAdmin, $friends){
		$html = "
			<div id='challenges'>
			
		";
		for ($i=0; $i < count($challenges); $i++) { 
			$html .= self::$challengeView->DisplayChallenge($challenges[$i], $hasChallenge, $isAdmin, $friends);
		}
		
		$html .= "
			</div>
		";
		
		return $html;
	}
	
	/**
	 * @var Array with friend info
	 * Return HTML for the friend given
	 */
	public function ShowFriend($friend, $isAdmin, $isBanned){
		$html = "
			<div class='friend'>
			
		";
			$html .= "	<div id='accountInfo'>
							<h3>Friend?</h3>
							<p>Username: <label class='accInfoLabel'><a href='index.php?" . self::$commentView->GetUserIDString() ."=" . $friend[0]['ID'] ."'>" . $friend[0]['Username'] . "</a></label> </p>
							<p>Email: <label class='accInfoLabel'>" . $friend[0]['Email'] . "</label> </p>
							<p>First name: <label class='accInfoLabel'>" . $friend[0]['FName'] . "</label> </p>
							<p>Last name: <label class='accInfoLabel'>" . $friend[0]['LName'] . "</label> </p>
							<p>Challenge points: <label class='accInfoLabel'>" . $friend[0]['ChallengePoints'] . "</label> </p>
							<p>Banned? <label class='accInfoLabel'>";
							
							if((boolean)$isBanned){
								$html .= "YES :(";
							} 
							else {
								$html .= "Nah, not yet";
							}
							
							$html .="</label></p>
							";
							
							$html .= "<a class='removeChallenge' href='index.php?" . $this->removeFriendString ."=" . $friend[0]['ID'] . "'>Remove Friend</a>";
		
		if((boolean)$isAdmin && !(boolean)$friend[0]['IsAdmin']){
			if(!(boolean)$isBanned){
				$html .= "<a class='destroyChallenge' href='index.php?" . $this->banUserID . "=" . $friend[0]['ID'] ."' >BAN USER!</a>";
			}
			else {
				$html .= "<a class='completeChallenge' href='index.php?" . $this->unbanUserID . "=" . $friend[0]['ID'] ."' >UNBAN USER!</a>";
			}			
		}
		
		$html .= "
			</div>
			</div>
		";
		
		return $html;
	}
	
	public function ShowUser($user, $isFriend, $isAdmin, $isBanned){

		$html = "<div id='accountInfo'>
							<h3>Challenger:</h3>
							<p>Username: <label class='accInfoLabel'><a href='index.php?" . self::$commentView->GetUserIDString() ."=" . $user['ID'] ."'>" . $user['Username'] . "</a></label> </p>
							<p>Email: <label class='accInfoLabel'>" . $user['Email'] . "</label> </p>
							<p>First name: <label class='accInfoLabel'>" . $user['FName'] . "</label> </p>
							<p>Last name: <label class='accInfoLabel'>" . $user['LName'] . "</label> </p>
							<p>Challenge points: <label class='accInfoLabel'>" . $user['challengePoints'] . "</label> </p>
							<p>Banned? <label class='accInfoLabel'";
							
							if((boolean)$isBanned){
								$html .= " class='banned'>YES :(";
							} 
							else {
								$html .= ">Nah, not yet";
							}
							
							$html .="</label></p>
							";
		// If not friend
		if(!$isFriend){
			$html .= "<a class='completeChallenge' href='index.php?" . $this->addUserString . "=" . $user['ID'] . "' >Become Internet STALKER</a>";
		}
		
		if((boolean)$isAdmin && !(boolean)$user['IsAdmin']){
			if(!(boolean)$isBanned){
				$html .= "<a class='destroyChallenge' href='index.php?" . $this->banUserID . "=" . $user['ID'] ."' >BAN USER!</a>";
			}
			else {
				$html .= "<a class='completeChallenge' href='index.php?" . $this->unbanUserID . "=" . $user['ID'] ."' >UNBAN USER!</a>";
			}			
		}
		
		$html .= "</div>";
		
		return $html;
	}
	
	/**
	 * @var Array
	 * @var User ID
	 * Return HTML for the challenges given
	 */
	public function ShowChallenge($challenge, $hasChallenge, $isAdmin, $friends){

		return self::$challengeView->DisplayChallenge($challenge, $hasChallenge, $isAdmin, $friends);

	}
	
	public function ShowAddChallengeHTML(){
		return "<div class='challenge'><p>Admin Stuff</p> <a class='completeChallenge' href='index.php?" . $this->addChallengeString ."'>Add new challenge</a></div>";
	}
	
	public function AdminWatsToAddAChallenge(){
		return isset($_GET[$this->addChallengeString]);
	}
	
	/**
	 * @var Array containing comment for a challenge
	 * Returns HTML for showing comments and to comment for a challenge
	 */
	public function ShowComment($comment, $user, $ourUser){
		$html = "";		
		
		// Get start HTML for base comment
		$html .= self::$commentView->WriteCommentHTMLStart($comment, $user[0], self::$challengeView, $ourUser);
		
		/*
		// Get all comments on comment
		$commentOnComment = self::$applicationDAL->GetCommentsOnComment($comments[$i][0]);
		
		// Go through all comments
		for ($j=0; $j < count($commentOnComment); $j++) {
			// Get user who commented this
			$user = self::$applicationDAL->GetUser($commentOnComment[$j][2]);
			// Get HTML for comment
			$html .= self::$commentView->WriteCommentOnCommentHTML($commentOnComment[$j], $user[0], self::$challengeView);
		}
		*/
		 
		// Get end HTML for base comment 
		//$html .= self::$commentView->WriteCommentHTMLEnd($comments[$i], $user[0], self::$challengeView);	
		
		return $html;
	}

	public function ShowCommentSystemStart(){
		return "
			<div id='comments'>
			<h2>Comments</h2>
		";
	}
	
	public function ShowCommentSystemEnd(){
		$html ="";
		
		$html .= "
			<div id='addComment'>
			";
			
			$html .= self::$commentView->WriteAddCommentHTML(self::$challengeView);
			
			$html .= "
				</div>
				</div>
			";
		return $html;
	}
	
	/**
	 * @return Bool if user wants to register new account
	 */
	public function UserWantsToRegisterNewAccount(){
		return isset($_GET[self::$loginView->GetRegisterString()]);
	}
	
	/*
	 * @var Array
	 * Return HTML for the challenge given
	 */
	public function ShowDetailedChallenge($challenge, $hasChallenge, $isAdmnin, $friends){	
		return self::$challengeView->DisplayChallenge($challenge, $hasChallenge, $isAdmnin, $friends);
	}
	
	/**
	 * @return Bool if user wants to see a specific challenge
	 */
	public function UserWantsToSeeAChallenge(){
		return isset($_GET[self::$challengeView->GetSeeAChallengeString()]);
	}
	
	/**
	 * @return Bool if user wants to see a specific challenge
	 */
	public function UserWantsToTakeAChallenge(){
		return isset($_GET[self::$challengeView->GetTakeAChallengeString()]);
	}	
	
	/**
	 * @return Bool if user wants to see new challenges
	 */
	public function UserWantsToSeeNewChallenges(){
		return isset($_GET[$this->showNewChallenges]);
	}
	
	/**
	 * @return Bool if user wants to remove a challenge
	 */
	public function UserWantsToRemoveAChallenge(){
		return isset($_GET[self::$challengeView->GetRemoveAChallengeString()]);
	}	
	
	/**
	 * @return Bool if admin wants to remove a challenge from db
	 */
	public function AdminWantsToRemoveAChallenge(){
		return isset($_GET[self::$challengeView->GetDestroyAChallengeString()]);
	}	
	
	/**
	 * @return Bool if user wants to challenge a friend
	 */
	public function AdminWantsToAddAChallenge(){
		return isset($_GET[$this->addChallengeString]);
	}
	
	/**
	 * @return 
	 */
	public function UserWantsToChallengeAUser(){
		return isset($_GET[self::$challengeView->GetChallengeAFriendString()]);
	}
	
	/**
	 * @return Bool if admin wants to add a challenge to db
	 */
	public function AdminWantsToBanAUser(){
		return isset($_GET[$this->banUserID]);
	}
	
	/**
	 * @return Bool if admin wants to add a challenge to db
	 */
	public function AdminWantsToUnBanAUser(){
		return isset($_GET[$this->unbanUserID]);
	}
	
	/**
	 * @return Bool if user wants to remove a challenge
	 */
	public function UserWantsToRemoveAComment(){
		return isset($_GET[self::$commentView->GetRemoveACommentString()]);
	}
	
	
	/**
	 * @return Bool if user wants to remove a challenge
	 */
	public function UserWantsToFinishAChallenge(){
		return isset($_GET[self::$challengeView->GetFinishAChallengeString()]);
	}	
	
	/**
	 * @return Bool if user wants to add a comment to a comment
	 */
	public function UserWantsToPostCommentOnComment(){
		return isset($_GET[self::$commentView->GetAddCommentOnCommentString()]);
	}
	
	/**
	 * @return Bool if user wants to add a comment to a comment
	 */
	public function UserWantsToPostComment(){
		return isset($_GET[self::$commentView->GetAddCommentOnChallengeString()]);
	}
	
	/**
	 * @return Bool if everyting exists after a post has been done
	 */
	public function UserHavePostedCommentOnCommentCorrectly(){
		return (isset($_POST[self::$commentView->GetTitleString()]) && isset($_POST[self::$commentView->GetCommentString()]));
	}
	
	/**
	 * @return Bool if everyting exists after a post has been done
	 */
	public function AdminHavePostedNewChallengeInfo(){
		return (isset($_POST[$this->newChallengeName]) && isset($_POST[$this->newChallengeDescription]));
	}	
	
	
	/**
	 * @return Bool if everyting exists after a post has been done
	 */
	public function UserHavePostedCommentCorrectly(){
		return (isset($_POST[self::$commentView->GetTitleString()]) && isset($_POST[self::$commentView->GetCommentString()]));
	}
	
	public function UserTriedToRegister(){
		return (isset($_POST[$this->registerUsername]) && isset($_POST[$this->registerPassword]) &&
				isset($_POST[$this->registerEmail]) && isset($_POST[$this->registerFName]) &&
				isset($_POST[$this->registerLName]));
	}
	
	/**
	 * @return Bool if user wants to see a user
	 */
	public function UserWantsToSeeAUser(){
		return isset($_GET[self::$commentView->GetUserIDString()]);
	}
	
	/**
	 * @return Bool if user wants to see all users
	 */
	public function UserWantsToSeeAllUsers(){
		return isset($_GET[self::$headerView->GetUsersString()]);
	}
	
	/**
	 * @return Bool if user wants to add a user
	 */
	public function UserWantsToAddUser(){
		return isset($_GET[$this->addUserString]);
	}
	
	/**
	 * @return Bool if user wants to add a user
	 */
	public function UserWantsToRemoveUser(){
		return isset($_GET[$this->removeFriendString]);
	}
	
	
	/**
	 * @return String with the posted title string
	 */
	public function GetCommentOnCommentTitle(){
		return $_POST[self::$commentView->GetTitleString()];
	}
	
	/**
	 * @return String with the posted title string
	 */
	public function GetCommentTitle(){
		return $_POST[self::$commentView->GetTitleString()];
	}
	
	/**
	 * @return String with the posted comment string
	 */
	public function GetCommentOnCommentComment(){
		return $_POST[self::$commentView->GetCommentString()];
	}
	
	/**
	 * @return String with the posted comment string
	 */
	public function GetCommentComment(){
		return $_POST[self::$commentView->GetCommentString()];
	}
	
	/**
	 * @return String with the posted name string
	 */
	public function GetNewChallengeNameFromPOST(){
		return $_POST[$this->newChallengeName];
	}

	/**
	 * @return String with the posted description string
	 */
	public function GetNewChallengeDescriptionFromPOST(){
		return $_POST[$this->newChallengeDescription];
	}
	
	/**
	 * @return String with the posted description string
	 */
	public function GetNewChallengeWorthFromPOST(){
		return $_POST[$this->newChallengeWorth];
	}
	
	/**
	 * @return 
	 */
	public function GetChallengedFriends(){		
		return $_POST[self::$challengeView->GetChallengeAFriendString()];
	}
	
	/**
	 * @return String ID
	 */
	public function GetChallengeFriendChallengeID(){
		return $_POST[self::$challengeView->GetChallengeAFriendString()];
	}
	
	/**
	 * @return 
	 */
	public function DidUserChoseAnyFriendsToChallenge(){		
		return isset($_POST[self::$challengeView->GetChallengeAFriendString()]);
	}
	
	/**
	 * @return Array with posted information
	 */
	public function GetPostedRegisterNewChallengerInfo(){
		$retArr = array();
		
		if($this->UserTriedToRegister()){
			array_push($retArr, $_POST[$this->registerUsername]);
			array_push($retArr, $_POST[$this->registerPassword]);
			array_push($retArr, $_POST[$this->registerEmail]);
			array_push($retArr, $_POST[$this->registerFName]);
			array_push($retArr, $_POST[$this->registerLName]);
		}
		
		return $retArr;
	}
	
	/**
	 * @return int ID
	 */
	public function GetSeeChallengeIDFromGet(){
		if(isset($_GET[self::$challengeView->GetSeeAChallengeString()])){
			return $_GET[self::$challengeView->GetSeeAChallengeString()];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID 
	 */
	public function GetTakeChallengeIDFromGet(){
		if(isset($_GET[self::$challengeView->GetTakeAChallengeString()])){
			return $_GET[self::$challengeView->GetTakeAChallengeString()];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID
	 */
	public function GetCommentOnCommentIDFromGet(){
		if(isset($_GET[self::$commentView->GetAddCommentOnCommentString()])){
			return $_GET[self::$commentView->GetAddCommentOnCommentString()];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID
	 */
	public function GetCommentIDFromGet(){
		if(isset($_GET[self::$commentView->GetAddCommentOnCommentString()])){
			return $_GET[self::$commentView->GetAddCommentOnCommentString()];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID
	 */
	public function GetRemoveChallengeIDFromGet(){
		if(isset($_GET[self::$challengeView->GetRemoveAChallengeString()])){
			return $_GET[self::$challengeView->GetRemoveAChallengeString()];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID
	 */
	public function GetDestroyChallengeIDFromGET(){
		if(isset($_GET[self::$challengeView->GetDestroyAChallengeString()])){
			return $_GET[self::$challengeView->GetDestroyAChallengeString()];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID
	 */
	public function GetBanUserIDFromGET(){
		if(isset($_GET[$this->banUserID])){
			return $_GET[$this->banUserID];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID
	 */
	public function GetUnBanUserIDFromGET(){
		if(isset($_GET[$this->unbanUserID])){
			return $_GET[$this->unbanUserID];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID
	 */
	public function GetFinishChallengeIDFromGet(){
		if(isset($_GET[self::$challengeView->GetFinishAChallengeString()])){
			return $_GET[self::$challengeView->GetFinishAChallengeString()];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID
	 */
	public function GetRemoveCommentIDFromGet(){
		if(isset($_GET[self::$commentView->GetRemoveACommentString()])){
			return $_GET[self::$commentView->GetRemoveACommentString()];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID
	 */
	public function GetUserIDFromGet(){
		if(isset($_GET[self::$commentView->GetUserIDString()])){
			return $_GET[self::$commentView->GetUserIDString()];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID
	 */
	public function GetAddUserIDFromGet(){
		if(isset($_GET[$this->addUserString])){
			return $_GET[$this->addUserString];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @return int ID
	 */
	public function GetRemoveUserIDFromGet(){
		if(isset($_GET[$this->removeFriendString])){
			return $_GET[$this->removeFriendString];
		}
		else {
			return -1;
		}
	}
	
	/**
	 * @var Array with unread notifications
	 * @return String HTML
	 */
	 public function GetNotificationBarHTML($unreadChallengesNotifications){
	 	$html = "
	 		<div id='notificationBar'>
	 	";
		
		// Show unread challenges
		$html .= "<p>New Challenges: <a href='index.php?" . $this->showNewChallenges . "'>" . count($unreadChallengesNotifications) . "</a></p>";
		
		$html .= "</div>";
		return $html;
	 }
	  /**
	  * @var Array with all the challenges the user has benn challenged with
	  * @return String HTML
	  */
	 public function ShowNewChallengesStart(){
	 	$html = "
	 		<div class='challenge'>
	 	";

		return $html;
	 }
	 
	 /**
	  * @var Array with all the challenges the user has benn challenged with
	  * @return String HTML
	  */
	 public function ShowNewChallenges($challenged, $challenge, $user){
	 	//var_dump($user);
	 	$html = "
	 		<div class='challenge'>
			<p>Name: <a href='index.php?" . self::$challengeView->GetSeeAChallengeString() . "=" . $challenged['CID'] . "'>" . $challenge[0]['Name']. " </a></p>
			<p>Challenged by: <a href='index.php?" . self::$commentView->GetUserIDString() . "=" . $user[0]['ID'] . "'>" . $user[0]['Username'] ." (" . $user[0]['FName'] ." 
																																					" . $user[0]['LName'] .")</a> </p>
		";
		
		
		$html .= "</div>";
		return $html;
	 }
	 
	  /**
	  * @var Array with all the challenges the user has benn challenged with
	  * @return String HTML
	  */
	 public function ShowNewChallengesEnd(){
		$html = "</div>";
		return $html;
	 }
	
	/**
	 * @return String HTML
	 */
	public function GetRegisterNewAccountHTML(){
		$html = "<div id='registerNewAccount'>
					<form action='index.php?". self::$loginView->GetRegisterString() . "' method='post'>
							<label> Register new Challenger </label><br>
							<label>Username</label><br>
							<input name='" . $this->registerUsername . "' type='text' ></input><br>
							<label>Password</label><br>
							<input type='password' name='". $this->registerPassword . "' >
							</input>	<br>
							<label>Email</label><br>
							<input type='email' name='". $this->registerEmail . "' >
							</input>	<br>
							<label>Firstname</label><br>
							<input type='text' name='". $this->registerFName . "' >
							</input>	<br>
							<label>Lastname</label><br>
							<input type='text' name='". $this->registerLName . "' >
							</input>	<br>
							<button class='.button-link' type='submit'>Register</button>
						</form>
					</div>";
		
		return $html;
	}
	
	/**
	 * @return String HTML
	 */
	 public function GetAddChallengeHTML($name, $desc, $worth){
	 	$html = "<div id='registerNewChallenge'>
					<form action='index.php?". $this->addChallengeString . "' method='post'>
							<label> Add new challenge </label><br>
							<label>Name:</label><br>
							<input value='" . $name . "' name='" . $this->newChallengeName . "' type='text' ></input><br>
							<label>Description:</label><br>
							<textarea value='" . $desc . "' name='". $this->newChallengeDescription . "' rows='4' cols='50'>
							</textarea>	<br>
							<label>Worth how much?:</label><br>
							<input value='" . $worth . "' name='" . $this->newChallengeWorth . "' type='text' ></input><br>
							<button class='.button-link' type='submit'>Submit</button>
						</form>
					</div>";
		
		return $html;
	 }
	
	/**
	 * Called whenever we cannot find the specific challenge we were looking for

	public function ChallengeNotFound(){
		echo "<p class='Error'> We could not find the challenge you were looking for! Browse all the challenges <a href='index.php?" .
		 self::$headerView->GetChallengeString() .  "'>here</a></p>";
	}
	 * 	 */
	
	//=================== PAGES ==============================//
	
	/*
	 * @Return Bool if user is at site MyStuff
	 */
	public function IsAtMyStuff(){
		return isset($_GET[self::$headerView->GetMyStuffString()]);
	}
	
	/*
	 * @Return Bool if user is at site Friends
	 */
	public function IsAtFriends(){
		return isset($_GET[self::$headerView->GetFriendsString()]);
	}			
		
	/*
	 * @Return Bool if user is at site Challenges
	 */
	public function isAtChallenges(){
		return isset($_GET[self::$headerView->GetChallengeString()]);
	}
	
	
	//======================== OTHER ============================//
	
	/**
	 *@param getdate() $today
	 *@Return String
	 */
	private function getDayOfWeek($today){
		// Check day of the week
		switch ($today["wday"]) {
			case 1:		
				return "Moooonday, ";
			case 2:		
				return "Tuesday, ";
			case 3:		
				return  "Almostfridayday, ";
			case 4:		
				return "Soonsoonday, ";
			case 5:		
				return  "Partyday, ";
			case 6:		
				return "Hangoverday, ";
			case 0:		
				return  "Nopeday, ";
		}
	}
	
	/**
	 *@param getdate()  $today
	 *@Return String
	 */
	private function getMonth($today){
		// Check month
		switch ($today["mon"]) {
			case 1:	
				return " Snowmonth ";
			case 2:	
				return " Stillsnowmonth ";
			case 3:	
				return " Hopefullysnowlessmonth ";
			case 4:	
				return " Middlemonth ";
			case 5:	
				return " Soonsummermonth ";
			case 6:	
				return" Gettingbetternowmonth ";
			case 7:	
				return " Finnalymonth ";	
			case 8:	
				return" Chillmonth ";
			case 9:	
				return " Itbeginsagainmonth ";	
			case 10:	
				return " Workingmonth ";
			case 11:	
				return " Soonchristmanmonth ";
			case 12:	
				return " Prepareyourselfwinteriscomingandalsochristmasunlessyoudntcelebratethatkindofstuffinthatcaseonlywinteriscomingunlessyouliveinaustraliathensummeriscoming ";
		}	
	}	
	
	//================= ERRORS AND SUCCESS ==============//
	
	/**
	 * Adds an error
	 */
	public function ChallengeNotFound(){
		array_push($this->errors, "This is not the challenge you are looking for!");
	}
	
	/**
	 * Adds an error
	 */
	public function CommentNotFound(){
		array_push($this->errors, "Hmm the comment you are trying to remove does not seem to exists.");
	}
	
	/**
	 * Adds an error
	 */
	public function UserDoesNotOwnComment(){
		array_push($this->errors, "You cannot remove another <a href='index.php?" . self::$headerView->GetChallengeString() . "'>users</a> comment!");
	}
	
	/**
	 * Adds an error
	 */
	public function AddRemoveChallengeNotFound(){
		array_push($this->errors, "Does not seem to be able to find the challenge you want to remove!");
	}
	
	/**
	 * Adds an error
	 */
	public function AddFinishChallengeNotFound(){
		array_push($this->errors, "Are you trying to finish something that you haven´t started yet?");
	}
	
	/**
	 * Adds an error
	 */
	public function UserDoesNotHaveThatChallenge(){
		array_push($this->errors, "You know you can´t remove a challenge that you do not have, right?");
	}
	
	/**
	 * Adds an error
	 */
	public function NoCommentWithIDFound(){
		array_push($this->errors, "This is not the comment you are looking for!");
	}
	
	/**
	 * Adds an error
	 */
	public function AlreadyFriends(){
		array_push($this->errors, "Why would you try to add a friend again?");
	}
	
	/**
	 * Adds an error
	 */
	public function CannotAddYourselfAsFriend(){
		array_push($this->errors, "Why would you try to add yourself? Does not make sense!");
	}
	
	/**
	 * Adds an error
	 */
	public function NotAdmin(){
		array_push($this->errors, "You do not have permission to do that :D");
	}
	
	/**
	 * Adds an error
	 */
	public function CannotBanAnotherAdmin(){
		array_push($this->errors, "You might be an admin, but you cannot ban another admin.");
	}
	
	/**
	 * Adds an error
	 */
	public function CannotBanYourself(){
		array_push($this->errors, "What on earth have you done to think that you deserve to ban yourself? Really, what? Send an email to iwantedtobanmyself@google.se");
	}
	
	
	/**
	 * Adds an error
	 */
	public function NotBanned($ID){
		array_push($this->errors, "This user is not banned, cannot unban, but you can <a href='index.php?" . $this->banUserID . "=" . $ID . "'>ban</a> him if you want to :D (unless he is admin)");
	}
	
	/**
	 * Adds an error
	 */
	public function AlreadyBanned($ID){
		array_push($this->errors, "This user is already banned, cannot ban, but you can <a href='index.php?" . $this->unbanUserID . "=" . $ID . "'>unban</a> him if you want to :D");
	}
	
	/**
	 * Adds an error
	 */
	public function PostWentWrong(){
		array_push($this->errors, "An error accured whe you tried to submit your information, please try again.");
	}
	
	/**
	 * Adds an error
	 */
	public function NoUserFound(){
		array_push($this->errors, "Could not find that user!");
	}
	
	/**
	 * Adds an error
	 */
	public function TitleIsInvalid(){
		array_push($this->errors, "Title must be > 0 and < 51 characters long!");
	}
	
	/**
	 * Adds an error
	 */
	public function NewChallengeNameInvalid(){
		array_push($this->errors, "Name must be > 0 and < 201 characters long!");
	}
	
	/**
	 * Adds an error
	 */
	public function NewChallengeDescriptionInvalid(){
		array_push($this->errors, "Description must be > 0 and < 251 characters long!");
	}
	
	/**
	 * Adds an error
	 */
	public function NewChallengeWorthNumericInvalid(){
		array_push($this->errors, "Worth must be numeric.");
	}
	
	
	/**
	 * Adds an error
	 */
	public function NewChallengeWorthBoundsInvalid(){
		array_push($this->errors, "Worth must be > 0 and < 1 000 000 001.");
	}
	
	/**
	 * Adds an error
	 */
	public function CommentIsInvalid(){
		array_push($this->errors, "Comment must be > 0 and < 251 characters long!");
	}
	
	
	/**
	 * Adds an error
	 */
	public function UsernameIsInvalid(){
		array_push($this->errors, "Username must be > 0 and < 51 characters long!");
	}
	
	/**
	 * Adds an error
	 */
	public function UsernameAlreadyExists(){
		array_push($this->errors, "Username already exists!");
	}
	
	/**
	 * Adds an error
	 */
	public function PasswordIsInvalid(){
		array_push($this->errors, "Password must be > 0 and < 51 characters long!");
	}
	
	/**
	 * Adds an error
	 */
	public function EmailIsInvalid(){
		array_push($this->errors, "Email must be > 0 and < 201 characters long!");
	}
	
	/**
	 * Adds an error
	 */
	public function EmailAlreadyExists(){
		array_push($this->errors, "Email already exists!");
	}
	
	/**
	 * Adds an error
	 */
	public function FNameIsInvalid(){
		array_push($this->errors, "Firstname must be > 0 and < 51 characters long!");
	}
	
	/**
	 * Adds an error
	 */
	public function LNameIsInvalid(){
		array_push($this->errors, "Lastname must be > 0 and < 51 characters long!");
	}
	
	
	/**
	 * Adds an error
	 */
	public function Banned(){
		$bannedHTMLString = "UnbanAllBannedUserNow";
		
		if(isset($_GET[$bannedHTMLString])){
			array_push($this->errors, "BAH! You thought you could unban yourself that easily? :D Only admin can unban ;)");
		}
		else {
			array_push($this->errors, "You have been banned, have a nice day! Unban yourself <a href='index.php?" . $bannedHTMLString . "' >here</a>!");
		}
	}
	
	/**
	 * Adds an error
	 */
	public function DataBaseError(){
		array_push($this->errors, "A database error has occured, please try again later or press <a href='index.php'>here to go to main</a>.");
	}	
	
	/**
	 * Adds an error
	 */
	public function UnexpectedError(){
		array_push($this->errors, "An unexpected error has occured, please try again later or press <a href='index.php'>here to go to main</a>.");
	}
	
	/**
	 * Adds success message
	 */
	public function AddCommentToCommentSucess(){
		array_push($this->succeded, "Your comment has been added :)!");
	}
	
	/**
	 * Adds success message
	 */
	public function AddCommentToChallengeSucess(){
		array_push($this->succeded, "Your comment has been added :)!");
	}
	
	/**
	 * Adds success message
	 */
	public function AddCommentRemoveSucess(){
		array_push($this->succeded, "Your comment has been removed, did it was something racist or stupid?!");
	}
	
	/**
	 * Adds success message
	 */
	public function AddTakeChallengeSucess(){
		array_push($this->succeded, "Good luck with your challenge! :)!");
	}
	
	/**
	 * Adds success message
	 */
	public function AddRemoveChallengeSucess(){
		array_push($this->succeded, "You no longer have to care about that shitty challenge, it was shitty anyways...");
	}
	
	/**
	 * Adds success message
	 */
	public function AddDestroyChallengeSucess(){
		array_push($this->succeded, "The challenge have been removed from the database!");
	}
	
	/**
	 * Adds success message
	 */
	public function AddChallengeFriendSucess(){
		array_push($this->succeded, "You have challenged another user(s)!");
	}
	
	/**
	 * Adds success message
	 */
	public function AddAddNewChallengeSucess(){
		array_push($this->succeded, "The challenge have been added to the database!");
	}
	
	/**
	 * Adds success message
	 */
	public function AddFinishChallengeSucess(){
		array_push($this->succeded, "Well look at you, a real challenger! You have been awarded points, go to \" MyStuff\" to see your score :) ");
	}
	
		
	/**
	 * Adds an error
	 */
	public function AddFriendAddedSucess(){
		array_push($this->succeded, "Looks like you made yourself a friend, stalk away dear challenger!");
	}
	
	/**
	 * Adds an error
	 */
	public function AddFriendRemoveSucess(){
		array_push($this->succeded, "Screw that guy!");
	}
	
	/**
	 * Adds an error
	 */
	public function AddUserCanNowLogin($name){
		array_push($this->succeded, "Well, well, well, look who we got here, you think you got what it takes \"" . $name . "\" to be a real challenger? Login <a href='index.php?'>here</a>!");
	}
	
	/**
	 * Adds an error
	 */
	public function AddBanSuccessfull(){
		array_push($this->succeded, "User have been banned! I hope you are happy...");
	}
	
	/**
	 * Adds an error
	 */
	public function AddUnBanSuccessfull(){
		array_push($this->succeded, "He didn´t deserve to get banned? Anyways he is not anymore :D");
	}
	
	/**
	 * @return HTML with all errors
	 */
	public function GetErrorsHTML(){
		$html = "
			<div class='errors'>
			<label> We found some error for you to take care of!</label>
		";
		
		for ($i=0; $i < count($this->errors); $i++) { 
			$html .= "<p> #" . $i . " ". $this->errors[$i] . "</p>";
		}
		
		$html .= "</div>";
		
		return $html;
	}


	/**
	 * @return HTML with all things that went well
	 */
	public function GetSuccededHTML(){
		$html = "
			<div class='success'>
		";
		
		for ($i=0; $i < count($this->succeded); $i++) { 
			$html .= "<p> #" . $i . " ". $this->succeded[$i] . "</p>";
		}
		
		$html .= "</div>";
		
		return $html;
	}
	
	/**
	 * @return Bool if any errors
	 */
	public function AnyErrors(){
		return count($this->errors) > 0;
	}
	
	/**
	 * @return Bool if anything went well
	 */
	public function AnySuccess(){
		return count($this->succeded) > 0;
	}
	
	public function OneDoesNotTryToInjectHTML(){
		return "<img src='https://i.imgflip.com/akgxl.jpg'>";
	}
}
