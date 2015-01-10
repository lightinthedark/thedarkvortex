<?php
switch( $_GET['action'] ) {
case( 'setpath'):
	$cmd = json_decode($_GET['command']);
//	echo'<pre>';var_dump($_GET['command']);echo'</pre>';
//	echo'<pre>';var_dump($cmd);echo'</pre>';
	$data = array(
		array( 'id'=>2,
			'unitid'=>$cmd->unit,
			"fT"=>$cmd->fT,
			"fX"=>$cmd->fX,
			"fY"=>$cmd->fY,
			"tT"=>$cmd->fT + 200,
			"tX"=>$cmd->tX,
			"tY"=>$cmd->tY ),
		array( 'id'=>13,
			'unitid'=>$cmd->unit,
			"fT"=>null )
		);
	break;

case( 'getunits' ):
	$data = array(
		array( 'id'=>1,
			'curX'=>100,
			'curY'=>150),
		array( 'id'=>2,
			'curX'=>200,
			'curY'=>250),
		array( 'id'=>3,
			'curX'=>800,
			'curY'=>0)
		);
	break;

case( 'getpaths' ):
	$data = array(
		array( 'id'=>10,
			"unitid"=>1,
			"fT"=>(time()-200),
			"fX"=>100,
			"fY"=>100,
			"tT"=>(time()-50),
			"tX"=>500,
			"tY"=>500 ),
		array( 'id'=>2,
			'unitid'=>1,
			"fT"=>(time()-50),
			"fX"=>500,
			"fY"=>500,
			"tT"=>(time()+50),
			"tX"=>500,
			"tY"=>0 ),
		array( 'id'=>13,
			'unitid'=>1,
			"fT"=>(time()+50),
			"fX"=>500,
			"fY"=>0,
			"tT"=>(time()+200),
			"tX"=>200,
			"tY"=>400 ),

		array( 'id'=>11,
			'unitid'=>2,
			"fT"=>(time()-200),
			"fX"=>500,
			"fY"=>500,
			"tT"=>(time()+100),
			"tX"=>0,
			"tY"=>500 ),
		array( 'id'=>1,
			'unitid'=>2,
			"fT"=>(time()+100),
			"fX"=>0,
			"fY"=>500,
			"tT"=>(time()+200),
			"tX"=>500,
			"tY"=>600 ),
		
		array( 'id'=>40,
			'unitid'=>3,
			"fT"=>(time()-200),
			"fX"=>800,
			"fY"=>0,
			"tT"=>(time()+500),
			"tX"=>0,
			"tY"=>300 )
		);
	break;
}

echo json_encode( $data );
?>