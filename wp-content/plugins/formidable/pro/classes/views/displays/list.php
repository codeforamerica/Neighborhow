<div class="wrap">
    <div id="icon-themes" class="icon32"><br/></div>
    <h2><?php _e('Custom Displays', 'formidable'); ?>
        <?php if(current_user_can('frm_create_entries')){ ?>
        <a href="?page=formidable-entry-templates&amp;frm_action=new" class="button add-new-h2"><?php _e('Add New', 'formidable'); ?></a>
        <?php } ?>
    </h2>
  
<?php require(FRM_VIEWS_PATH.'/shared/errors.php'); 

if(class_exists('WP_List_Table')){ ?>

<form id="posts-filter" method="get">
    <input type="hidden" name="page" value="formidable-entry-templates" />
    <input type="hidden" name="frm_action" value="list" />
<?php $wp_list_table->search_box( __( 'Search', 'formidable' ), 'entry' ); 

require(FRM_VIEWS_PATH.'/shared/nav.php');
if($form) FrmAppController::get_form_nav($form, true);

$wp_list_table->display(); ?>

</form>
<?php }else{

do_action('frm_before_item_nav',$sort_str, $sdir_str, $search_str, false);
require(FRM_VIEWS_PATH.'/shared/nav.php');
if (isset($form)) FrmAppController::get_form_nav($form, true); ?>
        
<form name="item_list_form" id="posts-filter" method="post" >
    <input type="hidden" name="frm_action" value="list-form"/>  
    <?php $footer = false; require(FRM_VIEWS_PATH.'/shared/item-table-nav.php'); ?>
    <table class="widefat post fixed" cellspacing="0">
        <thead>
        <tr>
            <th class="manage-column check-column" scope="col"> <?php do_action('frm_column_header'); ?> </th>
            <th class="manage-column sortable <?php echo (($sort_str == 'id' and $sdir_str == 'asc')?'desc':'asc'); ?>" width="50px">
                <a href="?page=formidable-entry-templates&amp;sort=id&amp;sdir=<?php echo (($sort_str == 'id' and $sdir_str == 'asc')?'desc':'asc'); ?>">
                    <span><?php _e('ID', 'formidable') ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th class="manage-column sortable <?php echo (($sort_str == 'name' and $sdir_str == 'asc')?'desc':'asc'); ?>" width="">
                <a href="?page=formidable-entry-templates&amp;sort=name&amp;sdir=<?php echo (($sort_str == 'name' and $sdir_str == 'asc')?'desc':'asc'); ?>">
                    <span><?php _e('Name', 'formidable'); ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th class="manage-column sortable <?php echo (($sort_str == 'description' and $sdir_str == 'asc')?'desc':'asc'); ?>">
                <a href="?page=formidable-entry-templates&amp;sort=description&amp;sdir=<?php echo (($sort_str == 'description' and $sdir_str == 'asc')?'desc':'asc'); ?>">
                    <span><?php _e('Description', 'formidable') ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th class="manage-column sortable <?php echo (($sort_str == 'display_key' and $sdir_str == 'asc')?'desc':'asc'); ?>" width="120px">
                <a href="?page=formidable-entry-templates&amp;sort=display_key&amp;sdir=<?php echo (($sort_str == 'display_key' and $sdir_str == 'asc')?'desc':'asc'); ?>">
                    <span><?php _e('Key', 'formidable') ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th class="manage-column" width="120px"><?php _e('Form', 'formidable') ?></th>
            <th class="manage-column" width="35px"><?php _e('Entry', 'formidable') ?></th>
            <th class="manage-column" width="50px"><?php _e('Page', 'formidable') ?></th>
            <th class="manage-column" width="140px"><?php _e('ShortCode', 'formidable') ?></th>
        </tr>
        </thead>
        <tbody>
<?php if($record_count <= 0){ ?>
    <tr>
        <td colspan="8"><?php _e('No Custom Displays Found.', 'formidable') ?>
            <a href="?page=formidable-entry-templates&amp;frm_action=new"><?php _e('Add New', 'formidable'); ?></a>
        </td>
    </tr>
<?php }else{
    $alternate = false;
    foreach($displays as $display){
        $alternate = (!$alternate) ? 'alternate' : false;
?>
    <tr class="<?php echo $alternate ?>">
        <th class="check-column" scope="row"><?php do_action('frm_first_col', $display->id); ?></th>
        <td><?php echo $display->id ?></td>
        <td class="post-title">
            <a class="row-title" href="?page=formidable-entry-templates&amp;frm_action=edit&amp;id=<?php echo $display->id; ?>" title="Edit <?php echo stripslashes($display->name); ?>"><?php echo stripslashes($display->name); ?></a>
            <br/>
            <div class="row-actions">
                <span class="edit"><a href="?page=formidable-entry-templates&amp;frm_action=edit&amp;id=<?php echo $display->id; ?>" title="<?php _e('Edit', 'formidable'); ?> <?php echo $display->name; ?>"><?php _e('Edit', 'formidable') ?></a></span> |
                <span><a href="?page=formidable-entry-templates&amp;frm_action=duplicate&amp;id=<?php echo $display->id; ?>" title="<?php _e('Copy', 'formidable') ?> <?php echo $display->name; ?>"><?php _e('Duplicate', 'formidable') ?></a></span> |
                <span class="trash"><a href="?page=formidable-entry-templates&amp;frm_action=destroy&amp;id=<?php echo $display->id; ?>"  onclick="return confirm('<?php printf(__('Are you sure you want to delete your %1$s display data?', 'formidable'), $display->name) ?>');" title="<?php _e('Delete', 'formidable') ?> <?php echo $display->display_key; ?>"><?php _e('Delete', 'formidable') ?></a></span>
            </div>
        </td>
        <td><?php echo stripslashes($display->description) ?></td>
        <td><?php echo $display->display_key ?></td>
        <td><?php echo stripslashes($frm_form->getName($display->form_id)) ?></td>
        <td><?php echo ($display->entry_id > 0) ? $display->entry_id : $display->show_count ?></td>
        <td><?php if ($display->post_id and $display->insert_loc != 'none') echo get_the_title($display->post_id); ?></td>
        <td><input type='text' style="font-size: 10px; width: 100%;" readonly="true" onclick='this.select();' onfocus='this.select();' value='[display-frm-data id=<?php echo $display->id ?>]' /> </td>
    </tr>
<?php
    }
  }
?>
    </tbody>
    <tfoot>
    <tr>
        <th class="manage-column check-column" scope="col"> <?php do_action('frm_column_header'); ?> </th>
        <th class="manage-column"><?php _e('ID', 'formidable') ?></th>
        <th class="manage-column"><?php _e('Name', 'formidable') ?></th>
        <th class="manage-column"><?php _e('Description', 'formidable') ?></th>
        <th class="manage-column"><?php _e('Key', 'formidable') ?></th>
        <th class="manage-column"><?php _e('Form', 'formidable') ?></th>
        <th class="manage-column"><?php _e('Entry', 'formidable') ?></th>
        <th class="manage-column"><?php _e('Page', 'formidable') ?></th>
        <th class="manage-column"><?php _e('ShortCode', 'formidable') ?></th>
    </tr>
    </tfoot>
</table>
<?php $footer = true; require(FRM_VIEWS_PATH.'/shared/item-table-nav.php'); ?>
</form>
<?php } ?>
</div>