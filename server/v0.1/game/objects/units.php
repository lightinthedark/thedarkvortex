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
		return array(
			'a'=>array( 'size'=>10, 'color'=>'red' ),
			'b'=>array( 'size'=>15, 'color'=>'blue' )
		);
	}
	
	/**
	 * Get the orders for some / all units for some / all time
	 */
	function getWaypoints()
	{
		$t = time();
		return array(
			'a'=>array(
				array( 'x'=> 5, 'y'=>10, 't'=> 2 + $t ),
				array( 'x'=>12, 'y'=> 8, 't'=> 6 + $t ),
				array( 'x'=>14, 'y'=>20, 't'=>20 + $t ),
			),
			'b'=>array(
				array( 'x'=>20, 'y'=> 8, 't'=> 2 + $t ),
				array( 'x'=>5,  'y'=> 8, 't'=> 6 + $t ),
				array( 'x'=>20, 'y'=>16, 't'=>10 + $t ),
				array( 'x'=>25, 'y'=>25, 't'=>20 + $t ),
			),
		);
	}
}
?>