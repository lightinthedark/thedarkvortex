function Unit(rgb, size, startPos) {
	this.color = rgb;
	this.radius = size;
	this.waypoints = [startPos];
	this.position = {intX: startPos.intX, intY: startPos.intY};
	this.stationary = true;
}

Unit.prototype.addWaypoint = function(xPos, yPos, time) {
	if (!time) time = new Date().getTime();
	var wpNode = new Point(xPos, yPos, time);
	this.waypoints.push(wpNode);
};

Unit.prototype.update = function(timestamp) {
	
	// provide current time when undefined
	var lastWP = new Point (0, 0, 0);
	var nextWP = new Point (0, 0, Number.POSITIVE_INFINITY);
	var travel;
	var time = new Date().getTime();
	
	// determine last and next waypoints for interpolation
	if (this.waypoints.length > 1) {

		var i;

		for (i = 0; i < this.waypoints.length; i++) {

			//get previous waypoint
			if (this.waypoints[i].intT < time && this.waypoints[i].intT > lastWP.intT) {
				lastWP = this.waypoints[i];
			}
			
			//get next waypoint
			if (this.waypoints[i].intT > time && this.waypoints[i].intT < nextWP.intT) {
				nextWP = this.waypoints[i];
			}

		}

		//interpolate waypoint
		if (nextWP.intT != Number.POSITIVE_INFINITY) {

			// duplicate last waypoint if stationary
			if (this.stationary) {
				this.addWaypoint(lastWP.intX, lastWP.intY, new Date().getTime());
				this.stationary = false;
			}
			travel = (time - lastWP.intT) / (nextWP.intT - lastWP.intT);
			this.position.intX = Lint (lastWP.intX, nextWP.intX, travel);
			this.position.intY = Lint (lastWP.intY, nextWP.intY, travel);
			return true;
		}
		else {
			//console.log('last Waypoint reached');
			this.position.intX = lastWP.intX;
			this.position.intY = lastWP.intY;
			this.stationary = true;
			return false;
		}
	}
}

Unit.prototype.render = function(ctx, offset, scale) {
//render unit

	//console.log("render unit: " + this.position.intX + ", " + this.position.intY);
	ctx.beginPath();
	ctx.arc ((this.position.intX * scale) + offset.x, (this.position.intY * scale) + offset.y, this.radius * scale, 0, 2*Math.PI, false);
	//ctx.arc (100, 100, 10, 0, 2*Math.PI, false);
	ctx.fillStyle = this.color;
	ctx.fill();

}