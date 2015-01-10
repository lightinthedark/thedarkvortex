<?php

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
		$this->_a = $a;
		$this->_b = $b;
		$this->_magnitude = sqrt( (($a * $a) + ($b * $b)) );
		$this->_slope = ( ($a == 0) ? null : ($b / $a) );
	}
	
	function createFromCoords( $x1, $y1, $x2, $y2 )
	{
		return new Vector( ($x2 - $x1), ($y2 - $y1) );
	}
	
	function createUnit( $s )
	{
		$unit = ($s / $this->_magnitude);
		$a = $s * ($this->_a/$this->_magnitude);
		$b = $s * ($this->_b/$this->_magnitude);
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
		return new Vector( ($this->_a + $v->getA()), ($this->_b + $v->getB()) );
	}
	
	function sub( $v )
	{
		return new Vector( ($this->_a - $v->getA()), ($this->_b - $v->getB()) );
	}
	
	function mult( $val )
	{
		return new Vector( ($this->_a * $val), ($this->_b * $val) );
	}
	
	function div( $val )
	{
		return new Vector( ($this->_a / $val), ($this->_b / $val) );
	}
	
	function dot( $v )
	{
		return ( ($this->_a * $v->getA()) + ($this->_b * $v->getB()) );
	}
	
}


class Point
{
	function __construct( $x, $y )
	{
		$this->_x = $x;
		$this->_y = $y;
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
		$dx = ( $this->_x - $p->getX() );
		$dy = ( $this->_y - $p->getY() );
		return sqrt( (($dx * $dx) + ($dy * $dy)) );
	}
	
	function addVector( $v )
	{
		return new Point( ($this->_x + $v->getA()), ($this->_y + $v->getB()) );
	}
	
	function sub( $point )
	{
		return new Point( ($this->_x - $point->getX()), ($this->_y - $point->getY()) );
	}
	
	function mult( $val )
	{
		return new Point( ($this->_x * $val), ($this->_y * $val) );
	}
	
	function dot( $point )
	{
		return ( ($this->_x * $point->getX()) + ($this->_y * $point->getY()) );
	}
}

function pathsDistTime( $p1, $p2, $dist )
{
	// http://softsurfer.com/Archive/algorithm_0106/algorithm_0106.htm
	// apparently, D(t) = d(t)^2 = w(t)•w(t)
	// That becomes quadratic equation:
	// (u-v)•(u-v)t^2 + 2w(0)•(u-v)t + w(0)•w(0)
	//
	// Also remember that quadratic equations can be solved with:
	// 0 = ax^2 + bx + c
	// x = ( -b +- sqrt( b^2 - 4ac) ) / 2a
	
	$u = $p1->getUnit();
	$v = $p2->getUnit();
	$u_v = $u->sub($v);
	
	$w_0 = $p1->getFrom()->sub($p2->getFrom());
	
	$a = $u_v->dot($u_v);
	$b = $w_0->mult( 2 );
	$c = $w_0->dot($w_0) - ($dist * $dist);
	
	$t = ( (($b * -1) + sqrt( (($b*$b) - (4*$a*$c)) ) ) / (2*$a) );
	var_dump_pre( $t, 't: ');
}