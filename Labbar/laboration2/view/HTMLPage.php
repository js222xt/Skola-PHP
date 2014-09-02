<?php

namespace view;


class HTMLPage {

	/**
	 * @param  String $title 
	 * @param  String $body  
	 * @return String HTML
	 */
	public function getPageHTML($title, $body) {
		return '<!DOCTYPE HTML>
				<html>
				  <head>
				    <title>' . $title . '</title>
				    <meta http-equiv=\'content-type\' 
				    				   content=\'text/html;
									   charset=utf-8\'>
				  </head>
				  <body>
				  	' . $body . '
				  </body>
				</html>';
	}
}