<?php if (!isset($row_num)) $row_num = 0; ?>
<?php if (count($rows)) :  ?>
<?php
// NEIGHBORHOW MOD 
// - changed copy for delete button
// - added TD for img upload
?>	
	<?php foreach ($rows as $opt_key => $opt) : ?>
		<tr id="step-<?php echo $row_num; ?>" class="row-<?php echo $row_num; ?>">
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
				<tr id="step-<?php echo $row_num; ?>" class="step row-<?php echo $row_num; ?>"><?php
				foreach ($columns as $col_key => $col_opt){
					?><td class="column-<?php echo $col_num; ?>"><?php require('table-field.php'); $col_num++; ?></td><?php
				}
			?><?php if ($display_only !== true) : ?>
				<td><div class="nh-step-image nh-step-image-<?php echo $row_num;?>" style="display:inline-block;border:1px solid red;"><input class="step-image" type="file" name="step-image-<?php echo $row_num;?>"><?php $step_num = $row_num + 1;?><!--input type="hidden" id="field_bskkgu" name="item_meta[154]" value="step-image-<?php echo $step_num;?>"/-->
<?php 
/*
REMOVE IMAGE - how to ?
CHECK if its current or posted or empty
$current_images = esc_attr($_POST['nh_cities']);
$posted_cities = esc_attr($_FILES);
foreach ($posted_cities as $posted_city) {
	
}
//if () { 
*/	
echo '<p><a href="">Remove image</a></p>';
//}
?></div></td>




				<td><a class="frmplus-delete-row" href="javascript:delete_row(<?php echo $field['id']; ?>,<?php echo $row_num; ?>)">Delete this Step <img src="<?php echo FRM_IMAGES_URL ?>/trash.png" alt="<?php echo apply_filters('frmplus_text_delete_row','Delete this Step',$field); ?>" title="<?php echo apply_filters('frmplus_text_delete_row','Delete this Step',$field); ?>" border="0"></a></td>
<?php endif;
// END NEIGHBORHOW MOD			
				?></tr>
			<?php $row_num++; }
		}
?>
<?php endif; ?>
