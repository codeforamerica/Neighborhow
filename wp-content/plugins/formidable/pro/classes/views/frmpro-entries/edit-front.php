<div class="frm_forms<?php echo ($values['custom_style']) ? ' with_frm_style' : ''; ?>" id="frm_form_<?php echo $form->id ?>_container">
<?php include(FRM_VIEWS_PATH.'/frm-entries/errors.php'); ?>

<?php if (isset($show_form) and $show_form){ 

if(!empty($errors)){ ?>
<script type="text/javascript">window.onload=function(){location.href="#frm_errors";}</script>
<?php }else if(isset($jump_to_form) and $jump_to_form){ ?>
<script type="text/javascript">window.onload=function(){location.href="#form_<?php echo $form->form_key ?>";}</script>
<?php } ?>
<form enctype="multipart/form-data" method="post" class="frm-show-form <?php do_action('frm_form_classes', $form) ?>" id="form_<?php echo $form->form_key ?>" <?php echo ($frm_settings->use_html) ? '' : 'action=""'; ?>>
<?php $form_action = 'update';
    require(FRM_VIEWS_PATH.'/frm-entries/form.php');
    $submit = apply_filters('frm_submit_button', $submit, $form); 
?>
<p class="submit"><input type="submit" value="<?php echo esc_attr($submit); ?>" <?php do_action('frm_submit_button_action', $form, $form_action); ?> /></p>
</form>
<?php } ?>
</div>