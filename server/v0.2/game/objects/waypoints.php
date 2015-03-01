<?php
require_once( 'game'.DS.'objects.php' );

/**
 * Units are the players' agents in the game world
 */
class ObjectWaypoints extends ObjectAbstract
{
	var $_object = 'wpnt';
	var $_table  = 'waypoints';
	var $_properties = array(
		'id'      =>array( 'pdo_type'=>PDO::PARAM_INT ),
		'x'       =>array( 'pdo_type'=>PDO::PARAM_INT, 'selector'=>'X( point )' ),
		'y'       =>array( 'pdo_type'=>PDO::PARAM_INT, 'selector'=>'Y( point )' ),
		't'       =>array( 'pdo_type'=>PDO::PARAM_STR, 'selector'=>'UNIX_TIMESTAMP( time )' ),
		'time'    =>array( 'pdo_type'=>PDO::PARAM_STR, 'selector'=>'UNIX_TIMESTAMP( time )' ),
		'unit_id' =>array( 'pdo_type'=>PDO::PARAM_INT )
	);
	var $_d = array();
	
	/**
	 * Implements the general behaviour that each column has a matching entry in the internal data array
	 * Returns by reference so it can be used when binding queries etc
	 * Should be overridden if the data structure is more complex
	 * @param string $col  The column name whose data var is required
	 */
	protected function &_getVarForCol( $col )
	{
		switch( $col ) {
			case( 'x' ):
			case( 'y' ):
				return $this->_d[ 'point' ][ $col ];
			break;
			
			case( 't' ):
				return $this->_d[ 'time' ][ $col ];
			break;
			
			default:
				return parent::_getVarForCol( $col );
			break;
		}
	}
	
	
	/* ============== *
	 * Public getters *
	 * ============== */
		
	
	/**
	 * Alias for getList
	 */
	public function get() {
		return $this->getList();
	}
	
	/**
	 * Get core information on some / all units for some timeframe
	 */
	public function getList()
	{
		$st = $this->_getBoundStmt( array( 'id', 'x', 'y', 'time', 'unit_id' ));
		
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
		$st = $this->_getBoundStmt( array( 'id', 'x', 'y', 'time', 'unit_id' ));
		
		$r = array();
		while( $row = $st->fetch( PDO::FETCH_BOUND ) ) {
			if( !isset( $r[ $this->_d['unit_id'] ] ) ) {
				$r[ $this->_d['unit_id'] ] = array();
			}
			
			$r[ $this->_d['unit_id'] ][ $this->_d['id'] ] = array(
				'id'=>$this->_d['id'],
				'x'=>$this->_d['point']['x'],
				'y'=>$this->_d['point']['y'],
				't'=>$this->_d['time']
			);
		}
		
		return $r;
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