<?php
require( 'game'.DS.'objects.php' );

/**
 * A non-API-able object was requested. This will always result in an error
 */
class Object_Error extends ObjectAbstract
{
	public function _error()
	{
		echo 'function did not exist';
		$retVal = parent::_error();
		$retVal[ 'message' ] = 'object did not exist';
		return $retVal;
	}
}
?>