<?php

namespace model;

require_once("User.php");

/**
 * Should be for example a database or a database access layer.
 */
class UserList {
	private $userList = array();

	public function __construct() {
		$this->userList = array(new \model\User("Admin",md5("Password")));
	}

	/**
	 * @return array \model\User $userList
	 */
	public function getUsers() {
		return $this->userList;
	}

	/**
	 * @param  \model\User $user
	 * @return bool
	 * @throws \Exception If $user is not in $userList.
	 */
	public function validateUser($user) {
		/* My code */
		$appDal =  new \model\db\ApplicationDAL();
		if( count($appDal->LoginUser($user->getUserName(), $user->getUserPassword())) == 0){
			throw new \Exception("Felaktigt användarnamn och/eller lösenord.");
		}
		
		
		/* Other person code
		if (in_array($user, $this->userList)) {
			return true;
		} else {
			throw new \Exception("Felaktigt användarnamn och/eller lösenord.");
		}*/
	}

	/**
	 * @param  \model\User $savedUser
	 * @return bool
	 * @throws \Exception If $savedUser is not in $userList.
	 */
	public function validateSavedUser($savedUser) {
		if (in_array($savedUser, $this->userList)) {
			return true;
		} else {
			throw new \Exception("Felaktig information i cookie.");
		}
	}
}