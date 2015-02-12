function ClickHandler(clickTarget, objMap, objUnits, objRenderer) {
//Responsible for click events

	console.log('new EventHandler');

	this.clickLayer = clickTarget;
	this.map = objMap;
	this.units = objUnits;
	this.renderer = objRenderer;
	this.selected; //stores reference to selected unit
	
	//cancel right click
	$(document).on('contextmenu', function() {
    return false;
	});
	
	this.moveOffset(this.map, this.renderer);
};

ClickHandler.prototype.moveOffset = function(map, renderer) {
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
				x: event.pageX - map.offset.x,
				y: event.pageY - map.offset.y
			};
		}
		if(event.which == 3) {
			//FOR PROTOTYPE PURPOSES ONLY
			//right click to add waypoint
			var now = new Date().getTime();
			game.units.arrUnits[0].addWaypoint(event.pageX - map.offset.x, event.pageY - map.offset.y, now + 5000);			
			//console.log(game.units.arrUnits[0]);
		}
	});
	
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
	map.moveOffset(offset.x, offset.y);
	renderer.renderAll();
	});
}

ClickHandler.prototype.zoomIn = function(map) {
	if (map.scale < 8) {
		map.scale = map.scale * 2;
	}
}

ClickHandler.prototype.zoomOut = function(map) {
	if (map.scale > 0.25) {
		map.scale = map.scale / 2;
	}
}

ClickHandler.prototype.moveUnit = function() {
	console.log('moveunit');
}