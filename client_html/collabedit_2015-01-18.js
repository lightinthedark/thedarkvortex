// ==== unit.js
function Unit
{

}

Unit.prototype.render = function( canvas, mapOffset /* x, y, t */ )
{
    // calculate precise position at microtime (interpolate between waypoints)
    // render a circle to the canvas at that position
]

// ==== map.js
function Map( unitLoader )
{
    this.canvas = $( 'canv_element' );
    this.loader = unitLoader
    // ????
}

Map.prototype.zoomIn()  = function() { this.scale = this.scale * 2; }
Map.prototype.zoomOut() = function() { this.scale = this.scale / 2; }

Map.prototype.renderLoop( time )
{
    var pointBL = new Point( this.offsetX, this.offsetY, time );
    this.renderBackground( pointBL );
    this.renderGrid( point );
    this.renderUnits( point );
    this.renderOverlay( point );
    
    window.requestAnimationFrame( this.renderLoop );
}

Map.prototype.renderUnits( offset )
{
    var units = this.loader();
    units.each( function( u ) { u.render( this.canvas ) )
}


// ==== interface.js
function Interface( map )
{
}


// ==== game.js
function Game
{
    this.loader = new Loader();
    this.units = {};
    this.map = new Map( this.getUnits );
    this.interface = new Interface( this.map );
    this.buildUnits();
}

Game.prototype.buildUnits()
{
    var rawData = this.loader.getUnits( pointBL, pointTR );

    for( u in rawData ) {
        unit = rawData[ u ];
        this.units[ unit.id ] = unit;
    }
/*
    // this is not needed as the loop above will create them all ... in theory
    new Unit( 10, 'player1', 'archer' );
    new Unit( 10, 'player1', 'spearman' );
    new Unit( 10, 'player2-enemy', 'wizard' );
    
    new Archer( 10, 'player1' );
    new Spearman( 10, 'player1' );
    new Wizard( 10, 'player2-enemy' );
*/
}

Game.prototype.getUnits( /* friendly */ )
{
//    if( friendly === undefined || typeof friendly !== boolean ) { friendly = true; }
    return this.units;
}

Game.prototype.run = function() {
    this.map.renderLoop();
}


// === main.js
$( document ).onReady( function() {
    var game1 = new Game();
    game1.run();
} );