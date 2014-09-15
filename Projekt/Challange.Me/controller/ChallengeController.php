<?php


namespace controller;

class ChallengeController{
	
	/** @var \view\ApplicationView */
	private $applicationView;
	/** @var \model\db\ApplicationDAL */
	private $applicationDAL;
	
	public function __construct($appView, $appDAL){
		$this->applicationView = $appView;
		$this->applicationDAL = $appDAL;
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
	 * @return String HTML
	 */
	public function ShowAddChallengeHTML(){
		return $this->applicationView->ShowAddChallengeHTML();
	}
	
	/**
	 * @return String HTML
	 */
	public function GetChallengesHTML($loggedInUser, $challengeFriendHTML){
		
		$isAdmin = $loggedInUser[0]['IsAdmin'];
		$isBanned = $loggedInUser[0]['Banned'];
		
		$html ="";
		$html.= $this->applicationView->GetChallengeHeaderHTML();
						
		// Show all challenges
		
		// Get Array with all challanges
		$challenges = $this->applicationDAL->GetAllChallenges();

		// Get Array with all challanges ID
		$challengesID = $this->applicationDAL->GetAllChallangesIDForID($loggedInUser[0]['ID']);

		for ($i=0; $i < count($challenges); $i++) {
			
				if($this->UserHasChallenge($challengesID, $challenges[$i]['ID'])){
					// Get HTML
					$html .= $this->applicationView->ShowChallenge($challenges[$i], true, $isAdmin, $challengeFriendHTML);
				}
				else{
					$html.= $this->applicationView->ShowChallenge($challenges[$i], false, $isAdmin, $challengeFriendHTML);
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
		$challenges = $this->applicationDAL->GetAllChallenges();

		// If we find the challenge
		$foundChallenge = false;
		
		// Get the one we want
		$ID = $this->applicationView->GetSeeChallengeIDFromGet(); 

		for ($i=0; $i < count($challenges); $i++) {
			
			if($ID == $challenges[$i]['ID']){

				$foundChallenge = true;
				
				// Show challenge!
				$html .= $this->applicationView->ShowDetailedChallenge($challenges[$i], $loggedInUser[0]['ID'], $loggedInUser[0]['IsAdmin'], $friends);							

				// Get all comments
				$comments = $this->applicationDAL->GetComments($challenges[$i]['ID']);
				
				// Show comment system
				$html .= $this->applicationView->ShowCommentSystemStart();
				
				for ($i=0; $i < count($comments); $i++) { 
												
					// Get user who wrote base comment
					$user = $this->applicationDAL->GetUser($comments[$i][1]);
				
					if(count($user) == 1){
						// Is it our user?
						$ourUser = false;
						if($loggedInUser[0]['ID'] == $user[0]['ID']){
							$ourUser = true;
						}
					
						$html .= $this->applicationView->ShowComment($comments[$i], $user, $ourUser);
					}
					else {
						$this->applicationView->NoUserFound();
						$errorsFound = true;
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
		$challenges = $this->applicationDAL->GetAllChallenges();
		// Get Array with all challanges ID
		$challengesID = $this->applicationDAL->GetAllChallangesIDForID($ID);
		// get all challenges					
		$myActiveChallenges = array();
		for ($i=0; $i < count($challenges); $i++) { 
			for ($j=0; $j < count($challengesID); $j++) {
				// If ID match 
				if($challenges[$i][0] == $challengesID[$j]['CID']){
					// Add
					array_push($myActiveChallenges, $challenges[$i]);
				}
			}
		}
		if(count($myActiveChallenges) > 0){
			// Get HTML
			if($this->loggedInUser[0]['ID'] == $ID){
				$html .= $this->applicationView->GetMyActiveChallengesHeaderHTML($username, true, $loggedInUser[0]['ID']);
			}
			else {
				$html.= $this->applicationView->GetMyActiveChallengesHeaderHTML($username, false, $ID);
			}
			$html.= $this->applicationView->ShowChallenges($myActiveChallenges, true, $loggedInUser[0]['IsAdmin'], $challengeFriendHTML);
		}
		else{
			$html .= $this->applicationView->UserHasNoChallengesHTML();
		}
		
		// Show all my completed challenges
	
		// Get Array with all completed challanges ID
		$completedChallengesID = $this->applicationDAL->GetAllCompletedChallangesIDForID($ID);
		// get all challenges					
		$myCompletedChallenges = array();
		for ($i=0; $i < count($challenges); $i++) { 
			for ($j=0; $j < count($completedChallengesID); $j++) {
				// If ID match 
				if($challenges[$i]['ID'] == $completedChallengesID[$j]['CID']){
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
			if($loggedInUser[0]['ID'] == $ID){
				$html .= $this->applicationView->GetMyCompletedChallengesHeaderHTML($username, true, $loggedInUser[0]['ID']);
			}
			else {
				$html .= $this->applicationView->GetMyCompletedChallengesHeaderHTML($username, false, $ID);
			}

			$html .= $this->applicationView->ShowChallenges($myCompletedChallenges, false, $loggedInUser[0]['ID'], $challengeFriendHTML);
		}
		else{
			if($loggedInUser[0]['ID'] == $ID){
				$html .= $this->applicationView->UserHasNoCompletedChallengesHTML();
			}
		}
		
		return $html;
	}
	
	
	public function RemoveChallengeFromUser($loggedInUser){
		// Get which one, if -1 error
		if($CID = $this->applicationView->GetRemoveChallengeIDFromGet()){
			if($CID != -1 && is_numeric($CID)){
				// Validate
			
				// Does user has the challenge?
				// Get challenges
				$challenges = $this->applicationDAL->GetAllChallangesIDForID($loggedInUser[0]['ID']);
				if($this->UserHasChallenge($challenges, $CID)){
					
					// Remove from Database
					$this->applicationDAL->RemoveChallengeFromUser($CID,$oggedInUser[0]['ID']);
			
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
	}
	
	public function FinishAChallenge($loggedInUser){
		// Get which one, if -1 error
		if($CID = $this->applicationView->GetFinishChallengeIDFromGet()){
			
			if($CID != -1 && is_numeric($CID)){
				// Validate
				
				// Does user has the challenge?
				// Get challenges
				$challenges = $this->applicationDAL->GetAllChallangesIDForID($loggedInUser[0]['ID']);
				if($this->UserHasChallenge($challenges, $CID)){
					
					// Database stuff
					$this->applicationDAL->FinishChallengeForUser($CID, $loggedInUser[0]['ID']);	
			
					$this->applicationView->AddFinishChallengeSucess();
				}
				else {
					$this->applicationView->AddFinishChallengeNotFound();
				}
			}
			
		}
		else {
			$this->applicationView->ChallengeNotFound();
		}
	}
	
	public function TakeChallenge($loggedInUser){
		// Take the challenge, if id = -1 error
		if($ID = $this->applicationView->GetTakeChallengeIDFromGet()){
			if($ID != -1 && is_numeric($ID)){
				// Get challenges
				$challenges = $this->applicationDAL->GetAllChallanges();
				
				// If we find the challenge
				$foundChallenge = false;
				
				// Get the one we want
				for ($i=0; $i < count($challenges); $i++) { 
					if($ID == $challenges[$i][0]){
						$foundChallenge = true;
						
						// Take Challenge
						$this->applicationDAL->TakeChallenge($loggedInUser[0]['ID'],$ID);
						$this->applicationView->AddTakeChallengeSucess();							
					}
				}
			
				// We have not found our challenge!
				if(!$foundChallenge){
					$this->applicationView->ChallengeNotFound();
				}
			}
			else {
				$this->applicationView->ChallengeNotFound();
			}
		}	
	}
	
	public function AdminRemoveChallenge($isAdmin){
		// If we are admin
		if($isAdmin){
			// Get Challegne ID
			$CID = $this->applicationView->GetDestroyChallengeIDFromGET();
			
			if($CID != -1 && is_numeric($CID)){
				$this->applicationDAL->RemoveAChallegneFromDatabase($CID);
				
				$this->applicationView->AddDestroyChallengeSucess();
			}
			else {
				$this->applicationView->ChallengeNotFound();
			}
		}
		else {
			$this->applicationView->NotAdmin();
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
				
				// Validate
				if(strlen($name) == 0 || strlen($name) > 200){
					$this->applicationView->NewChallengeNameInvalid();
				}
				if(strlen($description) == 0 || strlen($description) > 250){
					$this->applicationView->NewChallengeDescriptionInvalid();
				}
				if(!is_numeric($worth)){
					$this->applicationView->NewChallengeWorthNumericInvalid();
				}
				if($worth < 0 || $worth > 1000000000){
					$this->applicationView->NewChallengeWorthBoundsInvalid();
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
		
		// Get Challegne ID
		$ID = $this->applicationView->GetChallengeFriendChallengeID();
		$CID = $ID[0];
		
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
