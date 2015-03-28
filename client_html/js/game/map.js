function Map() {

	console.log('new Map');
	
	this.width = 201;
	this.height = 201;
		
}

Map.prototype.drawGrid = function(context, scale, offset, view) {
//Draw grid to canvas
	
	var interval = 50;
	
	context.strokeStyle = "#000";
	context.lineCap="round";
	context.lineWidth = 1 * scale;

	context.beginPath();

	//draw verticals
	for (var x = 0 + offset.x; x < (this.width * scale) + offset.x; x += interval * scale) {
		if (x > 0 && x < view.width) {
			context.moveTo(x, Math.max(0, offset.y));
			context.lineTo(x, Math.min(view.height, ((this.height - 1) * scale) + offset.y));
		}
	}

	//draw verticals
	for (var y = 0 + offset.y; y < (this.height * scale) + offset.y; y += interval * scale) {
		if (y > 0 && y < view.height) {
			context.moveTo(Math.max(0, offset.x), y);
			context.lineTo(Math.min(view.width, ((this.width - 1) * scale) + offset.x), y);
		}
	}
	
	/*//outline boundary
	var boundary = {
		top: offset.y - (interval * scale),
		bottom: offset.y + (this.height * scale) + (interval * scale),
		left: offset.x - (interval * scale),
		right: offset.x + (this.width * scale) + (interval * scale)
	};
	context.lineWidth = 1 * scale;
	context.strokeStyle = "#000";
	context.lineJoin="round";
	context.moveTo(boundary.left, boundary.top);
	context.lineTo(boundary.right, boundary.top);
	context.lineTo(boundary.right, boundary.bottom);
	context.lineTo(boundary.left, boundary.bottom);
	context.lineTo(boundary.left, boundary.top);*/
	context.stroke();
	
}