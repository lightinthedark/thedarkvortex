<?php
define( 'DS', DIRECTORY_SEPARATOR );

class Config
{
	public static $db_server = 'mysql1103.servage.net';
	public static $db_database = 'tdv_server0';
	public static $db_user = 'tdv_server0';
	public static $db_pwd = 'QdF7HAUiW6apFmf4eaN7';
	
	public static $knownObjects = array(
		'unit'=>array( 'file'=>'units.php'     , 'class'=>'ObjectUnits'     ),
		'wpnt'=>array( 'file'=>'waypoints.php' , 'class'=>'ObjectWaypoints' ),
		'mapc'=>array( 'file'=>'mapchunk.php'  , 'class'=>'ObjectMapchunk'  ),
		'plyr'=>array( 'file'=>'player.php'    , 'class'=>'ObjectPlayer'    )
	);
}

?>