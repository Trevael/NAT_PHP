<?php
class Page_GameLibrarian extends Page {
	public $g, $cm;

	function initMainPage(){
		parent::init();

		//Instance the GameLibrarian controller to interact with our webservice provider
		$cm=$this->add('Controller_SierraBravo_xboxV2_GameLibrarian');
		$this->cm=$cm;
		
		//Get our game data
		$data=$cm->getGames();
		
		//Instance the VoteManager controller to see if we may vote
		$cookie=$this->api->cookie;

		// *** Page Display Actions ***

		//Add a 'clear all' button of we are admin
		if($this->api->auth->isLoggedIn()){
			$bS=$this->add('ButtonSet');
			$b=$bS->addButton('ClearAll')->setLabel('Clear All Games');
			$b->redirect('clearGames');
		}

		//Setup a grid to receive the games list
		$g=$this->add('Grid');
		$this->g=$g;

		$g->addColumn('text','title','Game Title');
		$g->addColumn('text','gameId','ID');
		$g->addColumn('text','ownIt','We own it');
		$g->addColumn('text','votes','Votes');
		if($this->api->auth->isLoggedIn()){
			$g->addColumn('button','GotIt','Got It');
		}else{
			if($cookie->canVote()){
				$b=$g->addColumn('button','Vote');
			}
		}
		$g->setSource($data);
		//$g->addPaginator(5);


		//call to refresh the games list
		$g->js(true)->addClass('reloadGamesList');
		$g->js('reloadGamesList')->reload();


		// *** Button Click actions ***
		
		if(isset($_GET['Vote'])){
			$gId=$_GET['Vote'];
			$gameId=$data[$gId]['gameId'];
			$success=$this->cm->addVote($gameId);
			if($success){
				$voteManaged=$cookie->voted();
				$this->js()
					->_selector('.reloadGamesPage')->trigger('reloadGamesPage')
					->execute()
				;
			}
		}
		if(isset($_GET['GotIt'])){
			$gId=$_GET['GotIt'];
			$gameId=$data[$gId]['gameId'];
			$success=$this->cm->setGotIt($gameId);
			if($success){
				$this->js()
					->_selector('.reloadGamesList')->trigger('reloadGamesList')
					->execute()
				;
			}
		}
		if(isset($_GET['page'])){
			if($_GET['page']=='clearGames'){
				$success=$this->cm->clearGames();
				if($success){
					$this->js()
						->_selector('.reloadGamesPage')->trigger('reloadGamesPage')
						->execute()
					;
				}
			}
		}
	}
	function page_clearGames(){
		$r=$this->add('Controller_SierraBravo_xboxV2_GameLibrarian')->clearGames();
		header('Location: '.$this->api->pm->base_path."?page=admin");
	}
}
