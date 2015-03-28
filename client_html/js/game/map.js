function Map() {

	console.log('new Map');
	
	this.width = 200.5;
	this.height = 200.5;
		
}

Map.prototype.drawGrid = function(context, scale, offset) {
//Draw grid to canvas
	
	var interval = 50;
	
	context.clearRect(0, 0, window.innerWidth, window.innerHeight);
	context.strokeStyle = "#000";
	context.lineCap="round";
	context.lineWidth = 1 * scale;

	var view = {
		width: window.innerWidth,
		height: window.innerHeight
	};

	
	//draw verticals
	for (var x = 0 + offset.x; x < (this.width * scale) + offset.x; x += interval * scale) {
		if (x > 0 && x < view.width) {
			context.beginPath();
			context.moveTo(x, Math.max(0, offset.y));
			context.lineTo(x, Math.min(view.height, (this.height * scale) + offset.y));
			context.stroke();
		}
	}

	//draw verticals
	for (var y = 0 + offset.y; y < (this.height * scale) + offset.y; y += interval * scale) {
		if (y > 0 && y < view.height) {
			context.beginPath();
			context.moveTo(Math.max(0, offset.x), y);
			context.lineTo(Math.min(view.width, (this.width * scale) + offset.x), y);
			context.stroke();
		}
	}
	
	//outline boundary
	var boundary = {
		top: offset.y - (interval * scale),
		bottom: offset.y + (this.height * scale) + (interval * scale),
		left: offset.x - (interval * scale),
		right: offset.x + (this.width * scale) + (interval * scale)
	};
	context.lineWidth = 1 * scale;
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