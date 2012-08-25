<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<h3 class="page-title">Sign In to Neighborhow</h3>
<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">
<?php $template->the_action_template_message( 'login' ); ?>
<?php $template->the_errors(); ?>
<form name="loginform" id="loginform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'login' ); ?>" method="post">
	<p>
		<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Username (or email address)', 'theme-my-login' ) ?></label>
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
		<label for="rememberme<?php $template->the_instance(); ?>"><?php _e( 'Keep me signed in', 'theme-my-login' ); ?></label>
	</p>
	<p class="submit">
		<input class="nh-btn-orange" type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php _e( 'Sign In', 'theme-my-login' ); ?>" tabindex="100" />
		<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'login' ); ?>" />
		<input type="hidden" name="testcookie" value="1" />
		<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
	</p>
</form>
<!--p><a title="" rel="nofollow" href="<?php echo $app_url;?>/register">Sign Up</a></p>

<p><a title="" rel="nofollow" href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" title="Lost Password">Lost your password?</a></p-->
<?php $template->the_action_links( array( 'login' => false ) ); ?>
</div>