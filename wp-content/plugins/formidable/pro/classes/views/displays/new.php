<div class="wrap columns-2">
    <div id="icon-themes" class="icon32"><br/></div>
    <h2><?php _e('Add New Custom Display', 'formidable') ?></h2>

    <?php include(FRM_VIEWS_PATH.'/shared/errors.php'); ?>
    
    <p class="alignright"><a href="http://formidablepro.com/display-your-form-data/" target="blank" title="Get Help"><img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" width="14px"/></a> <a href="http://formidablepro.com/display-your-form-data/" target="blank" title="Get Help"><?php _e('Get Help with This Page', 'formidable') ?></a></p>
    <?php include(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
    
    <form method="post" >
        <input type="hidden" name="frm_action" value="create" />
        <?php wp_nonce_field('update-options'); ?>

        <?php require(FRMPRO_VIEWS_PATH .'/displays/form.php'); ?>
    </form>

</div>