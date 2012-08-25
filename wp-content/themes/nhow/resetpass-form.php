<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<h3 class="page-title">Reset Your Neighborhow Password</h3>
<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">
	<?php $template->the_action_template_message( 'resetpass' ); ?>
	<?php $template->the_errors(); ?>
<form name="resetpasswordform" id="resetpasswordform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'resetpass' ); ?>" method="post">
	<div class="reset-elements">
		<p>
			<label for="pass1<?php $template->the_instance(); ?>"><?php _e( 'New password', 'theme-my-login' );?></label>
			<input autocomplete="off" name="pass1" id="pass1<?php $template->the_instance(); ?>" class="input" size="20" value="" type="password" autocomplete="off" tabindex="10" />
		</p>
		<p>
			<label for="pass2<?php $template->the_instance(); ?>"><?php _e( 'Confirm new password', 'theme-my-login' );?></label>
			<input autocomplete="off" name="pass2" id="pass2<?php $template->the_instance(); ?>" class="input" size="20" value="" type="password" autocomplete="off" tabindex="20" />
		</p>
	</div>
	<div class="reset-indicator">
		<div id="pass-strength-result" class="hide-if-no-js"><?php _e( 'Strength indicator', 'theme-my-login' ); ?></div>
		<p class="description indicator-hint"><?php _e( '<b>Hint:</b> Your password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).' ); ?>
		</p>
	</div>
<?php
do_action( 'resetpassword_form' ); // Wordpress hook
do_action_ref_array( 'tml_resetpassword_form', array( $template ) ); // TML hook
?>
	<p class="submit reset-with-pwd">
		<input class="nh-btn-orange" type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php esc_attr_e( 'Reset Password', 'theme-my-login' ); ?>" tabindex="100" />
		<input type="hidden" name="key" value="<?php $template->the_posted_value( 'key' ); ?>" />
		<input type="hidden" name="login" id="user_login" value="<?php $template->the_posted_value( 'login' ); ?>" />
		<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
	</p>
</form>
<?php $template->the_action_links( array( 'lostpassword' => false,'register' => false,'login' => false ) ); ?>
</div>
