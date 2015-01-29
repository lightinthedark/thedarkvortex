function RenderManager(context, objMap, objUnits) {

	console.log('new RenderManager');
	
	this.ctx = context;
	this.map = objMap;
	this.units = objUnits;
	this.start = null;
	
	this.renderAll();
	$(window).resize( function(){ this.renderAll() }.bind(this) ); //this should call request animation frame, but not sure how to set the scope (using bind(this) already)
	this.renderUnits();
};

RenderManager.prototype.renderAll = function() {
//clears and redraws entire canvas for movement of view
	this.map.resize();
	this.map.drawGrid();
	if(this.units.arrUnits.length > 0)
		this.units.arrUnits[0].render(this.ctx, this.map.offset);
}

RenderManager.prototype.renderUnits = function(now) {
//updates units only, could be extended to include other non-static elements
/* currently doesn't function as intended. It may be performance enhancing 
to clear and redraw only where units have moved. This could work as follows:
1. clear previous location of unit
2. redraw background in previous location
3. draw new location
*/
	window.requestAnimationFrame(this.renderUnits.bind(this));

	this.map.resize();
	this.map.drawGrid();
	if(this.units.arrUnits.length > 0) {
		this.units.arrUnits[0].update(now);
		this.units.arrUnits[0].render(this.ctx, this.map.offset);
	}
}