<?php

/*********************************************************
* To Customize, replace the following
*	- fplus_ 			-> a unique function prefix
*	- Formidable Plus 	-> the name of the plugin
* 	- formidable-plus 	-> the plugin slug at the mothership
*	- $mothership 	-> the actual mothership
*********************************************************/

// add the admin options page
add_action('admin_menu', 'fplus_plugin_admin_add_page');
function fplus_plugin_admin_add_page() {
	add_options_page('Formidable Plus', 'Formidable Plus', 'manage_options', 'formidable-plus', 'fplus_plugin_options_page');
}

// display the admin options page
function fplus_plugin_options_page() {
	?>
	<div>
	<h2>Formidable Plus</h2>
	<form action="options.php" method="post">
	<?php settings_fields('fplus_plugin_options'); ?>
	<?php do_settings_sections('formidable-plus'); ?>

	<p class="submit">
	<input id="submit" class="button-primary" type="submit" value="Save Changes" name="submit">
	</p>
	</form></div>
<?php
}

// add the admin settings and such
add_action('admin_init', 'fplus_plugin_admin_init');
function fplus_plugin_admin_init(){
	register_setting( 'fplus_plugin_options', 'fplus_plugin_options', 'fplus_plugin_options_validate' );
	add_settings_section('fplus_plugin_main', 'Top Quark Credentials', 'fplus_plugin_section_text', 'formidable-plus');
	add_settings_field('topquark_email', 'Email:', 'fplus_plugin_setting_string', 'formidable-plus', 'fplus_plugin_main','topquark_email');
	add_settings_field('topquark_password', 'Password:', 'fplus_plugin_setting_string', 'formidable-plus', 'fplus_plugin_main','topquark_password');
}

function fplus_plugin_options_validate($input){
	$valid = array('topquark_password' => '','topquark_email' => '');
	$input = wp_parse_args($input,$valid);
	return $input;
}

function fplus_plugin_section_text() {
	echo '<p>To receive updates to this plugin, you must have purchased Formidable Plus from TopQuark.com.  Please enter your Top Quark email/password below, or visit <a href="http://topquark.com">TopQuark.com</a> to purchase.</p>';
	$options = get_option('fplus_plugin_options');
	if (is_array($options) and isset($options['topquark_password']) and isset($options['topquark_email'])){
		// Verify against the mothership
		$mothership = 'http://topquark.com';
		$options['topquark_password'] = md5($options['topquark_password']); // Don't send password plain text
		$options['topquark_request'] = 'verify';
		$options['topquark_plugin'] = 'formidable-plus';
		$url = $mothership.'/?'.http_build_query($options);
		$result = wp_remote_get($url);
		if (!is_array($result) or !is_array($result['response']) or $result['response']['code'] != 200){
			$note = "Unable to communicate with TopQuark.com to verify your settings.";
			echo sprintf('<div class="error fade"><p>%s</p></div>', $note);
		}
		elseif ($result['body'] != 'verified'){
			$note = "Your credentials could not be verified.<br/>";
			$note.= $result['body'];
			echo sprintf('<div class="error fade"><p>%s</p></div>', $note);
		}
		else{
			$note = "Awesome!  You're good to go!";
			echo sprintf('<div class="updated fade"><p>%s</p></div>', $note);
		}
	}
} 

function fplus_plugin_setting_string($what) {
	$options = get_option('fplus_plugin_options');
	$option = $options[$what];
	$name = $id = $what;
	switch($what){
	case 'topquark_password':
		$type = 'password';
		break;
	default:
		$type = 'text';
		break;
	}
	echo "<input id=\"$id\" name=\"fplus_plugin_options[$name]\" type=\"$type\" value=\"".esc_attr($option)."\" />";
}

add_filter('puc_request_info_query_args-formidable-plus','fplus_request_info_query_args');
function fplus_request_info_query_args($queryArgs){
	$options = get_option('fplus_plugin_options');
	$options['topquark_password'] = md5($options['topquark_password']); // Don't send password plain text
	$queryArgs+=$options;
	return $queryArgs;
}

?>
