<?php
/** Manages the Session vars responsible for vote tracking. */
class Controller_VoteManager extends AbstractController {
	protected $dataName	= "CanVoteAfter";
	protected $dataVal	= 0;

	public $cm;

	function init(){
		parent::init();

		$this->cm=$this->add('Controller_CookieManager');

		$val=$this->recall($this->dataName);
		if( ($val == null) || ($val == '_atk4_undefined_value') ){
			$this->memorize($this->dataName, $this->dataVal);
			if($this->cm->get($this->dataName) == null){
				$this->cm->set($this->dataName, $this->dataVal);
			}
		} else {
			$this->cm->set($this->dataName, $val); //Shadow the data in a Cookie
		}

		return $this;
	}
	function getNextMidnight(){
		$time=time();
		$year=date("Y",$time);
		$month=date("m",$time);
		$day=date("d",$time);

		return mktime(24, 0, 0, $month, $day, $year);
	}
	function isWeekDay(){
		$time=time();
		$weekDay=date("N",$time);
		return ($weekDay < 6) ? true : false;
	}
	function canVote(){
		$val=$this->recall($this->dataName);
		//var_dump($val);

		if($this->isWeekDay()){
			if( ($val !== null) && (intval($val) !== 0) ){
				if(intval($val) < $this->getNextMidnight()){
					return true;
				}
			} else {
				//HACK - This is a workaround for dealing with the Admin LogOut process restting the session
				//     - Data has been shadowed in a cookie, so we will read it back from there
				$val=$this->cm->get($this->dataName);
				if($val !== null){
					$this->memorize($this->dataName, $val);
					if(intval($val) < $this->getNextMidnight()){
						return true;
					}
				}
			}
		}
		return false;
	}
	function voted(){
		$val=$this->getNextMidnight();
		$this->cm->set($this->dataName, $val); //Shadow the data in a Cookie
		return $this->memorize($this->dataName, $val);
	}
}
