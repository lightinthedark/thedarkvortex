<?php

class Model
{
	var $_cName;
	var $_mName;
	
	function __construct()
	{
		$this->_layout = 'default';
	}
	
	function setController( $c )
	{
		$this->_controller = $c;
		$this->_cName = $c->getCName();
		$parts = explode( $this->_cName, strtolower(get_class($this)) );
		$this->_mName = $parts[1];
	}
	
}
?>