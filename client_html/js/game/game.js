function Game( ldrLoader ) {
	console.log('new Game');
	
	this.canvases = [
		document.getElementById('terrain'),
		document.getElementById('units'),
		document.getElementById('ui')
		];
	this.units = new Units();
	this.map = new Map();
	this.renderer = new Renderer(this.canvases, this.map, this.units);
	this.events = new ClickHandler("#clickLayer", this.units, this.renderer);
	//this.loader = new Loader();
	//this.loader.init();
}

/* This to go in Loader object?

Game.prototype.init = function()
{
	this.ldrLoader.getUnitsCore().then(
	this._updateUnits.bind( this ),
	this._logError.bind( this, 'ajax' )
	);
	
	// could do
	//$.when( l.getUnits() ).then( ... );
	
}

/*Game.prototype.run = function()
	};


Game.prototype._updateUnits = function( objData )
{
	console.log( 'updating units', objData );
}

Game.prototype._logError = function( strType, mixData )
{
	console.log( 'game error', strType, mixData );
}
*/