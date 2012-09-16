<?php
/**
 * Call this function when plugin is activate
 */
function wp_email_template_install(){
	update_option('a3rev_wp_email_template_version', '1.0.0');
	WP_Email_Template_Settings::set_settings_default(true);
}

update_option('a3rev_wp_email_template_plugin', 'wp_email_template');

/**
 * Load languages file
 */
function wp_email_template_init() {
	load_plugin_textdomain( 'wp_email_template', false, WP_EMAIL_TEMPLATE_FOLDER.'/languages' );
}
// Add language
add_action('init', 'wp_email_template_init');

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('WP_Email_Template_Hook_Filter', 'plugin_extra_links'), 10, 2 );
		
	// Add Admin Menu
	add_action('admin_menu', array('WP_Email_Template_Hook_Filter', 'add_menu' ), 11);
	
	add_action('wp_ajax_preview_wp_email_template', array('WP_Email_Template_Hook_Filter', 'preview_wp_email_template') );
	add_action('wp_ajax_nopriv_preview_wp_email_template', array('WP_Email_Template_Hook_Filter', 'preview_wp_email_template') );
		
	// Add marker at start of email template header from woocommerce
	add_action('woocommerce_email_header', array('WP_Email_Template_Hook_Filter', 'woo_email_header_marker_start'), 1 );
	
	// Add marker at end of email template header from woocommerce
	add_action('woocommerce_email_header', array('WP_Email_Template_Hook_Filter', 'woo_email_header_marker_end'), 100 );
	
	// Add marker at start of email template footer from woocommerce
	add_action('woocommerce_email_footer', array('WP_Email_Template_Hook_Filter', 'woo_email_footer_marker_start'), 1 );
	
	// Add marker at end of email template footer from woocommerce
	add_action('woocommerce_email_footer', array('WP_Email_Template_Hook_Filter', 'woo_email_footer_marker_end'), 100 );
		
	// Apply the email template to wp_mail of wordpress
	add_filter('wp_mail_content_type', create_function('', 'return "text/html";'), 20);
	add_filter('wp_mail', array('WP_Email_Template_Hook_Filter', 'change_wp_mail'), 20);
	
	// Include script admin plugin
	add_action('admin_head', array('WP_Email_Template_Hook_Filter', 'admin_head_scripts') );

	update_option('a3rev_wp_email_template_version', '1.0.0');
?>