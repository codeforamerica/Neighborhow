<?php
class FrmProEntry{

    function FrmProEntry(){
        add_filter('frm_continue_to_new', array(&$this, 'frmpro_editing'), 10, 3);
        add_action('frm_validate_entry', array(&$this, 'pre_validate'), 10, 2);
        add_action('frm_validate_form_creation', array(&$this, 'validate'), 10, 5);
        add_action('frm_after_create_entry', array(&$this, 'set_cookie'), 20, 2);
        add_action('frm_after_create_entry', array(&$this, 'create_post'), 40, 2);
        add_action('frm_after_update_entry', array(&$this, 'update_post'), 40, 2);
        add_action('frm_before_destroy_entry', array(&$this, 'destroy_post'));
    }
    
    function frmpro_editing($continue, $form_id, $action='new'){
        //Determine if this is a new entry or if we're editing an old one
        $form_submitted = FrmAppHelper::get_param('form_id');
        if ($action == 'new' or $action == 'preview')
            $continue = true;
        else
            $continue = (is_numeric($form_submitted) and (int)$form_id != (int)$form_submitted) ? true : false;
        
        return $continue;
    }
    
    function user_can_edit($entry, $form){
        $allowed = FrmProEntry::user_can_edit_check($entry, $form);
        return apply_filters('frm_user_can_edit', $allowed, compact('entry', 'form'));
    }
    
    function user_can_edit_check($entry, $form){
        global $user_ID;
        
        if(!$user_ID)
            return false;
            
        if(is_numeric($form))
            $form = FrmForm::getOne($form);
            
        $form->options = maybe_unserialize($form->options);
        
        //if editable and user can edit someone elses entry
        if($form->editable and isset($form->options['open_editable']) and $form->options['open_editable'] and isset($form->options['open_editable_role']) and FrmAppHelper::user_has_permission($form->options['open_editable_role']))
            return true;
            
        if(is_object($entry)){
            if($entry->user_id == $user_ID)
                return true;
            else
                return false;
        }        
        
        $where = "user_id='$user_ID' and fr.id='$form->id'";
        if ($entry and !empty($entry)){
            if(is_numeric($entry))
                $where .= ' and it.id='. $entry;
            else
                $where .= " and item_key='" . $entry ."'";
        }

        return FrmEntry::getAll( $where, '', ' LIMIT 1', true);
    }
    
    function get_tagged_entries($term_ids, $args = array()){
        return get_objects_in_term( $term_ids, 'frm_tag', $args );
    }
    
    function get_entry_tags($entry_ids, $args = array()){
        return wp_get_object_terms( $entry_ids, 'frm_tag', $args );
    }
    
    function get_related_entries($entry_id){
        $term_ids = FrmProEntry::get_entry_tags($entry_id, array('fields' => 'ids'));
        $entry_ids = FrmProEntry::get_tagged_entries($term_ids);
        foreach ($entry_ids as $key => $id){
            if ($id == $entry_id)
                unset($entry_ids[$key]);
        }
        return $entry_ids;
    }

    function pre_validate($errors, $values){
        global $user_ID, $frm_entry_meta, $frm_entry, $frmdb, $frm_form, $frmpro_settings, $frm_form_params;
        
        $params = $frm_form_params[$values['form_id']];
        if($params['action'] != 'create')
            return $errors;
        
        $form = $frm_form->getOne($values['form_id']);
        $form_options = stripslashes_deep(maybe_unserialize($form->options));
        
        $can_submit = true;
        if (isset($form_options['single_entry']) and $form_options['single_entry']){
            if ($form_options['single_entry_type'] == 'cookie' and isset($_COOKIE['frm_form'. $form->id . '_' . COOKIEHASH])){
                $can_submit = false;
            }else if ($form_options['single_entry_type'] == 'ip'){
                $prev_entry = $frm_entry->getAll(array('it.ip' => $_SERVER['REMOTE_ADDR']), '', 1);
                if ($prev_entry)
                    $can_submit = false;
            }else if ($form_options['single_entry_type'] == 'user' and !$form->editable){
                if($user_ID)
                    $meta = $frmdb->get_var($frmdb->entries, array('user_id' => $user_ID, 'form_id' => $form->id));
                
                if (isset($meta) and $meta)
                    $can_submit = false;
            }else if (is_numeric($form_options['single_entry_type'])){
                $field_id = $form_options['single_entry_type'];
                $meta = $frm_entry_meta->getAll("meta_value='". $_POST['item_meta'][$field_id] ."' and fi.id='$field_id' and fi.form_id='$form->id'", '', ' LIMIT 1');
                if ($meta)
                    $can_submit = false;
                unset($meta);
            }
            
            if (!$can_submit){
                $k = is_numeric($form_options['single_entry_type']) ? 'field'. $form_options['single_entry_type'] : 'single_entry';
                $errors[$k] = stripslashes($frmpro_settings->already_submitted);
                add_filter('frm_continue_to_create', create_function('', 'return false;'));
                return $errors;
            }
        }
        unset($can_submit);
        
        if ((isset($_POST) and isset($_POST['frm_page_order_'. $form->id]))){
            add_filter('frm_continue_to_create', create_function('', 'return false;'));
        }else if ($form->editable and isset($form_options['single_entry']) and $form_options['single_entry'] and $form_options['single_entry_type'] == 'user' and $user_ID){
            $meta = $frmdb->get_var($frmdb->entries, array('user_id' => $user_ID, 'form_id' => $form->id));
            
            if($meta){
                $errors['single_entry'] = stripslashes($frmpro_settings->already_submitted);
                add_filter('frm_continue_to_create', create_function('', 'return false;'));
            }
        }
        return $errors;
    }
        
    function validate($params, $fields, $form, $title, $description){
        global $user_ID, $frm_entry_meta, $frm_entry, $frm_settings, $frmpro_settings, $frmpro_entries_controller, $frmdb;
        $form_options = stripslashes_deep(maybe_unserialize($form->options));
        
        $can_submit = true;
        if (isset($form_options['single_entry']) and $form_options['single_entry']){
            if ($form_options['single_entry_type'] == 'cookie' and isset($_COOKIE['frm_form'. $form->id . '_' . COOKIEHASH])){
                $can_submit = false;
            }else if ($form_options['single_entry_type'] == 'ip'){
                $prev_entry = $frm_entry->getAll(array('it.ip' => $_SERVER['REMOTE_ADDR']), '', 1);
                if ($prev_entry)
                    $can_submit = false;
            }else if ($form_options['single_entry_type'] == 'user' and !$form->editable){
                $meta = $frmdb->get_var($frmdb->entries, array('user_id' => $user_ID, 'form_id' => $form->id));
                if ($meta)
                    $can_submit = false;
            }else if (is_numeric($form_options['single_entry_type'])){
                $field_id = $form_options['single_entry_type'];
                $meta = $frm_entry_meta->getAll("meta_value='". $_POST['item_meta'][$field_id] ."' and fi.id='$field_id' and fi.form_id='$form->id'", '', ' LIMIT 1');
                if ($meta)
                    $can_submit = false;
            }
            
            if (!$can_submit){
                echo stripslashes($frmpro_settings->already_submitted); //TODO: DO SOMETHING IF USER CANNOT RESUBMIT FORM
                add_filter('frm_continue_to_create', create_function('', 'return false;'));
                return;
            }
        }
        
        if (isset($_POST) and isset($_POST['frm_page_order_'. $form->id])){
            global $frm_next_page;
            $errors = '';
            $fields = FrmFieldsHelper::get_form_fields($form->id);
            $form_name = $form->name;
            $submit = isset($form_options['submit_value']) ? $form_options['submit_value'] : $frm_settings->submit_value;
            $values = FrmEntriesHelper::setup_new_vars($fields, $form);
            require(FRM_VIEWS_PATH.'/frm-entries/new.php');
            add_filter('frm_continue_to_create', create_function('', 'return false;'));
        }else if ($form->editable and isset($form_options['single_entry']) and $form_options['single_entry'] and $form_options['single_entry_type'] == 'user'){
            global $frm_created_entry;
            
            if($user_ID){
                $entry = $frm_entry->getAll(array('it.user_id' => $user_ID, 'it.form_id' => $form->id), '', 1, true);
                if($entry)
                    $entry = reset($entry);
            }else{
                $entry = false;
            }
            
            if ($entry and !empty($entry) and (!isset($frm_created_entry[$form->id]['entry_id']) or $entry->id != $frm_created_entry[$form->id]['entry_id'])){
                $frmpro_entries_controller->show_responses($entry, $fields, $form, $title, $description);
            }else{
                $record = $frm_created_entry[$form->id]['entry_id'];
                $saved_message = isset($form_options['success_msg']) ? $form_options['success_msg'] : $frm_settings->success_msg;
                $message = ($record) ? $saved_message : $frm_settings->failed_msg;
                $frmpro_entries_controller->show_responses($record, $fields, $form, $title, $description, $message, '', $form_options);
            }
            add_filter('frm_continue_to_create', create_function('', 'return false;'));
        }
    }
    
    function set_cookie($entry_id, $form_id){
        //if form options['single] or isset($_POST['frm_single_submit']){
        if(defined('WP_IMPORTING') or defined('DOING_AJAX')) return;
        
        if(isset($_POST) and isset($_POST['frm_skip_cookie'])){
            if(!headers_sent())
                FrmProEntriesController::set_cookie($entry_id, $form_id);
            return;
        }
?>
<script type="text/javascript">
jQuery(document).ready(function($){
jQuery.ajax({type:"POST",url:"<?php echo FRM_SCRIPT_URL; ?>",
data:"controller=entries&frm_action=ajax_set_cookie&entry_id=<?php echo $entry_id; ?>&form_id=<?php echo $form_id; ?>"
});
});    
</script>
<?php
        //}
    }
    
    function update_post($entry_id, $form_id){
        if(isset($_POST['frm_wp_post'])){
            global $frmdb;
            $post_id = $frmdb->get_var($frmdb->entries, array('id' => $entry_id), 'post_id');
            if($post_id){
                $post = get_post($post_id, ARRAY_A);
                $this->insert_post($entry_id, $post, true);
            }else{
                $this->create_post($entry_id, $form_id);
            }
        }
    }
    
    function create_post($entry_id, $form_id){
        global $wpdb, $frmdb;
        $post_id = NULL;
        if(isset($_POST['frm_wp_post'])){
            $post = array();
            $post['post_type'] = FrmProForm::post_type($form_id);
            if(isset($_POST['frm_user_id']) and is_numeric($_POST['frm_user_id']))
                $post['post_author'] = $_POST['frm_user_id'];
            
            $status = false;
            foreach($_POST['frm_wp_post'] as $post_data => $value){
                if($status)
                    continue;
                    
                $post_data = explode('=', $post_data);
                
                if($post_data[1] == 'post_status')
                    $status = true;
            }
            
            if(!$status){
                $form_options = $frmdb->get_var($frmdb->forms, array('id' => $form_id), 'options');
                $form_options = maybe_unserialize($form_options);
                if(isset($form_options['post_status']) and $form_options['post_status'] == 'publish')
                    $post['post_status'] = 'publish';
            }
            
            $post_id = $this->insert_post($entry_id, $post);
        }
        //save post_id with the entry
        $updated = $wpdb->update( $frmdb->entries, array('post_id' => $post_id), array( 'id' => $entry_id ) );
        if($updated)
            wp_cache_delete( $entry_id, 'frm_entry' );
    }
    
    function insert_post($entry_id, $post, $editing=false){
        $field_ids = $new_post = array();

        foreach($_POST['frm_wp_post'] as $post_data => $value){
            $post_data = explode('=', $post_data);
            $field_ids[] = $post_data[0];
            
            if(isset($new_post[$post_data[1]]))
                $value = array_merge((array)$value, (array)$new_post[$post_data[1]]);
            
            $post[$post_data[1]] = $new_post[$post_data[1]] = $value;
            //delete the entry meta below so it won't be stored twice
        }
            
        $post_ID = wp_insert_post( $post );
    	
    	if ( is_wp_error( $post_ID ) or empty($post_ID))
    	    return;
    	
    	global $frm_entry_meta, $user_ID, $frm_media_id;
    	
    	if($frm_media_id and !empty($frm_media_id)){
    	    global $wpdb;
    	    //link the uploads to the post
    	    foreach($frm_media_id as $media_id)
    	        $wpdb->update( $wpdb->posts, array('post_parent' => $post_ID), array( 'ID' => $media_id ) );
    	}

    	if($editing and count($_FILES) > 0){
    	    global $wpdb;
    	    $args = array( 
    	        'post_type' => 'attachment', 'numberposts' => -1, 
    	        'post_status' => null, 'post_parent' => $post_ID, 
    	        'exclude' => $frm_media_id
    	    ); 

            $attachments = get_posts( $args );
            foreach($attachments as $attachment)
                $wpdb->update( $wpdb->posts, array('post_parent' => null), array( 'ID' => $attachment->ID ) );
    	}
    	
    	if(isset($_POST['frm_tax_input'])){
            foreach ($_POST['frm_tax_input'] as $taxonomy => $tags ) {
    		    if ( is_array($tags) ) // array = hierarchical, string = non-hierarchical.
    				$tags = array_keys($tags);
    				
    			wp_set_post_terms( $post_ID, $tags, $taxonomy );
    		}
        }

    	if(isset($_POST['frm_wp_post_custom'])){
        	foreach($_POST['frm_wp_post_custom'] as $post_data => $value){
        	    $post_data = explode('=', $post_data);
                $field_id = $post_data[0];

                update_post_meta($post_ID, $post_data[1], maybe_serialize($value));
            	$frm_entry_meta->delete_entry_meta($entry_id, $field_id); 
            }
        }
        
        foreach($field_ids as $field_id)
            $frm_entry_meta->delete_entry_meta($entry_id, $field_id); 
        
    	update_post_meta( $post_ID, '_edit_last', $user_ID );
    	return $post_ID;
    }
    
    function destroy_post($entry_id){
        global $frmdb;
        $entry = $frmdb->get_one_record($frmdb->entries, array('id' => $entry_id), 'post_id');
        if($entry and is_numeric($entry->post_id))
          wp_delete_post($entry->post_id);
    }
    
    function create_comment($entry_id, $form_id){
        global $user_ID;
        
        $comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;

        $post = get_post($comment_post_ID);

        if ( empty($post->comment_status) )
        	return;

        // get_post_status() will get the parent status for attachments.
        $status = get_post_status($post);

        $status_obj = get_post_status_object($status);

        if ( !comments_open($comment_post_ID) ) {
        	do_action('comment_closed', $comment_post_ID);
        	//wp_die( __('Sorry, comments are closed for this item.') );
        	return;
        } elseif ( 'trash' == $status ) {
        	do_action('comment_on_trash', $comment_post_ID);
        	return;
        } elseif ( !$status_obj->public && !$status_obj->private ) {
        	do_action('comment_on_draft', $comment_post_ID);
        	return;
        } elseif ( post_password_required($comment_post_ID) ) {
        	do_action('comment_on_password_protected', $comment_post_ID);
        	return;
        } else {
        	do_action('pre_comment_on_post', $comment_post_ID);
        }

        $comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : '';

        // If the user is logged in
        if ( $user_ID ) {
            global $current_user;
        
        	$display_name = (!empty( $current_user->display_name )) ? $current_user->display_name : $current_user->user_login;
        	$comment_author       = $wpdb->escape($display_name);
        	$comment_author_email = ''; //get email from field
        	$comment_author_url   = $wpdb->escape($user->user_url);
        }else{
            $comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : '';
            $comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : '';
            $comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : '';
        }

        $comment_type = '';

        if (!$user_ID and get_option('require_name_email') and (6 > strlen($comment_author_email) || $comment_author == '') )
        		return;

        if ( $comment_content == '')
        	return;


        $commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'user_ID');

        $comment_id = wp_new_comment( $commentdata );
 
    }
}
?>