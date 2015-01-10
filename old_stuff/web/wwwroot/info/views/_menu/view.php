<?php
class ViewInfo_Menu extends View
{
	function display()
	{
		$items = array(
			  'home'   =>array('view'=>'home',    'text'=>'Home')
			, 'blog'   =>array('view'=>'blog',    'text'=>'Development Blog')
			, 'story'  =>array('view'=>'story',   'text'=>'Story')
			, 'servers'=>array('view'=>'servers', 'text'=>'Servers and worlds')
			, 'guide'  =>array('view'=>'guide',   'text'=>'Getting started') );
		echo $this->makeMenu( $items );
	}
}
?>