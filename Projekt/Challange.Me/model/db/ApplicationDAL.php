<?php

namespace model\db;


class ApplicationDAL{
	
	/**@var MySQLi */
	protected $mysqli;
	
		
	protected function ConnectToDB(){
		$this->mysqli = new \mysqli("brjgames.nu.mysql", "brjgames_nu", "P9RQLBhg");
		mysqli_select_db($this->mysqli, "brjgames_nu");	
	}
	
	public function GetChallenge($CID){
		$this->ConnectToDB();
		
		$retArray = array();
		$this->mysqli->query("SET @CID = " . "'" . $this->mysqli->real_escape_string($CID) . "'");
		if (!$result = $this->mysqli->query("CALL GetChallenge(@CID)")) {
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
	public function GetAllCompletedChallangesIDForID($AID){
		$this->ConnectToDB();
		
		$retArray = array();
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		
		if (!$result = $this->mysqli->query("CALL GetAllCompletedChallangesForID(@AID)")) {
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
	 * Return user that can be logged in with this data
	 */
	public function LoginUser($user, $pass){
		$this->ConnectToDB();
		
		$retArray = array();
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @user = " . "'" . $this->mysqli->real_escape_string($user) . "'");
		$this->mysqli->query("SET @pass = " . "'" . $this->mysqli->real_escape_string($pass) . "'");
		
		if (!$result = $this->mysqli->query("CALL LoginUser(@user,@pass)")) {
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
	 * @return Array containing all comments for a challenge
	 */
	public function GetComments($CID){
		
		$this->ConnectToDB();

		$this->mysqli->query("SET @CID = " . "'" . $this->mysqli->real_escape_string($CID) . "'");
		
		$retArray = array();
		
		if (!$result = $this->mysqli->query("CALL GetAllComments(@CID)")) {
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
	 * @return Array containing all friends that a user has
	 */
	public function GetAllFriendsIDForUser($AID){
		$this->ConnectToDB();
		
		$retArray = array();
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		
		if (!$result = $this->mysqli->query("CALL GetAllFriendsForUser(@AID)")) {
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
	 * @var int comment ID
	 * Returns a array with a comment
	 */
	public function GetComment($CID){
		$this->ConnectToDB();
		
		$retArray = array();
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @CID = " . "'" . $this->mysqli->real_escape_string($CID) . "'");
		
		if (!$result = $this->mysqli->query("CALL GetComment(@CID)")) {
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
	 * Adds a comment to a comment
	 */
	public function AddCommentToComment($CID, $AID, $title, $comment){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @CID = " . "'" . $this->mysqli->real_escape_string($CID) . "'");
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		$this->mysqli->query("SET @title = " . "'" . $this->mysqli->real_escape_string($title) . "'");
		$this->mysqli->query("SET @comment = " . "'" . $this->mysqli->real_escape_string($comment) . "'");
		
		if (!$result = $this->mysqli->query("CALL AddCommentToComment(@CID,@AID,@title, @comment )")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_free_result($result);
		mysqli_close($this->mysqli);
	}
	
	
	/**
	 * Adds a comment to a comment
	 */
	public function AddCommentToChallenge($CID, $AID, $title, $comment){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		$this->mysqli->query("SET @CID = " . "'" . $this->mysqli->real_escape_string($CID) . "'");
		$this->mysqli->query("SET @title = " . "'" . $this->mysqli->real_escape_string($title) . "'");
		$this->mysqli->query("SET @comment = " . "'" . $this->mysqli->real_escape_string($comment) . "'");
		
		if (!$result = $this->mysqli->query("CALL AddCommentToChallenge(@AID,@CID,@title, @comment )")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);
	}
	
	
	/**
	 * @var string name
	 * @var string description
	 */
	public function AddChallengeToDB($name, $desc, $worth){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @Name = " . "'" . $this->mysqli->real_escape_string($name) . "'");
		$this->mysqli->query("SET @Desc = " . "'" . $this->mysqli->real_escape_string($desc) . "'");
		$this->mysqli->query("SET @Worth = " . "'" . $this->mysqli->real_escape_string($worth) . "'");
		
		if (!$result = $this->mysqli->query("CALL AddChallengeToDB(@Name,@Desc, @Worth)")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);
	}
	
	/**
	 * @var int account ID 
	 * @var int challenge ID
	 * Removes a challenge from a user
	 */
	public function RemoveChallengeFromUser($CID, $AID){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @CID = " . "'" . $this->mysqli->real_escape_string($CID) . "'");
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		
		if (!$result = $this->mysqli->query("CALL RemoveChallengeFromUser(@AID,@CID )")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);
	}
	
	
	/**
	 * @var int comment ID
	 * Removes a comment
	 */
	public function RemoveComment($CID){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @CID = " . "'" . $this->mysqli->real_escape_string($CID) . "'");
		
		if (!$result = $this->mysqli->query("CALL RemoveComment(@CID )")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);
	}
	
	/**
	 * @var String
	 * @var String
	 * @var String
	 * @var String
	 * @var String
	 */
	public function RegisterAccount($username, $password, 
									$email, $fName,
									$lName, $challengePoints){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @Username = " . "'" . $this->mysqli->real_escape_string($username) . "'");
		$this->mysqli->query("SET @Password = " . "'" . $this->mysqli->real_escape_string($password) . "'");
		$this->mysqli->query("SET @Email = " . "'" . $this->mysqli->real_escape_string($email) . "'");
		$this->mysqli->query("SET @FName = " . "'" . $this->mysqli->real_escape_string($fName) . "'");
		$this->mysqli->query("SET @LName = " . "'" . $this->mysqli->real_escape_string($lName) . "'");
		$this->mysqli->query("SET @CHP = " . "'" . $this->mysqli->real_escape_string($challengePoints) . "'");
		
		if (!$result = $this->mysqli->query("CALL RegisterAccount(@Username, @Password, @Email, @FName, @LName, @CHP)")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);										
	}
			
	/**
	 * @var String
	 */						
	public function GetUserFromUsername($username){
		$this->ConnectToDB();
		
		$retArray = array();
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @Username = " . "'" . $this->mysqli->real_escape_string($username) . "'");
		
		if (!$result = $this->mysqli->query("CALL GetUserFromUsername(@Username)")) {
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
	 * @var String
	 */						
	public function GetEmailFromEmail($email){
		$this->ConnectToDB();
		
		$retArray = array();
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @Email = " . "'" . $this->mysqli->real_escape_string($email) . "'");
		
		if (!$result = $this->mysqli->query("CALL GetEmailFromEmail(@Email)")) {
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
	 * @var String ID with who challenged
	 * @var String ID with who got challenged
	 * @var Srring ID with challenge ID
	 */
	public function ChallengeUser($userID, $friendID, $CID){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @UserID = " . "'" . $this->mysqli->real_escape_string($userID) . "'");
		$this->mysqli->query("SET @FriendID = " . "'" . $this->mysqli->real_escape_string($friendID) . "'");
		$this->mysqli->query("SET @CID = " . "'" . $this->mysqli->real_escape_string($CID) . "'");
		
		if (!$result = $this->mysqli->query("CALL ChallengeUser(@UserID, @FriendID, @CID)")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);	
	}
	
	/**
	 * @var String logged in user ID
	 */
	public function MarkChallengedAsRead($ID){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($ID) . "'");
		
		if (!$result = $this->mysqli->query("CALL MarkChallengedAsRead(@AID)")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);	
	}
	
	
	
	public function GetUnreadChallengesNotifications($AID){
		$this->ConnectToDB();
		
		$retArray = array();
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		
		if (!$result = $this->mysqli->query("CALL GetUnreadChallengesNotifications(@AID)")) {
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
	
	public function GetAllChallengesUserChallnegedWith($AID){
		$this->ConnectToDB();
		
		$retArray = array();
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @AID = " . "'" . $this->mysqli->real_escape_string($AID) . "'");
		
		if (!$result = $this->mysqli->query("CALL GetAllChallengesUserChallnegedWith(@AID)")) {
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
	
	
	
}
