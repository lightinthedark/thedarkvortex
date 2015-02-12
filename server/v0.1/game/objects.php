<?php
/**
 * All API-able game objects (Units, Map-Chunks etc) extend this
 */
abstract class ObjectAbstract
{
	public function __call( $strName, $arrArgs )
	{
// 		var_dump( $this );
// 		var_dump( $strName );
// 		var_dump( $arrArgs );
		return call_user_func_array( array( $this, '_error' ), $arrArgs );
	}
	
	/**
	 * General error message to use when a non-available info request is made
	 */
	public function _error()
	{
		return array( 'status'=>'error', 'message'=>'function did not exist' );
	}
	
	/**
	 * convert id=... or coords=... into an array of user-accessible ids
	 */
	protected function getIds()
	{
		return array( 'a', 'b' );
	}
}