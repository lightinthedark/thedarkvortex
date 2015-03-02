<?php
$debug = true;
if( $debug ) { ob_start(); }
error_reporting( $debug ? E_ALL : E_NONE );

// set up the application
require( 'configuration.php' );
require( 'lib'.DS.'request.php' );
require( 'lib'.DS.'database.php' );
require( 'game'.DS.'game.php' );

// get / set the data (setting returns new state?)
$strObj = Request::get( 'obj', '_error' );
$strInfo = Request::get( 'info', '' );
$strMethod = Request::getServer( 'REQUEST_METHOD', 'GET' );
$strFunc = strtolower( $strMethod ).ucFirst( $strInfo );

$hdlHandler = Game::getObjectHandler( $strObj );
$data = $hdlHandler->$strFunc();

// render the returned data
$format = Request::get( 'format', 'json' );
require( 'output'.DS.$format.'.php' );

$o = new OutputJson();

if( $debug ) { $debugOut = ob_get_clean(); }
$o->renderData( $data );

if( $debug ) { echo "\r\n\r\n".$debugOut; }
?>