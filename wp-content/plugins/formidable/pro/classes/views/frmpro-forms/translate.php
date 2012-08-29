<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="frm_action" value="update_translate" />

<table class="widefat fixed">
<thead>
    <tr>
    <th class="manage-column" width="170px"><?php echo FrmAppHelper::truncate(stripslashes($values['name']), 40) ?></th>
    <?php foreach($langs as $lang){ 
        if($lang['code'] == $base_lang)
            continue;
        $col_order[] = $lang['code'];
        ?>
    <th class="manage-column frm_lang_<?php echo $lang['code'] ?>"><?php echo $lang['display_name']; ?></th>
    <?php } ?>
    </tr>
</thead>
<tbody>
<?php
$alternate = false;
foreach($strings as $string){ 
    $name = preg_replace('/^'.$id.'_/', '', $string->name, 1); 
    $alternate = ($alternate == '') ? 'alternate' : '';
    $col = 0;
?>
<tr class="<?php echo $alternate; ?>">
    <td><?php echo htmlspecialchars(stripslashes($string->value)); ?></td>
<?php
    foreach($translations as $trans){
        if($trans->string_id != $string->id)
            continue;
        
        $col++; 
        $next_col = array_search($trans->language, $col_order);
        for($col; $col<$next_col; $col++){ ?>
<td><input type="text" value="" name="frm_wpml[<?php echo $string->id .'_'. $col_order[$col] ?>][value]" style="width:100%" />
    <input type="checkbox" value="<?php echo ICL_STRING_TRANSLATION_COMPLETE ?>" id="<?php echo $string->id .'_'. $col_order[$col] ?>_status" name="frm_wpml[<?php echo $string->id .'_'. $col_order[$col] ?>][status]" /> <label for="<?php echo $string->id .'_'. $col_order[$col] ?>_status"><?php _e('Complete', 'formidable')?></label>
</td>
<?php
        }
 ?>
<td><input type="text" value="<?php echo esc_attr(stripslashes($trans->value)) ?>" name="frm_wpml[<?php echo $trans->id ?>][value]" style="width:100%" />
    <input type="checkbox" value="<?php echo ICL_STRING_TRANSLATION_COMPLETE ?>" id="<?php echo $trans->id ?>_status" name="frm_wpml[<?php echo $trans->id ?>][status]" <?php checked($trans->status, ICL_STRING_TRANSLATION_COMPLETE) ?>/> <label for="<?php echo $trans->id ?>_status"><?php _e('Complete', 'formidable')?></label>
</td>
<?php
        unset($trans);
    }
    
    if($col < $lang_count){
        $col++; 
        for($col; $col<=$lang_count; $col++){ ?>
<td><input type="text" value="" name="frm_wpml[<?php echo $string->id .'_'. $col_order[$col] ?>][value]" style="width:100%" />
    <input type="checkbox" value="<?php echo ICL_STRING_TRANSLATION_COMPLETE ?>" id="<?php echo $string->id .'_'. $col_order[$col] ?>_status" name="frm_wpml[<?php echo $string->id .'_'. $col_order[$col] ?>][status]" /> <label for="<?php echo $string->id .'_'. $col_order[$col] ?>_status"><?php _e('Complete', 'formidable')?></label>
</td>
<?php
        }
    }
    unset($string);
?>
</tr>
<?php
}
?> 
</tr>
</tbody>
</table>
<p class="howto"><?php printf(__('If you are missing parts of the form that need translation, please visit the %1$sWPML Translation Management%2$s page then return.', 'formidable'), '<a href="'.  admin_url('admin.php?page=wpml-translation-management/menu/main.php') .'">', '</a>'); ?></p>

    