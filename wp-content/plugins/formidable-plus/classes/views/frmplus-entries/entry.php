<div class="entry-actions">
	<a class="edit-entry-link" href="?action=edit_entry&amp;id=<?php echo $id; ?>">Edit this entry</a>       
	<a class="return-link" href="?action=list_all">Return to All Forms</a>       
	<a class="print-link" href="?action=show_entry&amp;id=<?php echo $id; ?>&amp;printable=true" target="_blank">Printable Version</a>       
</div>
<h1><?php echo $entry->form_name; ?></h1>
<table class="form-table"><tbody>
<?php foreach($fields as $field){ 
    if ($field->type == 'break' or $field->type == 'divider'){ ?>
</tbody></table>
<br/>
<h3><?php echo stripslashes($field->name) ?></h3>
<table class="form-table"><tbody>
<?php }else{?>
    <tr valign="top">
        <th scope="row"><?php echo stripslashes($field->name) ?>:</th>
        <td>
            <?php $field_value = $frm_entry_meta->get_entry_meta_by_field($entry->id, $field->id, true);
              echo FrmProEntryMetaHelper::display_value($field_value, $field, array('type' => $field->type));
            ?>
        </td>
    </tr>
<?php }
} ?>
</tbody></table> 
<div class="entry-actions">
	<a class="edit-entry-link" href="?action=edit_entry&amp;id=<?php echo $id; ?>">Edit this entry</a>       
	<a class="return-link" href="?action=list_all">Return to All Forms</a>       
	<a class="print-link" href="?action=show_entry&amp;id=<?php echo $id; ?>&amp;printable=true" target="_blank">Printable Version</a>       
</div>
