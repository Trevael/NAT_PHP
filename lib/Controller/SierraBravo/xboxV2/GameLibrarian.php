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
	function addRequest($type){
		return $this
			->add('Model_SierraBravo_xboxV2_Soap')
			->setFunction($type)
			->addHook('request-complete',array($this,'requestComplete'))
			;
	}
	function requestComplete($transaction,$response){
		// This function is executed after successful request
		$this->hook('request-complete',array($transaction,$response));
	}

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
	function addVote($gameId){
		return $this->addRequest('addVote')
			->set('id',$gameId)
			->process();
	}
	function setGotIt($gameId){
		return $this->addRequest('setGotIt')
			->set('id',$gameId)
			->process();
	}
	function clearGames(){
		//TODO - Add a Confirmation Dialog here
		return $this->addRequest('clearGames')->process();
	}
}
