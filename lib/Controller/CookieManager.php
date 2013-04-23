<?php
/** Adds the ability to set cookie on page view. */
class Controller_CookieManager extends AbstractController{
	function init(){
		parent::init();
		$this->api->requires('atk','4.2');
	}
	function set($name,$val,$exp=NULL){
		if( ($name===undefined) || ($val===undefined) ){
			throw new BaseException("Must supply name and value. If you wish to clear the cookie, use the clear method instead.");
			return false;
		}
		if(!$exp){
			$time=time();
			$day=date('d',$time);
			$nextMonth=date('m',$time)+1;
			$year=date('Y',$time);
			$exp=mktime(0, 0, 0, $nextMonth, $day, $year); //Default expiration date is one month from now (mktime understands that the 13th month is really Jan of next year)
		}
		return setcookie($name,$val,$exp);
	}
	function get($name){
		if(isset($_COOKIE[$name])){
			return $_COOKIE[$name];
		}
		return undefined;
	}
	function clear($name){
		setcookie($name,null);
	}
}
