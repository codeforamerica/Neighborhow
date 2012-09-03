<?php

class FrmPlusAppController{
    function FrmPlusAppController(){
        add_action('frm_standalone_route', array(&$this, 'standalone_route'),14,1);
        add_action('init', array(&$this, 'front_head'),1); // needs to come before the FRMPlus one
    }
    
    function front_head(){
        $css = apply_filters('get_frmplus_stylesheet', FRMPLUS_URL .'/css/custom_theme.css');
        wp_enqueue_style('frmplus-forms', $css);
		$script = apply_filters('get_frmplus_script', FRMPLUS_URL .'/js/frm_plus.js');
        wp_enqueue_script('frmplus-scripts', $script, array('jquery'));
		if (!is_admin()){
			add_action('wp_print_scripts',array($this,'declare_ajaxurl'));
		}
    }  

    function standalone_route($controller, $action=''){
        global $frm_forms_controller;
        if($controller=='settings'){
            global $frmpro_settings;
            require(FRMPLUS_PATH .'/css/custom_theme.css.php');
		}
    }

	function & get_frmdb(){
		// The global $frmdb was introduced in Formidable > 1.02.  To get Formidable Plus working with earlier versions, I'll spoof it here for what I need
		if (!isset($this0))
		global $frmdb;
		if (!isset($frmdb)){
			$frmdb = new stdClass;
			global $frm_entry_meta;
			$frmdb->entry_metas = $frm_entry_meta->table_name;
		}
		return $frmdb;
	}
	
	function declare_ajaxurl(){
		echo '
		<script type="text/javascript">
		//<![CDATA[
		var ajaxurl = "'.admin_url('admin-ajax.php').'";
		//]]>
		</script>
		';
	}

}

?>