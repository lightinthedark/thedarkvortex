<?php
require( 'game'.DS.'objects.php' );

/**
 * Units are the players' agents in the game world
 */
class ObjectUnits extends ObjectAbstract
{
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
		$st = $this->_getBoundStmt();
		
		$r = array();
		while( $row = $st->fetch( PDO::FETCH_BOUND ) ) {
			$r[ $this->_d['id'] ] = array(
				'id'=>$this->_d['id'],
				'player_id'=>$this->_d['player_id'],
				'size'=>$this->_d['size'],
				'color'=>$this->_d['color']
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
			      'SELECT id, player_id, size, color'
			."\n".'FROM units'
			."\n".'ORDER BY id ASC' );
		$st->execute();
		$st->bindColumn( 'id',        $this->_d['id'],        PDO::PARAM_INT );
		$st->bindColumn( 'player_id', $this->_d['player_id'], PDO::PARAM_INT );
		$st->bindColumn( 'size',      $this->_d['size'],      PDO::PARAM_INT );
		$st->bindColumn( 'color',     $this->_d['color'],     PDO::PARAM_STR );
		
		return $st;
	}
}
?>