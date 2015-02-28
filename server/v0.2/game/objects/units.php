<?php
require( 'game'.DS.'objects.php' );

/**
 * Units are the players' agents in the game world
 */
class ObjectUnits extends ObjectAbstract
{
	var $_knownCols = array( 'id', 'player_id', 'size', 'color' );
	
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
	
	public function getIds()
	{
		return $this->_calcIds();
	}
	
	/**
	 * Prepares a statement to retrieve waypoint data into variables
	 * @return PDOStatement
	 */
	private function _getBoundStmt()
	{
		$db = Database::getDB();
		
		$ids = $this->_calcIds();
		$where = $this->_createInClause( 'id', $ids );
		
		$st = $db->prepare(
			      'SELECT id, player_id, size, color'
			."\n".'FROM units'
			."\n".'WHERE '.$where
			."\n".'ORDER BY id ASC' );
		$this->_bindInClause( 'id', $ids, $st, PDO::PARAM_INT );
		$st->execute();
// 		var_dump( $st->queryString );
// 		var_dump( 'err:', $st->errorInfo() );
		
		$st->bindColumn( 'id',        $this->_d['id'],        PDO::PARAM_INT );
		$st->bindColumn( 'player_id', $this->_d['player_id'], PDO::PARAM_INT );
		$st->bindColumn( 'size',      $this->_d['size'],      PDO::PARAM_INT );
		$st->bindColumn( 'color',     $this->_d['color'],     PDO::PARAM_STR );
		
		return $st;
	}
	
	/**
	 * convert id=... or coords=... into an array of user-accessible ids
	 */
	private function _calcIds( $requirements = null )
	{
		return array( 2, 4 );
		if( is_null( $requirements ) ) {
			$requirements = $_GET;
		}
		
		$also = array();
		$wheres = array();
		$ids = array();
		foreach( $requirements as $k=>$v ) {
			if( substr( $k, 4, 1 ) !== '_' ) {
				continue; // skip things that obviously aren't [a-z]{4}_.*
			}
			
			$objectType = substr( $k, 0, 4 );
			if( $objectType === 'unit' ) {
				switch( substr( $k, 5 ) ) {
					case( 'id' ):
						$ids[] = $this->_toArray( $v );
					break;
					
					case( 'player_id' ):
						$wheres[ 'player_id' ] = $v;
					break;
				}
			}
			else {
				if( array_search( $objectType, $this->_knownObjects ) !== false ) {
					$also[ $objectType ] = true;
				}
			}
		}
		var_dump( 'ids,wheres,also', $ids, $wheres, $also );
		
		// do "also" things to get arrays of unit ids
		
		// reduce unit ids from "also"s and from basic 'id' case to single array
		// andadd them to the where clause (restrict to only found ids)
		$ids = $this->_flattenIds( $ids );
		$wheres[ 'id' ] = $ids;
		
		// put all "where" clauses into a prep statement
		foreach( $wheres as $col=>$vals ) {
			$this->_createInClause( $col, $vals );
		}
		
		// create the statement
		$db = Database::getDB();
		
		$st = $db->prepare(
				'SELECT id'
				."\n".'FROM units'
				."\n".'WHERE '.$where
				."\n".'ORDER BY id ASC' );
		
		// bind all "where" clauses
		foreach( $wheres as $col=>$vals ) {
			$this->_bindInClause( $col, $vals, $st, PDO::PARAM_INT );
		}
		
		// execute prep statement to get all unit ids that match criteria
		$st->execute();
		var_dump( 'err:', $st->errorInfo() );
		
		$st->bindColumn( 'id',        $this->_d['id'],        PDO::PARAM_INT );
		
		// get all the data
		
		return array( 2, 4 );
	}
	
	protected function getPlayerIds( $requirements )
	{
		
	}
}
?>