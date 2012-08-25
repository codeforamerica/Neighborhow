<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<h3 class="page-title">Reset Your Neighborhow Password</h3>
<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">
<?php $template->the_action_template_message( 'lostpassword' ); ?>
<?php $template->the_errors(); ?>
<form name="lostpasswordform" id="lostpasswordform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'lostpassword' ); ?>" method="post">
	<p>
		<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Username or Email Address', 'theme-my-login' ) ?></label>
		<input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_login' ); ?>" size="20" tabindex="10" />
	</p>
<?php
do_action( 'lostpassword_form' ); // Wordpress hook
do_action_ref_array( 'tml_lostpassword_form', array( &$template ) ); // TML hook
?>
	<p class="submit reg-with-pwd">
		<input class="nh-btn-orange" type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php _e( 'Get New Password', 'theme-my-login' ); ?>" tabindex="100" />
		<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'lostpassword' ); ?>" />
		<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
	</p>
</form>
<?php $template->the_action_links( array( 'lostpassword' => false,'register' => false ) ); ?>
</div>
