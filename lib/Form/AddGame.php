<?php
class Form_AddGame extends Form_Basic {
	function init(){
		parent::init();

		$f=$this;
		$cm=$this->add('Controller_SierraBravo_xboxV2_GameLibrarian');

		$f->addClass('stacked')
		->addField('Line',"game_title", "Game not listed!?! Add it here!")
			->validateNotNull()
			->addButton('Add', 'after')
				->js('click',$f->js()->atk4_form('submitForm','otro'))
			;

		if($f->isSubmitted() ){
			$r=$cm->addGame($f->get('game_title'));
			if($r){
				$cookie=$this->api->cookie;
				$cookie->voted();
				$f->js()
					->_selector('.reloadGamesPage')->trigger('reloadGamesPage')
					->univ()
						->alert('Thank you, we have received your submission.')
						->execute()
					;
			}else{
				$f->js()
					->univ()
						->alert('We\'re sorry, we can not add your game at this time.
							Please make sure it is not already in our database.')
						->execute()
					;
			}
		}
	}

}