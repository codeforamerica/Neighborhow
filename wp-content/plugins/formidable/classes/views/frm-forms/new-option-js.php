<script type="text/javascript">
jQuery(document).ready(function(){
jQuery("#frm_delete_field_<?php echo $field['id']; ?>-<?php echo $opt_key ?>_container .frm_ipe_field_option, #frm_delete_field_<?php echo $field['id']; ?>-<?php echo $opt_key ?>_container .frm_ipe_field_option_key").editInPlace({
url:"<?php echo $frm_ajax_url ?>",params:"action=frm_field_option_ipe",default_text:"<?php _e('(Blank)', 'formidable') ?>"
});
});
</script>