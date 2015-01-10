var tdvUnit = new Class(
{
	Implements: [Options, Events],
	
	options: {
		id: 0,
		x: 200,
		y: 200,
		c: false,
		selected: false,
		visible: true
	},
	
	initialize: function( options ) {
		this.setOptions( options );
		this.curX = this.options.x;
		this.curY = this.options.y;
		this.curVisible = this.options.visible;
		this.curPath = null;
		this.firstPath = null;
		this.lastPath = null;
	},
	
	getId: function() {
		return this.options.id;
	},
	
	clearPaths: function() {
		this.curPath = null;
		this.firstPath = null;
		this.lastPath = null;
	},
	
	setPath: function( d ) {
		found = this.findPath( d.id, d.fT );
		// null time = remove if exists;
		if( d.fT == null ) {
			if( (found != null) && (found.id == d.id) ) {
				if( found.pPrev == null ) { 
					this.firstPath = found.pNext;
				}
				else {
					found.pPrev.pNext = found.pNext;
				}
				
				if( found.pNext == null ) {
					this.lastPath = found.pPrev;
				}
				else {
					found.pNext.pPrev = found.pPrev;
				}
				
				found = null;
			}
		}
		else {
			var p = new tdvPath( d.id, d.fT, d.fX, d.fY, d.tT, d.tX, d.tY );
			if( (found != null) && (found.id == d.id) ) {
				found.update( d.fT, d.fX, d.fY, d.tT, d.tX, d.tY );
			}
			else if( this.firstPath == null ) {
				// this is our first last and only path
				this.firstPath = this.curPath = this.lastPath = p;
			}
			else if( found == null ) {
				// this goes at the start
				this.firstPath.pPrev = p;
				p.pNext = this.firstPath;
				this.firstPath = p;
			}
			else if( found.pNext == null ) {
				// this goes at the end
				this.lastPath.pNext = p;
				p.pPrev = this.lastPath;
				this.lastPath = p;
			}
			else {
				// this goes somewhere in the middle
				p.pPrev = found;
				p.pNext = found.pNext;
				found.pNext.pPrev = p;
				found.pNext = p;
			}
		}
	},
	
	/**
	 * looks for the given path in the list of paths. searches from the current path outwards (forward and back)
	 * if it can be found, a reference to it is returned
	 * if it cannot be found, a reference to the element which should precede it is returned
	 */
	findPath: function( id, fT ) {
		// work out from the current position to find given path, or spot for it if doesn't exist
		var lookFwd;
		var lookBack;
		if( this.curPath == null ) {
			lookFwd = lookBack = this.firstPath;
		}
		else {
			lookFwd = lookBack = this.curPath;
		}
		var found = false;
		var after = null;
		var before = null;
		while( (found == false) && ((lookFwd != null) || (lookBack != null)) ) {
			if( lookFwd != null ) {
				if( lookFwd.id == id ) {
					found = lookFwd;
				}
				else if( (before == null) && (lookFwd.fT >= fT) ) {
					before = lookFwd;
				}
				
				lookFwd = lookFwd.pNext;
			}
			
			
			if( lookBack != null ) {
				if( lookBack.id == id ) {
					found = lookBack;
				}
				else if( (after == null) && (lookBack.fT <= fT) ) {
					after = lookBack;
				}
				
				lookBack = lookBack.pPrev;
			}
		}
		
		if( found ) {
			return found;
		}
		else {
			if( after == null ) {
				return null;
			}
			else if( before == null ) {
				return this.lastPath;
			}
			else {
				// if we found things in both directions we want to pick the one that wasn't a starting point
				if( before == this.curPath ) {
					return after;
				}
				else {
					return before.pPrev;
				}
			}
		}
	},
	
	getVisible: function() {
		return this.options.visible && this.curVisible;
	},
	
	select: function( val ) {
		this.options.selected = val;
	},
	
	getPos: function() {
		return {x:this.curX, y:this.curY};
	},
	
	setPos: function( tIndex ) {
		var ok = false;
		if( this.curPath != null ) {
			// check if our current path is valid
			if( (this.curPath.fT <= tIndex) && (this.curPath.tT >= tIndex) ) {
				ok = true;
			}
			if( !ok ) {
				// check prev/next (whichever most likely)
				if( this.curPath.fT <= tIndex ) {
					var tmp = this.curPath.pNext;
				}
				else {
					var tmp = this.curPath.pPrev;
				}
				if( tmp != null ) {
					if( (tmp.fT <= tIndex) && (tmp.tT >= tIndex) ) {
						ok = true;
						this.curPath = tmp;
					}
				}
			}
		}
		
		if( !ok ) {
			this.curPath = this.findPath( null, tIndex );
			if( this.curPath != null ) {
				if( (this.curPath.fT <= tIndex) && (this.curPath.tT >= tIndex) ) {
					ok = true;
				}
			}
		}
		
		if( ok ) {
			this.curVisible = true;
			var pos = this.curPath.getPosAt(tIndex);
			this.curX = pos.x;
			this.curY = pos.y;
		}
		else {
			this.curVisible = false;
			this.curPath = null;
		}
	},
	
	draw: function( c ) {
		if( (this.curPath == null) || !this.curVisible ) {
			return; // don't do anything if we don't have a valid path at the moment
		}
		
		var ang = this.curPath.getHeading();
		
		if( this.options.selected ) {
			c.fillStyle = "rgb( 0, 0, 255 )";
		}
		else {
			c.fillStyle = "rgb( 0, 128, 128 )";
		}
		c.beginPath();
		c.moveTo( this.curX, this.curY );
		c.arc( this.curX, this.curY, 40, 0, Math.PI*2, true );
		c.fill();
		c.fillStyle = "rgba( 0, 255, 0, 0.5 )";  
		
		c.beginPath();
		c.moveTo( this.curX, this.curY );
		c.arc( this.curX, this.curY, 200, (ang-1.2), (ang+1.2), false)
		c.fill();
		c.fillStyle = "rgba( 255, 0, 0, 0.8 )";  
		
		c.beginPath();
		c.moveTo( this.curX, this.curY );
		c.arc( this.curX, this.curY, 60, (ang-0.9), (ang+0.9), false)
		c.fill();
	}
});