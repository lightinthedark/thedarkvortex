<?php
require_once( 'game'.DS.'objects.php' );

/**
 * Units are the players' agents in the game world
 */
class ObjectUnits extends ObjectAbstract
{
	var $_object = 'unit';
	var $_table  = 'units';
	var $_properties = array(
		'id'       =>array( 'pdo_type'=>PDO::PARAM_INT ),
		'player_id'=>array( 'pdo_type'=>PDO::PARAM_INT ),
		'size'     =>array( 'pdo_type'=>PDO::PARAM_INT ),
		'color'    =>array( 'pdo_type'=>PDO::PARAM_STR )
	);
	var $_d = array();
	
	
	/* ============== *
	 * Public getters *
	 * ============== */
	
	
	/**
	 * Alias for getCore
	 */
	public function get() {
		return $this->getCore();
	}
	
	/**
	 * Get core information on some / all units for some timeframe
	 */
	public function getCore()
	{
		$st = $this->_getBoundStmt( array( 'id', 'player_id', 'size', 'color' ) );
		
		$r = array();
		while( $row = $st->fetch( PDO::FETCH_BOUND ) ) {
			$r[ $this->_d[ 'id' ] ] = array(
				'id'=>$this->_d[ 'id' ],
				'player_id'=>$this->_d[ 'player_id' ],
				'size'=>$this->_d[ 'size' ],
				'color'=>$this->_d[ 'color' ]
			);
		}
		
		return $r;
	}
	
	public function getIds()
	{
		$st = $this->_getBoundStmt( array( 'id' ) );
		
		$r = array();
		while( $row = $st->fetch( PDO::FETCH_BOUND ) ) {
			$r[ $this->_d[ 'id' ] ] = array(
				'id'=>$this->_d[ 'id' ],
			);
		}
		
		return $r;
	}
	
	protected function getPlayerIds( $requirements )
	{
		$st = $this->_getBoundStmt( array( 'id', 'player_id' ) );
		
		$r = array();
		while( $row = $st->fetch( PDO::FETCH_BOUND ) ) {
			$r[ $this->_d[ 'id' ] ] = array(
				'player_id'=>$this->_d[ 'player_id' ],
			);
		}
		
		return $r;
	}

}
?>