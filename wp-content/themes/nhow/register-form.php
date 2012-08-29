<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

$nh_errors = $theme_my_login->errors;
$value = getL2Keys($nh_errors);

//print_r($_POST);

?>
<div id="content">
	<div id="page-register">
		<h3 class="page-title">Sign Up for Neighborhow</h3>
			
		<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">
<?php $template->the_action_template_message( '' ); ?>
<?php $template->the_errors(); ?>
    
			<form class="nh-register form-horizontal" name="registerform" id="registerform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'register' ); ?>" method="post">				
				
			<div class="form-item">
				<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Username', 'theme-my-login' ) ?></label>

				<input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_login' ); ?>" size="20" tabindex="10" required />

				<span class="help-block <?php foreach ($value as $key) { if ($key == "empty_username" OR $key == "minlength_user_login" OR $key == "maxlength_user_login" OR $key == "invalid_username" OR $key == "username_exists") { echo 'nh-error'; }} ?>">Make the length of your username between 6 and 16 characters. You can include letters, numbers, hyphen, dash, and apostrophe. Choose carefully &#8212; usernames cannot be changed later.</span>
			</div>
			
			<div class="form-item">
				<label for="first_name<?php $template->the_instance(); ?>"><?php _e( 'First name', 'theme-my-login' ) ?></label>

				<input type="text" name="first_name" id="first_name<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'first_name' ); ?>" size="20" tabindex="15" required />
				<span class="help-block <?php foreach ($value as $key) { if ($key == "empty_first_name" OR $key == "maxlength_first_name" OR $key == "invalid_first_name") { echo 'nh-error'; }} ?>">Enter your first name (max length is 16 characters). Your first name will be publicly visible.</span>
			</div>

			<div class="form-item">
				<label for="last_name<?php $template->the_instance(); ?>"><?php _e( 'Last name', 'theme-my-login' ) ?></label>

				<input type="text" name="last_name" id="last_name<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'last_name' ); ?>" size="20" tabindex="20" required  />
				<span class="help-block <?php foreach ($value as $key) { if ($key == "empty_last_name" OR $key == "maxlength_last_name" OR $key == "invalid_last_name") { echo 'nh-error'; }} ?>">Enter your last name (max length is 30 characters). Your last name will be publicly visible.</span>
			</div>				
		
			<div class="form-item">
				<label for="user_email<?php $template->the_instance(); ?>"><?php _e( 'E-mail', 'theme-my-login' ) ?></label>

				<input type="text" name="user_email" id="user_email<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_email' ); ?>" size="20" tabindex="25" required />

				<span class="help-block <?php foreach ($value as $key) { if ($key == "empty_email" OR $key == "invalid_email" OR $key == "email_exists") { echo 'nh-error'; }} ?>">Enter your email address.</span>
			</div>	

<?php
do_action( 'register_form' ); // Wordpress hook
do_action_ref_array( 'tml_register_form', array( &$template ) ); //TML hook
?>

			

			<p id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters( 'tml_register_passmail_template_message', __( 'A password will be e-mailed to you.', 'theme-my-login' ) ); ?></p>

			<p id="nh-submit" class="submit reg-with-pwd">
	            <input class="nh-btn-orange" type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php _e( 'Sign Up', 'theme-my-login' ); ?>" tabindex="40" />
				<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'register' ); ?>" />
				<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
	        </p>
	    	</form>

		</div>
	</div><!--/ page-register-->
</div><!--/ content-->
<?php get_sidebar('login-register'); ?>