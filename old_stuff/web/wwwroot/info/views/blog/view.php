<?php

class ViewInfoBlog extends View
{
	function display()
	{
		$this->posts = $this->get( 'posts' );
		echo $this->loadTemplate();
	}
}
?>