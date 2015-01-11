function Loader() {
	this.urlGetUnits = $( 'input[id=apiGetUnits]' ).val();
}

Loader.prototype.test = function()
{
	console.log( 'tested', this );
}

Loader.prototype.getUnits = function( objCoords )
{
	$.ajax( {
		url: this.urlGetUnits,
		success: function( objData ) {
			console.log( 'success', objData );
		},
		error: function( objData ) {
			console.log( 'error', objData );
		}
	} );
}