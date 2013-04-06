<?php
/* {{{ vim:ts=4:sw=4:et

	 About: This file is part of Nerdery PHP Assessment Test implementing SierraBravo xbox game librarian V2
	 Documentation: http://xbox.sierrabravo.net/v2/xbox.wsdl

	 ---------------------------------------------------------------------

	 Agile Toolkit 4

	 (c) 1999-2010 Agile Technologies Limited

	 See COPYRIGHT for details

	 ---------------------------------------------------------------------

	 Authors:

		Romans
		Sippy

	 ---------------------------------------------------------------------

	}}} */

class Model_SierraBravo_xboxV2_GameLibrarian extends AbstractModel {
	public $soap;
	public $function;
	public $area;
	public $result;

	function init(){
		parent::init();
		$this->soap=new SoapClient($this->owner->url,array('trace'=>true,'exceptions'=>true));
			$this->set('apiKey',$this->owner->key);
			//$this->set('ClientID',$this->owner->client);
	}
	function set($key,$val=null){
		if(is_array($key)){
			foreach($key as $a=>$b){
				$this->set($a,$b);
			}
			return;
		}

		if(is_null($val))unset($this->arguments[$key]);
		$this->arguments[$key]=$val;
			return $this;
	}
	function setFunction($function){
		$this->function=$function;
			return $this;
	}
	function process(){
		if($this->api->getConfig('SierraBravo/xboxV2/GameLibrarian/demo_mode',false)){
			return $this;
		}

		$fn=$this->function;
		$args=$this->arguments;

		// handle return values and throw exceptions!
		$this->resp=$this->soap->__call($fn,$args);

		if(isset($this->resp)){
			$fn=$this->area.'.'.$this->function;
			foreach($this->resp as $key=>$val){
				if(substr($key,-6)=='Result')$this->result=$val;
			}
			if($this->result){
				if(isset($this->result->enc_value))$this->result=$this->result->enc_value;

				if(isset($this->result->Code) && $this->result->Code>100){
					// Problem 
					throw new BaseException("Received error (".$this->result->Code."): ".$this->result->Message." from
						<pre>".htmlentities($this->soap->__getLastRequest()).'</pre>');
				}
			}else{
				//var_dump('No Result: ',$this->resp);
				$this->hook('request-failed',array($this,$this->resp));
				//throw new BaseException("No results returned from:<br><br>
				//	SOAP Headers<br><pre>".htmlentities($this->soap->__getLastRequestHeaders())."</pre>
				//	SOAP Request<br><pre>".htmlentities($this->soap->__getLastRequest())."</pre>
				//	SOAP Response<br><pre>".htmlentities($this->soap->__getLastResponse())."</pre>");
			}
		}

		$this->hook('request-complete',array($this,$this->resp));
		return $this;
	}
}
