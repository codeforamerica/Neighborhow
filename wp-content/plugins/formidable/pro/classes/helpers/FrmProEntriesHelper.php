<?php

class FrmProEntriesHelper{
    function FrmProEntriesHelper(){
        add_filter('frm_redirect_url', array(&$this, 'redirect_url'));
        add_filter('frm_show_new_entry_page',array(&$this, 'allow_form_edit'), 10, 2);
        add_filter('frm_setup_edit_entry_vars', array(&$this, 'setup_edit_vars'), 10, 2);
    }
    
    function redirect_url($url){
        $url = str_replace(array(' ', '[', ']', '|'), array('%20', '%5B', '%5D', '%7C'), $url);
        return $url;
    }
    
    function allow_form_edit($action, $form){
        global $user_ID;
        if (!$form or !$form->editable or !$user_ID)
            return $action;

        $form_options = maybe_unserialize($form->options);
        if (isset($form_options['single_entry']) and $form_options['single_entry'] and $form_options['single_entry_type'] == 'user' and $action != 'destroy'){
            if($action == 'update' and ($form->id == FrmAppHelper::get_param('form_id'))){
                //don't change the action is this is the wrong form
            }else{
                global $frmdb;
                $meta = $frmdb->get_var($frmdb->entries, array('user_id' => $user_ID, 'form_id' => $form->id));
                if($meta)
                    $action = 'edit';
            }
        }
       
        if ($action == 'edit' and isset($form_options['editable_role']) and !FrmAppHelper::user_has_permission($form_options['editable_role']))
            $action = 'new';

        return $action;
    }
    
    function allow_delete($entry){
        global $user_ID;
        
        $allowed = false;
        
        if(current_user_can('frm_delete_entries'))
            $allowed = true;
        
        if($user_ID and !$allowed){
            if(is_numeric($entry)){
                global $frmdb;

                $allowed = $frmdb->get_var( $frmdb->entries, array('id' => $entry, 'user_id' => $user_ID) );
            }else{
                $allowed = ($entry->user_id == $user_ID);
            }
        }
        
        return apply_filters('frm_allow_delete', $allowed, $entry);
    }
    
    function setup_edit_vars($values, $record=false){
        global $frm_form, $frmpro_settings;
        if(!$record)
            $record = $frm_form->getOne($values['form_id']);
        
        foreach (array('edit_value' => 'Update', 'edit_msg' => $frmpro_settings->edit_msg) as $opt => $default){
            if (!isset($values[$opt]))
                $values[$opt] = ($_POST and isset($_POST['options'][$opt])) ? $_POST['options'][$opt] : $default;
        }
        return $values;
    }
    
    function resend_email_links($entry_id, $form_id){ ?>
<a href="javascript:frm_resend_email(<?php echo $entry_id ?>,<?php echo $form_id ?>,'email')" id="frm_resend_email" title="<?php _e('Resend Email Notification', 'formidable') ?>"><?php _e('Resend Email Notification', 'formidable') ?></a><br/>
<a href="javascript:frm_resend_email(<?php echo $entry_id ?>,<?php echo $form_id ?>,'autoresponder')" id="frm_resend_autoresponder" title="<?php _e('Resend Autoresponse', 'formidable') ?>"><?php _e('Resend Autoresponse', 'formidable') ?></a>

<script type="text/javascript">
//<![CDATA[
function frm_resend_email(entry_id,form_id,type){
jQuery('#frm_resend_'+type).replaceWith('<img id="frm_resend_'+type+'" src="<?php echo FRM_IMAGES_URL; ?>/wpspin_light.gif" alt="<?php _e('Loading...', 'formidable'); ?>" />');
jQuery.ajax({type:"POST",url:"<?php echo FRM_SCRIPT_URL ?>",data:"controller=entries&frm_action=send_email&entry_id="+entry_id+"&form_id="+form_id+"&type="+type,
success:function(msg){ jQuery('#frm_resend_'+type).replaceWith('<?php _e('Email Resent to', 'formidable') ?> '+msg);}
});
}
//]]>
</script>
<?php
    }
    
    function before_table($footer, $form_id=false){
        if($footer)
            return;
            
        if ($_GET['page'] == 'formidable-entries'){ ?>
            <div class="alignleft actions">
            <?php FrmFormsHelper::forms_dropdown('frm_redirect_to_list', '', __('Switch Form', 'formidable'), false,  "frmRedirectToForm(this.value,'list')"); ?>
            </div>
            <?php            
            if ($form_id){ 
                if(current_user_can('frm_create_entries')){ ?>
                <div class="alignleft"><a href="?page=formidable-entries&amp;frm_action=new&amp;form=<?php echo $form_id ?>" class="button-secondary"><?php _e('Add New Entry to this form', 'formidable') ?></a></div>
            <?php } 
            }
        }else if($_GET['page'] == 'formidable-entry-templates' and $form_id){ ?>
            <div class="alignleft actions">
            <?php FrmFormsHelper::forms_dropdown('frm_redirect_to_list', '', __('Switch Form', 'formidable'), false,  "frmRedirectToDisplay(this.value,'list')"); ?>
            </div>
        <?php    
        }
    }
    
    function get_search_ids($s, $form_id){
        global $wpdb, $frmdb, $frm_entry_meta;
        
        if(empty($s)) return false;
        
        $s = stripslashes($s);
		preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $s, $matches);
		$search_terms = array_map('_search_terms_tidy', $matches[0]);
		$n = '%'; //!empty($q['exact']) ? '' : '%';
		
        $p_search = $search = '';
        $search_or = '';
        
        $data_field = FrmProForm::has_field('data', $form_id, false);
        
		foreach( (array) $search_terms as $term ) {
			$term = esc_sql( like_escape( $term ) );
			$p_search .= " AND (($wpdb->posts.post_title LIKE '{$n}{$term}{$n}') OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}'))";
			
			$search .= "{$search_or}meta_value LIKE '{$n}{$term}{$n}'";
            $search_or = ' OR ';
            
            if($data_field){
                $df_form_ids = array();
                
                //search the joined entry too
                foreach((array)$data_field as $df){
                    $df->field_options = maybe_unserialize($df->field_options);
                    if (is_numeric($df->field_options['form_select']))
                        $df_form_ids[] = $df->field_options['form_select'];
                    
                    unset($df);
                }
                
                global $wpdb, $frmdb;
                $data_form_ids = $wpdb->get_col("SELECT form_id FROM $frmdb->fields WHERE id in (". implode(',', $df_form_ids).")");
                unset($df_form_ids);
                
                if($data_form_ids){
                    $data_entry_ids = $frm_entry_meta->getEntryIds("fi.form_id in (". implode(',', $data_form_ids).") and meta_value LIKE '%". $term ."%'");
                    if($data_entry_ids)
                        $search .= "{$search_or}meta_value in (".implode(',', $data_entry_ids).")";
                }
                
                unset($data_form_ids);
            }
		}
		
		$p_ids = '';
		$matching_posts = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE 1=1 $p_search");
		if($matching_posts){
		    $p_ids = $wpdb->get_col("SELECT id from $frmdb->entries WHERE post_id in (". implode(',', $matching_posts) .") AND form_id='$form_id'");
		    $p_ids = ($p_ids) ? " OR item_id in (". implode(',', $p_ids) .")" : '';
		}
		
        return $frm_entry_meta->getEntryIds("(($search)$p_ids) and fi.form_id='$form_id'");
    }
    
    function encode_value($line, $from_encoding, $to_encoding){
        $convmap = false;
        
        switch($to_encoding){
            case 'macintosh':
            // this map was derived from the differences between the MacRoman and UTF-8 Charsets
            // Reference:
            //   - http://www.alanwood.net/demos/macroman.html
                $convmap = array(
                    256, 304, 0, 0xffff,
                    306, 337, 0, 0xffff,
                    340, 375, 0, 0xffff,
                    377, 401, 0, 0xffff,
                    403, 709, 0, 0xffff,
                    712, 727, 0, 0xffff,
                    734, 936, 0, 0xffff,
                    938, 959, 0, 0xffff,
                    961, 8210, 0, 0xffff,
                    8213, 8215, 0, 0xffff,
                    8219, 8219, 0, 0xffff,
                    8227, 8229, 0, 0xffff,
                    8231, 8239, 0, 0xffff,
                    8241, 8248, 0, 0xffff,
                    8251, 8259, 0, 0xffff,
                    8261, 8363, 0, 0xffff,
                    8365, 8481, 0, 0xffff,
                    8483, 8705, 0, 0xffff,
                    8707, 8709, 0, 0xffff,
                    8711, 8718, 0, 0xffff,
                    8720, 8720, 0, 0xffff,
                    8722, 8729, 0, 0xffff,
                    8731, 8733, 0, 0xffff,
                    8735, 8746, 0, 0xffff,
                    8748, 8775, 0, 0xffff,
                    8777, 8799, 0, 0xffff,
                    8801, 8803, 0, 0xffff,
                    8806, 9673, 0, 0xffff,
                    9675, 63742, 0, 0xffff,
                    63744, 64256, 0, 0xffff,
                );
            break;
            case 'ISO-8859-1':
                $convmap = array(256, 10000, 0, 0xffff);
            break;
        }
        
        if (is_array($convmap))
            $line = mb_encode_numericentity($line, $convmap, $from_encoding);
        
        if ($to_encoding != $from_encoding)
            return iconv($from_encoding, $to_encoding.'//IGNORE', $line);
        else
            return $line;
    }
}

?>