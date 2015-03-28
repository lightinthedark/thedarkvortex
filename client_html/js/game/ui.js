function UI() {
//draw ui
	this.textStyle = {
		basic: "bold 12px Verdana",
	}

	this.magIcon = {}

}

UI.prototype.drawScale = function(ctx, scale, view) {
	
	this.uiZoom = {
		x: view.width - 100,
		y: view.height - 55
	};

	//draw magnifying glass
	ctx.beginPath();
	ctx.lineWidth = 1.5;
	ctx.arc (this.uiZoom.x, this.uiZoom.y, 3, 0, 2 * Math.PI, false);
	ctx.moveTo(this.uiZoom.x + 3, this.uiZoom.y + 3);
	ctx.lineTo(this.uiZoom.x + 6, this.uiZoom.y + 6);
	ctx.stroke();

	//write zoom scale
	ctx.font = this.textStyle.basic;
	ctx.textAlign = "left";
	ctx.fillText(scale.toFixed(2).toString(), this.uiZoom.x + 15, this.uiZoom.y + 6);
}