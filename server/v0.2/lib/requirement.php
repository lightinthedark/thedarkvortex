<?php
/**
 * All API-able game objects (Units, Map-Chunks etc) extend this
 */
abstract class Requirement
{
	private static $_rId = 0;
	
	/**
	 * Creates a (subclass of) Requirment to suit the key and value where it was found
	 * 
	 * @param string $key  The string identifier of the requirement; the property
	 * @param mixed:string|array $val  The values associated with the property in the key
	 */
	public static function create( $key, $val )
	{
		$objectType = null;
		$prop = null;
		if( substr( $key, 4, 1 ) === '_' ) {
			$objectType = substr( $key, 0, 4 );
			$prop = substr( $key, 5 );
			if( array_search( $objectType, array_keys( Config::$knownObjects ) ) === false ) {
				$objectType = null; // don't try to find properties of objects that don't exist
			}
		}
		
		if( is_null( $objectType ) || is_null( $prop ) ) {
			$r = new RequirementUndefined( self::$_rId++, null, null, null );
		}
		else {
			// with a valid object lets try to interpret the val to work out what sort of Requirement we have here
			if( is_string( $val ) ) {
				$json = json_decode( $val );
				if( !is_null( $json ) ) {
					$val = $json;
				}
			}
			
			if( is_array( $val ) ) {
				if( array_key_exists( 'from', $val ) && array_key_exists( 'to', $val ) ) {
					$r = new RequirementRange( self::$_rId++, $objectType, $prop, $val );
				}
				else {
					$r = new RequirementArray( self::$_rId++, $objectType, $prop, $val );
				}
			}
			else {
				$r = new RequirementVal( self::$_rId++, $objectType, $prop, $val );
			}
		}
		
		return $r;
	}
	
	public function __construct( $id, $objectType, $prop, $val )
	{
		$this->_id = $id;
		$this->_objectType = $objectType;
		$this->_prop = $prop;
		$this->_val = $val;
	}
	
	public function getObjectType()
	{
		return $this->_objectType;
	}
	public function getProperty()
	{
		return $this->_prop;
	}
	public function getValue()
	{
		return $this->_val;
	}
	public abstract function setValue( $val );
	public abstract function getWhereClause();
	public abstract function bindWhereClause( &$st );
	
	public function combine( $inReq )
	{
		if( $this->getObjectType() !== $inReq->getObjectType()
		 || $this->getProperty()   !== $inReq->getProperty() ) {
			return false;
		}
		
		$c = array(
			get_class( $this )=>$this,
			get_class( $inReq )=>$inReq
		);
		ksort( $c );
		
		$r1 = reset( $c );
		$c1 = key( $c );
		$r2 = next( $c );
		$c2 = key( $c );
		switch( $c1 ) {
			case( 'RequirementsArray' ):
				switch( $c2 ) {
					case( 'RequirementArray' ):
						$vals = array();
						foreach( $r1->getValue() as $id ) {
							$vals[ $id ] = true;
						}
						foreach( $r2->getValue() as $id ) {
							$vals[ $id ] = true;
						}
						$r1->setValue( array_keys( $ret ) );
						
						$ret = $r1;
					break;
					
					case( 'RequirementRange' ):
						$ret = false;
					break;
					
					case( 'RequirementVal' ):
						if( array_search( $r2->getValue(), $r1->getValue() ) === false ) {
							$r1->addValue( $r2->getValue() );
						}
					break;
					
					default:
						$ret = false;
					break;
				}
			break;
			
			case( 'RequirementsRange' ):
				switch( $c2 ) {
				case( 'RequirementRange' ):
						$ret = false;
					break;
					
					case( 'RequirementVal' ):
						$ret = false;
					break;
					
					default:
						$ret = false;
					break;
				}
			break;
			
			case( 'RequirementVal' ):
				switch( $c2 ) {
					case( 'RequirementVal' ):
						$ret = new RequirementArray( self::$_rId++, $r1->getObject(), $r1->getProperty(), array( $r1->getValue(), $r2->getValue() ) );
					break;
					
					default:
						$ret = false;
					break;
				}
			break;
			
			default:
				$ret = false;
			break;
		}
	}
	
	protected function _getCol()
	{
		$obj = ObjectLoader::_( $this->_objectType );
		$property = $obj->findProperty( $this->_prop );
		
		return isset( $property[ 'selector' ] )
			? $property[ 'selector' ]
			: $this->_prop;
	}
	
	protected function _getValType()
	{
		$obj = ObjectLoader::_( $this->_objectType );
		$property = $obj->findProperty( $this->_prop );
		
		return isset( $property[ 'pdo_type' ] )
			? $property[ 'pdo_type' ]
			: PDO::PARAM_STR;
	}
}

class RequirementArray extends Requirement
{
	public function setValue( $val )
	{
		if( is_array( $val ) ) {
			$this->_val = $val;
		}
	}
	
	public function addValue( $val )
	{
		if( !is_array( $val ) && !is_object( $val ) ) {
			$this->_val[] = $val;
		}
	}
	
	public function getWhereClause()
	{
		$col = $this->_getCol();
		
		$str = $col.' IN ( NULL'; // nothing in clause means nothing in results; gives starter for comma-separated list
		if( !empty( $this->_val ) ) {
			foreach( $this->_val as $k=>$v ) {
				$str .= ',:'.$this->_id.'_'.$k;
			}
		}
		$str .= ')';
		
		return $str;
	}
	
	public function bindWhereClause( &$st )
	{
		$dataType = $this->_getValType();
		if( !empty( $this->_val ) ) {
			foreach( $this->_val as $k=>$v ) {
				$st->bindValue( ':'.$this->_id.'_'.$k, $v, $dataType );
			}
		}
	}
}

class RequirementRange extends Requirement
{
	public function setValue( $val )
	{
		if( is_array( $val ) ) {
			$this->_val = $val;
		}
	}
	
	public function getWhereClause()
	{
		
	}
	
	public function bindWhereClause( &$st )
	{
		
	}
}

class RequirementUndefined extends Requirement
{
	public function setValue( $val ) {}
	public function getWhereClause() {}
	public function bindWhereClause( &$st) {}
	
	public function getObjectType()
	{
		return '_undefined';
	}
}

class RequirementVal extends Requirement
{
	public function setValue( $val )
	{
		if( is_array( $val ) ) {
			$this->_val = $val;
		}
	}
	
	public function getWhereClause()
	{
		$col = $this->_getCol();
		
		return $col.' = :'.$this->_id.'_v';
	}
	
	public function bindWhereClause( &$st)
	{
		$dataType = $this->_getValType();
		$st->bindValue( ':'.$this->_id.'_v', $this->_val, $dataType );
	}
}
