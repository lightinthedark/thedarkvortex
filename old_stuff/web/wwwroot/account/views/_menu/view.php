<?php
class ViewAccount_Menu extends View
{
	function display()
	{
		$items = array(
			  'profile'  =>array('view'=>'profile',   'text'=>'Profile')
			, 'account'  =>array('view'=>'account',   'text'=>'Account')
			, 'empire'   =>array('view'=>'empire',    'text'=>'Empire')
			, 'alliances'=>array('view'=>'alliances', 'text'=>'Alliances') );
		echo $this->makeMenu( $items );
	}
}
?>