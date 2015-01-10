function Loader() {
	this.urlGetUnits = $( 'input[id=apiGetUnits]' );
}

Loader.prototype.test = function()
{
	console.log( 'tested', this );
}

Loader.prototype.getUnits = function( objCoords )
{
	$.ajax( {
		url: this.urlGetUnits,
		success: function() {
			console.log( data );
		},
		error: function() {
			console.log( data );
		}
	} );
}