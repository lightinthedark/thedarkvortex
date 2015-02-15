<?php
require( 'game'.DS.'objects.php' );

/**
 * Units are the players' agents in the game world
 */
class ObjectWaypoints extends ObjectAbstract
{
	/**
	 * Get core information on some / all units for some timeframe
	 */
	public function getList()
	{
		$st = $this->_getBoundStmt( $unit, $time, $point );
		
		$r = array();
		while( $row = $st->fetch( PDO::FETCH_BOUND ) ) {
			$r[] = array( 'x'=>$point['x'], 'y'=>$point['y'], 't'=>$time, 'unit'=>$unit );
		}
		
		return $r;
	}
	
	/**
	 * Get the orders for some / all units for some / all time
	 */
	function getForunits()
	{
		$st = $this->_getBoundStmt( $unit, $time, $point );
		
		$r = array();
		while( $row = $st->fetch( PDO::FETCH_BOUND ) ) {
			if( !isset( $r[ $unit ] ) ) {
				$r[ $unit ] = array();
			}
			
			$r[ $unit ][] = array( 'x'=>$point['x'], 'y'=>$point['y'], 't'=>$time );
		}
		
		return $r;
	}
	
	/**
	 * Prepares a statement to retrieve waypoint data into variables
	 * @return PDOStatement
	 */
	function _getBoundStmt( &$unit, &$time, &$point )
	{
		$db = Database::getDB();
		
		$st = $db->prepare(
			      'SELECT unit_id, UNIX_TIMESTAMP( time ) AS time, X( point ) AS px, Y( point ) AS py'
			."\n".'FROM waypoints'
			."\n".'ORDER BY time ASC' );
		$st->execute();
		$st->bindColumn( 'unit_id', $unit,       PDO::PARAM_INT );
		$st->bindColumn( 'time',    $time,       PDO::PARAM_INT );
		$st->bindColumn( 'px',      $point['x'], PDO::PARAM_INT );
		$st->bindColumn( 'py',      $point['y'], PDO::PARAM_INT );
		
		return $st;
	}
	
	/*
	 * original sample data
			array( 'x'=> 5, 'y'=>10, 't'=> 2 + $t, 'unit'=>'a' ),
			array( 'x'=>20, 'y'=> 8, 't'=> 2 + $t, 'unit'=>'b' ),
			array( 'x'=>12, 'y'=> 8, 't'=> 6 + $t, 'unit'=>'a' ),
			array( 'x'=>5,  'y'=> 8, 't'=> 6 + $t, 'unit'=>'b' ),
			array( 'x'=>20, 'y'=>16, 't'=>10 + $t, 'unit'=>'b' ),
			array( 'x'=>14, 'y'=>20, 't'=>20 + $t, 'unit'=>'a' ),
			array( 'x'=>25, 'y'=>25, 't'=>20 + $t, 'unit'=>'b' ),
	 */
}
?>