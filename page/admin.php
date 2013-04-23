<?php
class page_admin extends page_index {
	function init(){
		parent::init();
		$this->api->auth->check();
	}

}
