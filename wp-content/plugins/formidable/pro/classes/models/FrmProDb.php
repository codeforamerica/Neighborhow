<?php
class FrmProDb{
    var $displays;
    
    function FrmProDb(){
        global $wpdb;
        $this->displays = $wpdb->prefix . "frm_display";
    }
    
    function upgrade(){
      global $wpdb;
      $db_version = 15; // this is the version of the database we're moving to
      $old_db_version = get_option('frmpro_db_version');

      if ($db_version != $old_db_version){
          require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
          
          $charset_collate = '';
          if( $wpdb->has_cap( 'collation' ) ){
              if( !empty($wpdb->charset) )
                $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
              if( !empty($wpdb->collate) )
                $charset_collate .= " COLLATE $wpdb->collate";
          }

          /* Create/Upgrade Display Table */
          $sql = "CREATE TABLE {$this->displays} (
                id int(11) NOT NULL auto_increment,
                display_key varchar(255) default NULL,
                name varchar(255) default NULL,
                description text default NULL,
                content longtext default NULL,
                dyncontent longtext default NULL,
                insert_loc varchar(255) default NULL,
                param varchar(255) default NULL,
                type varchar(255) default NULL,
                show_count varchar(255) default NULL,
                options longtext default NULL,
                form_id int(11) default NULL,
                entry_id int(11) default NULL,
                post_id int(11) default NULL,
                created_at datetime NOT NULL,
                PRIMARY KEY id (id),
                KEY form_id (form_id),
                KEY entry_id (entry_id),
                KEY post_id (post_id),
                UNIQUE KEY display_key (display_key)
          ) {$charset_collate};";

          dbDelta($sql);
          
          if($db_version >= 3 and $old_db_version < 3){ //migrate hidden field data into the parent field
              $wpdb->update( $frmdb->fields, array('type' => 'scale'), array('type' => '10radio') );
              $fields = FrmField::getAll();
              foreach($fields as $field){
                  $field->field_options = maybe_unserialize($field->field_options);
                  if(isset($field->field_options['hide_field']) and is_numeric($field->field_options['hide_field']) and
                   ((isset($field->field_options['hide_opt']) and !empty($field->field_options['hide_opt'])) or
                    (isset($field->field_options['form_select']) and !empty($field->field_options['form_select'])))){
                      global $frm_field;
                      //save hidden fields to parent field
                      $parent_field = FrmField::getOne($field->field_options['hide_field']);
                      if($parent_field){
                          $parent_options = maybe_unserialize($parent_field->field_options);
                          if(!isset($parent_options['dependent_fields']))
                              $parent_options['dependent_fields'] = array();
                          else{
                              foreach($parent_options['dependent_fields'] as $child_id => $child_opt){
                                  if(empty($child_opt) or $child_opt == ''){
                                      unset($parent_options['dependent_fields'][$child_id]);
                                  }else if($child_id != $field->id){
                                      //check to make sure this field is still dependent
                                      $check_field = FrmField::getOne($child_id);
                                      $check_options = maybe_unserialize($check_field->field_options);
                                      if(!is_numeric($check_options['hide_field']) or $check_options['hide_field'] != $parent_field->id or (empty($check_options['hide_opt']) and empty($check_options['form_select'])))
                                         unset($parent_options['dependent_fields'][$child_id]); 
                                  }
                              }
                          }
                          
                          $dep_fields = array();
                          if($field->type == 'data' and isset($field->field_options['form_select']) and is_numeric($field_options['form_select'])){
                              $dep_fields[] = $field->field_options['form_select'];
                              $dep_fields[] = $field->field_options['data_type'];
                          }else if(isset($field->field_options['hide_opt']) and !empty($field->field_options['hide_opt']))
                              $dep_fields[] = $field->field_options['hide_opt'];
                              
                          if(!empty($dep_fields))
                              $parent_options['dependent_fields'][$field->id] = $dep_fields;
                          
                          $frm_field->update($parent_field->id, array('field_options' => $parent_options));
                          
                      }
                  }
              }
          }
      
          /**** ADD DEFAULT TEMPLATES ****/
          FrmFormsController::add_default_templates(FRMPRO_TEMPLATES_PATH);
        
          update_option('frmpro_db_version', $db_version);
          
          global $frmpro_settings;
          $frmpro_settings->store(); //update the styling settings
      }
    }
    
    function uninstall(){
        if(!current_user_can('administrator')){
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
        global $wpdb;
        $wpdb->query('DROP TABLE IF EXISTS ' . $this->displays);
        delete_option('frmpro_options');
        delete_option('frmpro_db_version');
    }

}
?>