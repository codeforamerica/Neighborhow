<div class="wrap columns-2">
    <div id="icon-themes" class="icon32"><br/></div>
    <h2><?php _e('Edit Custom Display', 'formidable'); ?>
        <?php if(current_user_can('frm_create_entries')){ ?>
        <a href="?page=formidable-entry-templates&amp;frm_action=new" class="button add-new-h2"><?php _e('Add New', 'formidable'); ?></a>
        <?php } ?>
    </h2>

    <?php include(FRM_VIEWS_PATH.'/shared/errors.php'); ?>
    
    <p class="alignright"><a href="http://formidablepro.com/display-your-form-data/" target="blank" title="Get Help"><img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" width="14px"/></a> <a href="http://formidablepro.com/display-your-form-data/" target="blank" title="Get Help"><?php _e('Get Help with This Page', 'formidable') ?></a></p>
    <?php include(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
    


    <form method="post">
        <input type="hidden" name="frm_action" value="update" />
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <?php 
        if(is_numeric($values['form_id'])) FrmAppController::get_form_nav($values['form_id'], true);
        wp_nonce_field('update-options'); 
        require(FRMPRO_VIEWS_PATH .'/displays/form.php'); 
        ?>
    </form> 

</div>