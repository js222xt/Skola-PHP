<?php


namespace controller;

class ChallengeController{
	
	/** @var \view\ApplicationView */
	private $applicationView;
	/** @var \model\db\ApplicationDAL */
	private $applicationDAL;
	/** @var \model\ChallengeModel */
	private $challengeModel;
	/** @var \model\UserModel */
	private $userModel;
	
	public function __construct($appView, $appDAL){
		$this->applicationView = $appView;
		$this->applicationDAL = $appDAL;
		$this->challengeModel = new \model\ChallengeModel();
		$this->userModel = new \Model\UserModel();
	}
		
	
	
	/**
	 * @return String HTML
	 */
	public function ShowAddChallengeHTML(){
		return $this->applicationView->ShowAddChallengeHTML();
	}
	
	/**
	 * @return String HTML
	 */
	public function GetChallengesHTML($loggedInUser, $friends){
		
		$isAdmin = $loggedInUser->GetIsAdmin();
		$isBanned = $loggedInUser->GetIsBanned();
		
		$html ="";
		$html.= $this->applicationView->GetChallengeHeaderHTML();
						
		// Show all challenges
		
		// Get Array with all challanges
		$challenges = $this->challengeModel->GetAllChallenges();

		// Get Array with all challanges ID
		$challengesID = $this->challengeModel->GetAllChallangesIDForID($loggedInUser->GetID());
		
		for ($i=0; $i < count($challenges); $i++) {
		
			if($this->challengeModel->UserHasChallenge($challengesID, $challenges[$i]->GetID())){
				// Get HTML
				$html .= $this->applicationView->ShowChallenge($challenges[$i], true, $isAdmin, $friends);
			}
			else{
				$html .= $this->applicationView->ShowChallenge($challenges[$i], false, $isAdmin, $friends);
			}					
		}
		
		return $html;	
	}
	
	/**
	 * @return String HTML
	 */
	public function ShowChallengeHTML($loggedInUser, $friends){
		$html ="";
		// Update challenge list from Database
		$challenges = $this->challengeModel->GetAllChallenges();

		// If we find the challenge
		$foundChallenge = false;
		
		// Get the one we want
		$ID = $this->applicationView->GetSeeChallengeIDFromGet(); 

		for ($i=0; $i < count($challenges); $i++) {
			
			if($ID == $challenges[$i]->GetID()){

				$foundChallenge = true;
				
				// Get Array with all challanges ID
				$challengesID = $this->challengeModel->GetAllChallangesIDForID($loggedInUser->GetID());

				if($this->challengeModel->UserHasChallenge($challengesID, $challenges[$i]->GetID())){
					// Get HTML
					$html .= $this->applicationView->ShowChallenge($challenges[$i], true, $loggedInUser->GetIsAdmin(), $friends);
				}
				else{
					$html .= $this->applicationView->ShowChallenge($challenges[$i], false, $loggedInUser->GetIsAdmin(), $friends);
				}								
				
				// Get all comments
				$comments = $this->applicationDAL->GetComments($challenges[$i]->GetID());
				
				// Show comment system
				$html .= $this->applicationView->ShowCommentSystemStart();

				if(count($comments) > 0){
					for ($i=0; $i < count($comments); $i++) { 
												
					
						// Get user who wrote base comment
	
						$user = $this->userModel->GetUser($comments[$i]['AID']);
						
						if(count($user) == 1){
							// Is it our user?
							$ourUser = $loggedInUser->GetID() == $user[0]['ID'] ? true : false;
						
							$html .= $this->applicationView->ShowComment($comments[$i], $user, $ourUser, $loggedInUser->GetIsAdmin());
						}
						else {
							$this->applicationView->NoUserFound();
							$errorsFound = true;
						}
					}
				}
				
				$html .= $this->applicationView->ShowCommentSystemEnd();				
			}
		}
		
		// We have not found our challenge!
		if(!$foundChallenge){
			$this->applicationView->ChallengeNotFound();
			return "";
		}
		else {
			return $html;
		}
	}

	/**
	 * @var String
	 * @var String
	 * @return Stting
	 */
	public function GetActiveAndCompletedChallengesHTML($username,$ID, $loggedInUser, $challengeFriendHTML){
		$html ="";
		// Get Array with all challanges
		$challenges = $this->challengeModel->GetAllChallenges();
		// Get Array with all challanges ID
		$challengesID = $this->challengeModel->GetAllChallangesIDForID($ID);
		// get all challenges					
		$myActiveChallenges = array();
		for ($i=0; $i < count($challenges); $i++) { 
			for ($j=0; $j < count($challengesID); $j++) {
				// If ID match 
				if($challenges[$i]->GetID() == $challengesID[$j]['CID']){
					// Add
					array_push($myActiveChallenges, $challenges[$i]);
				}
			}
		}

		if(count($myActiveChallenges) > 0){
			echo "ad";
			// Get HTML
			if($loggedInUser->GetID() == $ID){
				$html .= $this->applicationView->GetMyActiveChallengesHeaderHTML($username, true, $loggedInUser->GetID());
			}
			else {
				$html .=  $this->applicationView->GetMyActiveChallengesHeaderHTML($username, false, $ID);
			}
			$html .= $this->applicationView->ShowChallenges($myActiveChallenges, true, $loggedInUser->GetIsAdmin(), $challengeFriendHTML);
		}
		else{
			if($loggedInUser->GetID() == $ID){
				$html .= $this->applicationView->UserHasNoChallengesHTML(true);
			}
			else {
				$html .= $this->applicationView->UserHasNoChallengesHTML(false);
			}
		}

		// Show all my completed challenges
	
		// Get Array with all completed challanges ID
		$completedChallengesID = $this->applicationDAL->GetAllCompletedChallangesIDForID($ID);
		// get all challenges					
		$myCompletedChallenges = array();
		for ($i=0; $i < count($challenges); $i++) { 
			for ($j=0; $j < count($completedChallengesID); $j++) {
				// If ID match 
				if($challenges[$i]->GetID() == $completedChallengesID[$j]['CID']){
					// No point in showing a challenge more then once
					if(!in_array($challenges[$i], $myCompletedChallenges)){
						// Add
						array_push($myCompletedChallenges, $challenges[$i]);
					}					
				}
			}
		}

		if(count($myCompletedChallenges) > 0){
			// Get HTML
			if($loggedInUser->GetID() == $ID){
				$html .= $this->applicationView->GetMyCompletedChallengesHeaderHTML($username, true, $loggedInUser->GetID());
			}
			else {
				$html .= $this->applicationView->GetMyCompletedChallengesHeaderHTML($username, false, $ID);
			}

			$html .= $this->applicationView->ShowChallenges($myCompletedChallenges, false, $loggedInUser->GetIsAdmin(), $challengeFriendHTML);
		}
		else{
			if($loggedInUser->GetID() == $ID){
				$html .= $this->applicationView->UserHasNoCompletedChallengesHTML(true);
			}
			else{
				$html .= $this->applicationView->UserHasNoCompletedChallengesHTML(false);
			}
		}

		return $html;
	}
	
	
	public function RemoveChallengeFromUser($loggedInUser){
		// Get which one, if -1 error
		$CID = $this->applicationView->GetRemoveChallengeIDFromGet();
		if($CID != -1 && is_numeric($CID)){
			// Validate
		
			// Does user has the challenge?
			// Get challenges
			$challenges = $this->challengeModel->GetAllChallangesIDForID($loggedInUser->GetID());
			if($this->challengeModel->UserHasChallenge($challenges, $CID)){
				
				// Remove from Database
				$this->applicationDAL->RemoveChallengeFromUser($CID,$loggedInUser->GetID());
		
				$this->applicationView->AddRemoveChallengeSucess();
			}
			else {
				$this->applicationView->UserDoesNotHaveThatChallenge();
			}
		}
		else {
			$this->applicationView->AddRemoveChallengeNotFound();
		}
	}
	
	public function FinishAChallenge($loggedInUser){
		// Get which one, if -1 error
		$CID = $this->applicationView->GetFinishChallengeIDFromGet();
		$AID = $loggedInUser->GetID();
		
		$code = $this->challengeModel->FinishAChallenge($CID, $AID);

		switch ($code) {
			case \model\ChallengeModel::AddFinishChallengeSucess :
				$this->applicationView->AddFinishChallengeSucess();
				break;
			case \model\ChallengeModel::AddFinishChallengeNotFound:
				$this->applicationView->AddFinishChallengeNotFound();
				break;
			case \model\ChallengeModel::ChallengeNotFound:
				$this->applicationView->ChallengeNotFound();
				break;
		}
	}
	
	public function TakeChallenge($loggedInUser){
		// Take the challenge, if id = -1 error
		$ID = $this->applicationView->GetTakeChallengeIDFromGet();
		$AID = $loggedInUser->GetID();
		
		$code = $this->challengeModel->TakeChallenge($AID, $ID, $loggedInUser);
		
		switch ($code) {
			case \model\ChallengeModel::AddTakeChallengeSucess :
				$this->applicationView->AddTakeChallengeSucess();
				break;
			case \model\ChallengeModel::ChallengeNotFound:
				$this->applicationView->ChallengeNotFound();
				break;
		}
	}
	
	public function AdminRemoveChallenge($isAdmin){
		// Get Challegne ID
		$CID = $this->applicationView->GetDestroyChallengeIDFromGET();
				
		$code = $this->challengeModel->AdminRemoveChallenge($isAdmin, $CID);
		
		switch ($code) {
			case \model\ChallengeModel::AddDestroyChallengeSucess :
				$this->applicationView->AddDestroyChallengeSucess();
				break;
			case \model\ChallengeModel::ChallengeNotFound:
				$this->applicationView->ChallengeNotFound();
				break;
			case \model\ChallengeModel::NotAdmin:
				$this->applicationView->NotAdmin();
				break;
		}	
	}
	
	/**
	 * @return String HTML
	 */
	public function AdminAddChallengeToDB($isAdmin){
		$html ="";
		if($isAdmin){
							
			// Admin has alredy tried to submit
			if($this->applicationView->AdminHavePostedNewChallengeInfo()){
				// Get info
				$name = $this->applicationView->GetNewChallengeNameFromPOST();
				$description = $this->applicationView->GetNewChallengeDescriptionFromPOST();
				$worth = $this->applicationView->GetNewChallengeWorthFromPOST();
				
				$errorsFound = false;
				
				// Validate
				if(strlen($name) == 0 || strlen($name) > 200){
					$this->applicationView->NewChallengeNameInvalid();
					$errorsFound = true;
				}
				if(strlen(trim($description)) == 0 || strlen($description) > 250){
					$this->applicationView->NewChallengeDescriptionInvalid();
					$errorsFound = true;
				}
				if(!is_numeric($worth)){
					$this->applicationView->NewChallengeWorthNumericInvalid();
					$errorsFound = true;
				}
				if($worth < 0 || $worth > 1000000000){
					$this->applicationView->NewChallengeWorthBoundsInvalid();
					$errorsFound = true;
				}
				
				if(!$errorsFound){
					// Add to db
					$this->applicationDAL->AddChallengeToDB($name, $description, $worth);
					
					$this->applicationView->AddAddNewChallengeSucess();
				}
				
				$html.= $this->applicationView->GetAddChallengeHTML($name, $description, $worth);
				
			}else {
				$html.= $this->applicationView->GetAddChallengeHTML(null, null, null);
			}
			
		}
		else {
			$this->applicationView->NotAdmin();
		}
		
		return $html;	
	}

	/**
	 * @var Array with friends to challenge
	 */
	public function ChallengeFriends($loggedInUserID){
		// Get who
		$friends = $this->applicationView->GetChallengedFriends();

		// Get Challenge ID
		$ID = $this->applicationView->GetChallengeFriendFriendsID();
		$CID = $this->applicationView->GetChallengeFriendChallengeID();

		if($CID != -1 && is_numeric($CID)){
			for ($i=0; $i < count($friends); $i++) {
				// Add notification
				$this->applicationDAL->ChallengeUser($loggedInUserID, $friends[$i], $CID);
			}
			
			$this->applicationView->AddChallengeFriendSucess();
		}
		else {
			$this->applicationView->ChallengeNotFound();
		}
	}
}
