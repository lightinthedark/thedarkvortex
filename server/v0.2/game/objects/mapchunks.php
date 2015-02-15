<?php
require( 'game'.DS.'objects.php' );

/**
 * The game board / world map is made of chunks which can be individually manipulated
 */
class ObjectMapchunks extends ObjectAbstract
{
	/**
	 * Alias for getCore
	 */
	public function get() {
		return $this->getCore();
	}
	
	/**
	 * Get core information on a map chunk
	 */
	public function getCore()
	{
		return array(
			'192-168'=>array( 'type'=>'grass' )
		);
	}
}
?>