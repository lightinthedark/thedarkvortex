// W A Y P O I N T

function Point(xPos, yPos, time) {
	this.x = xPos;
	this.y = yPos;
	this.t = time;	
	};


// U N I T

function Unit(rgb, size, startPos) {
	this.color = rgb;
	this.radius = size;
	this.position = {x: startPos.x, y: startPos.y};
	this.waypoints = [startPos];
}

Unit.prototype.addWaypoint = function(xPos, yPos, time) {
	if (!time) time = new Date().getTime();
	var wpNode = new Point(xPos, yPos, time);
	console.log('waypoint added: ' + xPos + ', ' + yPos + ', ' + time);
	this.waypoints.push(wpNode);
};

Unit.prototype.update = function(timestamp) {
	if (!timestamp) timestamp = new Date().getTime(); // provide current time when undefined
	var lastWP = new Point (0, 0, 0);
	var nextWP = new Point (0, 0, Number.POSITIVE_INFINITY);
	var travel;
	
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
		}
		else {
			//console.log('last Waypoint reached');
			this.position.x = lastWP.x;
			this.position.y = lastWP.y;
		}
	}
};

Unit.prototype.render = function(ctx, offset) {
	//console.log('render');
	
	this.update();
	
	ctx.beginPath();
	ctx.arc (this.position.x + offset.x, this.position.y + offset.y, this.radius, 0, 2*Math.PI, false);
	ctx.fillStyle = this.color;
	ctx.fill();
}


// U N I T S

function Units() {
	this.arrUnits = [];		
}

Units.prototype.addUnit = function(rgb, size, startPos) {
	this.arrUnits.push(new Unit(rgb, size, startPos));
};

Units.prototype.updateAll = function() {
	for (var i = 0; i < this.arrUnits.length; i++) {
		this.arrUnits[i].update();
	}
}

/*Units.prototype.renderAll = function(ctx) {
	for (var i = 0; i < this.arrUnits.length; i++) {
		this.arrUnits[i].update(ctx);
	}
}*/