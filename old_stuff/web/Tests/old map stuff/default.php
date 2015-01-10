<style>
.map_div {
	width: 1000px;
	height: 700px;
	border: solid 1px goldenrod;
}

.map_cache {
	display: none;
}
.map {
}

</style>
<h1>Map at zoom 1</h1>
<!--
<p>This is the world map (closest zoom)</p>
<p><a href="index.php?section=map&view=world2">Zoom out</a></p>
-->
<?php
$t = Request::get('t', 0);

$src = 'map/views/world1/tmpl/lines.php'
	.'?lines=1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20'
	.'&points=1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20';
?>

   <a href="" onclick="prevMap(); return false">&lt; prev</a>
 | <a href="" id="curLink">current</a>
 | <a href="" onclick="nextMap(); return false">next &gt;</a>
 | <a href="" onclick="stopMap(); return false">stop [&#8226;]</a>
 | <a href="" onclick="playMap(); return false">play -&gt;</a><br />

   <a href="index.php?t=<?php echo $t - 100; ?>">&lt;&lt;&lt;</a>
 | <a href="index.php?t=<?php echo $t - 10; ?>">&lt;&lt;</a>
 | <a href="index.php?t=<?php echo $t - 1; ?>">&lt;</a>
 | <a href="index.php?t=0">0</a>
 | <a href="index.php?t=<?php echo $t + 1; ?>">&gt;</a>
 | <a href="index.php?t=<?php echo $t + 10; ?>">&gt;&gt;</a>
 | <a href="index.php?t=<?php echo $t + 100; ?>">&gt;&gt;&gt;</a>
<br /><br />

<div class="map_div">
	<img id="map_0" class="map_cache" src="" alt="World Map"/>
	<img id="map_1" class="map_cache" src="" alt="World Map"/>
	<img id="map_2" class="map" src="<?php echo $src.'&t='.$t; ?>" alt="World Map"/>
	<img id="map_3" class="map_cache" src="" alt="World Map"/>
	<img id="map_4" class="map_cache" src="" alt="World Map"/>
</div>

<script>
var _curId = 32;
var _t = <?php echo $t; ?>;
var _src = '<?php echo $src; ?>';
var _timer;

// I know using window.onload is a Bad Thing for responsiveness etc, but this is only on dev system
// **** should not be used on anything like a live site
window.onload = function() {
	var _pp = document.getElementById( 'map_'+((_curId-2) % 5) );
	var _p  = document.getElementById( 'map_'+((_curId-1) % 5) );
	var _n  = document.getElementById( 'map_'+((_curId+1) % 5) );
	var _nn = document.getElementById( 'map_'+((_curId+2) % 5) );
	_pp.src =_src+'&t='+(_t-2);
	_p.src  =_src+'&t='+(_t-1);
	_n.src  =_src+'&t='+(_t+1);
	_nn.src =_src+'&t='+(_t+2);
};

function nextMap()
{
	var _pp = document.getElementById( 'map_'+((_curId-2) % 5) );
	var _p  = document.getElementById( 'map_'+((_curId-1) % 5) );
	var _c  = document.getElementById( 'map_'+((_curId)   % 5) );
	var _n  = document.getElementById( 'map_'+((_curId+1) % 5) );
	var _nn = document.getElementById( 'map_'+((_curId+2) % 5) );
	_n.className  = 'map';
	_c.className  = 'map_cache';

	_t++;
	_curId++;
	
	_pp.src=_src+'&t='+(_t+2);
	document.getElementById( 'curLink' ).href = 'index.php?t='+_t;
	return false;
}

function prevMap()
{
	var _pp = document.getElementById( 'map_'+((_curId-2) % 5) );
	var _p  = document.getElementById( 'map_'+((_curId-1) % 5) );
	var _c  = document.getElementById( 'map_'+((_curId)   % 5) );
	var _n  = document.getElementById( 'map_'+((_curId+1) % 5) );
	var _nn = document.getElementById( 'map_'+((_curId+2) % 5) );
	_p.className  = 'map';
	_c.className  = 'map_cache';

	_t--;
	if( _curId <= 30 ) {
		_curId += 29;
	}
	else {
		_curId--;
	}
	
	_nn.src=_src+'&t='+(_t-2);
	document.getElementById( 'curLink' ).href = 'index.php?t='+_t;
	return false;
}

function playMap()
{
	nextMap();
	_timer = setTimeout(playMap,2000);
}

function stopMap()
{
	clearTimeout(_timer);
}

</script>
