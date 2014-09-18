<?php

namespace controller;

class UserController{
	
	/** @var \view\ApplicationView */
	private $applicationView;
	/** @var \model\db\ApplicationDAL */
	private $applicationDAL;
	
	public function __construct($appView, $appDAL){
		$this->applicationView = $appView;
		$this->applicationDAL = $appDAL;
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
				
				$html .= $this->applicationView->ShowFriend($friend, $loggedInUser[0]['IsAdmin'], $friend[$i]['Banned']);
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
		$users = $this->applicationDAL->GetAllUsers();
		
		for ($i=0; $i < count($users); $i++) { 
			// Are user friend with this user?
			$friendsID = $this->applicationDAL->GetAllFriendsIDForUser($loggedInUser[0]['ID']);
			
			// If it´s not us
			if($users[$i]['ID'] != $loggedInUser[0]['ID']){
				// Friend allready?
				if($this->UserIsFriend($friendsID, $users[$i]['ID'])){
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

		// Validate
		if($AID != -1 && is_numeric($AID) && count($this->applicationDAL->GetUSer($AID)) == 1){
			
			// Check if we try to add a friend that we already got
			$friends = $this->applicationDAL->GetAllFriendsIDForUser($loggedInUser[0]['ID']);
			
			if(!$this->UserIsFriend($friends, $AID)){
				// Cannot add yourself
				if($AID != $loggedInUser[0]['ID']){
					// Add to database
					$this->applicationDAL->AddFriend($loggedInUser[0]['ID'], $AID);
					
					$this->applicationView->AddFriendAddedSucess();
				}
				else {
					$this->applicationView->CannotAddYourselfAsFriend();
				}
				
			}
			else {
				$this->applicationView->AlreadyFriends();
			}
		}
		else{
			$this->applicationView->NoUserFound();
		}
	}
	
	public function RemoveFriendFromUser($loggedInUser){
		// Get ID
		$AID = $this->applicationView->GetRemoveUserIDFromGet();
		
		// Validate
		if($AID != -1 && is_numeric($AID) && count($this->applicationDAL->GetUSer($AID)) == 1){
			// Remove
			$this->applicationDAL->RemoveFriend($loggedInUser[0]['ID'], $AID);
			
			$this->applicationView->AddFriendRemoveSucess();
		}
		else {
			$this->applicationView->NoUserFound();
		}
	}
	
	/**
	 * @var Array with friends ID´s
	 * @var int user ID
	 */
	private function UserIsFriend($friends, $userID){
		var_dump($friends);
		for ($i=0; $i < count($friends); $i++) { 
			if($friends[$i]['PID1'] == $userID || $friends[$i]['PID2'] == $userID){
				return true;
			}
			echo $friends[$i]['ID'] . "  " . $userID;
		}
		return false;
	}
	
	public function AdminBanUser($isAdmin, $loggedInUser){
		if($isAdmin){
			// Get user ID who is about to get banned
			$AID = $this->applicationView->GetBanUserIDFromGET();
			
			// Get user from DB
			$user = $this->applicationDAL->GetUser($AID);
			
			if(count($user) == 1  && $AID != -1){
				if($user[0]['ID'] != $loggedInUser[0]['ID']){
					// Can we ban this user?
					if(!$user[0]['IsAdmin']){
						
						if(!$user[0]['Banned']){
							// Ban
							$this->applicationDAL->BanUser($AID);
							
							$this->applicationView->AddBanSuccessfull();
						}
						else {
							$this->applicationView->AlreadyBanned($user[0]['ID']);
						}
					}
					else {
						$this->applicationView->CannotBanAnotherAdmin();
					}
				}				
				else {
					$this->applicationView->CannotBanYourself();
				}
			}
			else {
				$this->applicationView->NoUserFound();
			}						
		}
		else {
			$this->applicationView->NotAdmin();
		}
	}
	
	public function AdminUnbanUser($isAdmin){
		if($isAdmin){
			// Get user ID who is about to get banned
			$AID = $this->applicationView->GetUnBanUserIDFromGET();
			
			if($AID != -1 && is_numeric($AID)){
				// Get user from DB
				$user = $this->applicationDAL->GetUser($AID);

				if(count($user) == 1){
					// Can we ban this user?
					if($user[0]['Banned']){
						// UnBan
						$this->applicationDAL->UnBanUser($AID);
						
						$this->applicationView->AddUnBanSuccessfull();
						$successFound = true;
					}
					else {
						$this->applicationView->NotBanned($user[0]['ID']);
					}	
				}
				else {
					$this->applicationView->NoUserFound();
				}
				
			}					
			else {
				$this->applicationView->NoUserFound();
			}
		}
		else {
			$this->applicationView->NotAdmin();
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
