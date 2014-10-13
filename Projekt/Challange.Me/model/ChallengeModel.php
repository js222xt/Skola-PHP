<?php

namespace model;

class ChallengeModel{
	
	const AddFinishChallengeSucess = 1;
	const AddFinishChallengeNotFound  = 2;
	const ChallengeNotFound = 3;
	const AddTakeChallengeSucess = 4;
	const AddDestroyChallengeSucess = 5;
	const NotAdmin = 6;
	
	/** @var \model\db\ChallengeDAL */
	private $challengeDAL;
	
	public function __construct(){
		$this->challengeDAL = new \model\db\ChallengeDAL();
	}
	
	/**
	 * @var Array with challenges ID´s
	 * @var int challenge ID
	 */
	public function UserHasChallenge($CIDS, $CID){
		for ($i=0; $i < count($CIDS); $i++) { 
			if($CIDS[$i]['CID'] == $CID){
				return true;
			}
			else{
				return false;
			}
		}
		
		return false;
	}
	
	/**
	 * @return Array with challenges
	 */
	public function GetAllChallenges(){
		return $this->challengeDAL->GetAllChallenges();
	}
	
	/**
	 * @param int ID
	 * @return Array with challenges that a user has
	 */
	public function GetAllChallangesIDForID($ID){
		return $this->challengeDAL->GetAllChallangesIDForID($ID);
	}
	
	/**
	 * @param int challenge ID
	 * @param int acccount ID
	 * @return int code
	 */
	public function FinishAChallenge($CID, $AID){
		if($CID != -1 && is_numeric($CID)){
			// Validate
			
			// Does user has the challenge?
			// Get challenges
			$challenges = $this->challengeDAL->GetAllChallangesIDForID($AID);
			if($this->UserHasChallenge($challenges, $CID)){
				
				// Database stuff
				$this->challengeDAL->FinishChallengeForUser($CID, $AID);	
				
				return self::AddFinishChallengeSucess;
			}
			else {
				return self::AddFinishChallengeNotFound;
			}
		}			
		else {
			return self::ChallengeNotFound;
		}
	}
	
	/**
	 * @param int account ID
	 * @param int challenge ID
	 * @return int code
	 */
	public function TakeChallenge($AID, $ID){
		if($ID != -1 && is_numeric($ID)){
			// Get challenges
			$challenges = $this->challengeDAL->GetAllChallanges();
			
			// If we find the challenge
			$foundChallenge = false;
			
			// Get the one we want
			for ($i=0; $i < count($challenges); $i++) { 
				if($ID == $challenges[$i][0]){
					$foundChallenge = true;
					
					// Take Challenge
					$this->challengeDAL->TakeChallenge($loggedInUser[0]['ID'],$ID);
					
					return self::AddTakeChallengeSucess;					
				}
			}
		
			// We have not found our challenge!
			if(!$foundChallenge){
				return self::ChallengeNotFound;
			}
		}
		else {
			return self::ChallengeNotFound;
		}	
	}
	
	/**
	 * @param bool if user is admin
	 * @param int challenge ID
	 * @return int code
	 */
	public function AdminRemoveChallenge($isAdmin, $CID){
		// If we are admin
		if($isAdmin){
			
			if($CID != -1 && is_numeric($CID)){
				$this->challengeDAL->RemoveAChallegneFromDatabase($CID);
				
				return self::AddDestroyChallengeSucess;
			}
			else {
				return self::ChallengeNotFound;
			}
		}
		else {
			return self::NotAdmin;
		}	
	}
}
