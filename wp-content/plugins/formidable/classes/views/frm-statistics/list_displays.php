<div class="wrap">
    <div id="icon-themes" class="icon32"><br/></div>
    <h2><?php _e('Custom Displays', 'formidable'); ?></h2>

    <?php 
        require(FRM_VIEWS_PATH.'/shared/errors.php');
        require(FRM_VIEWS_PATH.'/shared/nav.php');
        if($form) FrmAppController::get_form_nav($form);
        FrmAppController::update_message('display collected data in lists, calendars, and other formats'); 
    ?>

    <img src="http://fp.strategy11.com/images/custom-display-settings.png" alt="Display" style="max-width:100%"/>

</div>