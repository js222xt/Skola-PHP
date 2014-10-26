<?php


namespace view;

class ChallengeView{
	
	private $seeAChallengeString = "ChallengeID";
	private $takeAChallengeString = "TakeChallengeID";
	private $removeChallengeString = "RemoveChallengeID";
	private $finishChallengeString = "FinishChallengeID";
	private $destroyChallengeString = "DestroyChallengeID";
	private $challengeFriendString = "ChallengeFriend";
	
	/**
	 * @param Array with challenge info
	 * @param Bool if user already ahs challenge
	 */
	public function DisplayChallenge($challenge, $hasChallenge, $isAdmin, $friends){

		$html= "
			<div class='challenge'>
					<p>Name: <a href='index.php?" . $this->seeAChallengeString . "=" . $challenge->GetID() . "'>" . $challenge->GetName() . "</a> </p>
					<p>Description: " . $challenge->GetDescription() . " </p>
					<p>Accepted: " . $challenge->GetAccepted() . " times. </p> 
					<p>Worth: " . $challenge->GetWorthChallengePoints(). " Points</p>
					<p>Completed: " . $challenge->GetCompleted(). " times.</p>";
					
		// If user already has challenge
		if($hasChallenge){						
			$html .= "<a href='index.php?" . $this->finishChallengeString . "=" . $challenge->GetID(). "' class='completeChallenge' >
						Complete Challenge</a>";
			
			$html .= "<a href='index.php?" . $this->removeChallengeString . "=" . $challenge->GetID(). "' class='removeChallenge' >
						Remove Challenge</a>";
		}
		else{
			$html .= "<a class='button-link' href='index.php?" . $this->seeAChallengeString . "=" . $challenge->GetID(). "&" .
		 	$this->takeAChallengeString . "=" . $challenge->GetID(). "'>Take Challenge!</a>";	
		}
					
		// If admin

		if((boolean)$isAdmin){
			$html .= "<a class='destroyChallenge' href='index.php?" . $this->destroyChallengeString . "=" . $challenge->GetID(). "&" .
		 	$this->destroyChallengeString . "=" . $challenge->GetID(). "'>Destroy Challenge!</a>";
		}
					
		// Challenge Friend

		if(count($friends) > 0){
			$html .= "<br><br><form method='post' action='index.php?" . $this->challengeFriendString . "=" . $challenge->GetID(). "'";
			$html .= "<p>Challenge a friend?</p>";
			for ($i=0; $i < count($friends); $i++) {
				$html .= "
				<input type='checkbox' name='" . $this->challengeFriendString . "[]' value='" . $friends[$i][0]['ID'] . "'><label>" . $friends[$i][0]['Username'] ."</label><br>
				";
			}
			$html .= "<input type='submit' name='submit' value='Challenge Friend(s)' /><br>
						</form>";
		}
		
						
		$html .= "</div>";
		
		return $html;
	}
	
	
	
	/**
	 * @return a string that is used to see a challenge
	 */
	public function GetSeeAChallengeString(){
		return $this->seeAChallengeString;
	}
	
	/**
	 * @return a string that is used to take a challenge
	 */
	public function GetTakeAChallengeString(){
		return $this->takeAChallengeString;
	}
	
	/**
	 * @return a string that is used to remove a challenge
	 */
	public function GetRemoveAChallengeString(){
		return $this->removeChallengeString;
	}
	
	/**
	 * @return a string that is used to remove a challenge
	 */
	public function GetDestroyAChallengeString(){
		return $this->destroyChallengeString;
	}
	
	/**
	 * @return a string that is used to finish a challenge
	 */
	public function GetFinishAChallengeString(){
		return $this->finishChallengeString;
	}
	
	/**
	 * @return a string that is used to challenge a friend
	 */
	public function GetChallengeAFriendString(){
		return $this->challengeFriendString;
	}
}
