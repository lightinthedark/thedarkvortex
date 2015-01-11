function Range( pntA, pntB )
{
	this.pntA = pntA;
	this.pthB = pntB;
}

Range.prototype.toJSON = function()
{
	return JSON.stringify( this.pntA, this.pntB );
}

Range.prototype.containsPoint = function( pntTarget )
{
	// MATHS!
}
