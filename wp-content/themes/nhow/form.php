<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

$nh_errors = $theme_my_login->errors->get_error_codes();
var_dump($nh_errors);
echo '<br/><br/>';
var_dump($_POST);
?>
<div id="content">
	<div id="page-register">
		<h3 class="page-title">Sign Up for Neighborhow</h3>
	
		<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">
<?php $template->the_action_template_message(''); ?>
<?php $template->the_errors(); ?>
	
<form class="nh-register form-horizontal" name="registerform" id="registerform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'register' ); ?>" method="post">

<div class="form-item">
	<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Username', 'theme-my-login' ) ?></label>

	<input placeholder="" type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_login' ); ?>" size="20" tabindex="5" required />

	<span class="help-block">Make the length of your username between 6 and 16 characters. You can include letters, numbers, and these characters ( - _ and . ). Choose carefully &#8212; usernames cannot be changed later.</span>
</div>

<div class="form-item">
	<label for="user_email<?php $template->the_instance(); ?>"><?php _e( 'Email address', 'theme-my-login' ) ?></label>

	<input placeholder="" type="email" name="user_email" id="user_email<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_email' ); ?>" size="20" tabindex="10" required />

	<span class="help-block">Enter your email address.</span>
</div>

<?php
do_action( 'register_form' ); // Wordpress hook
do_action_ref_array( 'tml_register_form', array( &$template ) ); //TML hook
?>

<div class="form-item">
	<label for="first_name<?php $template->the_instance(); ?>"><?php _e( 'First name', 'theme-my-login' ) ?></label>

	<input placeholder="" type="text" name="first_name" id="first_name<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'first_name' ); ?>" size="20" tabindex="40" required pattern="" />
	<span class="help-block">Enter your first name. First and last name will be publicly visible.</span>
</div>

<div class="form-item">
	<label for="last_name<?php $template->the_instance(); ?>"><?php _e( 'Last name', 'theme-my-login' ) ?></label>

	<input placeholder="" type="text" name="last_name" id="last_name<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'last_name' ); ?>" size="20" tabindex="45" required pattern=""  />
	<span class="help-block">Enter your last name. First and last name will be publicly visible.</span>
</div>

<p id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters( 'tml_register_passmail_template_message', __( 'A password will be e-mailed to you.', 'theme-my-login' ) ); ?>
</p>

<p id="nh-submit" class="submit reg-with-pwd">
	<input class="nh-btn-orange" type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php _e( 'Sign Up', 'theme-my-login' ); ?>" tabindex="70" />
	<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'register' ); ?>" />
	<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
</p>
</form>

		</div>
	</div><!--/ page-register-->
</div><!--/ content-->
<?php get_sidebar('login-register'); ?>