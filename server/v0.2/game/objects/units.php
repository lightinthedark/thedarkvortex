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
}
?>