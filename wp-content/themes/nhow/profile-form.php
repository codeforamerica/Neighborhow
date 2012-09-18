<?php
/*
echo '<pre>';
print_r($_POST);
echo '</pre>';
*/
//echo '<pre>';
//print_r($_GET);
//print_r($errors);
//echo '</pre>';

$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

$nh_errors = $theme_my_login->errors;
$nh_error_keys = getL2Keys($nh_errors);

$user_role = reset( $profileuser->roles );
if ( is_multisite() && empty( $user_role ) ) {
	$user_role = 'subscriber';
}

$user_can_edit = false;
foreach ( array( 'posts', 'pages' ) as $post_cap )
	$user_can_edit |= current_user_can( "edit_$post_cap" );
	
// VIEWER INFO
global $current_user;
get_currentuserinfo();
?>
<div id="content">
	<div id="page-register">
		<p class="backto"><a href="<?php echo $app_url;?>/author/<?php echo $current_user->user_login;?>" title="Back to your profile">&#60; back to your Profile</a>
		</p>
		<h3 class="page-title profile">Edit Your Settings</h3>
		
		<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">

<?php $template->the_action_template_message( 'profile' ); ?>
<?php
// Msgs for new users who signed up through
// Facebook or Twitter
$referer = $_SERVER['HTTP_REFERER'];
$tmp_prev = $app_url.'/register';
$is_wsl = get_user_meta($current_user->ID,'wsl_user',true);

if ($referer == $tmp_prev AND $is_wsl == "Twitter") {
	echo '<p class="message"><strong>Almost done</strong><br/>If you signed up through Twitter, we assigned you a temporary email address because Twitter doesn&#39;t share your email. So before you leave this page, please enter your real email address. Also create a Neighborhow password and enter your city name!</p>';
}
elseif ($referer == $tmp_prev AND $is_wsl == "Facebook") {
		echo '<p class="message"><strong>Almost done</strong><br/>Before you leave this page, please create a Neighborhow password and confirm your city name!</p>';
}
// Remind users who signed up via Twitter
// to enter their email address
$tmp_email = $current_user->user_email;
if (empty($tmp_email)) {
	echo '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert">×</a><strong>If you signed up through Twitter, we assigned you a temporary email address because Twitter doesn&#39;t share your email.</strong> Please update your Settings with your email address.</div>';
}
?>

<?php //$template->the_errors(); ?>
<?php 
//$template->the_errors();
// replaced the above with below error messages
// so profile update + errors dont appear on same page
if (!empty($nh_error_keys)) {
	$key_update = array_search('profile_updated',$nh_error_keys);
	$error_count = count($nh_error_keys);

	if (!is_bool($key_update)) {
		if ($error_count === 1) {
			$new_message = $nh_errors->errors['profile_updated'][0];	
			echo '<p class="message">'.$new_message.'</p>';			
		}

		elseif ($error_count > 1) {
			$tmp = $nh_error_keys;
			unset($tmp[$key_update]);
			$new_array = array_values($tmp);
			echo '<p class="error">';
			foreach ($new_array as $new_value) {
				echo $nh_errors->errors[$new_value][0].'<br/>';
			}		
			echo '</p>';								
		}
	}
	elseif (is_bool($key_update)) {
		echo '<p class="error">';
		foreach ($nh_error_keys as $new_value) {
			echo $nh_errors->errors[$new_value][0].'<br/>';
		}		
		echo '</p>';
	}
}
?>
		<form class="nh-register form-horizontal" id="your-profile" action="" method="post">
<?php wp_nonce_field( 'update-user_' . $current_user->ID ) ?>
		<p>
<input type="hidden" name="from" value="profile" />
<input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />
		</p>

<?php do_action( 'profile_personal_options', $profileuser ); ?>

		<div class="form-item">
			<label class="nh-form-label" for="user_login"><?php _e( 'Username', 'theme-my-login' ); ?></label>
			<input type="text" name="user_login" id="user_login" value="<?php echo esc_attr( $profileuser->user_login ); ?>" disabled="disabled" class="regular-text" />
			<div class="help-block"><span class="txt-help">Your username cannot be changed.</span>
			</div>
		</div>
		
		<div class="form-item">
			<label class="nh-form-label" for="first_name"><?php _e( 'First Name', 'theme-my-login' ) ?></label>
			<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $profileuser->first_name ) ?>" class="regular-text" tabindex="10" />				
			<div class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "empty_first_name" OR $key == "invalid_first_name" OR $key == "maxlength_first_name") { echo 'nh-error'; }} ?>"><span class="txt-help">First name is publicly visible. You can use letters, spaces, and dash, and apostrophes up to 16 characters long.</span></div>
		</div>
		
		<div class="form-item">
			<label class="nh-form-label" for="last_name"><?php _e( 'Last Name', 'theme-my-login' ) ?></label>
			<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $profileuser->last_name ) ?>" class="regular-text" tabindex="15" />
			<div class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "empty_last_name" OR $key == "invalid_last_name" OR $key == "maxlength_last_name") { echo 'nh-error'; }} ?>"><span class="txt-help">Last name is publicly visible. You can use letters, spaces, hyphens, and apostrophes up to 16 characters long.</span>
			</div>
		</div>	
		
		<div class="form-item">
			<label class="nh-form-label" for="email"><?php _e( 'Email Address', 'theme-my-login' ); ?></label>
			<input type="email" name="email" id="email" value="<?php echo esc_attr( $profileuser->user_email ) ?>" class="regular-text" tabindex="20" />				
			<div class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "empty_email" OR $key == "invalid_email" OR $key == "email_exists") { echo 'nh-error'; }} ?>"><span class="txt-help">A valid email address is required. Your email is not visible to other users.</span>
			</div>
		</div>			

<?php
$show_password_fields = apply_filters( 'show_password_fields', true, $profileuser );
if ( $show_password_fields ) :
		?>
			<div class="form-item">
				<label class="nh-form-label" for="pass1"><?php _e( 'Password', 'theme-my-login' ); ?></label>
				<input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" tabindex="25" />
				<div class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "pass") { echo 'nh-error'; }} ?>"><span class="txt-help">Enter a new password to change your password. Otherwise leave blank.</span>
				</div>
			</div>

			<div class="form-item">			
				<label class="nh-form-label">Confirm Password</label>
				<input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" tabindex="30" />
				<div class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "pass") { echo 'nh-error'; }} ?>"><span class="txt-help">Type your new password again.</span>
				</div>
			</div>

			<div class="form-item">				
				<label class="nh-form-label">Password Strength</label>		
				<div id="pass-strength-result" class="hide-if-no-js"><?php _e( 'Strength indicator', 'theme-my-login' ); ?></div>
				<div class="help-block"><span class="txt-help">Your password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ and &amp;.</span>			
				</div>														
			</div>			
<?php endif; ?>			
	
			<div class="form-item">
				<label class="nh-form-label" for="description"><?php _e( 'About You (or Your Organization)', 'theme-my-login' ); ?></label>
				<textarea class="profile" name="description" id="description" rows="6" cols="30" tabindex="35"><?php echo esc_html( $profileuser->description ); ?></textarea>
				<div class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "maxlength_description") { echo 'nh-error'; }} ?>"><span class="txt-help"><span>optional - </span> This description is publicly visible, so share a little information about yourself. The character limit is 200 characters.</span>
				</div>
			</div>	
			
			<div class="form-item">
				<label class="nh-form-label" for="url"><?php _e( 'Website', 'theme-my-login' ) ?></label>
				<input type="url" name="url" id="url" value="<?php echo esc_attr( $profileuser->user_url ) ?>" class="regular-text code profile" tabindex="40" />
				<div class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "invalid_url") { echo 'nh-error'; }} ?>"><span class="txt-help"><span>optional - </span> If you have a website, copy the URL here. Or include a link to your Facebook profile, or any other service. This URL will be publicly visible.</span>
				</div>
			</div>	

<?php
// AVATAR fields are in Simple Local Avatars plugin file
// CITY fields are in Theme My Login - 
// the custom theme file in main plugins folder
// USER ORG field is in same TML file
?>
<?php
do_action( 'show_user_profile', $profileuser );
?>

<?php if ( count( $profileuser->caps ) > count( $profileuser->roles ) && apply_filters( 'additional_capabilities_display', true, $profileuser ) ) { ?>
<?php
// removed additional capabilities display
// from default TML profile template			
?>	
<?php } ?>	
		<p id="nh-submit" class="submit reg-with-pwd">
<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $current_user->ID ); ?>" />
<input class="nh-btn-blue" type="submit" class="button-primary" value="<?php esc_attr_e( 'Update Settings', 'theme-my-login' ); ?>" name="submit" />
		</p>
		</form>	
		</div><!-- / login profile-->		
	</div><!--/ page-register-->
</div><!--/ content-->
<?php get_sidebar('login-profile'); ?>