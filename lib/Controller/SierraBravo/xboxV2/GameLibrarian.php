<?php
class Controller_SierraBravo_xboxV2_GameLibrarian extends AbstractController {
	public $soap;
	public $soap_params;

	public $url;
	public $key;
	
	function init(){
		parent::init();
		$this->url=$this->api->getConfig('SierraBravo/xboxV2/GameLibrarian/url');
		$this->key=$this->api->getConfig('SierraBravo/xboxV2/GameLibrarian/key');
	}
	function addRequest($type){
		return $this
			->add('Model_SierraBravo_xboxV2_GameLibrarian')
			->setFunction($type)
			->addHook('request-complete',array($this,'requestComplete'))
			->addHook('request-failed',array($this,'requestComplete'))
			;
	}
	function requestComplete($transaction,$response){
		// This function is executed after successful request
		$this->hook('request-complete',array($transaction,$response));
	}
	function requestFailed($transaction,$response){
		// This function is executed after failed request
		$this->hook('request-failed',array($transaction,$response));
	}
}
