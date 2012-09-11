<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

$nh_errors = $theme_my_login->errors;
$old_value = getL2Keys($nh_errors);
$value = (string) $old_value[0];

//print_r($_POST);

?>
<div id="content">
	<div id="page-register">
		<h3 class="page-title">Reset Your Neighborhow Password</h3>

		<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">
<?php $template->the_action_template_message( '' ); ?>
<?php $template->the_errors(); ?>
	
		<form class="nh-lostpwd form-horizontal" name="lostpasswordform" id="lostpasswordform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'lostpassword' ); ?>" method="post">
			
			<div class="form-item">
				<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Username or Email Address', 'theme-my-login' ) ?></label>

				<input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_login' ); ?>" size="20" tabindex="10" required />
				
				<div class="help-block <?php if ($value == 'invalid_email' OR $value == 'invalidcombo') { echo 'nh-error'; } ?>"><span class="txt-help">Enter your username or the email address you registered with. You&#39;ll receive an email with a link to create a new password.</span>
				</div>
			</div>		
<?php
do_action( 'lostpassword_form' ); // Wordpress hook
do_action_ref_array( 'tml_lostpassword_form', array( &$template ) ); // TML hook
?>
			<p id="nh-submit" class="submit reg-with-pwd">
				<input class="nh-btn-blue" type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php _e( 'Reset My Password', 'theme-my-login' ); ?>" tabindex="100" />
				<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'lostpassword' ); ?>" />
				<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
			</p>
		</form>
		</div>
	</div><!--/ page-register-->
</div><!--/ content-->
<?php get_sidebar('login-pwd'); ?>