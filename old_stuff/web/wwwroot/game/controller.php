<?php

class ControllerGame extends Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	function display()
	{
		$view = $this->getView( Request::get('view', 'test') );
		$view->display();
	}
}
?>