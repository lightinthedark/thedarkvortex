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
	this.smoothScale = 1;
	this.offset = {x: window.innerWidth / 2 - this.map.width / 2, y: window.innerHeight / 2 - this.map.height / 2};
	this.smoothOffset = {x: window.innerWidth / 2 - this.map.width / 2, y: window.innerHeight / 2 - this.map.height / 2};
	
	//draw first view
	this.resize();

	//render loops
	$(window).resize( function(){ this.resize(); this.renderAll() }.bind(this) );
};


Renderer.prototype.renderTerrain = function() {
	this.map.drawGrid(this.ctxTerrain, this.smoothScale, this.smoothOffset);
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
	window.requestAnimationFrame(this.renderAll.bind(this));
}

Renderer.prototype.pan = function( moveX, moveY) {

	//click handler calls this method whilst clicked
	this.offset.x = moveX;
	this.offset.y = moveY;

	// updates both offset and smoothOffset variables to avoid lag
	this.smoothOffset.x = moveX;
	this.smoothOffset.y = moveY;
	
	//render view
	this.renderAll();
}

Renderer.prototype.zoom = function(mag, focus) {

	if (mag != 1) {
		this.scale = this.scale * mag;
		this.offset.x += window.innerWidth / 2 - focus.x;
		this.offset.y += window.innerHeight / 2 - focus.y;
	}

	//console.log(this.smoothScale);
	if (this.scale != this.smoothScale) {
		this.smoothScale = Lint(this.smoothScale, this.scale, 0.1);
		this.smoothOffset.x = Lint(this.smoothOffset.x, this.offset.x, 0.1);
		this.smoothOffset.y = Lint(this.smoothOffset.y, this.offset.y, 0.1);
		this.renderAll();
		window.requestAnimationFrame(this.zoom.bind(this, 1, focus));
	} else {
		// finalised move by setting offset to target
		this.smoothOffset.x = this.offset.x;
		this.smoothOffset.y = this.offset.y;
	}
}

Renderer.prototype.resize = function() {
//Resize canvas to fit window
	
	for (var i = 0; i < this.canvases.length; i++) {
		this.canvases[i].width = window.innerWidth;
		this.canvases[i].height = window.innerHeight;
	}
	this.renderAll();
}