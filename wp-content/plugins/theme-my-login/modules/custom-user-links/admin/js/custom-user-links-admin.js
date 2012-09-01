jQuery(document).ready( function($) {
	$('#tml-options-user-links tbody').wpList( {
		addBefore: function( s ) {
			var cls = $(s.target).attr('class').split(':'),
				role = cls[1].split('-')[0];
			s.what = role + '-link';
			return s;
		},
		addAfter: function( xml, s ) {
			var cls = $(s.target).attr('class').split(':'),
				role = cls[1].split('-')[0];
			$('table#' + role + '-link-table').show();
		},
		delBefore: function( s ) {
			var cls = $(s.target).attr('class').split(':'),
				role = cls[1].split('-')[0];
			s.data.user_role = role;
			return s;
		},
		delAfter: function( r, s ) {
			$('#' + s.element).remove();
		}
	} );
	
	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};
	
	$('#tml-options-user-links table.sortable tbody').sortable({
		axis: 'y',
		helper: fixHelper,
		items: 'tr'
	});
} );