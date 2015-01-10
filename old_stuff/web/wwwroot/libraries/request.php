<?php

class Request
{
	function get( $var, $default = null )
	{
		if( isset($_GET[$var]) ) {
			return $_GET[$var];
		}
		elseif( isset($_POST[$var]) ) {
			return $_POST[$var];
		}
		elseif( isset($_SESSION[$var]) ) {
			return $_SESSION[$var];
		}
		else {
			return $default;
		}
	}
}
?>