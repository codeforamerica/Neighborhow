<?php

class FrmProCopy{
    var $table_name;

    function FrmProCopy(){
      global $wpmuBaseTablePrefix, $wpdb;
      if($wpmuBaseTablePrefix)
          $prefix = $wpmuBaseTablePrefix;
      else
          $prefix = $wpdb->base_prefix;
      $this->table_name = "{$prefix}frmpro_copies";
    }
    
    function create( $values ){
        global $wpdb, $blog_id, $frm_form, $frmpro_display;
        
        $exists = $wpdb->query("DESCRIBE {$this->table_name}");
        if(!$exists)
            $this->install(true);
        unset($exists);
            
        $new_values = array();
        $new_values['blog_id'] = $blog_id;
        $new_values['form_id'] = isset($values['form_id']) ? (int)$values['form_id']: null;
        $new_values['type'] = isset($values['type']) ? $values['type']: 'form'; //options here are: form, display
        if ($new_values['type'] == 'form'){
            $form_copied = $frm_form->getOne($new_values['form_id']);
            $new_values['copy_key'] = $form_copied->form_key;
        }else{
            $form_copied = $frmpro_display->getOne($new_values['form_id']);
            $new_values['copy_key'] = $form_copied->display_key;
        }
        $new_values['created_at'] = current_time('mysql', 1);
        
        $exists = $this->getAll("blog_id='$blog_id' and form_id='".$new_values['form_id']."' and type='".$new_values['type']."'", '', ' LIMIT 1');
        $query_results = false;
        if (!$exists)
            $query_results = $wpdb->insert( $this->table_name, $new_values );

        if($query_results)
            return $wpdb->insert_id;
        else
           return false;
    }
    
    function destroy( $id ){
      global $wpdb;
      return $wpdb->query("DELETE FROM $this->table_name WHERE id=$id");
    }
    
    function getAll($where = '', $order_by = '', $limit = ''){
        global $wpdb, $frm_form, $frm_app_helper;
        $query = "SELECT * FROM $this->table_name ". 
                $frm_app_helper->prepend_and_or_where(' WHERE ', $where) . $order_by . $limit;
        if ($limit == ' LIMIT 1')
            $results = $wpdb->get_row($query);
        else
            $results = $wpdb->get_results($query);
        return $results;
    }
    
    function install($force=false){
        $db_version = 1.2; // this is the version of the database we're moving to
        $old_db_version = get_site_option('frmpro_copies_db_version');

        global $wpdb, $blog_id;
        
        if (($db_version != $old_db_version) or $force){
            $force = true;
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            $charset_collate = '';
            if( $wpdb->has_cap( 'collation' ) ){
                if( !empty($wpdb->charset) )
                  $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
                if( !empty($wpdb->collate) )
                  $charset_collate .= " COLLATE $wpdb->collate";
            }

            /* Create/Upgrade Display Table */
            $sql = "CREATE TABLE `{$this->table_name}` (
                    `id` int(11) NOT NULL auto_increment,
                    `type` varchar(255) default NULL,
                    `copy_key` varchar(255) default NULL,
                    `form_id` int(11) default NULL,
                    `blog_id` int(11) default NULL,
                    `created_at` datetime NOT NULL,
                    PRIMARY KEY `id` (`id`),
                    KEY `form_id` (`form_id`),
                    KEY `blog_id` (`blog_id`)
            ) {$charset_collate};";

            dbDelta($sql);

            update_site_option('frmpro_copies_db_version', $db_version);
        }

        //copy forms
        if(!$force){ //don't check on every page load
            $last_checked = get_option('frmpro_copies_checked');

            if(!$last_checked or ((time() - $last_checked) >= (60*60))) //check every hour
                $force = true;
        }
        
        if($force){        
            //get all forms to be copied from global table
            $templates = $this->getAll("blog_id !='$blog_id'", ' ORDER BY type DESC'); 

            foreach ($templates as $temp){
                if ($temp->type == 'form'){
                    global $frm_form;
                    if (!$frm_form->getOne($temp->copy_key))
                        $frm_form->duplicate($temp->form_id, false, true, $temp->blog_id);
                }else{
                    global $frmpro_display;
                    if (!$frmpro_display->getOne($temp->copy_key))
                        $frmpro_display->duplicate($temp->form_id, true, $temp->blog_id);

                    //TODO: replace any ids with field keys in the display before duplicated
                }
                unset($temp);
            }
                
            update_option('frmpro_copies_checked', time());
        }
    }
    
    function uninstall(){
        if(!current_user_can('administrator')){
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
        
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$this->table_name}");
        delete_option('frmpro_copies_db_version');
        delete_option('frmpro_copies_checked');
    }
}        
?>