<?php
/*
 * CUSTOM ELEMENTS actions library
 * (C) 2012 - David Cramer
 */

add_action('init', 'ce_start');
add_action('wp_loaded', 'ce_process');
add_action('admin_menu', 'ce_menus');
add_action('wp_footer', 'ce_footer');
add_action('wp_head', 'ce_header');
add_action('media_buttons', 'ce_button', 11);
add_filter('widget_text', 'do_shortcode');

add_shortcode('celement', 'ce_doShortcode');
$Elements = get_option('CE_ELEMENTS');
if(!empty($Elements)){
    if(phpversion() >= 5.2){
        foreach($Elements as $element){
            if(!empty($element['shortcode'])){
                    add_shortcode($element['shortcode'], 'ce_doShortcode');
            }
        }
    }
}

if(is_admin()){
    add_action('wp_ajax_delete_element', 'ce_deleteElement');
    add_action('wp_ajax_apply_element', 'ce_applyElement');
    add_action('wp_ajax_load_elements', 'ce_loadElements');
    add_action('wp_ajax_load_elementConfig', 'load_elementConfig');
    add_action('admin_head', 'ce_ajax_javascript');
}

?>