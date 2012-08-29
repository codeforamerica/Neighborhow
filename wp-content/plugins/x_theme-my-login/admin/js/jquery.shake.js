jQuery.fn.shake = function( speed, loop ) {
	if ( !speed || speed <= 0 )
		speed = 1000;
	if ( !loop || loop <= 0 )
		loop = 5;
		
	this.each( function() {
		jQuery(this).css( { position: 'relative' } );
		var origLeft = parseInt( jQuery(this).css( "left" ), 10 );
		for ( var x = 1; x <= loop; x++ ) {
			jQuery(this)
				.animate( { left: origLeft - 10 }, ( ( ( speed / 4 ) / 4 ) ) )
				.animate( { left: origLeft + 10 }, ( ( speed / 4 ) / 2 ) )
				.animate( { left: origLeft }, ( ( ( speed / 4 ) / 4 ) ) );
		}
	} );
	return this;
};