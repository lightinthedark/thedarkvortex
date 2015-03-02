<?php
class Request
{
	public static function get( $key, $default )
	{
		if( isset( $_GET[ $key ]     ) ) { $retVal = $_GET[ $key ]; }
		elseif( isset( $_POST[ $key ]    ) ) { $retVal = $_POST[ $key ]; }
		elseif( isset( $_REQUEST[ $key ] ) ) { $retVal = $_REQUEST[ $key ]; }
		else { $retVal = $default; }
		
		$retVal = preg_replace( '/[^a-zA-Z0-9\\{\\}|\\"\\\']/', '', $retVal );
		
		return $retVal;
	}
	
	public static function getServer( $key, $default )
	{
		if( isset( $_SERVER[ $key ] ) ) { $retVal = $_SERVER[ $key ]; }
		else { $retVal = $default; }
		
		return $retVal;
	}
}
?>