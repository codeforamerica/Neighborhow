jQuery(document).ready( function($) {	
	$('#dothis').on('click', function() {
		var $this = $(this);	
		var post_id = $this.data('post-id');
		var user_id = $this.data('user-id');
		var data = {
			action: 'dothis',
			item_id: post_id,
			user_id: user_id,
			do_this_nonce: do_this_vars.nonce
		};
		
// don't allow the user to love the item more than once
		if($this.hasClass('donethis')) {
			alert(do_this_vars.already_dothis_message);
			return false;
		}	
		if(do_this_vars.logged_in == 'false' && $.cookie('dothis-' + post_id)) {
			alert(do_this_vars.already_dothis_message);
			return false;
		}
		
		$.post(do_this_vars.ajaxurl, data, function(response) {
			if(response == 'dothis') {
				$this.addClass('donethis');
//				var count_wrap = $this.next();
				var count_wrap = $('.nh-love-count');				
				var count = count_wrap.text();
				count_wrap.text(parseInt(count) + 1);
				if(do_this_vars.logged_in == 'false') {
					$.cookie('dothis-' + post_id, 'yes', { expires: 1 });
				}
			} else {
				alert(do_this_vars.error_message);
			}
		});
		return false;
	});	
});