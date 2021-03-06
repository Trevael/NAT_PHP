<?php
//class page_index extends Page_SierraBravo_xboxV2_GameLibrarian {
class page_index extends Page_GameLibrarian {
	function init(){
		parent::init();

		$this->add('H1')->set('Welcome to the Nerdery\'s Xbox 360 Game Library!');

		// Adding view box with another view object inside with my custom HTML template
		if($this->api->auth->isLoggedIn() || $this->api->cookie->canVote()){
			$f=$this->add('Form_AddGame');
		} else {
			$this->add('View_Warning')->add('View',null,null,array('view/onceaday'));
			$this->add('HR');
		}

		// Setup the call that refreshes the whole page
		$this->js(true)->addClass('reloadGamesPage');
		$this->js('reloadGamesPage')->reload();
	}

}
