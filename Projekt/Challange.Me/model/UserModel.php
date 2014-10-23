<?php

namespace model;

class UserModel{
	
	/** @var \model\db\UserDAL */
	private $userDAL;
	
	const AddFriendAddedSucess = 0;
	const CannotAddYourselfAsFriend = 1;
	const AlreadyFriends = 2;
	const NoUserFound = 3;
	const AddFriendRemoveSucess = 4;
	const AddBanSuccessfull = 5;
	const CannotBanAnotherAdmin = 6;
	const CannotBanYourself = 7;
	const NotAdmin = 8;
	const AlreadyBanned = 9;
	const AddUnBanSuccessfull = 10;
	const NotBanned = 11;
	
	/**
	 * @param \model\db\UserDAL
	 */
	public function __construct(){
		$this->userDAL = new \model\db\UserDAL();		
	}
	
	/**
	 * @param int user ID
	 * @param Array with all friends that the user has
	 * @param Array with Current logged in user
	 * @return int code
	 */
	public function AddFriendForUser($AID, $friends, $loggedInUser){
		// Validate
		if($AID != -1 && is_numeric($AID)){
			
			if(!$this->UserIsFriend($friends, $AID)){
				// Cannot add yourself
				if($AID != $loggedInUser->GetID()){
					// Add to database
					$this->userDAL->AddFriend($loggedInUser->GetID(), $AID);
					
					return self::AddFriendAddedSucess;
				}
				else {
					return self::CannotAddYourselfAsFriend;
				}				
			}
			else {
				return self::AlreadyFriends;
			}
		}
		else{
			return self::NoUserFound;
		}
	}
	
	/**
	 * @param int user ID
	 * @param Array with Current logged in user
	 * @return int code
	 */
	public function RemoveFriendFromUser($AID, $loggedInUser){
		// Validate
		if($AID != -1 && is_numeric($AID) && count($this->userDAL->GetUser($AID)) == 1){
			// Remove
			$this->userDAL->RemoveFriend($loggedInUser->GetID(), $AID);
			
			return self::AddFriendRemoveSucess;
		}
		else {
			return self::NoUserFound;
		}
	}
	
	/**
	 * @param int user ID
	 * @param bool is user is admin
	 * @param Array with Current logged in user
	 * @param Array user to ban
	 */
	public function AdminBanUser($AID, $isAdmin, $loggedInUser, $user){
		if($isAdmin){
			
			if(count($user) == 1  && $AID != -1 && is_numeric($AID)){
				if($user->GetID() != $loggedInUser->GetID()){
					// Can we ban this user?
					if(!$user->GetIsAdmin()){
						
						if(!$user->GetIsBanned()){
							// Ban
							$this->userDAL->BanUser($AID);
							
							return self::AddBanSuccessfull;
						}
						else {
							return self::AlreadyBanned;
						}
					}
					else {
						return self::CannotBanAnotherAdmin;
					}
				}				
				else {
					return self::CannotBanYourself;
				}
			}
			else {
				return self::NoUserFound;
			}						
		}
		else {
			return self::NotAdmin;
		}
	}
	
	public function AdminUnbanUser($AID, $isAdmin, $loggedInUser, $user){

		if($isAdmin){			
			if($AID != -1 && is_numeric($AID)){
				// Can we ban this user?
				if($user->GetIsBanned()){
					// UnBan
					$this->userDAL->UnBanUser($AID);
					
					return self::AddUnBanSuccessfull;
				}
				else {
					return self::NotBanned;
				}				
			}					
			else {
				return self::NoUserFound;
			}
		}
		else {
			return self::NotAdmin;
		}
	}
	
	/**
	 * @var Array with friends ID´s
	 * @var int user ID
	 */
	public function UserIsFriend($friends, $userID){

		for ($i=0; $i < count($friends); $i++) { 
			if($friends[$i]['PID1'] == $userID || $friends[$i]['PID2'] == $userID){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * @return array with all users
	 */
	public function GetAllUsers(){
		return $this->userDAL->GetAllUsers();
	}
	
	/**
	 * @return array with all users
	 */
	public function GetUser($ID){
		return $this->userDAL->GetUser($ID);
	}
	
}
