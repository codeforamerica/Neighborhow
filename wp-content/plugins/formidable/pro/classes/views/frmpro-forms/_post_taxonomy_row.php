<div id="frm_posttax_<?php echo $field_vars['meta_name'] . $tax_key ?>" class="frm_posttax_row">
    <span><a href="javascript:frm_remove_tag('#frm_posttax_<?php echo $field_vars['meta_name'] . $tax_key ?>');"> X </a></span>
    &nbsp;
    
    <?php 
    $selected_type = '';
    
    _e('Populate', 'formidable') ?>
    <select name="options[post_category][<?php echo $field_vars['meta_name'] . $tax_key ?>][field_id]">
        <option value="">- <?php echo _e('Select Field', 'formidable') ?> -</option>
        <option value="checkbox">- <?php echo _e('A New Checkbox Field', 'formidable') ?> -</option>
        <?php
        if(!empty($values['fields'])){
        foreach($values['fields'] as $fo){
            if(is_object($fo)){
                $fo->field_options = maybe_unserialize($fo->field_options);
                if(isset($fo->field_options['form_select']))
                    $fo->form_select = $fo->field_options['form_select'];
                $fo = (array)$fo;
            }
            if(in_array($fo['type'], array('checkbox', 'radio', 'select', 'tag')) or ($fo['type'] == 'data' and isset($fo['form_select']) and $fo['form_select'] == 'taxonomy')){ 
        ?>
            <option value="<?php echo $fo['id'] ?>" <?php selected($field_vars['field_id'], $fo['id']) ?>><?php echo FrmAppHelper::truncate($fo['name'], 80) ?></option>
        <?php
            if($field_vars['field_id'] == $fo['id'])
                $selected_type = $fo['type'];
            }
            unset($fo);
        } 
        }
        ?>
    </select>

    <?php _e('with taxonomies from', 'formidable'); ?>
    <?php if(isset($taxonomies) and $taxonomies){ ?>
       <select name="options[post_category][<?php echo $field_vars['meta_name'] . $tax_key ?>][meta_name]">
       <?php foreach($taxonomies as $taxonomy){ ?>
           <option value="<?php echo $taxonomy ?>" <?php selected($field_vars['meta_name'], $taxonomy) ?>><?php echo str_replace(array('_','-'), ' ', ucfirst($taxonomy)) ?></option>
       <?php } ?>
       </select>
    <?php } ?>

<?php if($selected_type != 'tag'){ ?>
    <input type="checkbox" value="1" name="options[post_category][<?php echo $field_vars['meta_name'] . $tax_key ?>][show_exclude]" <?php echo (isset($field_vars['exclude_cat']) and $field_vars['exclude_cat'] and !empty($field_vars['exclude_cat'])) ? 'checked="checked"' : ''; ?> onchange="frm_show_div('frm_exclude_cat_list_<?php echo $field_vars['meta_name'] . $tax_key ?>',this.checked,1,'#')" /> <?php _e('Exclude options', 'formidable'); ?>
    
    <div class="frm_exclude_cat_<?php echo $field_vars['meta_name'] . $tax_key ?> with_frm_style">
        <div id="frm_exclude_cat_list_<?php echo $field_vars['meta_name'] . $tax_key ?>" class="frm_exclude_cat_list" style="margin:5px 10px 10px 0;<?php echo (isset($field_vars['exclude_cat']) and $field_vars['exclude_cat'] and !empty($field_vars['exclude_cat'])) ? '' : 'display:none;'; ?>">

            <?php if($selected_type != 'data'){ ?>
            <p class="howto check_lev1_label" style="margin-bottom:2px;display:none;"><?php _e('NOTE: if the parent is excluded, child categories will be automatically excluded.', 'formidable') ?></p>
            <?php } ?>
            <label for="check_all_<?php echo $field_vars['meta_name'] . $tax_key ?>"><input type="checkbox" id="check_all_<?php echo $field_vars['meta_name'] . $tax_key ?>" onclick="frmCheckAll(this.checked,'options[post_category][<?php echo $field_vars['meta_name'] . $tax_key ?>][exclude_cat]')" /> <span class="howto" style="float:none;"><?php _e('Check All', 'formidable') ?></span></label>
            
            <?php for($i=1;$i<5;$i++){ ?>
                <label for="check_lev<?php echo $i ?>_<?php echo $field_vars['meta_name'] . $tax_key ?>" class="check_lev<?php echo $i ?>_label" style="display:none;"><input type="checkbox" id="check_lev<?php echo $i ?>_<?php echo $field_vars['meta_name'] . $tax_key ?>" class="frm_check_all" value="0" name="options[exclude_cat_<?php echo $field_vars['meta_name'] . $tax_key ?>][]" onclick="frmCheckAllLevel(this.checked,'options[post_category][<?php echo $field_vars['meta_name'] . $tax_key ?>][exclude_cat]',<?php echo $i ?>)" /> <span class="howto" style="float:none;"><?php echo __('Check All Level', 'formidable') .' '. $i; ?></span></label>   
            <?php } ?>
        <div style="border:1px solid #DFDFDF; padding:5px; overflow:auto; max-height:200px;">
           <?php
                FrmProFieldsHelper::get_child_checkboxes(array('field' => array('post_field' => 'post_category', 'form_id' => $values['id'], 'field_options' => array('taxonomy' => $field_vars['meta_name']), 'type' => 'checkbox'), 'field_name' => 'options[post_category]['. $field_vars['meta_name'] . $tax_key .'][exclude_cat]', 'value' => (isset($field_vars['exclude_cat'])? $field_vars['exclude_cat'] : 0), 'exclude' => 'no', 'hide_id' => true)); 
           ?>
        </div>
        </div>  
    </div>
<?php 
unset($selected_type);
} 
?>
</div>