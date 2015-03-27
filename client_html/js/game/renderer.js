function Renderer(cnvs, objMap, objUnits /*, objUI */) {

	console.log('new Renderer');
	
	//canvas dom elements
	this.canvases = cnvs;

	//canvas contexts
	this.ctxTerrain = this.canvases[0].getContext('2d');
	this.ctxUnits = this.canvases[1].getContext('2d');
	this.ctxUI = this.canvases[1].getContext('2d');
	
	//objects refs
	this.map = objMap;
	this.units = objUnits;
	
	//view properties
	this.scale = 1;
	this.offset = {x: 0, y: 0};
	
	//draw first view
	this.resize();

	//render loops
	$(window).resize( function(){ this.resize(); this.renderAll() }.bind(this) );
};


Renderer.prototype.renderTerrain = function() {
	this.map.drawGrid(this.ctxTerrain, this.scale, this.offset);
}


Renderer.prototype.renderUnits = function() {	
	//Render units
}


Renderer.prototype.renderUI = function() {	
	//Render UI
}


Renderer.prototype.renderAll = function() {
	this.renderTerrain();
	this.renderUnits();
	this.renderUI();
}

Renderer.prototype.pan = function( moveX, moveY) {

	//click handler calls this method whilst clicked
	this.offset.x = moveX;
	this.offset.y = moveY;
	
	//render view
	this.renderAll();
}

Renderer.prototype.zoom = function() {

	//click handler calls this method
	//click handler stop listening to player input whilst zooming?
	
	//targetScale = new scale
	//targetOffset = new offset based on click
	//zoomSpeed = 0.1;
	
	//smoothScale = Lint (scale, targetScale, zoomSpeed);
	//smoothOffset.x = Lint (offset.x, targetOffset.x, zoomSpeed);
	//smoothOffset.y = Lint (offset.y, targetOffset.y, zoomSpeed);

	//render view
	this.renderAll();
}

Renderer.prototype.resize = function() {
//Resize canvas to fit window
	
	for (var i = 0; i < this.canvases.length; i++) {
		this.canvases[i].width = window.innerWidth;
		this.canvases[i].height = window.innerHeight;
	}
	this.renderAll();
}