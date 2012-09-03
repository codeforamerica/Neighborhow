<?php list($columns,$rows) = FrmPlusFieldsHelper::get_table_options($field['options']); ?>
<?php echo apply_filters('frm-table-container-extras','',$field['id']); ?>
<div id="frm-table-container-<?php echo $field['id']; ?>" class="frm-table-container">
<table id="frm-table-<?php echo $field['id']; ?>" class="frm-table<?php if (count($classes = apply_filters('frm_table_classes',array(),$field['id']))) echo ' '.join(' ',$classes); ?>">
<?php if (count($columns)) : ?>
	<?php // First Row - Column Headers ?>
	<thead>
		<tr>
		<?php if (count($rows)) : ?>
			<?php // Blank column header to go above Row headers ?>
			<th>&nbsp;</th>
		<?php endif; ?>
		<?php $col_num = 0; foreach ($columns as $opt_key => $opt) : ?>
			<th class="column-<?php echo $col_num++; ?>"><?php echo FrmPlusFieldsHelper::parse_option($opt,'name'); ?></th>
		<?php endforeach; ?>
		<?php if (!count($rows)) : ?>
			<?php if ($display_only !== true) : // Blank column header for action buttons (delete row, insert row) ?>
			<th>&nbsp;</th>
			<?php endif; ?>
		<?php endif; ?>
		</tr>
	</thead>
<?php endif; ?>
	<tbody>
<?php require('table-row.php'); ?>
	</tbody>
</table>
<?php if (count($columns) and !count($rows) and $display_only !== true) : ?>
<a class="frmplus-add-row" id="frmplus-add-row-<?php echo $field['id']; ?>" href="javascript:add_row(<?php echo $field['id']; ?>)"><?php echo apply_filters('frmplus_text_add_row','Add Row',$field); ?> <img style="vertical-align:middle" src="<?php echo FRM_IMAGES_URL ?>/duplicate.png" alt="New Row" title="New Row" border="0"></a> 	
<?php endif; ?>
</div>
