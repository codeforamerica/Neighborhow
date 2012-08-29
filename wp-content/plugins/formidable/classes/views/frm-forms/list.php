<div class="wrap">
    <div class="frmicon icon32"><br/></div>
    <h2><?php echo ($params['template'])? __('Form Templates', 'formidable') : __('Forms', 'formidable'); 
        if(!$params['template'] and current_user_can('frm_edit_forms')){ ?>
        <a href="?page=formidable-new" class="button add-new-h2"><?php _e('Add New', 'formidable'); ?></a>
        <?php } ?>
    </h2>
  
<?php require(FRM_VIEWS_PATH.'/shared/errors.php');
if(class_exists('WP_List_Table')){ 
    
?>

<form id="posts-filter" method="get">
    <input type="hidden" name="page" value="<?php echo $_GET['page'] ?>" />
    <input type="hidden" name="frm_action" value="list" />
<?php $wp_list_table->search_box( __( 'Search', 'formidable' ), 'entry' ); 

require(FRM_VIEWS_PATH.'/shared/nav.php');

if ($params['template']) require(FRM_VIEWS_PATH .'/frm-forms/default-templates.php');

$wp_list_table->display(); ?>

</form>
<?php }else{
  
do_action('frm_before_item_nav',$sort_str, $sdir_str, $search_str, false);
require(FRM_VIEWS_PATH.'/shared/nav.php');

if ($params['template']) require(FRM_VIEWS_PATH .'/frm-forms/default-templates.php'); ?>
        
<form class="form-fields item-list-form" name="item_list_form" id="posts-filter" method="post" >
  <input type="hidden" name="frm_action" value="list-form"/>
  <input type="hidden" name="template" value="<?php echo esc_attr($params['template']) ?>" />   
<?php $footer = false; require(FRM_VIEWS_PATH.'/shared/item-table-nav.php'); ?>
<table class="widefat fixed" cellspacing="0">
    <thead>
    <tr>
        <th class="manage-column check-column" scope="col"> <?php do_action('frm_column_header'); ?> </th>
        <?php if ($params['template']){ ?>
            <th class="manage-column <?php FrmFormsHelper::get_sortable_classes('name', $sort_str, $sdir_str); ?>" width="">
                <a href="?page=formidable-templates&amp;sort=name&amp;sdir=<?php echo (($sort_str == 'name' and $sdir_str == 'desc')?'asc':'desc'); ?>">
                    <span><?php _e('Name', 'formidable') ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th class="manage-column <?php FrmFormsHelper::get_sortable_classes('description', $sort_str, $sdir_str); ?>">
                <a href="?page=formidable-templates&amp;sort=description&amp;sdir=<?php echo (($sort_str == 'description' and $sdir_str == 'desc')?'asc':'desc'); ?>">
                    <span><?php _e('Description', 'formidable') ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
        <?php }else{?>
            <th class="manage-column <?php FrmFormsHelper::get_sortable_classes('id', $sort_str, $sdir_str); ?>" width="50px">
                <a href="?page=formidable&amp;sort=id&amp;sdir=<?php echo (($sort_str == 'id' and $sdir_str == 'desc')?'asc':'desc'); ?>">
                    <span><?php _e('ID', 'formidable') ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th class="manage-column <?php FrmFormsHelper::get_sortable_classes('name', $sort_str, $sdir_str); ?>" width="350px">
                <a href="?page=formidable&amp;sort=name&amp;sdir=<?php echo (($sort_str == 'name' and $sdir_str == 'desc')?'asc':'desc'); ?>">
                    <span><?php _e('Name', 'formidable') ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th class="manage-column <?php FrmFormsHelper::get_sortable_classes('description', $sort_str, $sdir_str); ?>">
                <a href="?page=formidable&amp;sort=description&amp;sdir=<?php echo (($sort_str == 'description' and $sdir_str == 'desc')?'asc':'desc'); ?>">
                    <span><?php _e('Description', 'formidable') ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th class="manage-column <?php FrmFormsHelper::get_sortable_classes('form_key', $sort_str, $sdir_str); ?>" width="70px">
                <a href="?page=formidable&amp;sort=form_key&amp;sdir=<?php echo (($sort_str == 'form_key' and $sdir_str == 'desc')?'asc':'desc'); ?>">
                    <span><?php _e('Key', 'formidable') ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th class="manage-column" width="60px"><?php _e('Entries', 'formidable') ?></th>
            <th class="manage-column" width="115px"><?php _e('Direct Link', 'formidable') ?></th>
            <th class="manage-column" width="115px"><?php _e('ShortCodes', 'formidable') ?></th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
<?php if($record_count <= 0){ ?>
    <tr>
        <td colspan="<?php echo ($params['template'])? '3':'7'; ?>">
            <?php if ($params['template']){ ?>
                <?php _e('No Templates Found', 'formidable') ?>. 
                <br/><br/><?php _e('To add a new template','formidable') ?>:
                <ol><li style="list-style:decimal;">Create a <a href="?page=formidable-new">new form</a>.</li>
                    <li style="list-style:decimal;">After your form is created, go to Formidable -> <a href="?page=formidable">Forms</a>.</li>
                    <li style="list-style:decimal;">Place your mouse over the name of the form you just created, and click the "Create Template" link.</li>
                </ol>
            <?php }else{ ?>
                <?php _e('No Forms Found', 'formidable') ?>. 
                <a href="?page=formidable-new"><?php _e('Add New', 'formidable'); ?></a>
            <?php } ?>
        </td>
    </tr>
<?php
}else{
    $alternate = '';
    foreach($forms as $form){
        $alternate = (empty($alternate)) ? ' alternate' : '';
?>
    <tr class="iedit<?php echo $alternate ?>" >
        <th class="check-column" scope="row"><?php do_action('frm_first_col', $form->id); ?></th>
        <?php if ($params['template']){ ?>
        <td class="post-title">
            <?php if(current_user_can('frm_edit_forms')){ ?>
            <a class="row-title" href="?page=formidable&amp;frm_action=edit&amp;id=<?php echo $form->id; ?>" title="<?php _e('Edit', 'formidable') ?> <?php echo esc_attr(strip_tags(stripslashes($form->name))); ?>"><?php echo stripslashes($form->name); ?></a>
            <?php }else{    
                    echo stripslashes($form->name); 
                  } ?>
            <br/>
            <div class="row-actions">
                <?php if(current_user_can('frm_edit_forms')){ ?>
                <span><a href="?page=formidable&amp;frm_action=duplicate&amp;id=<?php echo $form->id; ?>" title="<?php _e('Copy', 'formidable') ?> <?php echo esc_attr(strip_tags(stripslashes($form->name))); ?>"><?php _e('Create Form from Template', 'formidable') ?></a></span>
                | <span class="edit"><a href="?page=formidable&amp;frm_action=edit&amp;id=<?php echo $form->id; ?>" title="<?php _e('Edit', 'formidable') ?> <?php echo esc_attr(strip_tags(stripslashes($form->name))); ?>"><?php _e('Edit', 'formidable') ?></a></span>
                <?php } ?>
                <?php do_action('frm_template_action_links', $form); ?>
                <?php if(current_user_can('frm_delete_forms')){ ?>
                | <span class="trash"><a href="?page=formidable&amp;frm_=destroy&amp;id=<?php echo $form->id; ?>"  onclick="return confirm('<?php printf(__('Are you sure you want to delete your %1$s Form?', 'formidable'), strip_tags(stripslashes($form->name))) ?>');" title="<?php _e('Delete', 'formidable') ?> <?php echo esc_attr(strip_tags(stripslashes($form->name))); ?>"><?php _e('Delete', 'formidable') ?></a></span>
                <?php } ?>
            </div>
        </td>
        <td><?php echo $form->description ?></td>
        <?php }else{ ?>
        <td><?php echo $form->id ?></td>
        <td class="post-title">
            <?php if(current_user_can('frm_edit_forms')){ ?>
            <a class="row-title" href="?page=formidable&amp;frm_action=edit&amp;id=<?php echo $form->id; ?>" title="<?php _e('Edit', 'formidable') ?> <?php echo esc_attr(strip_tags(stripslashes($form->name))); ?>"><?php echo stripslashes($form->name); ?></a>
            <?php }else{
                    echo stripslashes($form->name); 
                  }
            ?>
            <br/>
            <div class="row-actions">
                <?php if(current_user_can('frm_edit_forms')){ ?>
                <span class="edit"><a href="?page=formidable&amp;frm_action=edit&amp;id=<?php echo $form->id; ?>" title="<?php _e('Edit', 'formidable') ?> <?php echo esc_attr(strip_tags(stripslashes($form->name))) ?>"><?php _e('Edit', 'formidable') ?></a></span>
	                | <span><a href="?page=formidable&amp;frm_action=settings&amp;id=<?php echo $form->id; ?>" title="<?php echo esc_attr(strip_tags(stripslashes($form->name))) ?> <?php _e('Settings', 'formidable') ?>"><?php _e('Settings', 'formidable') ?></a></span>	
				<?php } ?>
                    
                <?php if(current_user_can('frm_view_entries')){ ?>
                    | <span><a href="?page=formidable-entries&amp;form=<?php echo $form->id; ?>" title="<?php echo esc_attr(strip_tags(stripslashes($form->name))); ?> Entries"><?php _e('Entries', 'formidable') ?></a></span>
                    | <span><a href="?page=formidable-reports&amp;form=<?php echo $form->id; ?>" title="<?php echo esc_attr(strip_tags(stripslashes($form->name))); ?> Reports"><?php _e('Reports', 'formidable') ?></a></span>
                <?php } ?>
				
				<?php if($frmpro_is_installed and current_user_can('frm_create_entries')){ ?>
                    | <span><a href="?page=formidable-entries&amp;frm_action=new&amp;form=<?php echo $form->id; ?>" title="<?php _e('New', 'formidable') ?> <?php echo esc_attr(strip_tags(stripslashes($form->name))) ?> <?php _e('Entry', 'formidable') ?>"><?php _e('New Entry', 'formidable') ?></a></span></br>
                <?php } ?>
                
                <?php if(current_user_can('frm_edit_forms') and $frmpro_is_installed){ ?>
                 <span><a href="?page=formidable&amp;frm_action=duplicate&amp;id=<?php echo $form->id; ?>" title="<?php _e('Copy', 'formidable') ?> <?php echo esc_attr(strip_tags(stripslashes($form->name))) ?>"><?php _e('Duplicate', 'formidable') ?></a></span>
                | <span><a href="?page=formidable&amp;frm_action=duplicate&amp;id=<?php echo $form->id; ?>&amp;template=1" title="<?php _e('Create', 'formidable') ?> <?php echo esc_attr(strip_tags(stripslashes($form->name))) ?> <?php _e('Template', 'formidable') ?>"><?php _e('Create Template', 'formidable') ?></a></span>
                | <span><a href="<?php echo FRM_SCRIPT_URL ?>&amp;controller=forms&amp;frm_action=export&amp;id=<?php echo $form->id; ?>" title="<?php _e('Export', 'formidable') ?> <?php echo esc_attr(strip_tags(stripslashes($form->name))) ?> <?php _e('Template', 'formidable') ?>"><?php _e('Export as Template', 'formidable') ?></a></span>
                <?php
                } 
                
                if(current_user_can('frm_delete_forms')){ ?>
                | <span class="trash"><a href="?page=formidable&amp;frm_action=destroy&amp;id=<?php echo $form->id; ?>"  onclick="return confirm('<?php printf(__('Are you sure you want to delete your %1$s Form?', 'formidable'), strip_tags(stripslashes($form->name))) ?>');" title="<?php _e('Delete', 'formidable') ?> <?php echo esc_attr(strip_tags(stripslashes($form->name))) ?>"><?php _e('Delete', 'formidable') ?></a></span>
                <?php } ?>
            </div>
        </td>
        <td><?php echo stripslashes($form->description) ?></td>
        <td><?php echo $form->form_key ?></td>
        <td><?php
        $text = $frm_entry->getRecordCount($form->id);
        $text = sprintf(_n( '%1$s Entry', '%1$s Entries', $text, 'formidable' ), $text);
        echo (current_user_can('frm_view_entries')) ? '<a href="'. esc_url(admin_url('admin.php') .'?page=formidable-entries&form='. $form->id ) .'">'. $text .'</a>' : $text;
        unset($text);
         ?></td>
        <td>
            <input type="text" style="font-size:10px;width:100%;" readonly="true" onclick="this.select();" onfocus="this.select();" value="<?php echo $target_url = FrmFormsHelper::get_direct_link($form->form_key, $form->prli_link_id); ?>" /><br/><a href="<?php echo $target_url; ?>" target="blank"><?php _e('View Form', 'formidable') ?></a>
        </td>
        <td><input type="text" style="font-size:10px;width:100%;" readonly="true" onclick="this.select();" onfocus="this.select();" value="[formidable id=<?php echo $form->id; ?>]" /><br/>
            <input type="text" style="font-size:10px;width:100%;" readonly="true" onclick="this.select();" onfocus="this.select();" value="[formidable key=<?php echo $form->form_key ?>]" />
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
        <?php if ($params['template']){ ?>
            <th class="manage-column"><?php _e('Name', 'formidable') ?></th>
            <th class="manage-column"><?php _e('Description', 'formidable') ?></th>
        <?php }else{ ?>
            <th class="manage-column"><?php _e('ID', 'formidable') ?></th>
            <th class="manage-column"><?php _e('Name', 'formidable') ?></th>
            <th class="manage-column"><?php _e('Description', 'formidable') ?></th>
            <th class="manage-column"><?php _e('Key', 'formidable') ?></th>
            <th class="manage-column"><?php _e('Entries', 'formidable') ?></th>
            <th class="manage-column"><?php _e('Direct Link', 'formidable') ?></th>
            <th class="manage-column"><?php _e('ShortCodes', 'formidable') ?></th>
        <?php } ?>
    </tr>
    </tfoot>
</table>
<?php $footer = true; require(FRM_VIEWS_PATH.'/shared/item-table-nav.php'); ?>
</form>
<?php } ?>
</div>