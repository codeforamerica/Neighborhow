<?php

class FrmPlusFormsController{
    function FrmPlusFormsController(){
        //add_filter('get_frmplus_stylesheet', array($this, 'custom_stylesheet'));
        add_action('frm_direct_link_head', array($this, 'direct_link_head'));
    }
        
    function custom_stylesheet($css){
        return FRM_SCRIPT_URL . '&controller=settings';
    }
    
    function direct_link_head(){
        echo '<script type="text/javascript">'."\n";		
		require(FRMPLUS_PATH . '/js/frm_plus.js');
		echo '</script>'."\n";
    }

}

?>