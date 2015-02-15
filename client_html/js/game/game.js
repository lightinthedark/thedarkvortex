function Game( ldrLoader )
{
	this.ldrLoader = ldrLoader;
	this.init();
}

Game.prototype.init = function()
{
	this.ldrLoader.getUnitsCore().then(
		this._updateUnits.bind( this ),
		this._logError.bind( this, 'ajax' )
	);
	
	this.ldrLoader.getWaypointsList().then(
			this._updateWaypoints.bind( this ),
			this._logError.bind( this, 'ajax' )
	);
	
	this.ldrLoader.getWaypointsForUnits().then(
			this._updateWaypoints.bind( this ),
			this._logError.bind( this, 'ajax' )
	);
	
	/*
	// could do
	$.when( l.getUnits() ).then( ... );
	*/
}

Game.prototype.run = function()
{
	
}


Game.prototype._updateUnits = function( objData )
{
	console.log( 'updating units', objData );
}

Game.prototype._updateWaypoints = function( objData )
{
	console.log( 'updating waypoints', objData );
}

Game.prototype._logError = function( strType, mixData )
{
	console.log( 'game error', strType, mixData );
}