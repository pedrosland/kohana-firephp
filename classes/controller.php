<?php

class Controller extends Kohana_Controller{

	function __construct(Request $request){
		$this->fire = FirePHP::getInstance();
		parent::__construct($request);
	}
}