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
	 * @var Array with challenge info
	 * @var Bool if user already ahs challenge
	 */
	public function DisplayChallenge($challenge, $hasChallenge, $isAdmin, $friends){

		$html= "
			<div class='challenge'>
					<p>Name: <a href='index.php?" . $this->seeAChallengeString . "=" . $challenge['ID'] . "'>" . $challenge['Name'] . "</a> </p>
					<p>Description: " . $challenge['Description'] . " </p>
					<p>Accepted: " . $challenge['Accepted'] . " times. </p> 
					<p>Worth: " . $challenge['WorthChallengePoints']. " Points</p>
					<p>Completed: " . $challenge['Completed']. " times.</p>";
					
		// If user already has challenge
		if($hasChallenge){						
			$html .= "<a href='index.php?" . $this->finishChallengeString . "=" . $challenge['ID'] . "' class='completeChallenge' >
						Complete Challenge</a>";
			
			$html .= "<a href='index.php?" . $this->removeChallengeString . "=" . $challenge['ID'] . "' class='removeChallenge' >
						Remove Challenge</a>";
		}
		else{
			$html .= "<a class='button-link' href='index.php?" . $this->seeAChallengeString . "=" . $challenge['ID'] . "&" .
		 	$this->takeAChallengeString . "=" . $challenge['ID'] . "'>Take Challenge!</a>";	
		}
					
		// If admin
		if((boolean)$isAdmin){
			$html .= "<a class='destroyChallenge' href='index.php?" . $this->destroyChallengeString . "=" . $challenge['ID'] . "&" .
		 	$this->destroyChallengeString . "=" . $challenge['ID'] . "'>Destroy Challenge!</a>";
		}
					
		// Challenge Friend

		if(count($friends) > 0){
			$html .= "<br><br><form method='post' action='index.php?" . $this->challengeFriendString . "=" . $challenge['ID'] . "'";
			for ($i=0; $i < count($friends[0]); $i++) {
				$html .= "
				<p> Challenge a friend?</p>
				<input type='checkbox' name='" . $this->challengeFriendString . "[]' value='" . $friends[0][$i]['ID'] . "'><label>" . $friends[0][$i]['Username'] ."</label><br>
				";
			}
			$html .= "<input type='submit' name='submit' value='Challenge Friends' /><br>
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
