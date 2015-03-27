function Map(canvas, context) {

	console.log('new Map');
	
	this.offset = {x: -500, y: -500}; // initialise viewing position
	this.scale  = 1;
	this.cnv = canvas;
	this.ctx = context;
	
	// play area dimensions – change to just width and height!
	this.bounds = {
		top: 0,
		bottom: 2001,
		left: 0,
		right: 2001
	};
		
}

Map.prototype.moveOffset = function(newX, newY) {

	if(newX < this.bounds.left && newX > (this.bounds.right -this.cnv.width) * -1)
		this.offset.x = newX;
	
	if(newY < this.bounds.top && newY > (this.bounds.bottom -this.cnv.height) * -1)
		this.offset.y = newY;
	
	//limit to boundaries
	if(newX > this.bounds.left)
		this.offset.x = this.bounds.left;

	if(newX < (this.bounds.right -this.cnv.width) * -1)
		this.offset.x = (this.bounds.right -this.cnv.width) * -1;

	if(newY > this.bounds.top)
		this.offset.y = this.bounds.top;

	if(newY < (this.bounds.bottom -this.cnv.height) * -1)
		this.offset.y = (this.bounds.bottom -this.cnv.height) * -1;
	
	//console.log(this.offset);
}

Map.prototype.scale = function(newScale) {
	this.scale = newScale;
}


Map.prototype.resize = function() {
//Resize canvas to fit window

	this.cnv.width = window.innerWidth;
	this.cnv.height = window.innerHeight;
	//console.log(canvas.width + ' ' + canvas.height);

}

Map.prototype.drawGrid = function() {
//Draw grid to canvas

	var view = {
		top: 0,
		bottom: (this.cnv.height),
		left: 0,
		right: (this.cnv.width)
	};
	
	var interval = 50;
	
	this.ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);
	
	this.ctx.strokeStyle = "#000";
	this.ctx.lineWidth = 1;
			
	//draw verticals
	for (var x = this.bounds.left + this.offset.x; x < this.bounds.right + this.offset.x; x += interval) {
		if (x > view.left && x < view.right) {
			this.ctx.beginPath();
			this.ctx.moveTo(x, this.bounds.top + this.offset.y);
			this.ctx.lineTo(x, this.bounds.bottom + this.offset.y);
			this.ctx.stroke();
		}
	}	

	//draw horizontals
	for (var y = this.bounds.top + this.offset.y; y < this.bounds.bottom + this.offset.y; y += interval) {
		if (y > view.top && y < view.bottom) {
			this.ctx.beginPath();
			this.ctx.moveTo(this.bounds.left + this.offset.x, y);
			this.ctx.lineTo(this.bounds.right + this.offset.x, y);
			this.ctx.stroke();
		}
	}
	
	//outline boundary
	var boundary = {
		top: this.offset.y,
		bottom: this.bounds.bottom + this.offset.y,
		left: this.offset.x,
		right: this.bounds.right + this.offset.x
	}
	this.ctx.lineWidth = 98;
	this.ctx.strokeStyle = "#fff";
	this.ctx.beginPath();
	this.ctx.moveTo(boundary.left, boundary.top);
	this.ctx.lineTo(boundary.right, boundary.top);
	this.ctx.lineTo(boundary.right, boundary.bottom);
	this.ctx.lineTo(boundary.left, boundary.bottom);
	this.ctx.lineTo(boundary.left, boundary.top);
	this.ctx.stroke();
	
}

Map.prototype.drawGradient = function() {
//Draw gradient to canvas
	
}