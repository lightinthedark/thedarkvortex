function Game() {
	console.log('new Game');
	this.cnv = document.getElementById('canvas1');
	this.ctx = this.cnv.getContext('2d');
	this.units = new Units();
	this.map = new Map(this.cnv, this.ctx);
	this.renderer = new RenderManager(this.ctx, this.map, this.units);
	this.events = new ClickHandler("#clickLayer", this.map, this.units, this.renderer);
	};
	
$(document).ready( function() { 
	game = new Game();
	game.units.addUnit("#F00", 20, new Point(100, 200, new Date().getTime() ) );
	
});

