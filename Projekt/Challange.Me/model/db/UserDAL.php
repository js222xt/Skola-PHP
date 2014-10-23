<?php

namespace model\db;

class UserDAL extends ApplicationDAL{
	
	/**
	 * @var int ID
	 * @var int ID
	 * Adds a friend
	 */
	public function AddFriend($PID1, $PID2){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @PID1 = " . "'" . $this->mysqli->real_escape_string($PID1) . "'");
		$this->mysqli->query("SET @PID2 = " . "'" . $this->mysqli->real_escape_string($PID2) . "'");
		
		if (!$result = $this->mysqli->query("CALL AddFriend(@PID1,@PID2)")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);
	}
	
	/**
	 * @var int ID
	 * @var int ID
	 * Removes a friend
	 */
	public function RemoveFriend($PID1, $PID2){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @PID1 = " . "'" . $this->mysqli->real_escape_string($PID1) . "'");
		$this->mysqli->query("SET @PID2 = " . "'" . $this->mysqli->real_escape_string($PID2) . "'");
		
		if (!$result = $this->mysqli->query("CALL RemoveFriend(@PID1,@PID2)")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);
	}
	
	/**
	 * @param ID of user to ban
	 */
	public function BanUser($AID){
		$this->ConnectToDB();

		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		
		if (!$result = $this->mysqli->query("CALL BanUser(@AID)")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}
		
		// Free
		mysqli_close($this->mysqli);
	}
	
	/**
	 * @param ID of user to unban
	 */
	public function UnBanUser($AID){
		$this->ConnectToDB();

		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		
		if (!$result = $this->mysqli->query("CALL UnbanUser(@AID)")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}
		
		// Free
		mysqli_close($this->mysqli);
	}
	
	/**
	 * @return Array containing a user account
	 */
	public function GetAllUsers(){
		$this->ConnectToDB();
		
		$retArray = array();
		
		if (!$result = $this->mysqli->query("CALL GetAllUsers()")) {
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
	 * @return Array containing a user account
	 */
	public function GetUser($AID){
		$this->ConnectToDB();
		
		$retArray = array();
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		
		if (!$result = $this->mysqli->query("CALL GetUser(@AID)")) {
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
		//mysqli_close($this->mysqli);
		// Return
		return $retArray;
	}
}

