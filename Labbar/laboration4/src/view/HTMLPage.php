<?php

namespace view;

require_once("DateTimeStamp.php");

/**
 * Generates HTML code for HTML page.
 */
class HTMLPage {
	private $dateTimeStampView;

	public function __construct() {
		$this->dateTimeStampView = new \view\DateTimeStamp();
	}

	/**
	 * @param  string $body
	 * @return string HTML
	 */
	public function getPage($body) {
		$dateTimeStamp = $this->dateTimeStampView->getStamp();

		return '<!DOCTYPE html>
		<html lang="sv">
			<head>
				<title>Laboration 2 - jn222iw</title>
				<meta charset=\'UTF-8\' />
			</head>
			<body>
				<h1>Laboration 2 - Inloggningsmodul</h1>
				' . $body . $dateTimeStamp . '
			</body>
		</html>';
	}
}