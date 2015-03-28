function Unit(rgb, size, startPos) {
	this.color = rgb;
	this.radius = size;
	this.position = {x: startPos.x, y: startPos.y};
	this.waypoints = [startPos];
	this.stationary = true;
}

Unit.prototype.addWaypoint = function(xPos, yPos, time) {
	/*if (!time) time = new Date().getTime();
	var wpNode = new Point(xPos, yPos, time);
	console.log('waypoint added: ' + xPos + ', ' + yPos + ', ' + time);
	this.waypoints.push(wpNode);*/
};

Unit.prototype.update = function(timestamp) {
	/*
	// provide current time when undefined
	if (!timestamp) timestamp = new Date().getTime();
	var lastWP = new Point (0, 0, 0);
	var nextWP = new Point (0, 0, Number.POSITIVE_INFINITY);
	var travel;
	
	// duplicate last waypoint if stationary
	if (stationary) {
		var lastMove = this.waypoints[this.waypoints.length];
		this.addWaypoint (lastMove.x, lastMove.y, new Date().getTime());
	}
	
	// determine last and next waypoints for interpolation
	if (this.waypoints.length > 1) {

		for (var i = 0; i < this.waypoints.length; i++) {
		
			//get previous waypoint
			if (this.waypoints[i].t < timestamp && this.waypoints[i].t > lastWP.t) {
				lastWP = this.waypoints[i];
			}
			
			//get next waypoint
			if (this.waypoints[i].t > timestamp && this.waypoints[i].t < nextWP.t) {
				nextWP = this.waypoints[i];
			}
		}
		
		//interpolate waypoint
		if (nextWP.t != Number.POSITIVE_INFINITY) {
			
			travel = (timestamp - lastWP.t) / (nextWP.t - lastWP.t);
			this.position.x = Math.floor ( lastWP.x + travel * ( nextWP.x - lastWP.x ) );
			this.position.y = Math.floor ( lastWP.y + travel * ( nextWP.y - lastWP.y ) );
			this.stationery = false;
		}
		else {
			//console.log('last Waypoint reached');
			this.position.x = lastWP.x;
			this.position.y = lastWP.y;
			this.stationery = true;
			return;
		}
	}*/
};

Unit.prototype.render = function(ctx, offset, scale) {
	//console.log('render');
	
	this.update();
	
	ctx.beginPath();
	ctx.arc (this.position.x + offset.x, this.position.y + offset.y, this.radius, 0, 2*Math.PI, false);
	ctx.fillStyle = this.color;
	ctx.fill();
}