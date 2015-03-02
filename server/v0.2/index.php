<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<h1>The Dark Vortex - server 0 - v0.2</h1>

<p>You've reached version 0.2 of the API.</p>
   
<ul>
	<li>Units:
		<ul>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/units/core.json">units</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/units/core.json?unit_id=3">units filtered for id 3</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/units/core.json?unit_id=[3,4]">units filtered for id 3 or 4</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/units/core.json?wpnt_range=<?php
				echo htmlspecialchars( json_encode(
					array(
						'from'=>array( 'x'=>0, 'y'=>0, 't'=>'*' ),
						'to'  =>array( 'x'=>10, 'y'=>10, 't'=>'*' )
					)
				) );
				?>">units with presence between (0,0) and (10,10) at any time</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/units/core.json?unit_id=[3,4]&wpnt_range=<?php
				echo htmlspecialchars( json_encode(
					array(
						'from'=>array( 'x'=>0, 'y'=>0, 't'=>'*' ),
						'to'  =>array( 'x'=>10, 'y'=>10, 't'=>'*' )
					)
				) );
				?>">units with id 3 or 4 which have a presence between (0,0) and (10,10) at any time</a></li>
			
		</ul>
	</li>
	<li>Waypoints:
		<ul>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/waypoints/list.json">all waypoints</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/waypoints/list.json?wpnt_id=[5,6]">waypoints 5 and 6</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/waypoints/list.json?wpnt_range=<?php
				echo htmlspecialchars( json_encode(
					array(
						'from'=>array( 'x'=>'*', 'y'=>'*', 't'=>time()-20 ),
						'to'  =>array( 'x'=>'*', 'y'=>'*', 't'=>time()+20 )
					)
				) );
			?>">all waypoints anywhere from 20s ago to 20s in the future</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/waypoints/list.json?wpnt_range=<?php
				echo htmlspecialchars( json_encode(
					array(
						'from'=>array( 'x'=>'20', 'y'=>'30', 't'=>'*' ),
						'to'  =>array( 'x'=>'60', 'y'=>'70', 't'=>'*' )
					)
				) );
			?>">all waypoints anywhere between 20,20 and 60,70 at any time</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/waypoints/forunits.json">waypoints for all units</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/waypoints/forunits.json?unit_id=4">waypoints for unit 4</a></li>
		</ul>
	</li>
	<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/test">test up-ness</a></li>
	<li><a href="http://tdv-server0.lightinthedark.org.uk/dev/0.2/foo?bar=baz">dev page</a></li>
</ul>
</body>
</html>