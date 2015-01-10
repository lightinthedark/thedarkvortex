<?php

function var_dump_pre( $var, $text = '' )
{
	echo '<b>'.$text.'</b><pre>';var_dump($var);echo'</pre>';
}

?>