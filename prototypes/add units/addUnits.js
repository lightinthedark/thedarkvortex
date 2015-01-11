function Waypoint(xPos, yPos, time) {
	this.x = xPos;
	this.y = yPos;
	this.t = time;
	};


function Unit(rgb, size, startPos) {
	this.color = rgb;
	this.radius = size;
	this.position = {x: startPos.x, y: startPos.y};
	this.waypoints = [startPos];
	
	this.addWaypoint = function(xPos, yPos, time) {
		var wpNode = new Waypoint(xPos, yPos, time);
		console.log('waypoint added: ' + xPos + ', ' + yPos + ', ' + time);
		this.waypoints.push(wpNode);
	};
	
	this.update = function() {
		this.lastWP = new Waypoint (0, 0, 0);
		this.nextWP = new Waypoint (0, 0, Number.POSITIVE_INFINITY);
		this.travel;
		this.d = new Date();
		this.now = this.d.getTime();
		
		if (this.waypoints.length > 1) {

			for (var i = 0; i < this.waypoints.length; i++) {
			
				//get previous waypoint
				if (this.waypoints[i].t < this.now && this.waypoints[i].t > this.lastWP.t) {
					this.lastWP = this.waypoints[i];
				}
				
				//get next waypoint
				if (this.waypoints[i].t > this.now && this.waypoints[i].t < this.nextWP.t) {
					this.nextWP = this.waypoints[i];
				}
			}
			
			//interpolate waypoint
			if (this.nextWP.t != Number.POSITIVE_INFINITY) {
				
				this.travel = (this.now - this.lastWP.t) / (this.nextWP.t - this.lastWP.t);
				this.position.x = Math.floor ( this.lastWP.x + this.travel * ( this.nextWP.x - this.lastWP.x ) );
				this.position.y = Math.floor ( this.lastWP.y + this.travel * ( this.nextWP.y - this.lastWP.y ) );
			}
			else {
				console.log('last Waypoint reached');
				this.position.x = this.lastWP.x;
				this.position.y = this.lastWP.y;
			}
		}
	};
}


function Units() {
	this.arrUnits = [];
	
	this.addUnit = function(objUnit) {
		if (objUnit instanceof Unit) {
			this.arrUnits.push(objUnit);
			console.log('Unit added');
		}
		else {
			console.log('Add Unit failed: not a Unit');
		}
	};
	
	this.updateAll = function() {
		for (var i = 0; i < this.arrUnits.length; i++) {
			this.arrUnits[i].update();
		}
	};
	
	this.renderAll = function(objCanvas) {
		console.log('render all units');
		for (var i = 0; i < this.arrUnits.length; i++) {
			this.arrUnits[i].update();
			var vector = this.arrUnits[i].position;
			var radius = this.arrUnits[i].radius;
			objCanvas.context.beginPath();
			objCanvas.context.arc (vector.x, vector.y, radius, 0, 2*Math.PI, false);
			objCanvas.context.fillStyle = this.arrUnits[i].color;
			objCanvas.context.fill();
		}
	};
	
}


$(document).ready( function() {
	objUnits = new Units;
	objUnits.arrUnits.push (new Unit("#F00", 10, new Waypoint(100, 100, 1420997500000)));
	objUnits.arrUnits.push (new Unit("#0F0", 10, new Waypoint(100, 150, 1420993725091)));
	objUnits.arrUnits[0].addWaypoint (2, 200, 1420998000000);
	objUnits.arrUnits[0].addWaypoint (2000, 200, 1421000000000);
	objUnits.arrUnits[0].addWaypoint (200, 950, 1421000500000);
	objUnits.arrUnits[0].addWaypoint (10, 250, 1421003500000);
});
	var n = 0; 
	var intervalID = window.setInterval(function() { objUnits.updateAll(); writeUnitInfo(); }, 500);
	
function writeUnitInfo() {
		//console.log('refresh');
		var unitInfo = "";
		var i;
		var n;
	
		for (i = 0; i < objUnits.arrUnits.length; i++) {
			var strDate = '';
			var wpInfo = "";

			//get waypoints for inclusion in info
			for (n = 0; n < objUnits.arrUnits[i].waypoints.length; n++) {
				this.strDate = new Date(objUnits.arrUnits[i].waypoints[n].t).getDate().toString();
				this.strDate += '/';
				this.strDate += new Date(objUnits.arrUnits[i].waypoints[n].t).getMonth().toString() + 1;
				this.strDate += '/';
				this.strDate += new Date(objUnits.arrUnits[i].waypoints[n].t).getFullYear().toString();
				this.strDate += ' &nbsp;';
				this.strDate += new Date(objUnits.arrUnits[i].waypoints[n].t).getHours();
				this.strDate += ':';
				this.strDate += new Date(objUnits.arrUnits[i].waypoints[n].t).getMinutes();
				this.strDate += ':';
				this.strDate += new Date(objUnits.arrUnits[i].waypoints[n].t).getSeconds();
				this.strDate += ':';
				this.strDate += new Date(objUnits.arrUnits[i].waypoints[n].t).getMilliseconds();
				wpInfo += "<li>" + this.strDate + " &nbsp; x" + objUnits.arrUnits[i].waypoints[n].x + ", y" + objUnits.arrUnits[i].waypoints[n].y + "</li>";
			}

			unitInfo += 
				"<ul>" +
					"<li> Unit " + i + "</li>" +
					"<ul>" +
						"<li>colour: " + objUnits.arrUnits[i].color + "</li>" +
						"<li>size: " + objUnits.arrUnits[i].radius + "</li>" +
						"<li>position: " + objUnits.arrUnits[i].position.x + ", " + objUnits.arrUnits[i].position.y +"</li>" +
						"<li>waypoints:" + 
						"<ul>" + 
							wpInfo +
						"</ul>" +
					"</ul>" +
				"</ul>";
		}
		document.getElementById('units').innerHTML = unitInfo;
};