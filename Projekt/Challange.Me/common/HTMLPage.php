<?php

namespace view;


class HTMLPage {

	/**
	 * @param  String $title 
	 * @param  String $body  
	 * @param  String $footer
	 * @return String HTML
	 */
	public function getPageHTML($title, $header, $messages, $body, $footer) {
		return '<!DOCTYPE HTML>
				<html>
				  <head>
				    <title>' . $title . '</title>
				    <meta http-equiv=\'content-type\' 
				    				   content=\'text/html;
									   charset=utf-8\'>
					<link rel="stylesheet" type="text/css" href="common/basic.css">
				  </head>
				  <body>
				  <div id="main">
				  	' . $header . '
				  	' . $messages . '
				  	' . $body . '
				  	' . $footer . '
				  </div>
				  </body>
				</html>';
	}
}