<?php
// THIS IS THE SETTINGS PAGE FOR LOGGED IN USER LOOKING
// AT HER OWN AUTHOR PAGE AND THEN CLICKS SETTINGS
// USER SHOULDNT BE ABLE TO GET HERE UNLESS IT IS
// HER SETTINGS PAGE

//print_r($_POST);

// TODO
// url field seems to accept any chars and any format ??


$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

$user_role = reset( $profileuser->roles );
if ( is_multisite() && empty( $user_role ) ) {
$user_role = 'subscriber';
}

$user_can_edit = false;
foreach ( array( 'posts', 'pages' ) as $post_cap )
$user_can_edit |= current_user_can( "edit_$post_cap" );

$nh_errors = $theme_my_login->errors;
$nh_error_keys = getL2Keys($nh_errors);


// VIEWER INFO
global $current_user;
get_currentuserinfo();
$viewer_id = $current_user->ID;
$viewer = get_userdata($viewer_id);
?>
<div id="content">
	<div id="page-register">
		<p class="backto"><a href="<?php echo $app_url;?>/author/<?php echo $viewer->display_name;?>" title="Back to your profile">&#60; back to your Profile</a>
		</p>
		<h3 class="page-title profile">Edit Your Settings</h3>

		<div class="login profile" id="theme-my-login<?php $template->the_instance(); ?>">

<?php $template->the_action_template_message( 'profile' ); ?>
<?php 
//$template->the_errors();
// replaced this with above
// so that profile update and errors don't appear together
?>

<?php
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
				<span class="help-block">Your username cannot be changed.</span>
			</div>
	
			<div class="form-item">
				<label class="nh-form-label" for="first_name"><?php _e( 'First Name', 'theme-my-login' ) ?></label>
				<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $profileuser->first_name ) ?>" class="regular-text" />				
				<span class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "empty_first_name" OR $key == "invalid_first_name" OR $key == "maxlength_first_name") { echo 'nh-error'; }} ?>">First name is publicly visible. You can use letters, spaces, hyphens, and apostrophes up to 16 characters long.</span>
			</div>

			<div class="form-item">
				<label class="nh-form-label" for="last_name"><?php _e( 'Last Name', 'theme-my-login' ) ?></label>
				<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $profileuser->last_name ) ?>" class="regular-text" />
				<span class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "empty_last_name" OR $key == "invalid_last_name" OR $key == "maxlength_last_name") { echo 'nh-error'; }} ?>">Last name is publicly visible. You can use letters, spaces, hyphens, and apostrophes up to 30 characters long.</span>
			</div>

			<div class="form-item">
				<label class="nh-form-label" for="email"><?php _e( 'Email Address', 'theme-my-login' ); ?></label>
				<input type="email" name="email" id="email" value="<?php echo esc_attr( $profileuser->user_email ) ?>" class="regular-text" />				
				<span class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "empty_email" OR $key == "invalid_email" OR $key == "email_exists") { echo 'nh-error'; }} ?>">A valid email address is required. Your email is not visible to other users.</span>
			</div>

			<!--div class="break break-table"></div-->

<?php
$show_password_fields = apply_filters( 'show_password_fields', true, $profileuser );
if ( $show_password_fields ) :
		?>
			<div class="form-item">
				<label class="nh-form-label" for="pass1"><?php _e( 'New Password', 'theme-my-login' ); ?></label>
				<input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" />
				<span class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "pass") { echo 'nh-error'; }} ?>">Enter a new password to change your password. Otherwise leave blank.</span>
			</div>

			<div class="form-item">			
				<label class="nh-form-label">Re-enter Password</label>
				<input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" />
				<span class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "pass") { echo 'nh-error'; }} ?>">Type your new password again.</span>
			</div>

			<div class="form-item">				
				<label class="nh-form-label">Password Strength</label>		
				<div style="margin-top:.25em !important;" id="pass-strength-result"><?php _e( 'Strength indicator', 'theme-my-login' ); ?></div>
					<span class="help-block">Your password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ and &amp;.</span>																	
<?php endif; ?>				
			</div>

			<div class="form-item">
				<label class="nh-form-label" for="description"><?php _e( 'About You', 'theme-my-login' ); ?></label>
				<textarea style="width:25em;" class="profile" name="description" id="description" rows="6" cols="30"><?php echo esc_html( $profileuser->description ); ?></textarea>
				<span class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "maxlength_description") { echo 'nh-error'; }} ?>"><span>optional - </span> This description is publicly visible, so share a little information about yourself. The character limit is 300 characters.</span>
			</div>

			<div class="form-item">
				<label class="nh-form-label" for="url"><?php _e( 'Website', 'theme-my-login' ) ?></label>
				<input type="text" name="url" id="url" value="<?php echo esc_attr( $profileuser->user_url ) ?>" class="regular-text code profile" />
				<span class="help-block <?php foreach ($nh_error_keys as $key) { if ($key == "invalid_url") { echo 'nh-error'; }} ?>"><span>optional - </span> If you have a website, copy the URL here. Or include a link to your Facebook profile, or any other service. This URL will be publicly visible.</span>
			</div>

			<!--div class="break break-table"></div-->			

<?php do_action( 'show_user_profile', $profileuser ); ?>

<?php if ( count( $profileuser->caps ) > count( $profileuser->roles ) && apply_filters( 'additional_capabilities_display', true, $profileuser ) ) { ?>
<?php
//don't show this on the form
/*			<br class="clear" />
			<table width="99%" style="border: none;" cellspacing="2" cellpadding="3" class="editform">
			<tr>
			<th scope="row">
<?php 
//_e( 'Additional Capabilities', 'theme-my-login' ) 
// dont show this on the form
?></th>
			<td>
<?php
$output = '';
global $wp_roles;
foreach ( $profileuser->caps as $cap => $value ) {
if ( !$wp_roles->is_role( $cap ) ) {
if ( $output != '' )
$output .= ', ';
$output .= $value ? $cap : "Denied: {$cap}";
}
}
echo $output;
?>
			</td>
			</tr>
			</table>
*/			
?>		
<?php } ?>			
				
			<p id="nh-submit" class="submit reg-with-pwd">
				<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr( $current_user->ID ); ?>" />
				<input type="submit" class="button-primary submit-profile nh-btn-orange" value="<?php esc_attr_e( 'Save Settings', 'theme-my-login' ); ?>" name="submit" />
			</p>
			
		</div><!-- / page-profile-->		
	</div><!--/ page-register-->
</div><!--/ content-->
<?php //get_sidebar('login-profile'); ?>