<?php

namespace model;

class Challenge{
	
	/**@var int Challenge ID*/
	private $id;
	/**@var string Challenge name*/
	private $name;
	/**@var string Challenge description*/
	private $description;
	/**@var int number of times it has been accepted*/
	private $accepted;
	/**@var int how much it is worth */
	private $worthChallengePoints;
	/**@var int how many times it has been completed */
	private $completed;
	
	public function __construct($id, $name, $desc, $acc, $wcp, $comp){
		$this->id = $id;
		$this->name = $name;
		$this->description = $desc;
		$this->accepted = $acc;
		$this->worthChallengePoints = $wcp;
		$this->completed = $comp;
	}
	
	/// GETTERS ///

	public function GetID(){
		return $this->id;
	}
		
	public function GetName(){
		return $this->name;	
	}
	
	public function GetDescription(){
		return $this->description;
	}

	public function GetAccepted(){
		return $this->accepted;
	}
	
	public function GetWorthChallengePoints(){
		return $this->worthChallengePoints;
	}

	public function GetCompleted(){
		return $this->completed;
	}
	
}
