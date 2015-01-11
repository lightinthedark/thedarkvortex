function objCanvas(canvas1, canvas2) {
	this.canvas = document.getElementById('canvas1');
	this.context = canvas1.getContext('2d');
	
	function Resize() {
		objCanvas.canvas.width = window.innerWidth;
		objCanvas.canvas.height = window.innerHeight;
		Redraw(objCanvas)
	}
	
	function Redraw() {
		//redraw background
		//redraw units
	}
}

function Waypoint(xPos, yPos, time) {
	this.x = xPos;
	this.y = yPos;
	this.t = time;
	};


function Unit(rgb, size, startPos) {
	this.color = rgb;
	this.radius = size;
	this.position = [startPos.x, startPos.y];
	this.waypoints = [startPos];
	
	this.addWaypoint = function(xPos, yPos, time) {
		var wpNode = new Waypoint(xPos, yPos, time);
		console.log('waypoint added: ' + xPos + ', ' + yPos + ', ' + time);
		this.waypoints.push(wpNode);
	};
	
	this.update = function() {
		this.lastWP = 0;
		this.nextWP = Number.POSITIVE_INFINITY;
		this.d = new Date();
		this.now = this.d.getTime();
		
		if (this.waypoints.length > 1) {
		
			console.log(this.waypoints.length + ' waypoints');
			
			for (var i = 0; i < this.waypoints.length; i++) {
				
				//get previous waypoint
				if (this.waypoints[i].t < this.now && this.waypoints[i].t > this.lastWP) {
					this.lastWP = this.waypoints[i];
					console.log('nextWP x:' + this.lastWP.x + ', y: ' + this.lastWP.y + ', t: ' + this.lastWP.t);
				}
				
				//get next waypoint
				if (this.waypoints[i].t > this.now && this.waypoints[i].t < this.nextWP) {
					this.nextWP = this.waypoints[i];
					//console.log('update nextWP: ' + i);
					console.log('nextWP x:' + this.nextWP.x + ', y: ' + this.nextWP.y + ', t: ' + this.nextWP.t);
				}
			}
			//interpolate waypoint
			this.position.x = this.lastWP.x + this.nextWP.x * ( this.lastWP.t / this.nextWP.t );
			this.position.y = this.lastWP.y + this.nextWP.y * ( this.lastWP.t / this.nextWP.t );
			console.log(this.position.x + ' ' + this.position.y);
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
	
	this.updateAll = function() {
		for (var i = 0; i < this.arrUnits.length; i++) {
			console.log('unit ' + i);
			this.arrUnits[i].update();
		}
	}
}


$(document).ready( function() {
	var objCanvas = GetCanvas(); //store canvas references
	//window.addEventListener('resize', function() {Resize (objCanvas); objUnits.renderAll(objCanvas);}, false);
	//Resize(objCanvas);
	objUnits = new Units;
	objUnits.arrUnits.push (new Unit("#F00", 10, new Waypoint(100, 100, 0)));
	objUnits.arrUnits.push (new Unit("#0F0", 10, new Waypoint(100, 150, 0)));
	
	objUnits.renderAll(objCanvas);
	/*objUnits.arrUnits[0].addWaypoint(10,500, 1111111111111);
	objUnits.arrUnits[0].addWaypoint(400,500,2222222222222);
	objUnits.arrUnits[0].addWaypoint(400,300,3333333333333);
	objUnits.arrUnits[1].addWaypoint(10,500, 1234567899999);
	objUnits.arrUnits[1].addWaypoint(0,10,9000005473882);*/

	
	//myVar = setTimeout(function(){ objUnits.arrUnits[0].update(); }, 100);
	
});

function GetCanvas() {
	var objCanvasRefs = {canvas: '', context: ''}

	objCanvasRefs.canvas = document.getElementById('canvas1');
	objCanvasRefs.context = objCanvasRefs.canvas.getContext('2d');

	return objCanvasRefs;
}


function Redraw(objCanvas) {
	var bgGrad = objCanvas.context.createLinearGradient(0,0,window.innerWidth, 0);
	bgGrad.addColorStop(0, "#321");
	bgGrad.addColorStop(1, "#123");
	//render background
	objCanvas.context.fillStyle = bgGrad; //'#00F';
	objCanvas.context.fillRect(0, 0, window.innerWidth, window.innerHeight);
}


function Resize(objCanvas) {
	objCanvas.canvas.width = window.innerWidth;
	objCanvas.canvas.height = window.innerHeight;
	Redraw(objCanvas)
}