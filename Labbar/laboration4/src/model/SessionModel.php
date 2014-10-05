<?php

namespace model;

class SessionModel {
	private static $sessionLocation = "sessionModel::session";
	private $userSessionLocation = "sessionModel::user";
	private static $userAgentID = "browser";
	private static $userIPID = "ip";

	/**
	 * Starts session and saves information about browser in session.
	 */
	public function startSession() {
		session_start();

		if (!isset($_SESSION[self::$sessionLocation])) {
			$_SESSION[self::$sessionLocation] = array();
			$_SESSION[self::$sessionLocation][self::$userAgentID] = $_SERVER["HTTP_USER_AGENT"];
			$_SESSION[self::$sessionLocation][self::$userIPID] = $_SERVER["REMOTE_ADDR"];
		}
	}

	/**
	 * Validates session.
	 * @return bool
	 */
	public function isSessionValid() {
		if ($_SESSION[self::$sessionLocation][self::$userAgentID] != $_SERVER["HTTP_USER_AGENT"]) {
			return false;
		} 
		if ($_SESSION[self::$sessionLocation][self::$userIPID] != $_SERVER["REMOTE_ADDR"]) {
			return false;
		}

		return true;
	}

	/**
	 * Saves user in session.
	 * @param  \model\User $user
	 */
	public function loginUser(\model\User $user) {
		$_SESSION[$this->userSessionLocation] = $user;
	}

	/**
	 * @return false if $_SESSION is not set, else return saved user.
	 */
	public function userIsLoggedIn() {
		if (isset($_SESSION[$this->userSessionLocation])) {
			return $_SESSION[$this->userSessionLocation];
		} else {
			return false;
		}
	}
}