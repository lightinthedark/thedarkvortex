<?php

class ViewGameTest extends View
{
	function display()
	{
		echo $this->loadTemplate();
	}
}
?>