<?php
// This method is executed for ALL the pages you are going to add, before the page class is loaded. You can put additional checks or initialize additional elements in here which are common to all the pages.
class Frontend extends ApiFrontend {
	public $cookie;

	function init(){
		parent::init();
		
		$this->requires('atk','4.2.0');

		$this->cookie=$this->add('Controller_VoteManager');

		$this->addLocation('atk4-addons',array(
			'php'=>array(
				'mvc',
				'misc/lib',
				)
			))
			->setParent($this->pathfinder->base_location);

		// A lot of the functionality in Agile Toolkit requires jUI
		$this->add('jUI');

		// Initialize any system-wide javascript libraries here. If you are willing to write custom JavaScript code, place it into templates/js/atk4_univ_ext.js and include it here
		$this->js()
			->_load('atk4_univ')
			->_load('ui.atk4_notify')
			;

		// Setup basic access control with the BasicAuth class
		$af=$this->add('Auth')
			->allow('admin','n3rd3ry')
			;

		// Setup the logo, title and header
		$this->add('Text',null,'logo')->set('');
		$this->add('Text',null,'page_title')->set('Game Librarian');
		$this->add('Text',null,'name')->set('Game Librarian');
		$this->add('Text',null,'version')->set('1.0.0 -RC1');

		// Menu:
		$menu=$this->add('Menu',null,'Menu');
		if($this->auth->isLoggedIn()){
			$menu->addMenuItem('logout');
		}
		$menu->addMenuItem('admin');
	}
}
