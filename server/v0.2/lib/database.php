<?php
class Database
{
	function getDB()
	{
		if( !isset( $this->connection ) ) {
			try {
				$this->connection = new PDO(
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
		
		return $this->connection;
	}
	
	function close()
	{
		$this->connection = null;
	}
}
?>