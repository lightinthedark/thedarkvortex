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
		$st = $this->_getBoundStmt();
		
		$r = array();
		while( $row = $st->fetch( PDO::FETCH_BOUND ) ) {
			$r[ $this->_d['id'] ] = array(
				'id'=>$this->_d['id'],
				'x'=>$this->_d['point']['x'],
				'y'=>$this->_d['point']['y'],
				't'=>$this->_d['time'],
				'unit'=>$this->_d['unit'] );
		}
		
		return $r;
	}
	
	/**
	 * Get the orders for some / all units for some / all time
	 */
	function getForunits()
	{
		$st = $this->_getBoundStmt();
		
		$r = array();
		while( $row = $st->fetch( PDO::FETCH_BOUND ) ) {
			if( !isset( $r[ $this->_d['unit'] ] ) ) {
				$r[ $this->_d['unit'] ] = array();
			}
			
			$r[ $this->_d['unit'] ][ $this->_d['id'] ] = array(
				'id'=>$this->_d['id'],
				'x'=>$this->_d['point']['x'],
				'y'=>$this->_d['point']['y'],
				't'=>$this->_d['time']
			);
		}
		
		return $r;
	}
	
	/**
	 * Prepares a statement to retrieve waypoint data into variables
	 * @return PDOStatement
	 */
	function _getBoundStmt()
	{
		$db = Database::getDB();
		
		$st = $db->prepare(
			      'SELECT id, unit_id, UNIX_TIMESTAMP( time ) AS time, X( point ) AS px, Y( point ) AS py'
			."\n".'FROM waypoints'
			."\n".'ORDER BY time ASC' );
		$st->execute();
		$st->bindColumn( 'id',      $this->_d['id'],         PDO::PARAM_INT );
		$st->bindColumn( 'unit_id', $this->_d['unit'],       PDO::PARAM_INT );
		$st->bindColumn( 'time',    $this->_d['time'],       PDO::PARAM_INT );
		$st->bindColumn( 'px',      $this->_d['point']['x'], PDO::PARAM_INT );
		$st->bindColumn( 'py',      $this->_d['point']['y'], PDO::PARAM_INT );
		
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