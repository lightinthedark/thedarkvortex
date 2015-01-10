<html>
<head>
<title>The Dark Vortex testing page</title>
</head>

<body>
<h1>Test output</h1>

<h2>System call</h2>

<?php
$tdv = '/home/davidswain/c/TheDarkVortex/Default/thedarkvortex';
$vars =  $_GET['v'];
//$vars = 'foo bar baz';
$output = array();
$ret = null;

exec($tdv.' '.$vars, $output, $ret);

var_dump_pre( $vars, 'vars' );
var_dump_pre( $output, 'out' );
var_dump_pre( $ret, 'ret:' );
?>

</body>

</html>

<?php
function var_dump_pre( $v, $t = null )
{
	echo $t;
	echo '<pre>';
	var_dump($v);
	echo '</pre>';
	
}

?>