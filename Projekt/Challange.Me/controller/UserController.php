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
		$friendsID = $this->applicationDAL->GetAllFriendsIDForUser($loggedInUser->GetID());
		
		if(count($friendsID) != 0){
			// We have friends

			// Show them
			for ($i=0; $i < count($friendsID); $i++) {
				// Get this friend
				if($friendsID[$i]['PID1'] != $loggedInUser->GetID()){
					$friendAr = $this->userModel->GetUser($friendsID[$i]['PID1']);
				}
				else {
					$friendAr = $this->userModel->GetUser($friendsID[$i]['PID2']);
				}							
				
				$friend = new \model\User($friendAr[0]['ID'],$friendAr[0]['Username'],$friendAr[0]['APassword'],$friendAr[0]['Email'],
											$friendAr[0]['FName'],$friendAr[0]['challengePoints'],$friendAr[0]['LName'] ,$friendAr[0]['IsAdmin'],$friendAr[0]['Banned']);
				
				$html .= $this->applicationView->ShowFriend($friend, $loggedInUser->GetIsAdmin(), $friend->GetIsBanned());
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
		$friendsID = $this->applicationDAL->GetAllFriendsIDForUser($loggedInUser->GetID());
		
		$friends = array();
		// Add them
			for ($i=0; $i < count($friendsID); $i++) {
				// Get this friend
				if($friendsID[$i]['PID1'] != $loggedInUser->GetID()){
					$friend = $this->userModel->GetUser($friendsID[$i]['PID1']);
				}
				else {
					$friend = $this->userModel->GetUser($friendsID[$i]['PID2']);
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
		$usersArray = $this->userModel->GetAllUsers();
		$users = array();
		foreach($usersArray as $user){
			array_push($users, new \model\User($user['ID'],$user['Username'],$user['APassword'],$user['Email'],
											   $user['FName'],$user['challengePoints'],$user['LName'] ,$user['IsAdmin'],$user['Banned']));
		}
		
		for ($i=0; $i < count($users); $i++) { 
			// Are user friend with this user?
			$friendsID = $this->applicationDAL->GetAllFriendsIDForUser($loggedInUser->GetID());
			
			// If it´s not us
			if($users[$i]->GetID() != $loggedInUser->GetID()){
				// Friend allready?
				if($this->userModel->UserIsFriend($friendsID, $users[$i]->GetID())){
					$html .= $this->applicationView->ShowUser($users[$i], true, $loggedInUser->GetIsAdmin(), $users[$i]->GetIsBanned());
				}
				else {
					$html .= $this->applicationView->ShowUser($users[$i], false, $loggedInUser->GetIsAdmin(), $users[$i]->GetIsBanned());
				}
			}						
		}
		
		return $html;
	}
	
	public function AddFriendForUser($loggedInUser){
		// Get ID
		$AID = $this->applicationView->GetAddUserIDFromGet();
		$friends = $this->applicationDAL->GetAllFriendsIDForUser($loggedInUser->GetID());
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
		$AID = $this->applicationView->GetRemoveUserIDFromGet();
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
		$userArray = $this->userModel->GetUser($AID);
		$user = new \model\User($userArray[0]['ID'],$userArray[0]['Username'],$userArray[0]['APassword'],$userArray[0]['Email'],
								$userArray[0]['FName'],$userArray[0]['LName'],$userArray[0]['challengePoints'] ,$userArray[0]['IsAdmin'],$userArray[0]['Banned']);
		$code = $this->userModel->AdminBanUser($AID, $isAdmin, $loggedInUser, $user);
		
		switch ($code) {
			case \model\UserModel::AddBanSuccessfull:
				$this->applicationView->AddBanSuccessfull();
				break;
			case \model\UserModel::AlreadyBanned:
				$this->applicationView->AlreadyBanned($user->GetID());
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
	
	/**
	 * @param bool if user is admin
	 * @param User obj of current logged in user
	 */
	public function AdminUnbanUser($isAdmin, $loggedInUser){
		
		$AID = $this->applicationView->GetUnBanUserIDFromGET();
		$userArray = $this->userModel->GetUser($AID);
		$user = new \model\User($userArray[0]['ID'],$userArray[0]['Username'],$userArray[0]['APassword'],$userArray[0]['Email'],
								$userArray[0]['FName'],$userArray[0]['LName'],$userArray[0]['challengePoints'] ,$userArray[0]['IsAdmin'],$userArray[0]['Banned']);
		$code = $this->userModel->AdminUnbanUser($AID, $isAdmin, $loggedInUser, $user);
		
		switch ($code) {
			case \model\UserModel::AddUnBanSuccessfull:
				$this->applicationView->AddUnBanSuccessfull();
				break;
			case \model\UserModel::NotBanned:
				$this->applicationView->NotBanned($user->GetID());
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
			$userArray = $this->userModel->GetUser($ID);
			
			if(count($userArray) == 1){
				$user = new \model\User($userArray[0]['ID'],$userArray[0]['Username'],$userArray[0]['APassword'],$userArray[0]['Email'],
										$userArray[0]['FName'],$userArray[0]['LName'],$userArray[0]['challengePoints'] ,$userArray[0]['IsAdmin'],$userArray[0]['Banned']);
				// Show Account information
				$html .= $this->applicationView->GetAccountInformationHTML($user);
				
				// Show all active and completed challenges	
				$html .= $challengeController->GetActiveAndCompletedChallengesHTML($user->GetUsername(), $ID, $loggedInUser, $this->GetAllFriends($loggedInUser));					
				
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
