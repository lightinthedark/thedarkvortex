<?php
define( TDV_MAIN, 1 );
define( TDV_GAME, 2 );
define( TDV_ACCOUNT, 3 );

define( TDV_TEMPLATE, TDV_ROOT.'templates/' );
define( TDV_LIBRARIES, TDV_ROOT.'libraries/' );

$TDV_folders = array(
	  TDV_MAIN=>'info'
	, TDV_GAME=>'game'
	, TDV_ACCOUNT=>'account' );
$TDV_scripts      = array();
$TDV_scripts_cond = array();
$TDV_styles       = array();
$TDV_styles_cond  = array();
?>