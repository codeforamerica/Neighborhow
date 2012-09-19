<?php
/*
Plugin Name: NH Emails
Plugin URI: http://neighborhow.org
Description: Create / modify outgoing emails
Version: 1.0
Author: Neighborhow
Author URI: http://neighborhow.org
*/

/*
add_filter ("wp_mail_content_type", "nh_mail_content_type");
function nh_mail_content_type() {
	return "text/html";
}
	
add_filter ("wp_mail_from", "nh_mail_from");
function nh_mail_from() {
	return "information@neighborhow.org";
}
	
add_filter ("wp_mail_from_name", "nh_mail_from_name");
function nh_mail_from_name() {
	return "Neighborhow";
}

// NEW USER NOTIFICATION - Neighborhow accounts
/*function wp_new_user_notification($user_id, $plaintext_pass) {
	$user = new WP_User($user_id);
	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);
	$email_subject = "Thanks for signing up with Neighborhow";

	ob_start();

	include("email_header.php");

	?>

	<p style="color:#555;">Your login information is shown below. Be sure and visit your <a href="http://www.neighborhow.org/author/<?php echo $user_login;?>" style="color:#4996a4;"><span style="color:#4996a4;">Profile page</span></a> to add a photo and more details about yourself.</p>

	<p style="color:#555;"><span style="font-weight:bold;">Username: <span style="color:#4D946A;"><?php echo $user_login ?></span></span>
	</p>
	<p style="color:#555;"><span style="font-weight:bold;">Password: <span style="font-weight:bold;color:#4D946A;"><?php echo $plaintext_pass ?></span></span>
	</p>

	<p style="color:#555;">Neighborhow is an experiment, and we&#39;d like your feedback. So get involved by trying it out and giving your feedback. You can also vote on which features and content you&#39;d like to see next, or submit a story about a success you&#39;ve had in your neighborhood. And feel free to get in touch with us if you have additional feedback or questions!</p>

	<p style="color:#555;">Get started by exploring some of the <a href="http://www.neighborhow.org/guides" style="color:#4996a4;"><span style="color:#4996a4;">Neighborhow Guides</span></a> and then <a href="http://www.neighborhow.org/create-guide" style="color:#4996a4;"><span style="color:#4996a4;">create one</span></a> of your own!</p>

	<?php
	include("email_footer.php");
	$message = ob_get_contents();
	ob_end_clean();
	wp_mail($user_email, $email_subject, $message);
}


// PASSWORD RETRIEVAL
add_filter ("retrieve_password_title", "nh_retrieve_password_title");
function nh_retrieve_password_title() {
	return "Reset Password for Neighborhow";
}

add_filter ("retrieve_password_message", "nh_retrieve_password_message");
function nh_retrieve_password_message($content, $key) {
	global $wpdb;
	$user_login = $wpdb->get_var("SELECT user_login FROM $wpdb-<users WHERE user_activation_key = '$key'");
	
	ob_start();
	$email_subject = imp_retrieve_password_title();
	include("email_header.php");
	?>
	
	<p style="color:#555;">You asked to reset your Neighborhow password.</p>

	<p style="color:#555;">To reset your password, visit the following link. If you did not request a password reset, otherwise just ignore this email and your current password will remain in place.
	<br>
	<?php echo wp_login_url("url") ?>?action=rp&key=<?php echo $key ?>&login=<?php echo $user_login ?>			
	<p>
	
	<?php
	include("email_footer.php");
	$message = ob_get_contents();
	ob_end_clean();
	return $message;
}
*/



// STOP HERE
?>