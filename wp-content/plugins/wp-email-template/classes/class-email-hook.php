<?php
/**
 * WP Email Template Hook Filter
 *
 * Table Of Contents
 *
 * add_menu()
 * woo_email_header_marker_start()
 * woo_email_header_marker_end()
 * woo_email_footer_marker_start()
 * woo_email_footer_marker_end()
 * preview_wp_email_template()
 * change_wp_mail()
 * admin_head_scripts()
 * admin_plugin_scripts()
 * plugin_extra_links()
 */
class WP_Email_Template_Hook_Filter{
	
	function add_menu() {
		$email_template_page = add_submenu_page( 'options-general.php',  __( 'Email Template', 'wp_email_template' ), __( 'Email Template', 'wp_email_template' ), 'manage_options', 'email_template', array('WP_Email_Template_Settings', 'display') );
		
		// Include script admin plugin
		add_action('admin_footer', array('WP_Email_Template_Hook_Filter', 'admin_plugin_scripts') );
	}
	
	function woo_email_header_marker_start($email_heading='') {
		$wp_email_template_settings = get_option('wp_email_template_settings');
		if ( !is_array($wp_email_template_settings) ) $wp_email_template_settings = array();
		
		$wp_email_template_default_settings = WP_Email_Template_Settings::get_settings_default();
		$wp_email_template_settings = array_merge($wp_email_template_default_settings, $wp_email_template_settings);
		if (isset($wp_email_template_settings['apply_for_woo_emails']) && trim(esc_attr($wp_email_template_settings['apply_for_woo_emails'])) == 'yes') {
			ob_start();
			echo '<!--WOO_EMAIL_TEMPLATE_HEADER_START-->';
		}
	}
	
	function woo_email_header_marker_end($email_heading='') {
		$wp_email_template_settings = get_option('wp_email_template_settings');
		if ( !is_array($wp_email_template_settings) ) $wp_email_template_settings = array();
		
		$wp_email_template_default_settings = WP_Email_Template_Settings::get_settings_default();
		$wp_email_template_settings = array_merge($wp_email_template_default_settings, $wp_email_template_settings);
		if (isset($wp_email_template_settings['apply_for_woo_emails']) && trim(esc_attr($wp_email_template_settings['apply_for_woo_emails'])) == 'yes') {
			echo '<!--WOO_EMAIL_TEMPLATE_HEADER_END-->';
			ob_get_clean();
			$header = WP_Email_Template_Functions::email_header($email_heading);
			
			if (isset($_REQUEST['preview_woocommerce_mail']) && $_REQUEST['preview_woocommerce_mail'] == 'true') {
				$template_notice = WP_Email_Template_Functions::apply_email_template_notice( __('Attention! You have selected to apply your WP Email Template to all WooCommerce Emails. Go to Settings in your WordPress admin sidebar > Email Template to customize this template or to reactivate the WooCommerce Email Template.', 'wp_email_template') );
				$header = str_replace('<!--EMAIL_TEMPLATE_NOTICE-->', $template_notice, $header);
			}
			
			
			echo $header;
		}
	}
	
	function woo_email_footer_marker_start() {
		$wp_email_template_settings = get_option('wp_email_template_settings');
		if ( !is_array($wp_email_template_settings) ) $wp_email_template_settings = array();
		
		$wp_email_template_default_settings = WP_Email_Template_Settings::get_settings_default();
		$wp_email_template_settings = array_merge($wp_email_template_default_settings, $wp_email_template_settings);
		if (isset($wp_email_template_settings['apply_for_woo_emails']) && trim(esc_attr($wp_email_template_settings['apply_for_woo_emails'])) == 'yes') {
			ob_start();
			echo '<!--WOO_EMAIL_TEMPLATE_FOOTER_START-->';
		}
	}
	
	function woo_email_footer_marker_end() {
		$wp_email_template_settings = get_option('wp_email_template_settings');
		if ( !is_array($wp_email_template_settings) ) $wp_email_template_settings = array();
		
		$wp_email_template_default_settings = WP_Email_Template_Settings::get_settings_default();
		$wp_email_template_settings = array_merge($wp_email_template_default_settings, $wp_email_template_settings);
		if (isset($wp_email_template_settings['apply_for_woo_emails']) && trim(esc_attr($wp_email_template_settings['apply_for_woo_emails'])) == 'yes') {
			echo '<!--WOO_EMAIL_TEMPLATE_FOOTER_END-->';
			ob_get_clean();
			echo '<!--NO_USE_EMAIL_TEMPLATE-->';
			echo WP_Email_Template_Functions::email_footer();
		}
	}
	
	function preview_wp_email_template() {
		check_ajax_referer( 'preview_wp_email_template', 'security' );
		
		$email_heading = __('Email preview', 'wp_email_template');
		
		$message = '<h2>'.__('WordPress Email sit amet', 'wp_email_template').'</h2>';
		
		$message.= wpautop(__('Ut ut est qui euismod parum. Dolor veniam tation nihil assum mazim. Possim fiant habent decima et claritatem. Erat me usus gothica laoreet consequat. Clari facer litterarum aliquam insitam dolor. 

Gothica minim lectores demonstraverunt ut soluta. Sequitur quam exerci veniam aliquip litterarum. Lius videntur nisl facilisis claritatem nunc. Praesent in iusto me tincidunt iusto. Dolore lectores sed putamus exerci est. ', 'wp_email_template') );

		echo WP_Email_Template_Functions::email_content($email_heading, $message);
		
		die();
	}
	
	function change_wp_mail($email_data=array()) {
		$email_heading = $email_data['subject'] ;
		if (stristr($email_data['message'], '<!--NO_USE_EMAIL_TEMPLATE-->') === false ) {
			$email_data['message'] = WP_Email_Template_Functions::email_content($email_heading, $email_data['message']);
		}
		
		return $email_data;
	}
		
	function admin_head_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('farbtastic');
		wp_enqueue_style('farbtastic');
	}
	
	function admin_plugin_scripts() {
		$wp_email_template_default_settings = WP_Email_Template_Settings::get_settings_default();
	?>
    <script type="text/javascript">
		(function($){		
			$(function(){	
				// Color picker
				$('.colorpick').each(function(){
					$('.colorpickdiv', $(this).parent()).farbtastic(this);
					$(this).live('click',function() {
						if ( $(this).attr('id') == "base_colour" && $(this).val() == "" ) $(this).val('<?php echo $wp_email_template_default_settings['base_colour']; ?>');
						else if ( $(this).attr('id') == "header_text_colour" && $(this).val() == "" ) $(this).val('<?php echo $wp_email_template_default_settings['header_text_colour']; ?>');
						else if ( $(this).attr('id') == "background_colour" && $(this).val() == "" ) $(this).val('<?php echo $wp_email_template_default_settings['background_colour']; ?>');
						else if ( $(this).attr('id') == "content_background_colour" && $(this).val() == "" ) $(this).val('<?php echo $wp_email_template_default_settings['content_background_colour']; ?>');
						else if ( $(this).attr('id') == "content_text_colour" && $(this).val() == "" ) $(this).val('<?php echo $wp_email_template_default_settings['content_text_colour']; ?>');
						else if ( $(this).val() == "" ) $(this).val('#ffffff');
						$('.colorpickdiv', $(this).parent() ).show();
					});	
				});
				$(document).mousedown(function(){
					$('.colorpickdiv').hide();
				});
			});		  
		})(jQuery);
	</script>
    <?php
	}
	
	function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != WP_EMAIL_TEMPLATE_NAME) {
			return $links;
		}
		$links[] = '<a href="http://docs.a3rev.com/user-guides/wordpress/wp-email-template/" target="_blank">'.__('Documentation', 'wp_email_template').'</a>';
		$links[] = '<a href="http://a3rev.com/products-page/wordpress/wp-email-template/#help" target="_blank">'.__('Support', 'wp_email_template').'</a>';
		return $links;
	}
}
?>