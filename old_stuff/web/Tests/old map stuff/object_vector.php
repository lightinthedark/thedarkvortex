<?php
bcscale( 20 );

class Path
{
	function __construct( $x1, $y1, $x2, $y2, $s )
	{
		$this->_from = new Point( $x1, $y1 );
		$this->_to   = new Point( $x2, $y2 );
		$this->_speed = $s;
		
		$this->_v = Vector::createFromCoords( $x1, $y1, $x2, $y2 );
		$this->_unit = $this->_v->createUnit( $this->_speed );
		
		$this->_length = $this->_v->getMagnitude();
	}
	
	function getFrom()
	{
		return $this->_from;
	}
	
	function getTo()
	{
		return $this->_to;
	}
	
	function getV()
	{
		return $this->_v;
	}
	
	function getUnit()
	{
		return $this->_unit;
	}
	
	function getPointsStr()
	{
		return $this->_from->getCoordsStr().','.$this->_to->getCoordsStr();
	}
	
	function atTime( $t )
	{
		return $this->_from->addVector( $this->_unit->mult($t) );;
	}
	
}

class Vector
{
	function __construct( $a, $b )
	{
		$a = (string)$a;
		$b = (string)$b;
		$this->_a = $a;
		$this->_b = $b;
		$this->_magnitude = bcsqrt( bcadd(bcmul($a, $a), bcmul($b, $b)) );
		$this->_slope = ( ($a == 0) ? null : bcdiv($b,$a) );
	}
	
	function createFromCoords( $x1, $y1, $x2, $y2 )
	{
		$x1 = (string)$x1;
		$x2 = (string)$x2;
		$y1 = (string)$y1;
		$y2 = (string)$y2;
		return new Vector( bcsub($x2, $x1), bcsub($y2, $y1) );
	}
	
	function createUnit( $s )
	{
		$s = (string)$s;
		$unit = bcdiv($s, $this->_magnitude);
		$a = bcmul($s, bcdiv($this->_a, $this->_magnitude));
		$b = bcmul($s, bcdiv($this->_b, $this->_magnitude));
		return new Vector( $a, $b );
	}
	
	function getA()
	{
		return $this->_a;
	}
	
	function getB()
	{
		return $this->_b;
	}
	
	function getMagnitude()
	{
		return $this->_magnitude;
	}
	
	function getSlope()
	{
		return $this->_slope;
	}
	
	function add( $v )
	{
		return new Vector( bcadd($this->_a, $v->getA()), bcadd($this->_b, $v->getB()) );
	}
	
	function sub( $v )
	{
		return new Vector( bcsub($this->_a, $v->getA()), bcsub($this->_b, $v->getB()) );
	}
	
	function mult( $val )
	{
		return new Vector( bcmul($this->_a, $val), bcmul($this->_b, $val) );
	}
	
	function div( $val )
	{
		return new Vector( bcdiv($this->_a, $val), bcdiv($this->_b, $val) );
	}
	
	function dot( $v )
	{
		return bcadd( bcmul($this->_a, $v->getA()), bcmul($this->_b, $v->getB()) );
	}
	
}


class Point
{
	function __construct( $x, $y )
	{
		$this->_x = (string)$x;
		$this->_y = (string)$y;
	}
	
	function getX()
	{
		return $this->_x;
	}
	
	function getY()
	{
		return $this->_y;
	}
	
	function getCoordsStr()
	{
		return $this->_x.','.$this->_y;
	}
	
	function to( $p )
	{
		$dx = bcsub( $this->_x, $p->getX() );
		$dy = bcsub( $this->_y, $p->getY() );
		return sqrt( bcadd(bcmul($dx, $dx), bcmul($dy, $dy)) );
	}
	
	function addVector( $v )
	{
		return new Point( bcadd($this->_x, $v->getA()), bcadd($this->_y, $v->getB()) );
	}
	
	function sub( $point )
	{
		return new Point( bcsub($this->_x, $point->getX()), bcsub($this->_y, $point->getY()) );
	}
	
	function mult( $val )
	{
		return new Point( bcmul($this->_x, $val), bcmul($this->_y, $val) );
	}
	
	function dot( $point )
	{
		return bcadd( bcmul($this->_x, $point->getX()), bcmul($this->_y, $point->getY()) );
	}
}
