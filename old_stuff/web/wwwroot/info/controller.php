<?php

class ControllerInfo extends Controller
{
	function display()
	{
		$viewName = Request::get('view', 'home');
		$view = $this->getView( $viewName );
		
		switch( $viewName ) {
			case( 'blog' ):
				$model = $this->getModel( $viewName );
				$view->setModel( $model );
				break;
		}
		
		$view->display();
	}
}
?>