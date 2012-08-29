<script type="text/javascript">
__FRMURL='<?php echo $frm_ajax_url ?>';
__FRMDEFDESC="<?php _e('(Click here to add form description or instructions)', 'formidable') ?>";
jQuery(document).ready(function($){

$("#new_fields").sortable({
    placeholder:'sortable-placeholder',axis:'y',cursor:'move',opacity:0.65,
    cancel:'.widget,.frm_field_opts_list,input,textarea,select',
    accepts:'field_type_list',revert:true,forcePlaceholderSize:true,
    receive:function(event,ui){
        var new_id=(ui.item).attr('id');
        jQuery('#new_fields .frmbutton.frm_t'+new_id).replaceWith('<img class="frmbutton frmbutton_loadingnow" id="'+new_id+'" src="<?php echo FRM_IMAGES_URL; ?>/ajax_loader.gif" alt="<?php _e('Loading...', 'formidable'); ?>" />');
        jQuery.ajax({
            type:"POST",url:"<?php echo $frm_ajax_url ?>",data:"action=frm_insert_field&form_id=<?php echo $id; ?>&field="+new_id,
            success:function(msg){ 
                $('.frmbutton_loadingnow#'+new_id).replaceWith(msg);
                var regex = /id="(\S+)"/; match=regex.exec(msg);
                $('#'+match[1]+' .frm_ipe_field_label').click();
                var order= $('#new_fields').sortable('serialize');
                jQuery.ajax({type:"POST",url:"<?php echo $frm_ajax_url ?>",data:"action=frm_update_field_order&"+order});
            }
        });
    },
    update:function(){
        var order= $('#new_fields').sortable('serialize');
        jQuery.ajax({type:"POST",url:"<?php echo $frm_ajax_url ?>",data:"action=frm_update_field_order&"+order});
    }
});
});

</script>