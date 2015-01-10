var tdvPath = new Class(
{
	Implements: [Options, Events],
	
	options: {
	},
	
	initialize: function( id, fTime, fX, fY, tTime, tX, tY ) {
		this.id = id;
		this.pNext = null;
		this.pPrev = null;
		
		this.update( fTime, fX, fY, tTime, tX, tY );
	},
	
	update: function( fTime, fX, fY, tTime, tX, tY ) {
		// f? = from, t? = to, d? = difference
		this.fT = fTime;
		this.fX = fX;
		this.fY = fY;
		this.tT = tTime;
		this.tX = tX;
		this.tY = tY;
		
		this.dT = tTime - fTime;
		this.dX = tX - fX;
		this.dY = tY - fY;
		
		this.heading = ( Math.atan((this.dY/this.dX)) );
		if( this.dX < 0 ) {
			this.heading += Math.PI;
		}
	},
	
	getId: function() {
		return this.id;
	},
	
	getPosAt: function( t ) {
		// e? = elapsed
		var eT = t - this.fT
		var eFraction = eT / this.dT;
		return {x:(this.fX + this.dX * eFraction)
		      , y:(this.fY + this.dY * eFraction) };
	},
	
	getHeading: function() {
		return this.heading
	}
	
});
