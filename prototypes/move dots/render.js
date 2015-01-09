/*
TO DO

Add movement by interpolating between waypoints
fixed update rendering (setTimeout callback functions)
*/


function Unit(rgb, size, pos) {
	this.color = rgb;
	this.radius = size;
	this.position = pos;
	this.waypoints = [];
	
}

Unit.prototype.addWaypoint = function(x,y,t) {
	var wpNode = [x,y,t];
	console.log('addWaypoint');
	this.waypoints.push([wpNode]);
}

Unit.prototype.update = function(destination) {
	//this.waypoints.push();
	/*
	
	waypoints ...
	
	if (this.waypoints.length > 0) {
		//
		//find which waypoints are immediately before and after timestamp
		//interpolate between waypoints
		//set new positions
	}
	else {
		//set new position to first waypoint
	}*/
}

function Units() {
	this.arrUnits = [];	
}

Units.prototype.renderAll = function(objCanvas) {
	
		for (var i = 0; i < this.arrUnits.length; i++) {
			var vector = this.arrUnits[i].position;
			var radius = this.arrUnits[i].radius;
			//console.log(this.arrUnits[i]);
			objCanvas.context.beginPath();
			objCanvas.context.arc(vector[0],vector[1],radius,0,2*Math.PI, false);
			objCanvas.context.fillStyle = this.arrUnits[i].color;
			objCanvas.context.fill();
			//objCanvas.context.lineWidth = 15;
			//objCanvas.context.strokeStyle = "#000";
			//objCanvas.context.stroke();
		}

	};
	
function Time() {
	var d = new Date();
	this.begin = d.getTime();
	this.now = d.getTime();
}

Time.prototype.update = function() {
	this.now = d.getTime();
}


$(document).ready( function() {
	timer = new Time;
	console.log(timer.begin);
	var t = 0;
	var objCanvas = GetCanvas(); //store canvas references
	window.addEventListener('resize', function() { Resize (objCanvas); objUnits.renderAll(objCanvas);}, false);
	Resize(objCanvas);
	objUnits = new Units;
	objUnits.arrUnits.push (new Unit("#F00", 15, [100,100]));
	objUnits.arrUnits.push (new Unit("#0F0", 10, [150,110]));
	objUnits.renderAll(objCanvas);
	objUnits.arrUnits[0].addWaypoint(10,500,1420761357809);
});


function Count(time) {
    var d = new Date();
    document.getElementById("demo").innerHTML = d.toLocaleTimeString();
}

function GetCanvas() {
	var objCanvasRefs = {
		canvas: '',
		context: ''
	};

	objCanvasRefs.canvas = document.getElementById('canvas');
	objCanvasRefs.context = canvas.getContext('2d');

	return objCanvasRefs;
}


function Redraw(objCanvas) {
	var bgGrad = objCanvas.context.createLinearGradient(0,0,window.innerWidth, 0);
	bgGrad.addColorStop(0, "#321");
	bgGrad.addColorStop(1, "#123");
	//render background
	objCanvas.context.fillStyle = bgGrad; //'#00F';
	objCanvas.context.fillRect(0, 0, window.innerWidth, window.innerHeight);
	//render unit
	
}


function Resize(objCanvas) {
	objCanvas.canvas.width = window.innerWidth;
	objCanvas.canvas.height = window.innerHeight;
	Redraw(objCanvas)
}