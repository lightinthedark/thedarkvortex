<?php $this->addStyle( 'style.css' );  ?>
<?php $this->addStyle( 'ie_only.css', 'IE' ); ?>
<?php $this->addScript( 'map_path.js' );  ?>
<?php $this->addScript( 'map_unit.js' );  ?>
<?php $this->addScript( 'map_world.js' );  ?>
<?php $this->addScript( 'mapscript.js' );  ?>
<?php $this->addScript( 'excanvas.js', 'IE', true );  ?>

<div id="panels">
<div id="panel_1">
Units
</div>
<div id="panel_2">
Details
</div>
</div>

<div id="map">
<div id="map_inner">

<canvas class="main_canv"></canvas>

<div id="info_nav">
<ul>
<li>Units</li>
<li>Towns</li>
<li>Tech</li>
<li>Politics</li>
</ul>
</div>

<div id="map_nav">
</div>

</div>
</div>