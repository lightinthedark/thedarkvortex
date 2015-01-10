<?php

class Controller
{
	var $_cName;
	
	/**
	 * Constructs a generic controller
	 */
	function __construct()
	{
		$this->_cName = strtolower( substr(get_class($this), 10) );
		$this->_defaultTask = 'display';
	}
	
	/**
	 * Default display function.
	 * Should never be called as we should always be looking at a descendant of this class.
	 */
	function display()
	{
		echo 'Default controller, nothing to see here';
	}
	
	/**
	 * Sets an appropriate header, and outputs a message detailing what could not be found
	 *
	 * @param string $what  What type of thing were we looking for (model, view, controller)?
	 * @param string $name  What was the name that we were looking for
	 */
	function notFound( $what, $name )
	{
		header( "HTTP/1.0 404 Not Found" );
		echo $what.'"'.htmlspecialchars($name).'" not found, sorry';
		die( '<br />execution halted by controller' );
	}
	
	/**
	 * Executes the named function if it exists, or the default if it doesn't
	 *
	 * @param string $task  The task to perform (function to call);
	 */
	function execute( $task )
	{
		if( method_exists($this, $task) ) {
			$this->$task();
		}
		else {
			$d = $this->_defaultTask;
			$this->$d();
		}
	}
	
	function getMenu()
	{
		$view = $this->getView( '_menu' );
		$view->display();
	}
	
	/**
	 * Accessor to the controller's name
	 */
	function getCName()
	{
		return $this->_cName;
	}
	
	/**
	 * Finds a controller's file, includes it and instanciates the class
	 *
	 * @param string $name  The name of the controller to look for
	 */
	function &getController( $id )
	{
		global $TDV_folders;
		$name = $TDV_folders[$id];
		$cFile = TDV_ROOT.$name.'/controller.php';
		$cName = 'Controller'.ucfirst($name);
		
		if( file_exists($cFile) ) {
			require_once( $cFile );
			$controller = new $cName();
			$retVal = $controller;
		}
		else {
			$controller = new Controller();
			$controller->notFound( 'page', $page );
			$retVal = false;
		}
		return $retVal;
	}
	
	/**
	 * Finds a model's file, includes it and instanciates the class
	 *
	 * @param string $name  The name of the model to look for
	 */
	function &getModel( $name )
	{
		$mFile = TDV_ROOT.$this->_cName.'/models/'.$name.'.php';
		$mName = 'Model'.ucfirst($this->_cName).ucfirst($name);
		
		if( file_exists($mFile) ) {
			require_once( $mFile );
			$model = new $mName();
			$retVal = $model;
		}
		else {
			$this->notFound( 'model', $name );
			$retVal = false;
		}
		return $retVal;
	}
	
	/**
	 * Finds a view's file, includes it and instanciates the class
	 *
	 * @param string $name  The name of the view to look for
	 */
	function &getView( $name )
	{
		$vFile = TDV_ROOT.$this->_cName.'/views/'.$name.'/view.php';
		$vName = 'View'.ucfirst($this->_cName).ucfirst($name);
		
		if( file_exists($vFile) ) {
			require_once( $vFile );
			$view = new $vName();
			$view->setController( $this );
			$retVal = $view;
		}
		else {
			$this->notFound( 'view', $name );
			$retVal = false;
		}
		return $retVal;
	}
}
?>