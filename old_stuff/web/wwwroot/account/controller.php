<?php

class ControllerAccount extends Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	function display()
	{
		$view = $this->getView( Request::get('view', 'profile') );
		$view->display();
	}
}
?>