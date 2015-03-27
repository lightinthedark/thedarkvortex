function RenderManager(contexts, objMap, objUnits /*, objUI */) {

	console.log('new RenderManager');
	
	this.ctxMap = context.Map;
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