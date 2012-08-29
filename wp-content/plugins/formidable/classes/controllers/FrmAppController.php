<?php
/**
 * @package Formidable
 */
 
class FrmAppController{
    function FrmAppController(){
        add_action('admin_menu', array( &$this, 'menu' ), 1);
        add_action('admin_head', array(&$this, 'menu_css'));
        add_filter('frm_nav_array', array( &$this, 'frm_nav'), 1);
        add_filter('plugin_action_links_formidable/formidable.php', array( &$this, 'settings_link'), 10, 2 );
        add_action('after_plugin_row_formidable/formidable.php', array( &$this, 'pro_action_needed'));
        add_action('admin_notices', array( &$this, 'pro_get_started_headline'));
        add_filter('the_content', array( &$this, 'page_route' ), 10);
        add_action('init', array(&$this, 'front_head'));
        add_action('wp_footer', array(&$this, 'footer_js'), 1, 0);
        add_action('admin_init', array( &$this, 'admin_js'), 11);
        register_activation_hook(FRM_PATH.'/formidable.php', array( &$this, 'install' ));
        add_action('wp_ajax_frm_install', array(&$this, 'install') );
        add_action('wp_ajax_frm_uninstall', array(&$this, 'uninstall') );
        add_action('wp_ajax_frm_deauthorize', array(&$this, 'deauthorize') );

        // Used to process standalone requests
        add_action('init', array(&$this, 'parse_standalone_request'));
        // Update the session data
        add_action('init', array(&$this, 'referer_session'), 1);
        
        //Shortcodes
        add_shortcode('formidable', array(&$this, 'get_form_shortcode'));
        add_filter( 'widget_text', array(&$this, 'widget_text_filter'), 9 );
    }
    
    function menu(){
        global $frmpro_is_installed, $frm_settings;
        
        if(current_user_can('administrator') and !current_user_can('frm_view_forms')){
            global $current_user;
            $frm_roles = FrmAppHelper::frm_capabilities();
            foreach($frm_roles as $frm_role => $frm_role_description)
                $current_user->add_cap( $frm_role );
            unset($frm_roles);
            unset($frm_role);
            unset($frm_role_description);
        }
        
        if(current_user_can('frm_view_forms')){
            global $frm_forms_controller;
            add_object_page('Formidable', $frm_settings->menu, 'frm_view_forms', 'formidable', array($frm_forms_controller, 'route'), 'div');
        }elseif(current_user_can('frm_view_entries') and $frmpro_is_installed){
            global $frmpro_entries_controller;
            add_object_page('Formidable', $frm_settings->menu, 'frm_view_entries', 'formidable', array($frmpro_entries_controller, 'route'), 'div');
        }
    }
    
    function menu_css(){ ?>
    <style type="text/css">
    #adminmenu .toplevel_page_formidable div.wp-menu-image{background: url(<?php echo FRM_IMAGES_URL ?>/form_16.png) no-repeat center;}
    </style>    
    <?php    
    //#adminmenu .toplevel_page_formidable:hover div.wp-menu-image{background: url(<?php echo FRM_IMAGES_URL /icon_16.png) no-repeat center;}
    }
    
    function frm_nav($nav=array()){
        if(current_user_can('frm_view_forms')){
            $nav['formidable'] = __('Forms', 'formidable');
            $nav['formidable-templates'] = __('Templates', 'formidable');
        }
        return $nav;
    }
    
    function get_form_nav($id, $show_nav=false){
        $show_nav = FrmAppHelper::get_param('show_nav', $show_nav);
        
        if($show_nav)
            include(FRM_VIEWS_PATH.'/shared/form-nav.php');
    }

    // Adds a settings link to the plugins page
    function settings_link($links, $file){
        $settings = '<a href="'.admin_url('admin.php?page=formidable').'">' . __('Settings', 'formidable') . '</a>';
        array_unshift($links, $settings);
        
        return $links;
    }
    
    function pro_action_needed( $plugin ){
        global $frm_update;
       
        if( $frm_update->pro_is_authorized() and !$frm_update->pro_is_installed() ){
            if (IS_WPMU and $frm_update->pro_wpmu and !FrmAppHelper::is_super_admin())
                return;
            $frm_update->queue_update(true);
            $inst_install_url = wp_nonce_url('update.php?action=upgrade-plugin&plugin=' . $plugin, 'upgrade-plugin_' . $plugin);
    ?>
      <td colspan="3" class="plugin-update"><div class="update-message" style="-moz-border-radius:5px;border:1px solid #CC0000;; margin:5px;background-color:#FFEBE8;padding:3px 5px;"><?php printf(__('Your Formidable Pro installation isn\'t quite complete yet.<br/>%1$sAutomatically Upgrade to Enable Formidable Pro%2$s', 'formidable'), '<a href="'.$inst_install_url.'">', '</a>'); ?></div></td>
    <?php
        }
    }

    function pro_get_started_headline(){
        global $frm_update;

        // Don't display this error as we're upgrading the thing... cmon
        if(isset($_GET['action']) and $_GET['action'] == 'upgrade-plugin')
            return;
    
        if (IS_WPMU and !current_user_can('administrator'))
            return;
         
        if(!isset($_GET['activate'])){  
            global $frmpro_is_installed, $frm_db_version, $frm_ajax_url;
            $db_version = get_option('frm_db_version');
            $pro_db_version = ($frmpro_is_installed) ? get_option('frmpro_db_version') : false;
            if(((int)$db_version < (int)$frm_db_version) or ($frmpro_is_installed and (int)$pro_db_version < 15)){ //this number should match the db_version in FrmDb.php
            ?>
<div class="error" id="frm_install_message" style="padding:7px;"><?php _e('Your Formidable database needs to be updated.<br/>Please deactivate and reactivate the plugin to fix this or', 'formidable'); ?> <a id="frm_install_link" href="javascript:frm_install_now()"><?php _e('Update Now', 'formidable') ?></a></div>  
<script type="text/javascript">
function frm_install_now(){ 
jQuery('#frm_install_link').replaceWith('<img src="<?php echo FRM_IMAGES_URL; ?>/wpspin_light.gif" alt="<?php _e('Loading...', 'formidable'); ?>" />');
jQuery.ajax({type:"POST",url:"<?php echo $frm_ajax_url ?>",data:"action=frm_install",
success:function(msg){jQuery("#frm_install_message").fadeOut("slow");}
});
};
</script>
<?php
            }
        }
            
        if( $frm_update->pro_is_authorized() and !$frm_update->pro_is_installed()){
            $frm_update->queue_update(true);
            $inst_install_url = wp_nonce_url('update.php?action=upgrade-plugin&plugin=' . $frm_update->plugin_name, 'upgrade-plugin_' . $frm_update->plugin_name);
        ?>
    <div class="error" style="padding:7px;"><?php printf(__('Your Formidable Pro installation isn\'t quite complete yet.<br/>%1$sAutomatically Upgrade to Enable Formidable Pro%2$s', 'formidable'), '<a href="'.$inst_install_url.'">', '</a>'); ?></div>  
        <?php 
        }
    }
    
    function admin_js(){
        global $frm_version;
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
            
        if(isset($_GET) and isset($_GET['page']) and preg_match('/formidable*/', $_GET['page'])){
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-draggable');
            wp_enqueue_script('admin-widgets');
            wp_enqueue_style('widgets');
            wp_enqueue_script('formidable_admin', FRM_URL . '/js/formidable_admin.js', array('jquery', 'jquery-ui-draggable'), $frm_version);
            wp_enqueue_script('formidable');
            wp_enqueue_style('formidable-admin', FRM_URL. '/css/frm_admin.css', $frm_version);
            wp_enqueue_script('jquery-elastic', FRM_URL.'/js/jquery/jquery.elastic.js', array('jquery'));
            add_thickbox();
        }
    }
    
    function front_head(){
        global $frm_settings, $frm_version, $frm_db_version;
        
        if (IS_WPMU){
            global $frmpro_is_installed;
            //$frm_db_version is the version of the database we're moving to
            $old_db_version = get_option('frm_db_version');
            $pro_db_version = ($frmpro_is_installed) ? get_option('frmpro_db_version') : false;
            if(((int)$old_db_version < (int)$frm_db_version) or ($frmpro_is_installed and (int)$pro_db_version < 15))
                $this->install($old_db_version);
        }

        wp_register_script('formidable', FRM_URL . '/js/formidable.js', array('jquery'), $frm_version, true);
        wp_enqueue_script('jquery');
        
        if(!is_admin() and $frm_settings->load_style == 'all'){
            $css = apply_filters('get_frm_stylesheet', FRM_URL .'/css/frm_display.css', 'header');
            if(is_array($css)){
                foreach($css as $css_key => $file)
                    wp_enqueue_style('frm-forms'.$css_key, $file, array(), $frm_version);
                unset($css_key);
                unset($file);
            }else
                wp_enqueue_style('frm-forms', $css, array(), $frm_version);
            unset($css);
                
            global $frm_css_loaded;
            $frm_css_loaded = true;
        }
    }
    
    function footer_js($location='footer'){
        global $frm_load_css, $frm_settings, $frm_version, $frm_css_loaded, $frm_forms_loaded;

        if($frm_load_css and !is_admin() and ($frm_settings->load_style != 'none')){
            if($frm_css_loaded)
                $css = apply_filters('get_frm_stylesheet', '', $location);
            else
                $css = apply_filters('get_frm_stylesheet', FRM_URL .'/css/frm_display.css', $location);
             
            if(!empty($css)){
                echo "\n".'<script type="text/javascript">';
                if(is_array($css)){
                    foreach($css as $css_key => $file){
                        echo 'jQuery("head").append(unescape("%3Clink rel=\'stylesheet\' id=\'frm-forms'. ($css_key + $frm_css_loaded) .'-css\' href=\''. $file. '\' type=\'text/css\' media=\'all\' /%3E"));';
                        //wp_enqueue_style('frm-forms'.$css_key, $file, array(), $frm_version);
                        unset($css_key);
                        unset($file);
                    }
                }else{
                    echo 'jQuery("head").append(unescape("%3Clink rel=\'stylesheet\' id=\'frm-forms-css\' href=\''. $css. '\' type=\'text/css\' media=\'all\' /%3E"));';
                }
                unset($css);

                    //wp_enqueue_style('frm-forms', $css, array(), $frm_version);
                echo '</script>'."\n";
            }
        }

        if(!is_admin() and $location != 'header' and !empty($frm_forms_loaded)){ //load formidable js  
            global $wp_scripts;
            $wp_scripts->do_items( array('formidable') );
        }
    }
  
    function install($old_db_version=false){
        global $frmdb;
        $frmdb->upgrade($old_db_version);
    }
    
    function uninstall(){
        if(current_user_can('administrator')){
            global $frmdb;
            $frmdb->uninstall();
            return true;
            //wp_die(__('Formidable was successfully uninstalled.', 'formidable'));
        }else{
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
    }
    
    function deauthorize(){
        delete_option('frmpro-credentials');
        delete_option('frmpro-authorized');
    }
    
    // Routes for wordpress pages -- we're just replacing content here folks.
    function page_route($content){
        global $post, $frm_settings;

        if( $post && $post->ID == $frm_settings->preview_page_id && isset($_GET['form'])){
            global $frm_forms_controller;
            $content = $frm_forms_controller->page_preview();
        }

        return $content;
    }
    
    function referer_session() {
    	global $frm_siteurl, $frm_settings;
    	
    	if(!isset($frm_settings->track) or !$frm_settings->track or defined('WP_IMPORTING'))
    	    return;
    	    
    	if ( !isset($_SESSION) )
    		session_start();
    	
    	if ( !isset($_SESSION['frm_http_pages']) or !is_array($_SESSION['frm_http_pages']) )
    		$_SESSION['frm_http_pages'] = array("http://". $_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI']);
    	
    	if ( !isset($_SESSION['frm_http_referer']) or !is_array($_SESSION['frm_http_referer']) )
    		$_SESSION['frm_http_referer'] = array();
    	
    	if (!isset($_SERVER['HTTP_REFERER']) or (isset($_SERVER['HTTP_REFERER']) and (strpos($_SERVER['HTTP_REFERER'], $frm_siteurl) === false) and ! (in_array($_SERVER['HTTP_REFERER'], $_SESSION['frm_http_referer'])) )) {
    		if (! isset($_SERVER['HTTP_REFERER'])){
    		    $direct = __('Type-in or bookmark', 'formidable');
    		    if(!in_array($direct, $_SESSION['frm_http_referer']))
    			    $_SESSION['frm_http_referer'][] = $direct;
    		}else{
    			$_SESSION['frm_http_referer'][] = $_SERVER['HTTP_REFERER'];	
    		}
    	}
    	
    	if ($_SESSION['frm_http_pages'] and !empty($_SESSION['frm_http_pages']) and (end($_SESSION['frm_http_pages']) != "http://". $_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI']))
    		$_SESSION['frm_http_pages'][] = "http://". $_SERVER['SERVER_NAME']. $_SERVER['REQUEST_URI'];
    		
    	//keep the page history below 100
    	if(count($_SESSION['frm_http_pages']) > 100){
    	    foreach($_SESSION['frm_http_pages'] as $pkey => $ppage){
    	        if(count($_SESSION['frm_http_pages']) <= 100)
    	            break;
    	            
    		    unset($_SESSION['frm_http_pages'][$pkey]);
    		}
    	}
    }

    // The tight way to process standalone requests dogg...
    function parse_standalone_request(){
        $plugin     = $this->get_param('plugin');
        $action = isset($_REQUEST['frm_action']) ? 'frm_action' : 'action';
        $action = $this->get_param($action);  
        $controller = $this->get_param('controller');

        if( !empty($plugin) and $plugin == 'formidable' and !empty($controller) ){
          $this->standalone_route($controller, $action);
          exit;
        }
    }

    // Routes for standalone / ajax requests
    function standalone_route($controller, $action=''){
        global $frm_forms_controller, $frm_fields_controller;

        if($controller == 'forms' and !in_array($action, array('export', 'import', 'xml')))
            $frm_forms_controller->preview($this->get_param('form'));
        else if($controller == 'fields' and $action == 'import_choices')
             $frm_fields_controller->import_choices($this->get_param('field_id'));
        else
            do_action('frm_standalone_route', $controller, $action);
    }

    // Utility function to grab the parameter whether it's a get or post
    function get_param($param, $default=''){
        return (isset($_POST[$param]) ? $_POST[$param] : (isset($_GET[$param])?$_GET[$param]:$default));
    }


    //formidable shortcode
    function get_form_shortcode($atts){
        global $frm_entries_controller;
        extract(shortcode_atts(array('id' => '', 'key' => '', 'title' => false, 'description' => false, 'readonly' => false, 'entry_id' => false, 'fields' => array()), $atts));
        do_action('formidable_shortcode_atts', compact('id', 'key', 'title', 'description', 'readonly', 'entry_id', 'fields'));
        return $frm_entries_controller->show_form($id, $key, $title, $description); 
    }

    //filter form shortcode in text widgets
    function widget_text_filter( $content ){
    	$regex = '/\[\s*formidable\s+.*\]/';
    	return preg_replace_callback( $regex, array($this, 'widget_text_filter_callback'), $content );
    }


    function widget_text_filter_callback( $matches ) {
        return do_shortcode( $matches[0] );
    }
    
    function update_message($features){
        include(FRM_VIEWS_PATH .'/shared/update_message.php');
    }
    
    function get_postbox_class(){
        if(version_compare( $GLOBALS['wp_version'], '3.3.2', '>'))
            return 'postbox-container';
        else
            return 'inner-sidebar';
    }

}
?>