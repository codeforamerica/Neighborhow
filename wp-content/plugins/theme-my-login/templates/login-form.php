<?php
/*
If you would like to edit this file, copy it to your current theme's directory and edit it there.
Theme My Login will always look in your theme's directory first, before using this default template.
*/
?>
<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">
	<?php $template->the_action_template_message( 'login' ); ?>
	<?php $template->the_errors(); ?>
	<form name="loginform" id="loginform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'login' ); ?>" method="post">
		<p>
			<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Username', 'theme-my-login' ) ?></label>
			<input type="text" name="log" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'log' ); ?>" size="20" tabindex="10" />
		</p>
		<p>
			<label for="user_pass<?php $template->the_instance(); ?>"><?php _e( 'Password', 'theme-my-login' ) ?></label>
			<input type="password" name="pwd" id="user_pass<?php $template->the_instance(); ?>" class="input" value="" size="20" tabindex="20" />
		</p>
<?php
do_action( 'login_form' ); // Wordpress hook
do_action_ref_array( 'tml_login_form', array( &$template ) ); // TML hook
?>
		<p class="forgetmenot">
			<input name="rememberme" type="checkbox" id="rememberme<?php $template->the_instance(); ?>" value="forever" tabindex="90" />
			<label for="rememberme<?php $template->the_instance(); ?>"><?php _e( 'Remember Me', 'theme-my-login' ); ?></label>
		</p>
		<p class="submit">
			<input type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php _e( 'Log In', 'theme-my-login' ); ?>" tabindex="100" />
			<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'login' ); ?>" />
			<input type="hidden" name="testcookie" value="1" />
			<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
		</p>
	</form>
	<?php $template->the_action_links( array( 'login' => false ) ); ?>
</div>
