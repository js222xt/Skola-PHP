<?php

namespace controller;

class UserController{
	
	/** @var \view\ApplicationView */
	private $applicationView;
	/** @var \model\UserModel */
	private $userModel;
	
	
	
	// REMOVE!!
	/** @var \model\db\ApplicationDAL */
	private $applicationDAL;
	
	public function __construct($appView, $appDAL){
		$this->applicationView = $appView;
		$this->applicationDAL = $appDAL;
		$this->userModel = new \Model\UserModel();
	}
	
	/**
	 * @return String HTML
	 */
	public function GetFriendsHTML($loggedInUser){
		$html ="";
		// get all user friends
		$friendsID = $this->applicationDAL->GetAllFriendsIDForUser($loggedInUser[0]['ID']);
		
		if(count($friendsID) != 0){
			// We have friends

			// Show them
			for ($i=0; $i < count($friendsID); $i++) {
				// Get this friend
				if($friendsID[$i]['PID1'] != $loggedInUser[0]['ID']){
					$friend = $this->applicationDAL->GetUser($friendsID[$i]['PID1']);
				}
				else {
					$friend = $this->applicationDAL->GetUser($friendsID[$i]['PID2']);
				}							
				
				$html .= $this->applicationView->ShowFriend($friend, $loggedInUser[0]['IsAdmin'], $friend[0]['Banned']);
			}
		}
		else {
			// We do not have any friends
			$html .= $this->applicationView->UserHasNoFriendsHTML();
		}
		
		return $html;
	}
	
	public function GetAllFriends($loggedInUser){
		// get all user friends
		$friendsID = $this->applicationDAL->GetAllFriendsIDForUser($loggedInUser[0]['ID']);
		
		$friends = array();
		// Add them
			for ($i=0; $i < count($friendsID); $i++) {
				// Get this friend
				if($friendsID[$i]['PID1'] != $loggedInUser[0]['ID']){
					$friend = $this->applicationDAL->GetUser($friendsID[$i]['PID1']);
				}
				else {
					$friend = $this->applicationDAL->GetUser($friendsID[$i]['PID2']);
				}							
				
				array_push($friends, $friend);
			}
		
		return $friends;
	}
	
	/**
	 * @return String HTML
	 */
	public function GetAllUsersHTML($loggedInUser){
		$html = "";
		
		// Get all users
		$users = $this->userModel->GetAllUsers();
		
		for ($i=0; $i < count($users); $i++) { 
			// Are user friend with this user?
			$friendsID = $this->applicationDAL->GetAllFriendsIDForUser($loggedInUser[0]['ID']);
			
			// If it´s not us
			if($users[$i]['ID'] != $loggedInUser[0]['ID']){
				// Friend allready?
				if($this->userModel->UserIsFriend($friendsID, $users[$i]['ID'])){
					$html .= $this->applicationView->ShowUser($users[$i], true, $loggedInUser[0]['IsAdmin'], $users[$i]['Banned']);
				}
				else {
					$html .= $this->applicationView->ShowUser($users[$i], false, $loggedInUser[0]['IsAdmin'], $users[$i]['Banned']);
				}
			}						
		}
		
		return $html;
	}
	
	public function AddFriendForUser($loggedInUser){
		// Get ID
		$AID = $this->applicationView->GetAddUserIDFromGet();
		$friends = $this->applicationDAL->GetAllFriendsIDForUser($loggedInUser[0]['ID']);
		$code = $this->userModel->AddFriendForUser($AID, $friends, $loggedInUser);
		
		switch ($code) {
			case \model\UserModel::AddFriendAddedSucess:
				$this->applicationView->AddFriendAddedSucess();
				break;
			case \model\UserModel::CannotAddYourselfAsFriend:
				$this->applicationView->CannotAddYourselfAsFriend();
				break;
			case \model\UserModel::AlreadyFriends:
				$this->applicationView->AlreadyFriends();
				break;
			case \model\UserModel::NoUserFound:
				$this->applicationView->NoUserFound();
				break;
		}
	}
	
	public function RemoveFriendFromUser($loggedInUser){		
		
		// Get ID
		$AID = $this->applicationView->GetAddUserIDFromGet();
		$code = $this->userModel->RemoveFriendFromUser($AID, $loggedInUser);
		
		switch ($code) {
			case \model\UserModel::AddFriendRemoveSucess:
				$this->applicationView->AddFriendRemoveSucess();
				break;
			case \model\UserModel::NoUserFound:
				$this->applicationView->NoUserFound();
				break;
		}
	}
	
	public function AdminBanUser($isAdmin, $loggedInUser){
		
		$AID = $this->applicationView->GetBanUserIDFromGET();
		$user = $this->applicationDAL->GetUser($AID);
		$code = $this->userModel->AdminBanUser($AID, $isAdmin, $loggedInUser, $user);
		
		switch ($code) {
			case \model\UserModel::AddBanSuccessfull:
				$this->applicationView->AddBanSuccessfull();
				break;
			case \model\UserModel::AlreadyBanned:
				$this->applicationView->AlreadyBanned($user[0]['ID']);
				break;
			case \model\UserModel::CannotBanAnotherAdmin:
				$this->applicationView->CannotBanAnotherAdmin();
				break;
			case \model\UserModel::CannotBanYourself:
				$this->applicationView->CannotBanYourself();
				break;
			case \model\UserModel::NoUserFound:
				$this->applicationView->NoUserFound();
				break;
			case \model\UserModel::NotAdmin:
				$this->applicationView->NotAdmin();
				break;
		}
	}
	
	public function AdminUnbanUser($isAdmin){
		
		$AID = $this->applicationView->GetUnBanUserIDFromGET();
		$code = $this->AdminUnbanUser($AID, $isAdmin, $loggedInUser, $user);
		
		switch ($code) {
			case \model\UserModel::AddUnBanSuccessfull:
				$this->applicationView->AddUnBanSuccessfull();
				break;
			case \model\UserModel::NotBanned:
				$this->applicationView->NotBanned($user[0]['ID']);
				break;
			case \model\UserModel::NoUserFound:
				$this->applicationView->NoUserFound();
				break;
			case \model\UserModel::NotAdmin:
				$this->applicationView->NotAdmin();
				break;
		}
	}
	
	/**
	 * @return String HTML
	 */
	public function ShowUserHTML($loggedInUser, $challengeController){
		$html ="";
		$ID = $this->applicationView->GetUserIDFromGet();
	
		if($ID != -1 && is_numeric($ID)){
			// Get user
			$user = $this->applicationDAL->GetUser($ID);
			if(count($user) == 1){
				// Show Account information
				$html .= $this->applicationView->GetAccountInformationHTML($user);
				
				// Show all active and completed challenges	
				$html .= $challengeController->GetActiveAndCompletedChallengesHTML($user[0]['Username'], $ID, $loggedInUser, $this->GetAllFriends($loggedInUser));					
				
				return $html;
			}
			else {
				$this->applicationView->NoUserFound();
			}						
		}
		else {
			$this->applicationView->NoUserFound();
		}
	}
}
