<?php
abstract class Output
{
	
	protected function _renderHeaders()
	{
		header( 'Access-Control-Allow-Origin: *' ); // may need to specifically echo back the origin domain
		// *** may be optional. wide browser-testing required to be sure
		header( 'Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS' );
		header( 'Access-Control-Allow-Headers: x-requested-with, content-type, accept' );
	}
	
	
	abstract protected function renderData( $data );
	
}
?>