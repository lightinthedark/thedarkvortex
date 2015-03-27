function Map() {

	console.log('new Map');
	
	this.width = 2001;
	this.height = 2001;
		
}

Map.prototype.drawGrid = function(context, scale, offset) {
//Draw grid to canvas
	
	var interval = 50;
	
	context.clearRect(0, 0, window.innerWidth, window.innerHeight);
	context.strokeStyle = "#000";
	context.lineWidth = 1;

	var view = {
		width: 0 + window.innerWidth,
		height: 0 + window.innerHeight
	};

	
	//draw verticals
	for (var x = 0 + offset.x; x < this.width + offset.x; x += interval * scale) {
		if (x > 0 && x < view.width) {
			context.beginPath();
			context.moveTo(x, Math.max(0, offset.y));
			context.lineTo(x, Math.min(view.height, this.height + offset.y));
			context.stroke();
		}
	}

	//draw verticals
	for (var y = 0 + offset.y; y < this.height + offset.y; y += interval * scale) {
		if (y > 0 && y < view.height) {
			context.beginPath();
			context.moveTo(Math.max(0, offset.x), y);
			context.lineTo(Math.min(view.width, this.width + offset.x), y);
			context.stroke();
		}
	}
	
	//outline boundary
	var boundary = {
		top: -interval + offset.y,
		bottom: interval + this.height + offset.y,
		left: -interval + offset.x,
		right: interval + this.width + offset.x
	};
	context.lineWidth = 1;
	context.strokeStyle = "#000";
	context.lineJoin="round";
	context.beginPath();
	context.moveTo(boundary.left, boundary.top);
	context.lineTo(boundary.right, boundary.top);
	context.lineTo(boundary.right, boundary.bottom);
	context.lineTo(boundary.left, boundary.bottom);
	context.lineTo(boundary.left, boundary.top);
	context.stroke();
	
}