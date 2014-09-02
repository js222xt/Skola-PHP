<?php

namespace model;

class Logg{
	private static $failedFileLocation = "failedloginlogg.txt";
	private static $errorMessages = "errorMessages.txt";
	private static $userNameString = "[Username]";
	private static $passwordString = "[Password]";
	private static $timeString = "[Time]";
	private static $dashString = "--------------------------";
	private static $rowBreak = "\n";
	
	/**
	 * @param String with username
	 * @param String with password
	 * @param String ith time
	 */
	public function WriteUnsuccessfullLogin($userName,$password,$time){
		$fh = fopen(self::$failedFileLocation, 'abt');
		fwrite($fh, self::$userNameString);
		fwrite($fh, self::$rowBreak);
		fwrite($fh, $userName);
		fwrite($fh, self::$rowBreak);
		fwrite($fh, self::$passwordString);
		fwrite($fh, self::$rowBreak);
		fwrite($fh, $password);
		fwrite($fh, self::$rowBreak);
		fwrite($fh, self::$timeString);
		fwrite($fh, self::$rowBreak);
		fwrite($fh, $time);
		fwrite($fh, self::$rowBreak);
		fwrite($fh, self::$dashString);
		fwrite($fh, self::$dashString);
		fwrite($fh, self::$rowBreak);
		fwrite($fh, self::$rowBreak);
		fclose($fh);	
	}
	
	/**
	 * 
	 */
	public function WriteErrorMessage($ex){
		$fh = fopen(self::$errorMessages, 'abt');
		fwrite($fh, $ex);
		fwrite($fh, self::$rowBreak);
	}
}
