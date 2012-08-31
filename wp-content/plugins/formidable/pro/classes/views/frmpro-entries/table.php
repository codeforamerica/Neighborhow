<table class="form_results<?php echo ($style)? ' with_frm_style': ''; ?>" id="form_results<?php echo $form->id ?>" cellspacing="0">
    <thead>
    <tr> 
    <?php if(in_array('id', $fields)){ ?>   
    <th><?php _e('ID', 'formidable'); ?></th>  
    <?php }
        foreach ($form_cols as $col){ ?>
        <th><?php echo stripslashes($col->name); ?></th>
    <?php } 
        if($edit_link){ ?>
    <th><?php echo $edit_link ?></th>
    <?php } ?>
    </tr>
    </thead>
    <tbody>
<?php if(empty($entries)){ ?>
    <tr><td colspan="<?php echo count($form_cols) ?>"><?php echo $no_entries ?></td></tr>
<?php
}else{
    $class = 'odd';
    
    foreach($entries as $entry){  ?>
        <tr class="frm_<?php echo $class ?>">
        <?php if(in_array('id', $fields)){ ?>   
            <td><?php echo $entry->id ?></dh>  
        <?php }
            foreach ($form_cols as $col){ ?>
            <td valign="top">
                <?php echo FrmProEntryMetaHelper::display_value((isset($entry->metas[$col->id]) ? $entry->metas[$col->id] : false), $col, array('type' => $col->type, 'post_id' => $entry->post_id, 'entry_id' => $entry->id)); 
                ?>
            </td>
<?php       }

            if($edit_link){ ?>
        <td><?php if(FrmProEntry::user_can_edit($entry, $form)){ ?><a href="<?php echo esc_url(add_query_arg(array('frm_action' => 'edit', 'entry' => $entry->id), $permalink) . $anchor)  ?>"><?php echo $edit_link ?></a><?php } ?></td>
<?php       } ?>
        </tr>
<?php
    $class = ($class == 'even') ? 'odd' : 'even';
    }
}
?>
    </tbody>
    <tfoot>
    <tr>
        <?php foreach ($form_cols as $col){ ?>
            <th><?php echo stripslashes($col->name); ?></th>
        <?php } ?>
    </tr>
    </tfoot>
</table>