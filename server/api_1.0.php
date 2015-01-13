<?php
require( 'configuration.php' );
require( 'lib'.DS.'request.php' );
require( 'lib'.DS.'database.php' );

$format = Request::get( 'format', 'json' );

require( 'output'.DS.$format.'.php' );

$data = array( 'status'=>'OK', 'version'=>'0.1' );

$o = new OutputJson();
$o->renderData( $data );
?>