<div class="wrap">
    <div class="frmicon icon32"><br/></div>
    <h2><?php echo __('Edit', 'formidable') .' '. (($values['is_template']) ? __('Template', 'formidable') : __('Form', 'formidable')); ?>
        <a href="?page=formidable-new" class="button add-new-h2"><?php _e('Add New', 'formidable'); ?></a>
    </h2>
    <?php require(FRM_VIEWS_PATH.'/shared/errors.php'); ?>
    <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
    <?php if (!$values['is_template']){ ?>
        <div class="alignleft">
            <?php FrmAppController::get_form_nav($id, true); ?>
        </div>
    <?php } 
    
        $show_preview = true;
        if(version_compare( $GLOBALS['wp_version'], '3.3.3', '<')){ ?>
    <div id="poststuff" class="metabox-holder has-right-sidebar">
    <?php   
        require(FRM_VIEWS_PATH .'/frm-forms/add_field_links.php'); 
    }else{ ?>
        <div id="poststuff">
<?php } ?>

    <div id="post-body" class="metabox-holder columns-2">
    <div id="post-body-content">
    <div class="frm_form_builder<?php echo ($values['custom_style']) ? ' with_frm_style' : ''; ?>">
    <form method="post" >
        <p style="margin-top:0;">
            <input type="submit" value="<?php _e('Update', 'formidable') ?>" class="button-primary" />
            <?php _e('or', 'formidable') ?>
            <a class="button-secondary cancel" href="?page=formidable<?php echo ($values['is_template']) ? '-templates' : ''; ?>"><?php _e('Cancel', 'formidable') ?></a>
            <span style="margin-left:8px;">
            <?php FrmFormsHelper::forms_dropdown('frm_switcher', '', __('Switch Form', 'formidable'), false, "frmAddNewForm(this.value,'edit')"); ?>
            </span>
        </p>
        
        <input type="hidden" name="frm_action" value="update" />
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <?php wp_nonce_field('update-options'); ?>

        <?php require(FRM_VIEWS_PATH.'/frm-forms/form.php'); ?>

        <p>            
            <input type="submit" value="<?php _e('Update', 'formidable') ?>" class="button-primary" />
            <?php _e('or', 'formidable') ?>
            <a class="button-secondary cancel" href="?page=formidable<?php echo ($values['is_template']) ? '-templates' : ''; ?>"><?php _e('Cancel', 'formidable') ?></a>
        </p>
    </form>
    </div>
    </div>
    <?php 
    if(version_compare( $GLOBALS['wp_version'], '3.3.2', '>'))
        require(FRM_VIEWS_PATH .'/frm-forms/add_field_links.php'); 
    ?>
    </div>
    </div>
</div>
<?php require(FRM_VIEWS_PATH .'/frm-forms/footer.php'); ?>