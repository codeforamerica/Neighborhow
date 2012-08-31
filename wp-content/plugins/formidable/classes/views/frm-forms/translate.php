<div id="form_settings_page" class="wrap">
    <div class="frmicon icon32"><br/></div>
    <h2><?php _e('Translate Form', 'formidable') ?>
        <a href="?page=formidable-new" class="button add-new-h2"><?php _e('Add New', 'formidable'); ?></a>
    </h2>
    <?php require(FRM_VIEWS_PATH.'/shared/errors.php'); ?>
    <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
    <div class="alignright">
        <div id="postbox-container-1">
            <?php if(!isset($hide_preview) or !$hide_preview){ 
                if (!$values['is_template']){ ?>
            <p class="howto" style="margin-top:0;"><?php _e('Insert into a post, page or text widget', 'formidable') ?>
            <input type="text" style="text-align:center;font-weight:bold;width:100%;" readonly="true" onclick="this.select();" onfocus="this.select();" value="[formidable id=<?php echo $id; ?>]" /></p>
            <?php } ?>

            <p class="frm_orange"><a href="<?php echo FrmFormsHelper::get_direct_link($values['form_key']); ?>" target="_blank"><?php _e('Preview', 'formidable') ?></a>
            <?php global $frm_settings; 
                if ($frm_settings->preview_page_id > 0){ ?>
                <?php _e('or', 'formidable') ?> 
                <a href="<?php echo add_query_arg('form', $values['form_key'], get_permalink($frm_settings->preview_page_id)) ?>" target="_blank"><?php _e('Preview in Current Theme', 'formidable') ?></a>
            <?php } ?>
            </p>
            <?php
            } ?>
        </div>
    </div>
    <div class="alignleft">
    <?php FrmAppController::get_form_nav($id, true); ?>
    </div>
    
<form method="post">     
    <p style="clear:left;">        
        <input type="submit" value="<?php _e('Update', 'formidable') ?>" class="button-primary" />
        <?php _e('or', 'formidable') ?>
        <a class="button-secondary cancel" href="<?php echo admin_url('admin.php?page=formidable') ?>&amp;frm_action=settings&amp;id=<?php echo $id ?>"><?php _e('Cancel', 'formidable') ?></a> 
        <span style="margin-left:8px;">
        <?php FrmFormsHelper::forms_dropdown('frm_switcher', '', __('Switch Form', 'formidable'), false, "frmAddNewForm(this.value,'translate')"); ?>
        </span>
    </p>
    
    <div class="clear"></div> 

    <div id="poststuff" class="metabox-holder">
    <div id="post-body">
    
            <?php if(!$frmpro_is_installed){ ?>
            <p><?php _e('Go to the WPML String translation page to translate this form. The pro version also provides an easier interface for translations.', 'formidable') ?></p>
           <a href="<?php echo admin_url('admin.php?page=wpml-string-translation%2Fmenu%2Fstring-translation.php&context=formidable') ?>" class="button-secondary"><?php _e('Translate now', 'formidable') ?></a>
           <?php } ?>
           
           <?php do_action('frm_translation_page', $form, $action); ?>

    </div>

    </div>
    <p>        
        <input type="submit" value="<?php _e('Update', 'formidable') ?>" class="button-primary" />
        <?php _e('or', 'formidable') ?>
        <a class="button-secondary cancel" href="<?php echo admin_url('admin.php?page=formidable') ?>&amp;frm_action=settings&amp;id=<?php echo $id ?>"><?php _e('Cancel', 'formidable') ?></a>
    </p>
    </form>

</div>