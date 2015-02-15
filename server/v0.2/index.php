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
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/units/core.json?id=3">units filtered for id 3</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/units/core.json?id=[3,4]">units filtered for id 3 or 4</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/units/core.json?range=<?php
				echo htmlspecialchars( json_encode(
					array(
						'from'=>array( 'x'=>0, 'y'=>0, 't'=>'*' ),
						'to'=>array( 'x'=>10, 'y'=>10, 't'=>'*' )
					)
				) );
				?>">units with presence between (0,0) and (10,10) at any time</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/units/core.json?id=[3,4]&range=<?php
				echo htmlspecialchars( json_encode(
					array(
						'from'=>array( 'x'=>0, 'y'=>0, 't'=>'*' ),
						'to'=>array( 'x'=>10, 'y'=>10, 't'=>'*' )
					)
				) );
				?>">units with id 3 or 4 which have a presence between (0,0) and (10,10) at any time</a></li>
			
		</ul>
	</li>
	<li>Waypoints:
		<ul>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/waypoints/list.json?id=moo">all waypoints</a></li>
			<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/waypoints/forunits.json?id=moo">waypoints for all units</a></li>
		</ul>
	</li>
	<li><a href="http://tdv-server0.lightinthedark.org.uk/api/0.2/test">test up-ness</a></li>
	<li><a href="http://tdv-server0.lightinthedark.org.uk/dev/0.2/foo?bar=baz">dev page</a></li>
</ul>
</body>
</html>