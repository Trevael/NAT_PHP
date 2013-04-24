<?php
class Controller_SierraBravo_xboxV2_GameLibrarian extends Controller {
	public $soap;
	public $soap_params;

	public $url;
	public $key;
	public $debug;

	public $data;
	
	function init(){
		parent::init();
		$this->url=$this->api->getConfig('SierraBravo/xboxV2/GameLibrarian/url');
		$this->key=$this->api->getConfig('SierraBravo/xboxV2/GameLibrarian/key');
		$this->debug=$this->api->getConfig('SierraBravo/xboxV2/GameLibrarian/debug');
	}
	// Sets up a request action for the model to execute
	function addRequest($type){
		return $this
			->add('Model_SierraBravo_xboxV2_Soap')
			->setFunction($type)
			->addHook('request-complete',array($this,'requestComplete'))
			;
	}
	// Hook (or callback) from the model
	//  This function is executed after successful request
	function requestComplete($transaction,$response){
		$this->hook('request-complete',array($transaction,$response));
	}
	// Returns our list of games from the Model, formatted as an associative array
	function getGames(){
		$r=$this->addRequest('getGames')->process();
		$data=array();
		if(isset($r->result) > 0){
			foreach($r->result as $list){
				$data[]=array(
					'title'=>$list->title,
					'gameId'=>$list->id,
					'votes'=>$list->votes,
					'ownIt'=>($list->status==='wantit'?"No":"Yes")
					);
			}	
		}
		$this->data=$data;
		return $this->data;
	}
	// Tells the Model to add a game to our list
	//  Checks our game list first to make sure the game is not already listed
	//  Returns TRUE on success signal from the Model, and FALSE on failure
	function addGame($title){
		if( ($title!=undefined) && ($title!=null) ){
			$data=$this->getGames();
			$dupFound=false;
			foreach($data as $item){
				if( strcmp($title, $item['title']) == 0 ){
					$dupFound=true;
					break;
				}
			}
			if($dupFound){
				return false;
			} else {
				return $this->addRequest('addGame')
					->set('title',$title)
					->process()
					;
			}
		}
		return false;
	}
	// Tell the model to add a vote to our game's vote count
	//  Returns TRUE on success, FALSE on failure
	function addVote($gameId){
		if($this->ownIt($gameId) == false){
			return $this->addRequest('addVote')
				->set('id',$gameId)
				->process();
		}
		return false;
	}
	// Get our games list from the Model and return:
	//  TRUE if we already own it
	//  FALSE if we do not
	function ownIt($gameId){
		if( ($gameId!=undefined) && ($gameId!=null) ){
			$data=$this->getGames();
			foreach($data as $item){
				if( strcmp($gameId, $item['gameId']) == 0 ){
					if( strcmp($item['ownIt'],"Yes") == 0 ){
						return true;
					}
					break;
				}
			}
		}
		return false;
	}
	// Tell the Modle to mark a game as 'owned'
	function setGotIt($gameId){
		return $this->addRequest('setGotIt')
			->set('id',$gameId)
			->process();
	}
	// Tell the Model to clear our games list
	function clearGames(){
		//TODO - Add a Confirmation Dialog here
		return $this->addRequest('clearGames')->process();
	}
}
