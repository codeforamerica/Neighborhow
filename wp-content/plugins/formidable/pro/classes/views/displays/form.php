<?php
if(version_compare( $GLOBALS['wp_version'], '3.3.3', '<')){ ?>
<div id="poststuff" class="metabox-holder has-right-sidebar">
<?php   
    require(FRMPRO_VIEWS_PATH .'/displays/sidebar.php');
}else{ ?>
<div id="poststuff">
<?php } ?>

<div id="post-body" class="metabox-holder columns-2">
<div id="post-body-content">

<div class="postbox ">
<h3 class="hndle"><span><?php _e('Custom Display Info', 'formidable') ?></span></h3>
<div class="inside">
<table class="form-table">
    <tr class="form-field">
        <th valign="top" scope="row"><?php _e('Name', 'formidable'); ?></th>
        <td>
            <input type="text" id="name" name="name" value="<?php echo esc_attr($values['name']) ?>" style="width:98%;" />
        </td>
    </tr>
    
    <?php if (is_admin() && !$frm_settings->lock_keys){ ?>
    <tr class="form-field">
        <th valign="top" scope="row"><?php _e('Display Key', 'formidable') ?></th>   
        <td><input type="text" id="display_key" name="display_key" value="<?php echo esc_attr($values['display_key']) ?>" style="width:98%;" /></td>
    </tr>
    <?php }else{ ?>
    <input type="hidden" id="display_key" name="display_key" value="<?php echo esc_attr($values['display_key']) ?>" />
    <?php } ?>
    
    <tr class="form-field">
        <th valign="top" scope="row"><?php _e('Description', 'formidable'); ?></th>
        <?php $default_value = __('This is not displayed anywhere, but is just for your reference. (optional)', 'formidable');
            $description = (!empty($values['description'])) ? $values['description'] : $default_value; ?>
        <td><textarea id="description" name="description" style="width:98%;<?php if($description == $default_value) echo 'color:#aaa'; ?>" onfocus="frmClearDefault('<?php echo esc_attr($default_value) ?>',this)" onblur="frmReplaceDefault('<?php echo esc_attr($default_value) ?>',this)"><?php echo FrmAppHelper::esc_textarea($description) ?></textarea>
        </td>
    </tr>
</table>
</div>
</div>

<div class="postbox ">
<h3 class="hndle"><span><?php _e('Form and Display Type', 'formidable') ?></span></h3>
<div class="inside">
<table class="form-table">
    <tr class="form-field">
        <th valign="top" scope="row"><?php _e('Use Entries from Form', 'formidable'); ?></th>
        <td><?php FrmFormsHelper::forms_dropdown( 'form_id', $values['form_id'], true, false, "frmDisplayFormSelected(this.value,'$frm_ajax_url')"); ?>
            <span id="entry_select_container">
                <?php if (is_numeric($values['form_id']))
                    _e('Select Entry', 'formidable'). ': '. FrmEntriesHelper::entries_dropdown($values['form_id'], 'entry_id', $values['entry_id'], true, __('The first one depending on the Order specified below', 'formidable'));
                ?>
            </span>
        </td>
    </tr>
    <tr class="form-field">
        <th valign="top" scope="row"><?php _e('Display Format', 'formidable'); ?></th>
        <td>
            <fieldset>
            <p><label for="all"><input type="radio" value="all" id="all" <?php checked($values['show_count'], 'all') ?> name="show_count" onchange="javascript:frm_show_count(this.value)" /> <?php _e('All Entries &mdash; list all entries in the specified form', 'formidable'); ?>.</label></p>
            <p><label for="one"><input type="radio" value="one" id="one" <?php checked($values['show_count'], 'one') ?> name="show_count" onchange="javascript:frm_show_count(this.value)" /> <?php _e('Single Entry &mdash; display one entry', 'formidable'); ?>.</label></p>
            <p><label for="dynamic"><input type="radio" value="dynamic" id="dynamic" <?php checked($values['show_count'], 'dynamic') ?> name="show_count" onchange="javascript:frm_show_count(this.value)" /> <?php _e('Both (Dynamic) &mdash; list the entries that will link to a single entry page', 'formidable'); ?>.</label></p>
            <p><label for="calendar"><input type="radio" value="calendar" id="calendar" <?php checked($values['show_count'], 'calendar') ?> name="show_count" onchange="javascript:frm_show_count(this.value)" /> <?php _e('Calendar &mdash; insert entries into a calendar', 'formidable'); ?>.</label></p>
            </fieldset>
        
            <div id="date_select_container">
                <?php _e('Date Field', 'formidable'); ?>
                <select id="date_field_id" name="options[date_field_id]">
                    <option value="created_at" <?php selected($values['date_field_id'], 'created_at') ?>><?php _e('Entry creation date', 'formidable') ?></option>
                    <option value="updated_at" <?php selected($values['date_field_id'], 'updated_at') ?>><?php _e('Entry update date', 'formidable') ?></option>
                    <?php if (is_numeric($values['form_id'])) FrmProFieldsHelper::get_field_options($values['form_id'], $values['date_field_id'], '', "'date'"); ?>
                </select>
                <br/>
                <?php _e('End Date or day count', 'formidable'); ?>
                <select id="date_field_id" name="options[edate_field_id]">
                    <option value=""><?php _e('No multi-day events', 'formidable') ?></option>
                    <option value="created_at" <?php selected($values['edate_field_id'], 'created_at') ?>><?php _e('Entry creation date', 'formidable') ?></option>
                    <option value="updated_at" <?php selected($values['edate_field_id'], 'updated_at') ?>><?php _e('Entry update date', 'formidable') ?></option>
                    <?php if (is_numeric($values['form_id'])) FrmProFieldsHelper::get_field_options($values['form_id'], $values['edate_field_id'], '', "'date','number'"); ?>
                </select>
            </div>
        </td>
    </tr>
    <tr class="hide_dyncontent">
        <th valign="top" scope="row"><?php _e('Detail Link', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php printf(__('Example: If parameter name is \'contact\', the url would be like %1$s/selected-page?contact=2. If this entry is linked to a post, the post permalink will be used instead.', 'formidable'), $frm_siteurl) ?>" /></th>
        <td>
            <?php if( FrmProAppHelper::rewriting_on() && $frmpro_settings->permalinks){ ?>
                <select id="type" name="type">
                    <option value=""></option>
                    <option value="id" <?php selected($values['type'], 'id') ?>><?php _e('ID', 'formidable'); ?></option>
                    <option value="display_key" <?php selected($values['type'], 'display_key') ?>><?php _e('Key', 'formidable'); ?></option>
                </select> 
                <p class="description"><?php printf(__('Select the value that will be added onto the page URL. This will create a pretty URL like %1$s/selected-page/entry-key', 'formidable'), $frm_siteurl); ?></p>
            <?php }else{ ?>
                <?php _e('Parameter Name', 'formidable'); ?>: 
                <input type="text" id="param" name="param" value="<?php echo esc_attr($values['param']) ?>">

                <?php _e('Parameter Value', 'formidable'); ?>:
                <select id="type" name="type">
                    <option value=""></option>
                    <option value="id" <?php selected($values['type'], 'id') ?>><?php _e('ID', 'formidable'); ?></option>
                    <option value="display_key" <?php selected($values['type'], 'display_key') ?>><?php _e('Key', 'formidable'); ?></option>
                </select>
            <?php } ?>
        </td>
    </tr>
</table>
</div>
</div>

<div class="postbox ">
<h3 class="hndle"><span><?php _e('Content', 'formidable') ?></span></h3>
<div class="inside">
<table class="form-table">
<tbody>    
    <tr class="form-field">
        <th valign="top" scope="row"><?php _e('Before Content', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('This content will not be repeated. This would be a good place to put any HTML table tags.', 'formidable') ?>" /><br/>(<?php _e('optional', 'formidable') ?>)</th>
        <td valign="top">
            <textarea id="before_content" name="options[before_content]" rows="3" style="width:98%"><?php echo FrmAppHelper::esc_textarea($values['before_content']) ?></textarea>
        </td>
    </tr>
    
    <tr>
        <th valign="top" scope="row"><?php _e('Content', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('The HTML for your page. If \'All Entries\' is selected above, this content will be repeated for each entry. The field ID and Key work synonymously, although there are times one choice may be better. If you are panning to copy your custom display settings to other blogs, use the Key since they will be copied and the ids may differ from blog to blog.', 'formidable') ?>" /></th>
        <td valign="top">
            <div id="content_fields">
            <?php if (is_numeric($values['form_id'])) FrmProFieldsHelper::get_shortcode_select($values['form_id']); ?> 
            </div>
            <div id="<?php echo (user_can_richedit()) ? 'postdivrich' : 'postdiv'; ?>" class="postarea frm_full_rte">
            <?php 
            if(function_exists('wp_editor'))
                wp_editor($values['content'], 'content');
            else
                the_editor($values['content'], 'content', 'title', false); 
            ?>
            </div>
        </td>
    </tr>
    
    <tr class="form-field">
        <th valign="top" scope="row"><?php _e('After Content', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('This content will not be repeated. This would be a good place to close any HTML tags from the Before Content field.', 'formidable') ?>" /><br/>(<?php _e('optional', 'formidable') ?>)</th>
        <td valign="top">
            <textarea id="after_content" name="options[after_content]" rows="3" style="width:98%"><?php echo FrmAppHelper::esc_textarea($values['after_content']) ?></textarea>
        </td>
    </tr>
    
    <tr class="form-field hide_dyncontent">
        <th valign="top" scope="row"><?php _e('Dynamic Content', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php printf(__('The HTML for the entry on the dynamic page. This content will NOT be repeated, and will only show when the %1$s is clicked.', 'formidable'), '[detaillink]') ?>" /></th>
        <td valign="top">
            <div id="dyncontent_fields">
            <?php if (is_numeric($values['form_id'])) FrmProFieldsHelper::get_shortcode_select($values['form_id'], 'dyncontent'); ?>
            </div>
            <?php 
            if(function_exists('wp_editor')){
                wp_editor($values['dyncontent'], 'dyncontent' );
            }else{
            ?>
            <textarea id="dyncontent" name="dyncontent" rows="15" style="width:98%"><?php echo FrmAppHelper::esc_textarea($values['dyncontent']) ?></textarea>
            <?php } ?>
        </td>
    </tr>
    
</tbody>
</table>
</div>
</div>

<div class="postbox ">
<h3 class="hndle"><span><?php _e('Advanced', 'formidable') ?></span></h3>
<div class="inside">
<table class="form-table">     
    
    <tr class="form-field" id="order_by_container">
        <th valign="top" scope="row"><?php _e('Order', 'formidable'); ?> </th>
        <td>
            <select id="order_by" name="options[order_by]">
                <option value=""></option>
                <option value="rand" <?php selected($values['order_by'], 'rand') ?>><?php _e('Random', 'formidable') ?></option>
                <option value="created_at" <?php selected($values['order_by'], 'created_at') ?>><?php _e('Entry creation date', 'formidable') ?></option>
                <option value="updated_at" <?php selected($values['order_by'], 'updated_at') ?>><?php _e('Entry update date', 'formidable') ?></option>
                <?php if (is_numeric($values['form_id'])) FrmProFieldsHelper::get_field_options($values['form_id'], $values['order_by']); ?>
            </select>    

            <select id="order" name="options[order]">
                <option value=""></option>
                <option value="ASC" <?php selected($values['order'], 'ASC') ?>><?php _e('Ascending', 'formidable'); ?></option>
                <option value="DESC" <?php selected($values['order'], 'DESC') ?>><?php _e('Descending', 'formidable'); ?> &nbsp;</option>
            </select>    
        </td>
    </tr>
    
    <tr class="form-field" id="where_container">
        <th valign="top" scope="row"><?php _e('Where', 'formidable'); ?> 
            <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Narrow down which entries will be used.', 'formidable') ?>" />
        </th>
        <td>
            <div id="frm_where_options" class="tagchecklist" style="padding-bottom:8px;">
            <?php

              if(count($values['where']) > 0){
                foreach($values['where'] as $where_key => $where_field){
                  $this->add_where_row($where_key, $values['form_id'], $where_field, $values['where_is'][$where_key], $values['where_val'][$where_key]);
                }
              }

            ?>
            </div>
            <p><a href="javascript:frm_add_where_row();" class="button">+ <?php _e('Add', 'formidable') ?></a></p>
        </td>
    </tr>
    
    <tr class="limit_container">
        <th valign="top" scope="row"><?php _e('Limit', 'formidable'); ?> 
            <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('If you don’t want all your entries displayed, you can insert the number limit here. Leave blank if you’d like all entries shown.', 'formidable') ?>" />
        </th>
        <td>
            <input type="text" id="limit" name="options[limit]" value="<?php echo esc_attr($values['limit']) ?>" size="4" />
        </td>
    </tr>
    
    <tr class="limit_container">
        <th valign="top" scope="row"><?php _e('Page Size', 'formidable'); ?>
            <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('The number of entries to show per page. Leave blank to not use pagination.', 'formidable') ?>" />
        </th>
        <td>
            <input type="text" id="limit" name="options[page_size]" value="<?php echo esc_attr($values['page_size']) ?>" size="4" />
        </td>
    </tr>
    
    <tr class="form-field">
        <th valign="top" scope="row"><?php _e('Message if nothing to display', 'formidable'); ?></th>
        <td>
            <textarea id="empty_msg" name="options[empty_msg]" style="width:98%"><?php echo FrmAppHelper::esc_textarea($values['empty_msg']) ?></textarea>
        </td>
    </tr>
    
    <?php if (IS_WPMU){
        if (FrmAppHelper::is_super_admin()){ ?>    
        <tr class="form-field">
            <th valign="top" scope="row"><?php _e('Copy', 'formidable'); ?></th>
            <td>
                <input type="checkbox" id="copy" name="options[copy]" value="1" <?php checked($values['copy'], 1) ?> />
                <?php _e('Copy these display settings to other blogs when Formidable Pro is activated. <br/>Note: Use only field keys in the content box(es) above.', 'formidable') ?>
            </td>
        </tr>
        <?php }else if ($values['copy']){ ?>
        <input type="hidden" id="copy" name="options[copy]" value="1" />
        <?php }
    } ?>

</table>
</div>
</div>

</div>
<?php
    if(version_compare( $GLOBALS['wp_version'], '3.3.2', '>'))
        require(FRMPRO_VIEWS_PATH .'/displays/sidebar.php'); 
?>
</div>
</div>
<div style="clear:both"></div>

<p class="submit">
    <input type="submit" value="<?php echo esc_attr($submit) ?>" class="button-primary" />
    <?php _e('or', 'formidable') ?>
    <a class="button-secondary cancel" href="?page=formidable-entry-templates"><?php _e('Cancel', 'formidable') ?></a>
</p>

<script type="text/javascript">
jQuery(document).ready(function($){
$('.hide_dyncontent,#entry_select_container,#date_select_container').hide();
var show_count = $("input[name='show_count']:checked").val();
if(show_count=='dynamic') $('.hide_dyncontent').show();
else if(show_count=='one'){ $('#entry_select_container').show();$(".limit_container").hide();}
else if(show_count=='calendar'){$('.hide_dyncontent,#date_select_container').show();
$(".limit_container").hide();}

$("#post_select_container").hide();
if($("#insert_loc").val() != 'none') $("#post_select_container").show();
    
});

function frm_show_loc(val){
if(val=='none') jQuery("#post_select_container").fadeOut('slow');
else jQuery("#post_select_container").fadeIn('slow');
}

function frm_show_count(value){
if(value=='dynamic' || value=='calendar'){ jQuery('.hide_dyncontent').fadeIn('slow');}
else jQuery(".hide_dyncontent").fadeOut('slow');       
if(value=='one'){jQuery('#entry_select_container').fadeIn('slow');jQuery(".limit_container").fadeOut('slow');}
else{jQuery("#entry_select_container").fadeOut('slow');jQuery(".limit_container").fadeIn('slow');}
if(value=='calendar'){jQuery("#date_select_container").fadeIn('slow');jQuery(".limit_container").fadeOut('slow');}
else{jQuery("#date_select_container").fadeOut('slow');}
}

function frm_add_where_row(){
form_id = jQuery('#form_id').val();
jQuery.ajax({type:"POST",url:"<?php echo $frm_ajax_url ?>",
data:"action=frm_add_where_row&form_id="+form_id+"&where_key="+jQuery('#frm_where_options > div').size(),
success:function(html){jQuery('#frm_where_options').append(html);}
});
}

function frm_insert_where_options(value,where_key){
jQuery.ajax({type:"POST",url:"<?php echo $frm_ajax_url ?>",
data:"action=frm_add_where_options&where_key="+where_key+"&field_id="+value,
success: function(html){jQuery('#where_field_options_'+where_key).html(html);}
}); 
}

function frmClearDefault(default_value,thefield){if(thefield.value==default_value){thefield.value='';thefield.style.color="inherit"}}
function frmReplaceDefault(default_value,thefield){if(thefield.value==''){thefield.value=default_value; thefield.style.color="#aaa"}};
</script>