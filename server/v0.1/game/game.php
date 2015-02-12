<?php
class Game
{
	function getObjectHandler( $strObject )
	{
		$strHandlerPath = 'game'.DS.'objects'.DS.str_replace( array( '/', '\\' ), '_', $strObject ).'.php';
		if( file_exists( $strHandlerPath ) ) {
			require( $strHandlerPath );
			$strClass = 'Object'.ucfirst( $strObject );
			$hdlHandler = new $strClass();
		}
		else {
			require( 'game'.DS.'objects'.DS.'_error.php' );
			$hdlHandler = new Object_Error();
		}
		
		return $hdlHandler;
	}
}
?>