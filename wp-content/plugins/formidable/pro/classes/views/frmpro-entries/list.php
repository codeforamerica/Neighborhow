<div class="wrap">
    <div id="icon-edit-pages" class="icon32"><br/></div>
    <h2><?php echo ($form) ? (FrmAppHelper::truncate(stripslashes($form->name), 25) .' ') : ''; _e('Entries', 'formidable'); ?>
        <a href="?page=formidable-entries&amp;frm_action=new" class="button add-new-h2"><?php _e('Add New', 'formidable'); ?></a>
        <a href="?page=formidable-entries&amp;frm_action=import<?php echo ($form) ? '&amp;form_id='.$form->id : ''; ?>" class="button add-new-h2"><?php _e('Import', 'formidable'); ?></a>
    </h2>

<?php 
require(FRM_VIEWS_PATH.'/shared/errors.php');

if(class_exists('WP_List_Table')){ ?>

<form id="posts-filter" method="get">
    <input type="hidden" name="page" value="formidable-entries" />
    <input type="hidden" name="form" value="<?php echo ($form) ? $form->id : ''; ?>" />
    <input type="hidden" name="frm_action" value="list" />
<?php $wp_list_table->search_box( __( 'Search', 'formidable' ), 'entry' ); 

require(FRM_VIEWS_PATH.'/shared/nav.php');
if($form) FrmAppController::get_form_nav($form->id, true);

$wp_list_table->display(); ?>

</form>
<?php }else{  
do_action('frm_before_item_nav', $sort_str, $sdir_str, $search_str, $fid);
require(FRM_VIEWS_PATH.'/shared/nav.php');
if($form) FrmAppController::get_form_nav($form->id, true);
?>  
<form class="form-fields item-list-form" name="item_list_form" id="posts-filter" method="post" >
<input type="hidden" name="frm_action" value="list-form"/>
<?php $footer = false; $select_forms = true; require(FRM_VIEWS_PATH.'/shared/item-table-nav.php'); ?>

<table class="wp-list-table widefat post fixed" cellspacing="0">
    <thead>
    <tr>
        <th class="manage-column check-column" scope="col"> <?php do_action('frm_column_header'); ?> </th>
        <?php foreach ($form_cols as $col){ ?>
        <th class="manage-column column-frm_<?php echo $col->field_key ?>" id="frm_<?php echo $col->field_key ?>" scope="col">
            <?php echo FrmAppHelper::truncate($col->name, 40) ?>
        </th>
        <?php } ?>
    </tr>
    </thead>
<tbody>
<?php if($record_count <= 0){ ?>
    <tr><td colspan="<?php echo count($form_cols)+1 ?>">
    <?php 
        if($params['search'] and !empty($params['search']))
            _e('No Entries Found', 'formidable');
        else
            include(FRM_VIEWS_PATH.'/frm-entries/no_entries.php'); 
    ?>
    </td></tr>
<?php
}else{
    $alternate = false;
    foreach($items as $item){
        $alternate = (!$alternate) ? 'alternate' : false;
        ?>
    <tr class="<?php echo $alternate ?>">
        <th class="check-column" scope="row">
            <?php do_action('frm_first_col', $item->id); ?>
        </th>
    <?php foreach ($form_cols as $col){ ?>
        <td class="column-frm_<?php echo $col->field_key ?><?php if ($col == $form_cols[0]){ ?> post-title<?php } ?>">
            <?php if ($col == $form_cols[0]){
                $entry_action = (current_user_can('frm_edit_entries')) ? 'edit' : 'show'; 
            ?>
            <a class="row-title" href="?page=formidable-entries&amp;frm_action=<?php echo $entry_action ?>&amp;id=<?php echo $item->id; ?>" title="<?php echo esc_attr($entry_action .' '. stripslashes($item->name)); ?>">
            <?php }
            $field_value = isset($item->metas[$col->id]) ? $item->metas[$col->id] : false;
            $col->field_options = maybe_unserialize($col->field_options);
            
            if(!$field_value and $col->type == 'data' and $col->field_options['data_type'] == 'data' and
             isset($col->field_options['hide_field'])){
                 $field_value = array();
                 foreach((array)$col->field_options['hide_field'] as $hfield ){
                     if(isset($item->metas[$hfield]))
                         $field_value[] = maybe_unserialize($item->metas[$hfield]);
                 }
            }
            
            echo FrmProEntryMetaHelper::display_value($field_value, $col, array('type' => $col->type, 'truncate' => true, 'post_id' => $item->post_id, 'entry_id' => $item->id));  
                    
            if ($col == $form_cols[0]){ ?>
            </a><br/>
            <div class="row-actions">  
              <span><a href="?page=formidable-entries&amp;frm_action=show&amp;id=<?php echo $item->id; ?>" title="<?php _e('View', 'formidable') ?> <?php echo $item->item_key; ?>"><?php _e('View', 'formidable') ?></a></span>
                
              <?php if(current_user_can('frm_edit_entries')){ ?>
              | <span class="edit"><a href="?page=formidable-entries&amp;frm_action=edit&amp;id=<?php echo $item->id; ?>" title="<?php _e('Edit', 'formidable') ?> <?php echo $item->item_key; ?>"><?php _e('Edit', 'formidable') ?></a></span>
              <?php }

              if(current_user_can('frm_create_entries')){ ?>
              | <span><a href="?page=formidable-entries&amp;frm_action=duplicate&amp;form=<?php echo $params['form'] ?>&amp;id=<?php echo $item->id; ?>" title="<?php _e('Duplicate', 'formidable') ?> <?php echo $item->item_key; ?>"><?php _e('Duplicate', 'formidable') ?></a></span>
              <?php 
              }
              
              if(current_user_can('frm_delete_entries')){ ?>
              | <span class="trash"><a href="?page=formidable-entries&amp;frm_action=destroy&amp;id=<?php echo $item->id; ?><?php echo ($params['form']) ? '&form='.$params['form'] : '' ?>"  onclick="return confirm('<?php _e('Are you sure you want to delete that entry?', 'formidable') ?>');" title="<?php _e('Delete', 'formidable') ?> <?php echo $item->item_key; ?>"><?php _e('Delete', 'formidable') ?></a></span>
              <?php } ?>
            </div>
            <?php } ?>
        </td>
    <?php } ?>
    </tr>
<?php
    }
}
?>
</tbody>
<tfoot>
<tr>
    <th class="manage-column check-column" scope="col"> <?php do_action('frm_column_header'); ?> </th>
    <?php foreach ($form_cols as $col){ ?>
    <th class="manage-column"><?php echo FrmAppHelper::truncate($col->name, 40) ?></th>
    <?php } ?>
</tr>
</tfoot>
</table>
<?php 
$footer = true; $select_forms = false; 
require(FRM_VIEWS_PATH.'/shared/item-table-nav.php'); 
}

if($form){ ?>
<p class="alignright frm_uninstall"><a href="?page=formidable-entries&amp;frm_action=destroy_all<?php echo ($form) ? '&amp;form='. $form->id : '' ?>" class="button-secondary" onclick="return confirm('<?php _e('Are you sure you want to permanently delete ALL the entries in this form?', 'formidable') ?>')"><?php _e('Delete ALL Entries', 'formidable') ?></a></p>

<p><a href="<?php echo esc_url(FRM_SCRIPT_URL . "&controller=entries{$page_params}&frm_action=csv") ?>" class="button-secondary"><?php _e('Download CSV for', 'formidable'); echo ' '. stripslashes($form->name); ?></a></p><br/><br/>
<?php } ?>
</form>

</div>
