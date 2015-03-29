function Units() {
	//this.arrUnits = [];
	this.objUnits = {};
}

Units.prototype.addUnit = function(ident, c, s, px, py) {
	var unitName = ident.toString();
	this.objUnits[unitName] = new Unit (c, s, new Point(px, py, new Date().getTime() ) );
};

Units.prototype.updateAll = function() {
	/*for (var i = 0; i < this.arrUnits.length; i++) {
		this.arrUnits[i].update();
	}*/
}