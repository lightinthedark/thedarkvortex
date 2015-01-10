<?php

class View
{
	var $_cName;
	var $_vName;
	
	function __construct()
	{
		$this->_layout = 'default';
	}
	
	function setLayout( $layout )
	{
		$this->_layout = (string)$layout;
	}
	
	function setModel( &$m )
	{
		$this->_model = &$m;
	}
	
	function setController( $c )
	{
		$this->_controller = $c;
		$this->_cName = $c->getCName();
		$parts = explode( $this->_cName, strtolower(get_class($this)) );
		$this->_vName = $parts[1];
	}
	
	function display()
	{
		echo 'Default view says nothing to see here';
	}
	
	function loadTemplate( $name = null )
	{
		$t = $this->_layout.(is_null($name) ? '' : '_'.$name);
		$tFile = TDV_ROOT.$this->_cName.'/views/'.$this->_vName.'/tmpl/'.$t.'.php';
		$tName = 'View'.$this->_CName.ucfirst($name);
		
		if( file_exists($tFile) ) {
			ob_start();
			require_once( $tFile );
			$retVal = ob_get_clean();
		}
		else {
			$this->_controller->notFound( 'template', $name );
			$retVal = false;
		}
		return $retVal;
	}
	
	function makeMenu( $items )
	{
		$curView = Request::get( 'view' );
		if( isset($items[$curView]) ) {
			$items[$curView]['active'] = true;
		}
		$retVal = '';
		foreach( $items as $item ) {
			$retVal .= '<div class="menu_item'.($item['active'] ? ' active' : '').'"><a href="'.TDV_ROOT.$this->_cName.'/index.php?view='.$item['view'].'">'.$item['text'].'</a></div>'."\n";
		}
		return $retVal;
	}
	
	function addScript( $file, $conditional = false, $library = false )
	{
		global $TDV_scripts;
		global $TDV_scripts_cond;
		if( $library ) {
			$fName = TDV_LIBRARIES.$file;
		}
		else {
			$fName = TDV_ROOT.$this->_cName.'/views/'.$this->_vName.'/tmpl/'.$file;
		}
		
		if( $conditional === false ) {
			if( array_search($fName, $TDV_scripts) === false ) {
				$TDV_scripts[] = $fName;
			}
		}
		else {
			if( array_search($fName, $TDV_scripts_cond) === false ) {
				$TDV_scripts_cond[] = array( 'file'=>$fName, 'cond'=>$conditional );
			}
		}
	}
	
	function addStyle( $file, $conditional = false )
	{
		global $TDV_styles;
		global $TDV_styles_cond;
		$fName = TDV_ROOT.$this->_cName.'/views/'.$this->_vName.'/tmpl/'.$file;
		if( $conditional === false ) {
			if( array_search($fName, $TDV_styles) === false ) {
				$TDV_styles[] = $fName;
			}
		}
		else {
			if( array_search($fName, $TDV_styles_cond) === false ) {
				$TDV_styles_cond[] = array( 'file'=>$fName, 'cond'=>$conditional );
			}
		}
	}
	
	function get( $func )
	{
		$func = 'get'.ucfirst( $func );
		
		if( method_exists($this->_model, $func) ) {
			return $this->_model->$func();
		}
		else {
			return null;
		}
	}
}
?>