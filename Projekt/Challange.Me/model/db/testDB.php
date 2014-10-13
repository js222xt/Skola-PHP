<?php

namespace model\db;

class testDB{
	/*
		function __construct($con){
			
			if (!$con->multi_query("CALL sp_test")) {
				echo "CALL failed: (" . $con->errno . ") " . $con->error;
			}
			else {
				echo "Call is ok";	
				do {
				    if ($res = $con->store_result()) {
						foreach ($res->fetch_all() as $value) {
							$id = $value[0];
							$name = $value[1];
							
							echo "<br>My ID is: " . $id . " and my name is: " . $name;
						}
				        //var_dump($res->fetch_all());
				        $res->free();
				    } else {
				        if ($con->errno) {
				            echo "Store failed: (" . $con->errno . ") " . $con->error;
				        }
				    }
				} while ($con->more_results() && $con->next_result());
			}
		}
		
		
		/**
	 * tesst
	 *
	public function TestGetAccount(){
		$rs = $this->dbConnection->query( "CALL GetAccount(1, @email)");
		$rs = $this->dbConnection->query( "SELECT @email ");
		while($row = $rs->fetch_object())
		{
			var_dump($row);
		}
	} */
}