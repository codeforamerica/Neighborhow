<?php 
//if(!empty($field['value']) and $field['value'] != $field['default_value'])
//    return;
    
if ($observed_options['data_type'] == 'radio'){ ?>
frmCheckDependent($("input[name='item_meta[<?php echo $observed_field->id ?>]']:checked").val(),<?php echo $observed_field->id ?>);
<?php 
}else if ($observed_options['data_type'] == 'checkbox'){ 
    if ($field['data_type'] == '' or $field['data_type'] == 'data'){ ?>
$('#frm_field_<?php echo $field['id'] ?>_container').hide();
<?php } ?>
frmCheckDependent('',<?php echo $observed_field->id ?>);
<?php
}else if ($observed_options['data_type'] == 'select'){

    if ($field['data_type'] == '' or $field['data_type'] == 'data'){ ?>
$('#frm_field_<?php echo $field['id'] ?>_container').hide();
<?php } ?>
frmCheckDependent($("select[name='item_meta[<?php echo $observed_field->id ?>]']").val(),<?php echo $observed_field->id ?>);

<?php }?>
