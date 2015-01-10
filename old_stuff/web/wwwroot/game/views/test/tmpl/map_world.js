var tdvWorld = new Class(
{
	Implements: [Options, Events],
	
	options: {
		range: [-200000, 200000], // number of miliseconds to allow slider to cover
		refreshrate: 40,
		tPlay: true,
		dal: 'dal.php' // where do we interface with unit / path data? 
	},
	
	initialize: function( elContainer, elTime, options ) {
		this.setOptions( options );
		
		// set up canvas (add resize event to window so canvas always fills space
		this.container = document.id(elContainer);
		this.canv = this.container.getElement('.main_canv');
		this.ctx = this.canv.getContext( '2d' );
		this.canv.addEvent( 'click', this.onClick.bind(this) );
		window.addEvent( 'resize', function() {
			this.canv.width = this.container.offsetWidth;
			this.canv.height = this.container.offsetHeight;
			this.ctx = this.canv.getContext( '2d' );
			this.updateAll();
		}.bind(this));
		
		// set up slider
		elTime = document.id(elTime);
		this.tSlider = new Slider(elTime, elTime.getElement('.knob'), {
			steps: this.options.range[1] - this.options.range[0],
			range: this.options.range
		});
		this.tSlider.addEvent( 'change', function(value){
			this.updateAll();
			if( this.options.play ) {
				// only disable the play/pause toggle if we're not paused
				// if paused, the slider will be changing anyway
				this.tSlider.tdvClickedVal = false;
			}
		}.bind(this))
		this.tSlider.knob.addEvent( 'mousedown', function(event) {
			this.tSlider.tdvClickedVal = this.tSlider.step;
		}.bind(this));
		this.tSlider.knob.addEvent( 'mouseup', function(event) {
			if( this.tSlider.tdvClickedVal == this.tSlider.step ) {
				this.options.tPlay = !this.options.tPlay;
			}
			this.tSlider.tdvClickedVal = false;
		}.bind(this));
		
		// set up world artefacts
		this.loadUnits();
		this.selected = null;
		
		// Go! (draw initial state of this world)
		this.tSlider.set(0);
		window.fireEvent('resize');
		
		this.ticker = this.tick.periodical( this.options.refreshrate, this );
		$('panel_2').addEvent( 'click', function() {
			$clear(this.ticker);
		}.bind(this) );
	},
	
	onClick: function(event) {
		var coords = this.canv.getCoordinates();
		var clickPos = {x:(event.client.x-coords.left), y:(event.client.y-coords.top)};
//		console.log( clickPos );
		if( this.selected == null ) {
			// find closest unit and select it
			var min = 9999999;
			var sel = null;
			this.units.each( function(item, index){
				var p = item.getPos();
				var dx = clickPos.x - p.x;
				var dy = clickPos.y - p.y;
				var dist = dx*dx + dy*dy;
//				console.log( dist );
				if( dist < min ) {
					min = dist;
					sel = item;
				}
			});
			if( sel != null ) {
//				console.log( "selecting " + sel );
				sel.select( true );
				this.selected = sel;
			}
		}
		else {
			// send command to move selected unit to clicked location
//			console.log( "move to " + clickPos.x + ',' + clickPos.y );
//			console.log( this.selected );
			var pos = this.selected.getPos();
			var cmd = {unit:this.selected.getId(), fT:(this.tIndex/1000), fX:pos.x, fY:pos.y, tX:clickPos.x, tY:clickPos.y};
			// **** use JSONP instead to get data from a DAL at data.thedarkvortex.net ... or wherever.
			// Will be using custom server instead of a boxed comet webserver
			var req = new Request.JSON( {url:this.options.dal,
				onSuccess: function(data) {
//					console.log( this.selected );
//					this.selected.clearPaths();
					data.each( function(item) {
						this.selected.setPath( item );
//						console.log( "adding path: ");
//						console.log(item);
					}, this);
					// ... and deselect
					this.selected.select( false );
					this.selected = null;
				}.bind(this)
			}).get({'action':'setpath', 'command':JSON.encode(cmd)});
		}
	},

	
	loadUnits: function() {
		this.units = new Hash();
		var jsonRequest = new Request.JSON( {url:this.options.dal,
			onSuccess: function(data) {
				data.each( function(item) {
					this.units.set( item.id, new tdvUnit(item) );
				}, this);
				this.loadPaths();
			}.bind(this)
		}).get({'action':'getunits'});
		
	},
	
	loadPaths: function() {
		var jsonRequest = new Request.JSON( {url:this.options.dal,
			onSuccess: function(data) {
				data.each( function(item) {
					this.units.get( item.unitid ).setPath( item );
				}, this);
			}.bind(this)
		}).get({'action':'getpaths'});
	},
	
	
	tick: function() {
		if( this.options.tPlay ) {
			this.updateAll();
		}
		else {
			if( this.tSlider.tdvClickedVal == false ) {
				this.updateSlider();
			}
		}
	},
	
	updateSlider: function() {
		this.tSlider.set( (this.tIndex - $time()) );
	},
	
	updateAll: function() {
		if( this.tIndex != ( $time() + this.tSlider.step ) ) {
			this.tIndex = ( $time() + this.tSlider.step );
			var debug = $('panel_2');
			debug.innerHTML = 't='+this.tIndex;
			var tSec = this.tIndex / 1000;
			this.units.each( function(item, index){
				item.setPos( tSec );
				debug.innerHTML += '<br />unit pos: ('+item.curX.toFixed(2)+', '+item.curY.toFixed(2)+')';
			}, this );
		}
		this.ctx.clearRect( 0, 0, this.canv.width, this.canv.height );
		this.drawAll();
	},
	
	// should this be inside updateAll ?
	// ... or most of that in this (so it can delegate between redraw and move slider
	drawAll: function() {
		this.units.each( function(item, index){
			if( item.getVisible() ) {
				item.draw( this.ctx );
			}
		}, this );
	}
	
	
});