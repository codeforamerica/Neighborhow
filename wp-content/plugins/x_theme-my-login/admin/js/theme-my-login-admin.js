jQuery(document).ready(function($){

    $("#tml-container").tabs({ 
		select: function(event, ui) {
			setUserSetting( 'tml0', ui.index );
		},
		selected: getUserSetting( 'tml0', 0 )
	});
	
	$("#tml-container div").tabs({
		select: function(event, ui) {
			setUserSetting( 'tml1', ui.index );
		},
		selected: getUserSetting( 'tml1', 0 )
	});
	
	$("#tml-tips").shake( 500 );
	$("#tml-tips a").click(function() {
		var menu = $(this).attr( 'rel' );
		var submenu = $(this).attr( 'href' );
		var target = $(this).attr( 'target' );
		if ( '_blank' == target )
			return true;
		if ( '' != menu )
			$("#tml-container").tabs( 'select', '#' . menu );
		if ( '' != submenu )
			$("#tml-container div").tabs( 'select', submenu );
		return false;
	});
});