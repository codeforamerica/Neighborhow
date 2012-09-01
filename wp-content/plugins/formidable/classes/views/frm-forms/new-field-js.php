<script type="text/javascript">
jQuery(document).ready(function($){
$('select[name^="item_meta"], textarea[name^="item_meta"]').css('float','left');
$('input[name^="item_meta"]').not(':radio, :checkbox').css('float','left');
$("#frm_field_id_<?php echo $field['id']; ?> .frm_ipe_field_option, #frm_field_id_<?php echo $field['id']; ?> .frm_ipe_field_option_key").editInPlace({
url:"<?php echo $frm_ajax_url ?>",params:"action=frm_field_option_ipe", default_text:"<?php _e('(Blank)', 'formidable') ?>"
});
$("#frm_field_id_<?php echo $field['id']; ?> .frm_ipe_field_label").editInPlace({
url:"<?php echo $frm_ajax_url ?>",params:"action=frm_field_name_in_place_edit", value_required:"true"
});
$("#frm_field_id_<?php echo $field['id']; ?> .frm_ipe_field_desc").editInPlace({
url:"<?php echo $frm_ajax_url ?>",params:"action=frm_field_desc_in_place_edit",field_type:'textarea',textarea_rows:3,
default_text:"(<?php _e('Click here to add optional description or instructions', 'formidable') ?>)"
});
$("#frm_field_id_<?php echo $field['id']; ?> img.frm_help[title]").hover(
function(){frm_title=$(this).attr('title');$(this).removeAttr('title');$('#frm_tooltip').html(frm_title).fadeIn();},
function(){$('#frm_tooltip').fadeOut();$(this).attr('title',frm_title);}
);
});
</script>