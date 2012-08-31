
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#field_<?php echo $observed_field->id ?>').change(function(){
        var this_number = $(this).val();
        $("#field_<?php echo $field['id'] ?>").value = this_number + $("#field_<?php echo $observed_field2->id ?>");
    });
})
</script>