<?php
class Request
{
	function get( $key, $default )
	{
		if( isset( $_GET[ $key ]     ) ) { $retVal = $_GET[ $key ]; }
		if( isset( $_POST[ $key ]    ) ) { $retVal = $_POST[ $key ]; }
		if( isset( $_REQUEST[ $key ] ) ) { $retVal = $_REQUEST[ $key ]; }
		
		$retVal = preg_replace( '/[^a-zA-Z0-9\\{\\}|\\"\\\']/', '', $retVal );
		
		return $retVal;
	}
	
}
?>