window.addEvent( 'domready', init );

function init()
{
	var world = new tdvWorld( 'map_inner', 'timeline', {refreshrate:50, dal: 'http://192.168.1.5/thedarkvortex.net/libraries/dal.php'} );
}
