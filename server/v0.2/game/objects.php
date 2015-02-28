<?php
/**
 * All API-able game objects (Units, Map-Chunks etc) extend this
 */
abstract class ObjectAbstract
{
	var $_knownObjects = array( 'unit', 'wpnt', 'mapc', 'plyr' );
	var $_knownCols = array();
	
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
	 * @param unknown $ids
	 * @return multitype:
	 */
	protected function _flattenIds( $ids )
	{
		$ret = array();
		foreach( $ids as $arr ) {
			foreach( $arr as $id ) {
				$ret[ (int)$id ] = true;
			}
		}
		
		return array_keys( $ret );
	}
	
	protected function _createInClause( $col, $vals )
	{
		$colPos = array_search( $col, $this->_knownCols );
		$str = $col.' IN ( NULL'; // invalid id will never add to results, gives starter for comma-separated list
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
		$colPos = array_search( $col, $this->_knownCols );
		if( !( empty( $vals ) || $colPos === false ) ) {
			foreach( $vals as $k=>$v ) {
				$st->bindValue( ':'.$colPos.'_'.$k, $v, $dataType );
			}
		}
	}
	
}