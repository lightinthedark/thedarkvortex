<?php
class OutputJson extends Output
{
	/**
	 * indicate the content is JSON encoded
	 */
	protected function _renderHeaders()
	{
		parent::_renderHeaders();
		header('Content-Type: application/json');
	}
	
	/**
	 * Render a chunk of data
	 */
	public function renderData( $data )
	{
		$this->_renderHeaders();
		echo json_encode( $data );
	}
	
}
?>