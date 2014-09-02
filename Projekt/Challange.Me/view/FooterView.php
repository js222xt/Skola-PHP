<?php


namespace view;

class FooterView{
	
	/**
	 * @Return String containing HTML for the footer
	 */
	public function GetFooterHTML(){
		$footer = "
		<div id='footer'>
			&copy Copyright - Jonas Sandroos
		</div>
		";
		
		return $footer;
	}
	
}


