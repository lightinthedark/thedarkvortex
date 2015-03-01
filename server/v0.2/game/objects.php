<?php
/**
 * All API-able game objects (Units, Map-Chunks etc) extend this
 */
abstract class ObjectAbstract
{
	/*
	 * abstract properties which child classes must define
	 * 
	 * $_object
	 * $_table
	 * $_properties
	 * $_d
	 */
	var $_knownObjects = array( 'unit', 'wpnt', 'mapc', 'plyr' );
	
	public function __call( $strName, $arrArgs )
	{
// 		var_dump( $this );
// 		var_dump( $strName );
// 		var_dump( $arrArgs );
		return call_user_func_array( array( $this, '_error' ), $arrArgs );
	}
	
	/**
	 * General error message to use when a non-available info request is made
	 */
	public function _error()
	{
		return array( 'status'=>'error', 'message'=>'function did not exist' );
	}
	
	/**
	 * Implements the general behaviour that each column has a matching entry in the internal data array
	 * Returns by reference so it can be used when binding queries etc
	 * Should be overridden if the data structure is more complex
	 * @param string $col  The column name whose data var is required
	 */
	protected function &_getVarForCol( $col )
	{
		return $this->_d[ $col ];
	}
	
	
	
	/**
	 * Prepares a statement to retrieve this object's data into variables
	 * convert id=... or coords=... into an array of user-accessible ids
	 * @return PDOStatement
	 */
	protected function _getBoundStmt( $selects, $requirements = null )
	{
		$selects = array_intersect( $selects, array_keys( $this->_properties ) );
		$selectStrs = array();
		foreach( $selects as $col ) {
			$selectStrs[ $col ] = isset( $this->_properties[ $col ][ 'selector' ] )
				? $this->_properties[ $col ][ 'selector' ].' AS '.$col
				: $col;
		}
		
		if( is_null( $requirements ) ) {
			$requirements = $this->_getRequirementsFromRequest();
		}
		
		// convert requirements to where clauses
		$also = array();
		$wheres = array();
		foreach( $requirements as $k=>$v ) {
			if( substr( $k, 4, 1 ) !== '_' ) {
				continue; // skip things that obviously aren't [a-z]{4}_.*
			}
			
			$objectType = substr( $k, 0, 4 );
			$col = substr( $k, 5 );
			if( $objectType === $this->_object ) {
				if( isset( $this->_properties[ $col ] ) ) {
					$wheres[ $col ][] = $this->_toArray( $v );
				}
			}
			else {
				if( array_search( $objectType, $this->_knownObjects ) !== false ) {
					$also[ $objectType ] = true;
				}
			}
		}
		
		// do "also" things to get arrays of ids that this table can use
		// ****
		
		// reduce all where-in-value-list clauses to a single array each
		foreach( $wheres as $col=>$vals ) {
			$vals = $this->_flattenArrays( $vals );
			$wheres[ $col ] = $vals;
			$whereStrs[ $col ] = $this->_createInClause( $col, $vals );
		}
		
		// put all "where" clauses into a string for the prepared statement
		$where = empty( $whereStrs )
			? ''
			: "\n".'WHERE '.implode( "\n AND ", $whereStrs );
		
		// create the statement
		$db = Database::getDB();
		
		$st = $db->prepare(
				'SELECT '.implode( ', ', $selectStrs )
				."\n".'FROM '.$this->_table
				.$where
				."\n".'ORDER BY id ASC' );
		
		// bind all "where" clauses
		foreach( $wheres as $col=>$vals ) {
			$this->_bindInClause( $col, $vals, $st, PDO::PARAM_INT );
		}
		
		// execute prep statement to get all requested data that match criteria
		$st->execute();
// 		var_dump( 'query', $st->queryString );
// 		var_dump( 'err:', $st->errorInfo() );
		
		foreach( $selects as $col ) {
			$st->bindColumn( $col, $this->_getVarForCol( $col ), $this->_properties[ $col ][ 'pdo_type' ] );
		}
		
		return $st;
	}
	
	/**
	 * Extract json-decoded requirements from a GET request
	 * 
	 * @return array  The requirements specified in the GET
	 */
	protected function _getRequirementsFromRequest()
	{
		$r = array();
		foreach( $_GET as $k=>$v ) {
			$r[ $k ] = json_decode( $v );
		}
		return $r;
	}
	
	/**
	 * Ensure the given value is an array, making it so if it isn't
	 * 
	 * @param multitype:mixed $v  The value to array-ify
	 * @return array  The array $v or array( $v )
	 */
	protected function _toArray( $v )
	{
		if( !is_array( $v ) ) {
			if( empty( $v ) ) {
				$v = array();
			}
			else {
				$v = array( $v );
			}
		}
		return $v;
	}
	
	/**
	 * Combine a bunch of arrays into a single array
	 * 
	 * "reduce?"
	 * @param array $ids  An array of arrays of vaules to merge
	 * @return multitype:
	 */
	protected function _flattenArrays( $ids )
	{
		$ret = array();
		foreach( $ids as $arr ) {
			foreach( $arr as $id ) {
				$ret[ $id ] = true;
			}
		}
		
		return array_keys( $ret );
	}
	
	protected function _createInClause( $col, $vals )
	{
		$colPos = array_search( $col, array_keys( $this->_properties ) );
		
		$col = isset( $this->_properties[ $col ][ 'selector' ] )
			? $this->_properties[ $col ][ 'selector' ]
			: $col;
		
		$str = $col.' IN ( NULL'; // nothing in clause means nothing in results; gives starter for comma-separated list
		if( !( empty( $vals ) || $colPos === false ) ) {
			foreach( $vals as $k=>$v ) {
				$str .= ',:'.$colPos.'_'.$k;
			}
		}
		$str .= ')';
		
		return $str;
	}
	
	protected function _bindInClause( $col, $vals, $st, $dataType )
	{
		$colPos = array_search( $col, array_keys( $this->_properties ) );
		if( !( empty( $vals ) || $colPos === false ) ) {
			foreach( $vals as $k=>$v ) {
				$st->bindValue( ':'.$colPos.'_'.$k, $v, $dataType );
			}
		}
	}
	
}