<?php


namespace view;

class HeaderView{

	/** @var String containing URL*/
	private $challengesString = "Challenges";
	/** @var String containing URL*/
	private $mystuffString = "MyStuff";
	/** @var String containing URL*/
	private $friendsString = "Friends";
	/** @var String containing URL*/
	private $usersString = "Users";
	/**
	 * Shows Header
	 */
	public function GetHeaderHTML(){
		$header = "
		<div id='header'>
			<ul>
				<a href='index.php'>
					<li>
						Home
					</li>
				</a>
				<a href='index.php?". $this->challengesString . "'>
					<li>
						Challenges
					</li>
				</a>
				<a href='index.php?". $this->mystuffString . "'>
					<li>
						My Stuff
					</li>
				</a>
				<a href='index.php?". $this->friendsString . "'>
					<li>
						Friends
					</li>
				</a>
				<a href='index.php?". $this->usersString . "'>
					<li>
						Users
					</li>
				</a>
			</ul>
		</div>
		";
		
		return $header;
	}
	
	public function GetChallengeString(){
		return $this->challengesString;
	}
	
	public function GetMyStuffString(){
		return $this->mystuffString;
	}
	
	public function GetFriendsString(){
		return $this->friendsString;
	}
	
	public function GetUsersString(){
		return $this->usersString;
	}
}


