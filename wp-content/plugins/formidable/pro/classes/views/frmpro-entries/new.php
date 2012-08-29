<div class="wrap">
    <div id="icon-edit-pages" class="icon32"><br/></div>
    <h2><?php printf(__('Add New %1$s Entry', 'formidable'), stripslashes($form->name)); ?></h2>
    
    <div class="form-wrap">
        <div class="frm_forms<?php echo ($values['custom_style']) ? ' with_frm_style' : ''; ?>" id="frm_form_<?php echo $form->id ?>_container">
        <?php include(FRM_VIEWS_PATH.'/frm-entries/errors.php'); ?>

        <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>

        <form enctype="multipart/form-data" method="post"  id="form_<?php echo $form->form_key ?>">
        <?php
        if(version_compare( $GLOBALS['wp_version'], '3.3.3', '<')){ ?>
        <div id="poststuff" class="metabox-holder has-right-sidebar">
        <?php   
            require(FRMPRO_VIEWS_PATH .'/frmpro-entries/sidebar-new.php'); 
        }else{ ?>
        <div id="poststuff">
        <?php } ?>
            
            <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
            <?php $form_action = 'create'; ?>
            <?php wp_nonce_field('update-options'); ?>
            <?php if($form) FrmAppController::get_form_nav($form->id, true); ?>
            <?php require(FRM_VIEWS_PATH.'/frm-entries/form.php'); ?>
            
            <p>
                <input class="button-primary" type="submit" value="<?php echo esc_attr($submit) ?>" /> 
                <?php _e('or', 'formidable') ?>
                <a class="button-secondary cancel" href="?page=formidable-entries"><?php _e('Cancel', 'formidable') ?></a>
            </p>
            </div>
            <?php
                if(version_compare( $GLOBALS['wp_version'], '3.3.2', '>'))
                    require(FRMPRO_VIEWS_PATH .'/frmpro-entries/sidebar-new.php'); 
            ?>
            </div>
            </div>
        </form>
        </div>
    </div>

</div>