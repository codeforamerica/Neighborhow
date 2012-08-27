<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div id="content">
	<div id="page-register">
		<h3 class="page-title">Sign In to Neighborhow</h3>
		
		<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">
<?php $template->the_action_template_message( 'login' ); ?>
<?php $template->the_errors(); ?>

<form class="nh-signin form-horizontal" name="loginform" id="loginform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'login' ); ?>" method="post">
	
	<div class="form-item">
		<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Username (or email address)', 'theme-my-login' ) ?></label>

		<input placeholder="" type="text" name="log" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'log' ); ?>" size="20" tabindex="10" required pattern="^[a-zA-Z0-9-_\.]{6,16}$"/>
		<span class="help-block">To sign in, enter your username or the email address you registered with.</span>
	</div>	
	
	<div class="form-item">
		<label for="user_pass<?php $template->the_instance(); ?>"><?php _e( 'Password', 'theme-my-login' ) ?></label>

		<input type="password" name="pwd" id="user_pass<?php $template->the_instance(); ?>" class="input" value="" size="20" tabindex="20" required pattern="^[a-zA-Z0-9-_!?$%^\&)\.]{7,16}$"/>
		<span class="help-block">Enter your password. If you&#39;ve forgotten your password, use the link on the right to reset it.</span>
	</div>

<?php
do_action( 'login_form' ); // Wordpress hook
do_action_ref_array( 'tml_login_form', array( &$template ) ); // TML hook
?>
	<div class="form-item forgetmenot">
			<input name="rememberme" type="checkbox" id="rememberme<?php $template->the_instance(); ?>" value="forever" tabindex="90" />
			<label style="margin-top:-1.2em !important;" class="checkbox" for="rememberme<?php $template->the_instance(); ?>"><?php _e( 'Keep me signed in', 'theme-my-login' ); ?></label>
			<span class="help-block" style="display:block;">When you check this box, we'll remember your login information for up to 2 weeks.</span>
		
	</div>

	<p id="nh-submit" class="submit reg-with-pwd">
		<input class="nh-btn-orange" type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php _e( 'Sign In', 'theme-my-login' ); ?>" tabindex="100" />
		<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'login' ); ?>" />
		<input type="hidden" name="testcookie" value="1" />
		<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
	</p>
</form>
		</div>
	</div><!--/ page-register-->
</div><!--/ content-->
<?php get_sidebar('login-signin'); ?>