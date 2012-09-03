<?php if ($field['type'] == 'table'){ ?>
    <div class="frm-show-click">
    <?php list($columns,$rows) = FrmPlusFieldsHelper::get_table_options($field['options']); ?>
		Column Headings<br/>
	<div id="frm_column_list_<?php echo $field['id']; ?>">
<?php
		foreach ($columns as $opt_key => $opt){
			require('table-option.php');
		}
?>	
		<div id="frm_add_field_col_<?php echo $field['id']; ?>" class="frm-show-click-col"> <?php // had to change this class to get the thing to appear ?>
		    <a href="javascript:frmplus_add_field_option('<?php echo 'col_'.$field['id']; ?>')"><span class="ui-icon ui-icon-plusthick alignleft"></span> Add a Column</a>
		</div>
	</div> <!-- frm_column_list_<?php echo $field['id']; ?> -->
		<br/>Row Headings<br/>
	<div id="frm_row_list_<?php echo $field['id']; ?>">
<?php
		foreach ($rows as $opt_key => $opt){
			require('table-option.php');
		}
?>		
	    <div id="frm_add_field_row_<?php echo $field['id']; ?>" class="frm-show-click-row">
	        <a href="javascript:frmplus_add_field_option('<?php echo 'row_'.$field['id']; ?>')"><span class="ui-icon ui-icon-plusthick alignleft"></span> Add a Row</a>
	        <?php do_action('frm_add_multiple_opts', $field); ?>
	    </div>
	</div> <!-- frm_row_list_<?php echo $field['id']; ?> -->
	</div> <!-- frm-show-click -->

	<script type="text/javascript">
	jQuery("#frm_column_list_<?php echo $field['id']; ?>").sortable({
		axis:'y',
	    cursor:'move',
		handle:'.frm_sortable_handle',
	    revert:true,
		items:'div.frm_single_option_sortable',
	    update:function(){
	        var order= jQuery('#frm_column_list_<?php echo $field['id']; ?>').sortable('serialize');
	        jQuery.ajax({
	            type:"POST",
	            url:ajaxurl,
	            data:"action=frm_table_option_order&which=col&field_id=<?php echo $field['id']; ?>&"+order,
				success:function(msg){jQuery('#frm_column_list_<?php echo $field['id']; ?>').sortable('refresh');}
	        });
	    }
	});

	jQuery("#frm_row_list_<?php echo $field['id']; ?>").sortable({
		axis:'y',
	    cursor:'move',
		handle:'.frm_sortable_handle',
	    revert:true,
		items:'div.frm_single_option_sortable',
	    update:function(){
	        var order= jQuery('#frm_row_list_<?php echo $field['id']; ?>').sortable('serialize');
	        jQuery.ajax({
	            type:"POST",
	            url:ajaxurl,
	            data:"action=frm_table_option_order&which=row&field_id=<?php echo $field['id']; ?>&"+order,
				success:function(msg){jQuery('#frm_row_list_<?php echo $field['id']; ?>').sortable('refresh');}
	        });
	    }
	});
	</script>
<?php } ?>
