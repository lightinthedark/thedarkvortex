<?php
class Database
{
	private static $_connection;
	
	public static function getDB()
	{
		if( !isset( self::$_connection ) ) {
			try {
				self::$_connection = new PDO(
					'mysql:host='.Config::$db_server.';dbname='.Config::$db_database.';charset=utf8',
					Config::$db_user,
					Config::$db_pwd
				);
			}
			catch( PDOException $e ) {
				echo 'Error: '.$e->getMessage();
				die();
			}
		}
		
		return self::$_connection;
	}
	
	function close()
	{
		self::$_connection = null;
	}
}
?>