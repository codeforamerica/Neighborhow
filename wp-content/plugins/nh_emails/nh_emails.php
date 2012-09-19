<?php
/*
Plugin Name: NH Emails
Plugin URI: http://neighborhow.org
Description: Create / modify outgoing emails
Version: 1.0
Author: Neighborhow
Author URI: http://neighborhow.org
*/


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
function wp_new_user_notification($user_id, $plaintext_pass) {
	$user = new WP_User($user_id);
	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);
	$email_subject = "Thanks for signing up with Neighborhow";

	ob_start();

	include("email_header.php");

	?>

	<p>Your login information is shown below. Be sure and visit your <a href="http://www.neighborhow.org/author/<?php echo $user_login;?>" title="Your profile page">Profile page</a> to add a photo and more details about yourself.</p>

	<p>username: <span style="font-weight:bold;color:#cc6633;"><?php echo $user_login ?></span>
	</p>
	<p>password: <span style="font-weight:bold;color:#cc6633;"><?php echo $plaintext_pass ?></span>
	</p>

	<p>Neighborhow is an experiment, and we&#39;d like your feedback. So get involved by trying it out and giving your feedback. You can also vote on which features and content you&#39;d like to see next, or submit a story about a success you&#39;ve had in your neighborhood. And feel free to get in touch with us if you have additional feedback or questions!</p>

	<p>Get started by exploring some of the <a href="http://www.neighborhow.org/guides">Neighborhow Guides</a> and then <a href="http://www.neighborhow.org/create-guide">create one</a> of your own!</p>


	<?php
	include("email_footer.php");

	$message = ob_get_contents();
	ob_end_clean();

	wp_mail($user_email, $email_subject, $message);
}



// STOP HERE
?>