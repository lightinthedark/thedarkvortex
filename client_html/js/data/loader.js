function Loader( eleDefiner ) {
	this.apiUrl = eleDefiner.children( 'input[id=apiUrl]' ).val();
}

Loader.prototype.test = function()
{
	console.log( 'loader testing', this );
	return $.ajax( {
		url: this.apiUrl + 'test/',
		success: function( objData ) {
			console.log( 'loader test success', objData );
		},
		error: function( objData ) {
			console.log( 'loader test error', objData );
		}
	} );
}


/*
 * Public data access
 * Units
 */


/**
 * Accesses units/core.json[?{range=rngRange~|id=arrIds|id=strId}]
 * 
 * eg:
 * all units:
 *  units/core.json
 * units within the given space-time range (see game/Range.js):
 *  units/core.json?range=<json-encoded range object>
 * units with id 1, 23u, or 456
 * 	units/core.json?id=[1,23u,456]
 * unit 14a
 *  units/core.json?id=14a
 */
Loader.prototype.getUnitsCore = function( mixSpecifier )
{
	var strUrl;
	
	strUrl = this.apiUrl + 'units/core.json';
	strSpec = this._parseSpecifier( mixSpecifier );
	if( strSpec !== '' ) {
		strUrl += '?' + strSpec;
	}
	
	return $.ajax( { url: strUrl } );
}

/**
 * Accesses units/orders.json[?{range=rngRange~|id=arrIds|id=strId}]
 */
Loader.prototype.getUnitsOrders = function( mixSpecifier )
{
	var strUrl;
	
	strUrl = this.apiUrl + 'units/orders.json';
	strSpec = this._parseSpecifier( mixSpecifier );
	if( strSpec !== '' ) {
		strUrl += '?' + strSpec;
	}
	
	return $.ajax( { url: strUrl } );
}


/*
 * Public data access
 * Map Chunks
 */


/**
 * Accesses map-chunks.json[?{range=rngRange~|id=arrIds|id=strId}]
 */
Loader.prototype.getMapChunks = function( objCoords )
{
	var strUrl;
	
	strUrl = this.apiUrl+'map-chunks.json';
	if( objCoords !== undefined ) {
		strUrl += '?coords=' + encodeURIComponent( JSON.stringify( objCoords ) );
	}
	
	return $.ajax( { url: strUrl } );
}


/*
 * Private helper functions
 */


/**
 * Parse a specifier
 * Can handle: Range object, id array, id string
 */
Loader.prototype._parseSpecifier = function( mixSpecifier )
{
	if( mixSpecifier !== undefined ) {
		if( mixSpecifier.constructor === Range ) {
			strRet += 'range=' + encodeURIComponent( JSON.stringify( mixSpecifier ) );
		}
		else if( mixSpecifier.constructor === Array ) {
			strRet += 'id=' + encodeURIComponent( JSON.stringify( mixSpecifier ) );
		}
		else {
			strRet += 'id=' + encodeURIComponent( mixSpecifier.toString() );
		}
	}
	else {
		strRet = '';
	}
	
	return strRet;
}
