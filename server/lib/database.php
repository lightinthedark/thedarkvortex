<?php
class Database
{
	function getDB()
	{
		$this->connection = mysql_connect(
			Config::db_server,
			Config::db_database,
			Config::db_password
		);
		
		mysql_select_db( Config::db_database, $this->connection );
	}
	
}
?>