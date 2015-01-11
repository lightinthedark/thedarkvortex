$( function() {
	var l, g;
	l = new Loader( $( 'div#metadata' ) );
	l.test();
	
	g = new Game( l );
	g.run();
} );