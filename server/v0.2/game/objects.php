<?php
require_once( 'lib'.DS.'requirement.php' );

class ObjectLoader
{
	public static function _( $objType )
	{
		$objInfo = Config::$knownObjects;
		if( isset( $objInfo[ $objType ] ) && !class_exists( $objInfo[ $objType ][ 'class' ] ) ) {
			require 'game'.DS.'objects'.DS.$objInfo[ $objType ][ 'file' ];
		}
		
		return new $objInfo[ $objType ][ 'class' ]();
	}
}

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
	
	public function findProperty( $prop )
	{
		return $this->_properties[ $prop ];;
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
	 * convert id=... or coords=... etc into relevant "WHERE" clauses
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
		
		$nativeRequirements = isset( $requirements[ $this->_object ] )
			? $requirements[ $this->_object ]
			: array();
		unset( $requirements[ $this->_object ] );
		unset( $requirements[ '_undefined' ] );
		
		// turn foreign requirements into native requirements
		foreach( $requirements as $object=>$properties ) {
			// create (autoload?) object class
			// naturalisedRequirements = obj->naturalise( $this->_object, $properties )
			// foreach( naturalisedRequirements as $prop=>$req ) {
			//     $nativeRequirements[] = $req
		}
		
		// combine all requirements we can to create a minimal set
		for( $curId = count( $nativeRequirements ) - 1; $curId >= 0; $curId-- ) {
			$curReq = $nativeRequirements[ $curId ];
			for( $i = $curId - 1; $i >= 0; $i-- ) {
				$newReq = $nativeRequirements[ $i ]->combine( $curReq );
				if( $newReq !== false ) {
					$nativeRequirements[ $i ] = $newReq;
					unset( $nativeRequirements[ $curId ] );
					$i = 0;
				}
			}
		}
		
		// put all "where" clauses into a string for the prepared statement
		if( empty( $nativeRequirements ) ) {
			$where = '';
		}
		else {
			foreach( $nativeRequirements as $req ) {
				$whereStrs[] = $req->getWhereClause();
			}
			$where = "\n".'WHERE '.implode( "\n AND ", $whereStrs );
		}
		
		// create the statement
		$db = Database::getDB();
		
		$st = $db->prepare(
				'SELECT '.implode( ', ', $selectStrs )
				."\n".'FROM '.$this->_table
				.$where
				."\n".'ORDER BY id ASC' );
		
		// bind all requirements
		foreach( $nativeRequirements as $req ) {
			$req->bindWhereClause( $st );
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
			$req = Requirement::create( $k, $v );
			$r[ $req->getObjectType() ][] = $req;
		}
		return $r;
	}
	
}