function Renderer(cnvs, objMap, objUnits, objUI) {

	console.log('new Renderer');
	
	//canvas dom elements
	this.canvases = cnvs;

	//canvas contexts
	this.ctxTerrain = this.canvases[0].getContext('2d');
	this.ctxUnits = this.canvases[1].getContext('2d');
	this.ctxUI = this.canvases[2].getContext('2d');
	
	//objects refs
	this.map = objMap;
	this.units = objUnits;
	this.ui = objUI;
	
	//view properties
	this.view = {width: window.innerWidth, height: window.innerHeight};
	this.scale = 1;
	this.smoothScale = 1;
	this.offset = {x: this.view.width / 2 - this.map.width / 2, y: this.view.height / 2 - this.map.height / 2};
	this.smoothOffset = {x: this.view.width / 2 - this.map.width / 2, y: this.view.height / 2 - this.map.height / 2};
	
	//draw first view
	this.resize();

	//render loops
	$(window).resize( this.resize.bind(this) );
};


// R E S I Z E   C A N V A S E S

Renderer.prototype.resize = function() {
//Resize canvas to fit window
	
	//update cached window size
	this.view.width = window.innerWidth;
	this.view.height = window.innerHeight;

	//resize canvas elements
	for (var i = 0; i < this.canvases.length; i++) {
		this.canvases[i].width = this.view.width;
		this.canvases[i].height = this.view.height;
	}

	//redraw view
	this.renderAll();
}


// C L E A R   C A N V A S E S

Renderer.prototype.clearCtx = function(ctx) {
	ctx.clearRect(0, 0, this.view.width, this.view.height);
}


Renderer.prototype.renderAll = function() {
	this.renderTerrain();
	this.renderUnits();
	this.renderUI();
}


// R E N D E R   L A Y E R S

Renderer.prototype.renderTerrain = function() {
	this.clearCtx(this.ctxTerrain);
	this.map.drawGrid(this.ctxTerrain, this.smoothScale, this.smoothOffset, this.view);
}


Renderer.prototype.renderUnits = function() {	
	//clear units layer
	this.clearCtx(this.ctxUnits);

	//Render units
	var u;
	var units = this.units.objUnits;
	var moving = false;
	var redraw = false;

	for (var u in units) {
		if(units.hasOwnProperty(u)) {
			moving = units[u].update();
			units[u].render(this.ctxUnits, this.smoothOffset, this.smoothScale);
			if (moving)
				redraw = true;
		}
    }

    /*
    Recursive call (below) resulted in stuttering when units moving whilst user zooming, 
    presumably because both actions call this method. Second condition of if statement 
    (also below) gets around this by ensuring zoom scale is unchanging. There may be a
    more elegant solution!
    */

    if(redraw && this.scale == this.smoothScale)
    	window.requestAnimationFrame(this.renderUnits.bind(this));
}


Renderer.prototype.renderUI = function() {	
	//Render UI
	this.clearCtx(this.ctxUI);
	this.ui.drawScale(this.ctxUI, this.scale, this.view);
}


// M O V E   V I E W   P A R A M S

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

Renderer.prototype.zoom = function(mag, focus, start, timestamp) {

	if (!timestamp) {
		this.scale = this.scale * mag;
		this.offset.x += this.view.width / 2 - focus.x;
		this.offset.y += this.view.height / 2 - focus.y;
		window.requestAnimationFrame(this.zoom.bind(this, mag, focus, start));
	} else {
		if (this.scale != this.smoothScale) {
			var progress = timestamp - start;
			var interpAmount = 1 / progress;
			this.smoothScale = Lint(this.smoothScale, this.scale, interpAmount, 0.001);
			this.smoothOffset.x = Lint(this.smoothOffset.x, this.offset.x, interpAmount, 0.001);
			this.smoothOffset.y = Lint(this.smoothOffset.y, this.offset.y, interpAmount, 0.001);
			this.renderAll();
			window.requestAnimationFrame(this.zoom.bind(this, mag, focus, timestamp));
		} else {
			// complete pan movement when scale has reached target 
			this.smoothOffset.x = this.offset.x;
			this.smoothOffset.y = this.offset.y;
			return;
		}
	}
}