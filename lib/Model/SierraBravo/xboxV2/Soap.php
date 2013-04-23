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

class Model_SierraBravo_xboxV2_Soap extends AbstractModel {
	public $soap;
	public $function;
	public $area;
	public $result;

	function init(){
		parent::init();

		$this->soap=new SoapClient($this->owner->url,array('trace'=>true,'exceptions'=>true));
		$this->set('apiKey',$this->owner->key);
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
		$fn=$this->function;
		$args=$this->arguments;

		// handle return values and throw exceptions!
		$this->resp=$this->soap->__soapCall($fn,$args);
		
		if( (isset($this->resp)) && (is_array($this->resp)) ){
			$this->result=array();
			foreach ($this->resp as $resp) {
				array_push($this->result, $resp);
			}
			if($this->result){
				if(isset($this->result->enc_value))$this->result=$this->result->enc_value;

				if(isset($this->result->Code) && $this->result->Code>100){
					// Problem 
					throw new BaseException("Received error (".$this->result->Code."): ".$this->result->Message." from
						<pre>".htmlentities($this->soap->__getLastRequest()).'</pre>');
				}
			}else{
				if($this->owner->debug){
					throw new BaseException("No Results from
							<pre>".htmlentities($this->soap->__getLastRequest())."</pre>"
							."<pre>".htmlentities($this->soap->__getLastResponse())."</pre>"
					);
				}
				return;
			}
		}

		$this->hook('request-complete',array($this,$this->resp));
		return $this;
	}
}
