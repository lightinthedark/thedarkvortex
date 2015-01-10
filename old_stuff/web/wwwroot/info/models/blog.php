<?php

class ModelInfoBlog extends Model
{
	/**
	 * Loads the posts from the database
	 */
	function getPosts()
	{
		$db = pg_connect( 'dbname=thedarkvortex user=thedarkvortex_web password=JXEC:hu=3=N9rTZkQ8T#H%xl?fR-Ww' );
		$r = pg_query( $db, 'SELECT * FROM website.blog_posts ORDER BY "created" DESC;' );
		
		$retVal = array();
		while( $row = pg_fetch_assoc($r) ) {
			$retVal[] = $row;
		}
		return $retVal;
	}
}
?>