<div class="frm_forms<?php echo ($values['custom_style']) ? ' with_frm_style' : ''; ?>" id="frm_form_<?php echo $form->id ?>_container">
<form enctype="multipart/form-data" method="post" class="frm-show-form <?php do_action('frm_form_classes', $form) ?>" id="form_<?php echo $form->form_key ?>" <?php echo ($frm_settings->use_html) ? '' : 'action=""'; ?>>
<?php 
include(FRM_VIEWS_PATH.'/frm-entries/errors.php');
$form_action = 'create';
require(FRM_VIEWS_PATH.'/frm-entries/form.php'); 
?>

<?php if (!$form->is_template and $form->status == 'published'){ ?>
<p class="submit">
<?php $submit = apply_filters('frm_submit_button', $submit, $form); ?>
<input type="submit" value="<?php echo esc_attr($submit) ?>" <?php do_action('frm_submit_button_action', $form, $form_action); ?>/>
</p>
<?php } ?>
</form>
</div>