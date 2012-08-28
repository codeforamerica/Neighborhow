<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

$nh_errors = $theme_my_login->errors->get_error_codes();
echo '<pre>nh errors<br/>';
var_dump($nh_errors);
echo '</pre>';
echo '<pre>post<br/>';
var_dump($_POST);
echo '</pre>';
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

				<input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_login' ); ?>" size="20" tabindex="10" required pattern="^[a-zA-Z0-9_-\.]{6,16}$" />

				<span class="help-block <?php foreach ($nh_errors as $key => $value) { if ($value == 'empty_username' OR $value == 'minlength_user_login' OR $value == 'maxlength_user_login' OR $value == 'invalid_username' OR $value == 'username_exists') { echo 'nh-error'; } } ?>">Make the length of your username between 6 and 16 characters. You can include letters, numbers, and these characters ( - _ and . ). Choose carefully &#8212; usernames cannot be changed later.</span>
			</div>	
		
			<div class="form-item">
				<label for="user_email<?php $template->the_instance(); ?>"><?php _e( 'E-mail', 'theme-my-login' ) ?></label>

				<input type="text" name="user_email" id="user_email<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_email' ); ?>" size="20" tabindex="20" required />

				<span class="help-block <?php foreach ($nh_errors as $key => $value) { if ($value == 'empty_email' OR $value == 'invalid_email' OR $value == 'email_exists') { echo 'nh-error'; } } ?>">Enter your email address.</span>
			</div>	

<?php
do_action( 'register_form' ); // Wordpress hook
do_action_ref_array( 'tml_register_form', array( &$template ) ); //TML hook
?>

			<div class="form-item">
				<label for="first_name<?php $template->the_instance(); ?>"><?php _e( 'First name', 'theme-my-login' ) ?></label>

				<input type="text" name="first_name" id="first_name<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'first_name' ); ?>" size="20" tabindex="40" required pattern="^[a-zA-Z '-]{1,16}$" />
				<span class="help-block <?php foreach ($nh_errors as $key => $value) { if ($value == 'empty_first_name' OR $value == 'maxlength_first_name' OR $value == 'invalid_first_name') { echo 'nh-error'; } } ?>">Enter your first name. First and last name will be publicly visible.</span>
			</div>

			<div class="form-item">
				<label for="last_name<?php $template->the_instance(); ?>"><?php _e( 'Last name', 'theme-my-login' ) ?></label>

				<input type="text" name="last_name" id="last_name<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'last_name' ); ?>" size="20" tabindex="45" required pattern="^[a-zA-Z '-]{1,30}$"  />
				<span class="help-block <?php foreach ($nh_errors as $key => $value) { if ($value == 'empty_last_name' OR $value == 'maxlength_last_name' OR $value == 'invalid_last_name') { echo 'nh-error'; } } ?>">Enter your last name. First and last name will be publicly visible.</span>
			</div>

			<p id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters( 'tml_register_passmail_template_message', __( 'A password will be e-mailed to you.', 'theme-my-login' ) ); ?></p>

			<p id="nh-submit" class="submit reg-with-pwd">
	            <input class="nh-btn-orange" type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php _e( 'Sign Up', 'theme-my-login' ); ?>" tabindex="100" />
				<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'register' ); ?>" />
				<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
	        </p>
	    	</form>

		</div>
	</div><!--/ page-register-->
</div><!--/ content-->
<?php get_sidebar('login-register'); ?>