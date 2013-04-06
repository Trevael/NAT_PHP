<?php
class Page_SierraBravo_xboxV2_GameLibrarian extends Page {
	public $user;
	function cmRequest($r){
		return $this->add('Controller_SierraBravo_xboxV2_GameLibrarian')->addRequest($r);
	}
	function initMainPage(){
		// Drawing main page. Let's see if user is subscribed fist

		//$f=$this->add('Form');
		$g=$this->add('Grid');

		//$r=$this->cmRequest('checkKey')->process();

		$r=$this->cmRequest('getGames')->process();
		$data=array();
		//foreach($r->result->List as $list){
			//$r=$this->cmRequest('GetIsSubscribed')
			//	->set('ListID',$list->ListID)
			//	->set('Email',$this->user->get('email'))
			//	->process()->result;


			//$f->addField('checkbox','subscribed_'.$list->ListID,$list->Name)
			//	->set($r=='True');

			//$data[]=array(
			//	'Name'=>$list->Name,
			//	'id'=>$list->ListID,
			//	'Subscribed'=>$r=='True'?'Y':''
			//	);
		//}

		$g->addColumn('text','Name');
		$g->addColumn('text','id','ListID');
		$g->addColumn('text','Subscribed');

		$g->addColumn('button','Subscribe');
		$g->addColumn('button','Unsubscribe');


		$g->setStaticSource($data);
	}

}
