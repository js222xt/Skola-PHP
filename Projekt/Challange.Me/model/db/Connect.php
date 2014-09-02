<?php

namespace model\db;

// Exception class for Connection issues
require_once("DBException.php");



class Connection{
	
    private $host ="localhost";
    private $user = "admin"; 
    private $password = "password";
    private $db="challengeme";
    private $dbc = null;
    
    function __construct() {

        $con = mysqli_connect($this->host, $this->user, $this->password, $this->db);
		// Set global connection to this connection
		
        if(mysqli_errno($con)){
            throw new DBConnectionException("A databade connection could not be established, please try again later",1);
            
        }
        else{
           $this->dbc = $con; // assign $con to $dbc
        }
	}
	
	/*
	 * @return connection to db
	 */
	public function GetConnection(){
		return $this->dbc;
	}
}

