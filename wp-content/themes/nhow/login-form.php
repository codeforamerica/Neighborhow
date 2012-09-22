<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

$nh_errors = $theme_my_login->errors;
$old_value = getL2Keys($nh_errors);
$value = (string) $old_value[0];
?>
<div id="content">
	<div id="page-register">
		<h3 class="page-title">Sign In to Neighborhow</h3>
		
		<div class="mywsl"><?php do_action( 'wordpress_social_login' ); ?></div><h5 class="wsl-label">If you created an account with Facebook or Twitter</h5>
			
		<h5 class="nhow-label">If you created a Neighborhow account</h5>
		
<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">

<?php $template->the_action_template_message( 'login' ); ?>
<?php $template->the_errors(); ?>

	<form class="nh-signin form-horizontal" name="loginform" id="loginform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'login' ); ?>" method="post">

		<div class="form-item">
			<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Username (or email address)', 'theme-my-login' ) ?></label>

			<input placeholder="" type="text" name="log" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'log' ); ?>" size="20" tabindex="10" required />
			<div class="help-block <?php if ($value == 'authentication_failed') { echo 'nh-error'; } ?>"><span class="txt-help">To sign in, enter your username or the email address you registered with.</span>
			</div>
		</div>
		
		<div class="form-item">
			<label for="user_pass<?php $template->the_instance(); ?>"><?php _e( 'Password', 'theme-my-login' ) ?></label>

			<input type="password" name="pwd" id="user_pass<?php $template->the_instance(); ?>" class="input" value="" size="20" tabindex="20" required />
			<div class="help-block <?php if ($value == 'incorrect_password' OR $value == 'empty_password') { echo 'nh-error'; } ?>"><span class="txt-help">Enter your password. If you&#39;ve forgotten your password, use the link on the right to reset it.</span>
			</div>
		</div>
		
<?php
do_action( 'login_form' ); // Wordpress hook
do_action_ref_array( 'tml_login_form', array( &$template ) ); // TML hook
?>

		<div class="form-item forgetmenot">
				<input name="rememberme" type="checkbox" id="rememberme<?php $template->the_instance(); ?>" value="forever" tabindex="90" />
				<label style="" class="checkbox" for="rememberme<?php $template->the_instance(); ?>"><?php _e( 'Keep me signed in', 'theme-my-login' ); ?></label>
				<div class="help-block"><span class="txt-help">When you check this box, we'll remember your login information for up to 2 weeks.</span>
			</div>
		</div>

		<p id="nh-submit" class="submit reg-with-pwd">
<input class="nh-btn-blue" type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php _e( 'Sign In', 'theme-my-login' ); ?>" tabindex="100" />
<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'login' ); ?>" />
<input type="hidden" name="testcookie" value="1" />
<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
		</p>
	</form>
		</div><!--/ login-->
	</div><!--/ page-register-->
</div><!--/ content-->
<?php get_sidebar('login-signin'); ?>