function ClickHandler(clickTarget, objUnits, objRenderer) {
//Responsible for click events

	console.log('new ClickHandler');

	this.clickLayer = clickTarget;
	this.units = objUnits;
	this.renderer = objRenderer;
	this.objSelected; //stores reference to selected unit
	
	//cancel right click
	$(document).on('contextmenu', function() {
    return false;
	});
	
	this.mouseClick();
	this.zoomListener();
	
};

ClickHandler.prototype.mouseClick = function() {
// Transform offset on mouse click and move

	var clicking = false;
	var clickDown;
	var clickUp;
	var move;
	var offset;

	$(this.clickLayer).mousedown(function( event ){
		if (event.which == 1) {
			clicking = true;
			this.clickDown = {
				x: event.pageX - this.renderer.offset.x,
				y: event.pageY - this.renderer.offset.y
			};
		}
		if(event.which == 3) {
			//FOR PROTOTYPE PURPOSES ONLY
			//right click to add waypoint
			var now = new Date().getTime();
			var offset = this.renderer.offset;
			var scale = this.renderer.scale;
			this.units.objUnits.firstUnit.addWaypoint((event.pageX / scale) - (offset.x / scale), (event.pageY / scale) - (offset.y / scale), now + 5000);
			this.renderer.renderUnits();
		}
	}.bind(this));
	
	$(this.clickLayer).mouseup(function() {
		move = {
			x:0,
			y:0
		};
	});
	
	$(document).mouseup(function(){
		clicking = false;
	});
	
	var move = {x:0, y:0};
	$(this.clickLayer).mousemove(function( event ){
		if(clicking === false) return;
		this.clickUp = {x: event.pageX, y: event.pageY};
		offset = {
			x: this.clickUp.x - this.clickDown.x,
			y: this.clickUp.y - this.clickDown.y
		};
		this.renderer.pan(offset.x, offset.y);
	}.bind(this));
}

ClickHandler.prototype.zoomListener = function() {
	$(this.clickLayer).on("dblclick", function(event) {
		zoomFocus = { x: event.pageX, y: event.pageY };
		if(event.altKey) {
			window.requestAnimationFrame(this.renderer.zoom.bind(this.renderer, 0.8, zoomFocus));
		}
		else {
			window.requestAnimationFrame(this.renderer.zoom.bind(this.renderer, 1.25, zoomFocus));
		}
	}.bind(this));
}