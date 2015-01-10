<h1>Populating test tables</h1>
<?php
$conn = mysql_connect( 'localhost', 'thedarkvortex', 'byvectron' );
if( !$conn ) {
	die( 'could not connect' );
}
mysql_select_db( 'thedarkvortex' );

/*
// Re-use data
CREATE TEMPORARY TABLE holding AS
SELECT `stack_id` , `x1` , `y1` , `x2` , `y2` , `speed`, `time_start`
FROM paths;

TRUNCATE `events`;
TRUNCATE `paths`;

INSERT INTO paths
( `stack_id` , `x1` , `y1` , `x2` , `y2` , `speed`, `time_start` )
SELECT *
FROM holding;

DROP TABLE holding;


// Controlled test data
TRUNCATE `paths`;
TRUNCATE `events`;
INSERT INTO `paths`
( `stack_id` , `x1` , `y1` , `x2` , `y2` , `speed`, `time_start` )
VALUES
( '1',   '0',   '0',   '0', '500', '1', NULL ) ,
( '2', '100', '230', '100', '230', '0', NULL ) ,
( '3', '200',   '0', '200', '500', '1', 0 ) ,
( '4', '300',   '0', '300', '500', '1', 0 ) ,
( '5', '400',   '0', '400', '500', '1', 80 ) ,
( '6', '500',   '0', '500', '500', '1', 0 ) ,
( '7', '500',   '0', '500', '500', '1', 0 ) ,
( '8', '500',   '0', '500', '500', '1', 0 ) ,
( '10',  '0', '250', '500', '250', '1', 10 ) ;
*/

// Random test data
$stacks = 100;
$paths = 100;
$mapsizeX = 1000;
$mapsizeY = 700;
$maxChange = 200;

for( $s = 1; $s <= $stacks; $s++ ) {
	// Set up stack info
	$stackList[] = '("stack_'.$s.'"'
		.', '.rand(10, 50)
		.', "neutral"'
		.')';
	
	// starting position for this stack
	$x2 = rand(0, $mapsizeX);
	$y2 = rand(0, $mapsizeY);
	$end = 1;
//	$end = time();
	
	// each stack gets $paths number of paths laid one after the other
	for( $p = 0; $p < $paths; $p++ ) {
		$x1 = $x2;
		$y1 = $y2;
		
		$xMin = max( ($x1 - $maxChange), 0 );
		$xMax = min( ($x1 + $maxChange), $mapsizeX );
		$yMin = max( ($y1 - $maxChange), 0 );
		$yMax = min( ($y1 + $maxChange), $mapsizeY );
		
		$x2 = rand($xMin, $xMax);
		$y2 = rand($yMin, $yMax);
		$speed = (rand(1, 5));
		
		$dx = $x2 - $x1;
		$dy = $y2 - $y1;
		$dist = sqrt( (($dx * $dx) + ($dy * $dy)) );
		$t = ceil( ($dist / $speed) );
		
		$start = $end;
		$end = $start + $t;
		
		$valList[] = '('.$s
			.', '.$x1
			.', '.$y1
			.', '.$x2
			.', '.$y2
			.', '.$speed
			.', '.$start
			.')';
	}
}


$query = 'TRUNCATE events';
mysql_query($query);
$query = 'TRUNCATE paths';
mysql_query($query);
$query = 'TRUNCATE stacks';
mysql_query($query);

echo microtime().' - before stacks: <br />';

$query = 'INSERT INTO stacks (`name`, `radius`, `stance` )'
	."\n".' VALUES '.implode( ", \n", $stackList );;
mysql_query( $query );

echo microtime().' - after stacks: <br />';

//echo 'query: <pre>';var_dump($query);echo'</pre>';
echo mysql_info().'<br />';
echo mysql_affected_rows().'<br />';
echo mysql_error().'<br />';

echo microtime().' - before paths: <br />';

$query = 'INSERT INTO paths (`stack_id`, `x1`, `y1`, `x2`, `y2`, `speed`, `time_start` )'
	."\n".' VALUES '.implode( ", \n", $valList );;
mysql_query( $query );

echo microtime().' - after paths: <br />';

//echo 'query: <pre>';var_dump($query);echo'</pre>';
echo mysql_info().'<br />';
echo mysql_affected_rows().'<br />';
echo mysql_error().'<br />';


$rs = mysql_query( 'SHOW WARNINGS' );
while ($row = mysql_fetch_row( $rs ) ) {
	var_dump($row);echo'<br />';
}

?>
done.
