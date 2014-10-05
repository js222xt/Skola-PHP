<?php

namespace model\db;

class ApplicationDAL{
	/**@var MySQLi */
	private $mysqli;
	
		
	private function ConnectToDB(){
		$this->mysqli = new \mysqli("localhost", "admin", "password");
		mysqli_select_db($this->mysqli, "brjgames_nu");
	}
	
	/**
	 * Return user that can be logged in with this data
	 */
	public function GetUser($user){
		$this->ConnectToDB();
		
		$retArray = array();
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @user = " . "'" . $this->mysqli->real_escape_string($user) . "'");
		
		if (!$result = $this->mysqli->query("CALL js222xt_labb4_getuserfromuname(@user)")) {
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
		
		if (!$result = $this->mysqli->query("CALL js222xt_labb4_LoginUser(@user,@pass)")) {
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
	 * @var String username
	 * @var String password
	 */
	public function RegisterAccount($username, $password){
		$this->ConnectToDB();
		
		// Prepare IN and OUT parameters
		$this->mysqli->query("SET @Username = " . "'" . $this->mysqli->real_escape_string($username) . "'");
		$this->mysqli->query("SET @Password = " . "'" . $this->mysqli->real_escape_string($password) . "'");
		
		if (!$result = $this->mysqli->query("CALL js222xt_labb4_RegisterAccount(@Username, @Password)")) {
			throw new DBConnectionException($this->mysqli->error, $this->mysqli->errno);
		}		 
		
		// Free
		mysqli_close($this->mysqli);										
	}
}
