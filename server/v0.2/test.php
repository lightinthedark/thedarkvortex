<?php
require( 'configuration.php' );
require( 'output'.DS.'json.php' );

$data = array( 'status'=>'OK', 'version'=>'0.2' );

$o = new OutputJson();
$o->renderData( $data );
?>