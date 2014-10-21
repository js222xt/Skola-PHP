<?php

namespace model\db;

class ChallengeDAL extends ApplicationDAL{

	/*
	 * @return Array containing all challenges
	 */
	public function GetAllChallenges(){
		$this->ConnectToDB();
		
		$retArray = array();
		
		if (!$result = $this->mysqli->query("CALL GetAllChallenges")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}
		else{
			
			if($result->num_rows > 0) 
			{
			    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			    {
			    	array_push($retArray, $row);
			    }
			}			
		}
		
		// Free
		mysqli_free_result($result);
		mysqli_close($this->mysqli);
		// Return
		return $retArray;
	}	
	
	/**
	 * @return Array containing all challenges
	 */
	public function GetAllChallangesIDForID($AID){
		$this->ConnectToDB();
		
		$retArray = array();
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		
		if (!$result = $this->mysqli->query("CALL GetAllChallangesForID(@AID)")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}
		else{
			
			if($result->num_rows > 0) 
			{
			    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			    {
			    	array_push($retArray, $row);
			    }
			}			
		}
		
		// Free
		mysqli_free_result($result);
		mysqli_close($this->mysqli);
		// Return
		return $retArray;
	}
	
	/**
	 * @var int account ID 
	 * @var int challenge ID
	 * Finish a challenge for a user
	 */
	public function FinishChallengeForUser($CID, $AID){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @CID = " . "'" . $this->mysqli->real_escape_string($CID) . "'");
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		
		if (!$result = $this->mysqli->query("CALL FinishChallengeForUser(@AID,@CID )")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);
	}
	
	/**
	 * Add a challenge to a user
	 */
	public function TakeChallenge($ID, $challengeID){
		
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @ID = " . "'" . $this->mysqli->real_escape_string($ID) . "'");
		$this->mysqli->query("SET @CID = " . "'" . $this->mysqli->real_escape_string($challengeID) . "'");
		
		if (!$result = $this->mysqli->query("CALL AddChallenge(@ID,@CID )")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);
	}
	
	/**
	 * @var challenge ID
	 * Removes a challenge from the database
	 */
	public function RemoveAChallegneFromDatabase($CID){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @CID = " . "'" . $this->mysqli->real_escape_string($CID) . "'");
		
		if (!$result = $this->mysqli->query("CALL RemoveChallengeFromDB(@CID)")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);
	}
}
