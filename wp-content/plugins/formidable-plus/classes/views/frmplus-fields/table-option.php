<div class="frm_single_option_sortable" id="frm_field-<?php echo $field['id']; ?>-<?php echo $opt_key; ?>">
<span id="frm_delete_field_<?php echo $field['id']; ?>-<?php echo $opt_key; ?>_container" class="frm_single_option">
    <a href="javascript:frm_delete_field_option(<?php echo $field['id']?>, '<?php echo $opt_key ?>',ajaxurl);" class="frm_single_visible_hover alignleft" ><img src="<?php echo FRM_IMAGES_URL ?>/trash.png" alt="Delete"></a>
    <a href="javascript:void(0);" class="frm_single_visible_hover alignleft frm_sortable_handle" ><img src="<?php echo FRM_IMAGES_URL ?>/move.png" alt="Reorder"></a>
    <span class="frm_ipe_field_option" id="field_<?php echo $field['id']?>-<?php echo $opt_key ?>"><?php echo $opt ?></span>
</span>
<div class="clear"></div>
</div> <!-- frm_single_option_sortable -->