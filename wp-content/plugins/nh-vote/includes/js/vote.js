jQuery(document).ready( function($) {	
// NEIGHBORHOW MODS INCLUDED
	$('.vote').on('click', function() {
		var $this = $(this);	
		var post_id = $this.data('post-id');
		var user_id = $this.data('user-id');
		var data = {
			action: 'vote',
			item_id: post_id,
			user_id: user_id,
			vote_nonce: vote_vars.nonce
		};
// See footer.php for js handling the immediate
// onclick switch to new copy/link	
	
		// don't allow the user to vote on the item more than once
		if($this.hasClass('voted')) {
// dont show the messages - redundant -
// the link says user has already voted on the item			
//			alert(vote_vars.already_voted_message);
			return false;
		}	
		if(vote_vars.logged_in == 'false' && $.cookie('voted-' + post_id)) {
// dont show the messages - redundant -
// the link says user has already voted on the item			
//			alert(vote_vars.already_voted_message);
			return false;
		}
		
		$.post(vote_vars.ajaxurl, data, function(response) {
			if(response == 'voted') {
				$this.addClass('voted');
				var count_wrap = $('.vote-' + post_id);	

				var count = count_wrap.text();
				count_wrap.text(parseInt(count) + 1);
				if(vote_vars.logged_in == 'false') {
					$.cookie('voted-' + post_id, 'yes', { expires: 1 });
				}
			} else {
				alert(vote_vars.error_message);
			}
		});
		return false;
	});	
});