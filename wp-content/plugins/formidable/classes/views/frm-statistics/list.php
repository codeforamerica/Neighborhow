<div class="wrap">
    <div class="frm_report_icon icon32"><br/></div>
    <h2><?php _e('Form Statistics', 'formidable') ?></h2>

    <?php require(FRM_VIEWS_PATH.'/shared/errors.php'); ?>
    <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
    <?php if($form) FrmAppController::get_form_nav($form, true); ?>

    <?php FrmAppController::update_message('view reports and statistics on your saved entries'); ?>

    <img src="http://fp.strategy11.com/wp-content/themes/formidablepro/images/reports1.png" alt="Reports" style="max-width:100%"/>

</div>