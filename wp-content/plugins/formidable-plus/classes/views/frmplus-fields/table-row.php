<?php if (!isset($row_num)) $row_num = 0; ?>
<?php if (count($rows)) :  ?>
		
	<?php foreach ($rows as $opt_key => $opt) : ?>
		<tr class="row-<?php echo $row_num; ?>">
			<th><?php echo FrmPlusFieldsHelper::parse_option($opt,'name'); ?></th>
		<?php if (!count($columns)) $columns[] = ""; // Spoof to get a column up there to enter data into ?>
		<?php $col_num = 0; foreach ($columns as $col_key => $col_opt) : ?>
			<td class="column-<?php echo $col_num; ?>"><?php require('table-field.php'); $col_num++; ?></td>
		<?php endforeach; ?>
		</tr>
	<?php $row_num++; endforeach; ?>
<?php else : 
		if (count($columns)){
			if (isset($field['value']) and is_array($field['value'])){
				$rows_to_output = count($field['value']);
			}
			else{
				$rows_to_output = 1;
			}
			for($r = 0; $r < $rows_to_output; $r++){ $col_num = 0; ?>
				<tr class="row-<?php echo $row_num; ?>"><?php
				foreach ($columns as $col_key => $col_opt){
					?><td class="column-<?php echo $col_num; ?>"><?php require('table-field.php'); $col_num++; ?></td><?php
				}
			?><?php if ($display_only !== true) : ?><td><a class="frmplus-delete-row" href="javascript:delete_row(<?php echo $field['id']; ?>,<?php echo $row_num; ?>)"><img src="<?php echo FRM_IMAGES_URL ?>/trash.png" alt="<?php echo apply_filters('frmplus_text_delete_row','Delete Row',$field); ?>" title="<?php echo apply_filters('frmplus_text_delete_row','Delete Row',$field); ?>" border="0"></a></td><?php endif;
				?></tr>
			<?php $row_num++; }
		}
?>
<?php endif; ?>
