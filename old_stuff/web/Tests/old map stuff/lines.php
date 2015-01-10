<?php
header("Content-type: image/png");
ob_start();
echo '0: '.microtime()."\n";
include( '../../../../libraries/request.php' );

$conn = mysql_connect( 'localhost', 'thedarkvortex', 'byvectron' );
if( !$conn ) {
	die( 'could not connect' );
}
mysql_select_db( 'thedarkvortex', $conn );

$im = @imagecreatetruecolor(1000, 700)
    or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 255, 255, 255);
imagefill( $im, 0, 0, $background_color );

$t = Request::get( 't', 0 );
$lines = Request::get( 'lines', '' );
$lines = explode( ',', $lines );
$points = Request::get( 'points', '' );
$points = explode( ',', $points );
$black = imagecolorallocate($im, 0, 0, 0);
$colors = array(
	imagecolorallocate($im, 255, 0,   0  ),
	imagecolorallocate($im, 0,   255, 0  ),
	imagecolorallocate($im, 0,   0,   255),
	imagecolorallocate($im, 0,   0,   128),
	imagecolorallocate($im, 0,   200, 0  ),
	imagecolorallocate($im, 0,   200, 200),
	imagecolorallocate($im, 200, 0,   0  ),
	imagecolorallocate($im, 200, 0,   200),
	imagecolorallocate($im, 200, 200, 0  ),
	imagecolorallocate($im, 200, 200, 200) );
$numColors = count($colors);

echo '1: '.microtime()."\n";
$query = 'SELECT *'
	."\n".' FROM `paths`'
	."\n".' WHERE `stack_id` = %1$s'
	."\n".'   AND `time_start` <= %2$s'
	."\n".'   AND `time_end` > %2$s'
	."\n".' ORDER BY `time_start`';
foreach( $lines as $k=>$line ) {
	$q = sprintf( $query, $line, $t );
	$rs = mysql_query( $q, $conn );
	
	while( $line = mysql_fetch_assoc($rs) ) {
		$k = $k % $numColors;
		imageline( $im, $line['x1'], $line['y1'], $line['x2'], $line['y2'], $colors[$k] );
		imagefilledellipse( $im, $line['x1'], $line['y1'], (2*$line['speed']), (2*$line['speed']), $black );
	}
}
echo '2: '.microtime()."\n";

$query1 = 'CALL unitTimePosition( %1$s, %2$s, @x, @y, @r )';
$query2 = 'SELECT @x AS x, @y AS y, @r AS r; ';
foreach( $points as $k=>$point ) {
	$q = sprintf($query1, $point, $t);
	mysql_query( $q, $conn );
	$rs = mysql_query( $query2, $conn );
	
	if( $points = mysql_fetch_assoc($rs) ) {
		$k = $k % $numColors;
		imageellipse( $im, $points['x'], $points['y'], (2*$points['r']), (2*$points['r']), $colors[$k] );
		imageellipse( $im, $points['x'], $points['y'], 4, 4, $colors[$k] );
		imagestring( $im, 1, ($points['x'] + 5), ($points['y'] - 3), $point, $black );
	}
}
echo '3: '.microtime()."\n";

//imageantialias( $im, true ); // Maybe this will work on a different server?

$line = 0;
imagestring( $im, 2, 10, (15 * $line), 'Time: '.$t, $black );
$line++;

$query = 'SELECT stack_id_1, stack_id_2 FROM `events`'
	."\n".' WHERE time_start <= '.$t
	."\n".'   AND time_end > '.$t;
$rs = mysql_query( $query, $conn );

while( $row = mysql_fetch_assoc($rs) ) {
	$s1 = $row['stack_id_1'];
	$s2 = $row['stack_id_2'];
	imagestring( $im, 2, 10, (15 * $line), $s1, $colors[$s1-1] );
	imagestring( $im, 2, 25, (15 * $line), $s2, $colors[$s2-1] );
	$line++;
}


echo '4: '.microtime()."\n";

$txt = ob_get_clean();
$txt = explode( "\n", $txt );
foreach( $txt as $t ) {
	imagestring( $im, 1, 10, (15 * $line++), $t, $black );
}

imagepng($im);
imagedestroy($im);

?>