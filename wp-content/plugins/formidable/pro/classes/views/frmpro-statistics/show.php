<div class="wrap frm_charts">
    <div class="frm_report_icon icon32"><br/></div>
    <h2><?php echo (isset($form)) ? stripslashes($form->name) .' ' : ''; _e('Form Reports', 'formidable') ?></h2>

    <?php require(FRM_VIEWS_PATH.'/shared/errors.php'); ?>
    <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
    <?php if (!isset($form)){ ?>
        <div id="menu-management" class="clear nav-menus-php" style="margin-top:30px;">
            <div class="menu-edit" style="width:300px;">
            <div id="nav-menu-header"><div class="major-publishing-actions" style="padding:8px 0;">
                <div style="font-size:15px;background:transparent;" class="search"><?php _e('Go to Report', 'formidable') ?></div>
            </div></div>

            <form method="get">
                <div id="post-body">
                <p><?php _e('Select a report to view.', 'formidable'); ?></p>
                <input type="hidden" name="frm_action" value="show" />
                <input type="hidden" name="page" value="formidable-reports" />
                <?php FrmFormsHelper::forms_dropdown('form', '', false); ?><br/>
                </div>
                <div id="nav-menu-footer">
                <div class="major-publishing-actions"><input type="submit" class="button-primary" value="<?php _e('Go', 'formidable') ?>" /></div>

                <div class="clear"></div>
                </div>
            </form>
            </div>

        </div>
    <?php }else{
            FrmAppController::get_form_nav($form->id, true);
    ?>
        <form method="get" class="frm_no_print">
            <input type="hidden" name="frm_action" value="show" />
            <input type="hidden" name="page" value="formidable-reports" />
            <p><?php FrmFormsHelper::forms_dropdown('form', '', __('Switch Form', 'formidable')); ?>
            <input type="submit" class="button-secondary" value="<?php _e('Go', 'formidable') ?>" /></p>
        </form>

        <div id="chart_time"></div>
        <div id="img_chart_time" class="frm_print_graph"></div>
        <?php foreach ($fields as $field){ ?>
            <div style="margin-top:25px;">
            <div class="alignleft"><div id="chart_<?php echo $field->id ?>"></div>
                <div id="img_chart_<?php echo $field->id ?>" class="frm_print_graph"></div>
            </div>
            <div style="padding:10px; margin-top:40px;">
                <p><?php _e('Response Count', 'formidable') ?>: <?php echo FrmProFieldsHelper::get_field_stats($field->id, 'count'); ?></p>
            <?php if(in_array($field->type, array('number', 'hidden'))){ ?>
            <p><?php _e('Total', 'formidable') ?>: <?php echo FrmProFieldsHelper::get_field_stats($field->id); ?></p>
            <p><?php _e('Average', 'formidable') ?>: <?php echo FrmProFieldsHelper::get_field_stats($field->id, 'average'); ?></p>
            <p><?php _e('Median', 'formidable') ?>: <?php echo FrmProFieldsHelper::get_field_stats($field->id, 'median'); ?></p>
            <?php }else if($field->type == 'user_id'){ 
                $user_ids = $wpdb->get_col("SELECT ID FROM $wpdb->users ORDER BY display_name ASC");
                $submitted_user_ids = FrmEntryMeta::get_entry_metas_for_field($field->id, '', '', false, true);
                $not_submitted = array_diff($user_ids, $submitted_user_ids); ?>
            <p><?php _e('Percent of users submitted', 'formidable') ?>: <?php echo round((count($submitted_user_ids) / count($user_ids)) *100, 2) ?>%</p>
            <form action="<?php echo admin_url('user-edit.php') ?>" method="get">
            <p><?php _e('Users with no entry', 'formidable') ?>:<br/>
                <?php wp_dropdown_users(array('include' => $not_submitted, 'name' => 'user_id')) ?> <input type="submit" name="Go" value="<?php _e('View Profile', 'formidable') ?>" class="button-secondary" /></p>
            </form>
            <?php } ?>
            </div>
            <div class="clear"></div>
            </div>
        <?php } 
    } ?>
        <div id="chart_hour"></div>
        <div id="chart_month"></div>
        <div id="img_chart_month" class="frm_print_graph"></div>
        <div id="chart_year"></div>
</div>

<script type="text/javascript">
jQuery(window).bind("load", function(){
//frmUploadImage('chart_time');
OFC.jquery.rasterize('chart_time', 'img_chart_time');
OFC.jquery.rasterize('chart_month', 'img_chart_month');
<?php 
if(isset($fields) and $fields){
foreach ($fields as $field){ ?>
OFC.jquery.rasterize('chart_<?php echo $field->id ?>', 'img_chart_<?php echo $field->id ?>');
<?php } 
} ?>
});
function frmRedirectToStats(form){if(form !='') window.location='?page=formidable-reports&frm_action=show&form='+form}
</script>