<?php
class FrmEntryMeta{

  function add_entry_meta($entry_id, $field_id, $meta_key='', $meta_value){
    global $wpdb, $frmdb;

    $new_values = array();
    $new_values['meta_value'] = trim($meta_value);
    $new_values['item_id'] = $entry_id;
    $new_values['field_id'] = $field_id;
    $new_values['created_at'] = current_time('mysql', 1);
    $new_values = apply_filters('frm_add_entry_meta', $new_values);
    
    $wpdb->insert( $frmdb->entry_metas, $new_values );
  }

  function update_entry_meta($entry_id, $field_id, $meta_key='', $meta_value){
    //$this->delete_entry_meta($entry_id, $field_id);
    if (!empty($meta_value) or $meta_value == '0')
        $this->add_entry_meta($entry_id, $field_id, $meta_key, $meta_value);
  }
  
  function update_entry_metas($entry_id, $values){
    global $frm_field;
    $this->delete_entry_metas($entry_id, " AND field_id != '0'");
    foreach($values as $field_id => $meta_value){
        if(is_array($values[$field_id]))
            $values[$field_id] = (empty($values[$field_id])) ? false : maybe_serialize($values[$field_id]);
        $this->update_entry_meta($entry_id, $field_id, '', $values[$field_id]);
    }
  }
  
  function duplicate_entry_metas($old_id, $new_id){
      $metas = $this->get_entry_meta_info($old_id);
      foreach ($metas as $meta)
          $this->update_entry_meta($new_id, $meta->field_id, '', $meta->meta_value);
  }

  function delete_entry_meta($entry_id, $field_id){
    global $wpdb, $frmdb;
    $entry_id = (int)$entry_id;
    $field_id = (int)$field_id;
    return $wpdb->query("DELETE FROM $frmdb->entry_metas WHERE field_id='$field_id' AND item_id='$entry_id'");
  }
  
  function delete_entry_metas($entry_id, $where=''){
    global $wpdb, $frmdb;
    $entry_id = (int)$entry_id;
    $where = "item_id='$entry_id'". $where;

    return $wpdb->query("DELETE FROM $frmdb->entry_metas WHERE $where");
  }
  
  function get_entry_meta_by_field($entry_id, $field_id, $return_var=true){
      global $wpdb, $frmdb;
      
      $entry_id = (int)$entry_id;
      $field_id = (int)$field_id;
      
      $cached = wp_cache_get( $entry_id, 'frm_entry' );
      if($cached and isset($cached->metas) and isset($cached->metas[$field_id]))
            return $cached->metas[$field_id];
            
      if (is_numeric($field_id))
          $query = "SELECT `meta_value` FROM $frmdb->entry_metas WHERE field_id='$field_id' and item_id='$entry_id'";
      else
          $query = "SELECT `meta_value` FROM $frmdb->entry_metas it LEFT OUTER JOIN $frmdb->fields fi ON it.field_id=fi.id WHERE fi.field_key='{$field_id}' and `item_id`='{$entry_id}'";
          
      if($return_var){
          $result = maybe_unserialize($wpdb->get_var("{$query} LIMIT 1"));
          if($cached){
              if(!isset($cached->metas))
                  $cached->metas = array();
              $cached->metas[$field_id] = $result;
              wp_cache_set($entry_id, $cached, 'frm_entry');
          }
      }else{
          $result = $wpdb->get_col($query, 0);
      }
          
      return $result;
  }
  
  function get_entry_meta($entry_id, $field_id, $return_var=true){
      global $wpdb, $frmdb;
      
      $entry = wp_cache_get($entry_id, 'frm_entry');
      if($return_var and $entry and isset($entry->metas) and isset($entry->metas[$field_id]))
        return $entry->metas[$field_id];
        
      $query_str = "SELECT meta_value FROM $frmdb->entry_metas WHERE field_id=%d and item_id=%d";
      $query = $wpdb->prepare($query_str, $field_id, $entry_id);

      if($return_var)
        return $wpdb->get_var("{$query} LIMIT 1");
      else
        return $wpdb->get_col($query, 0);
  }

  function get_entry_metas($entry_id){
      global $wpdb, $frmdb;
      return $wpdb->get_col("SELECT meta_value FROM $frmdb->entry_metas WHERE item_id='{$entry_id}'");
  }
  
  function get_entry_metas_for_field($field_id, $order='', $limit='', $value=false, $unique=false){
      global $wpdb, $frmdb;
      $query = "SELECT ";
      $query .= ($unique) ? "DISTINCT(em.meta_value)" : "em.meta_value";
      $query .= " FROM $frmdb->entry_metas em ";
      $query .= (is_numeric($field_id)) ? "WHERE em.field_id='{$field_id}'" : "LEFT JOIN $frmdb->fields fi ON (em.field_id = fi.id) WHERE fi.field_key='{$field_id}'";
      if($value)
        $query .= " AND meta_value='$value'";
      $query .= "{$order}{$limit}";
      
      return $wpdb->get_col($query);
  }
  
  function get_entry_meta_info($entry_id){
      global $wpdb, $frmdb;
      return $wpdb->get_results("SELECT * FROM $frmdb->entry_metas WHERE item_id='{$entry_id}'");
  }
    
  function getAll($where = '', $order_by = '', $limit = ''){
    global $wpdb, $frmdb, $frm_field, $frm_app_helper;
    $query = "SELECT it.*, fi.type as field_type, fi.field_key as field_key, 
              fi.required as required, fi.form_id as field_form_id, fi.name as field_name, fi.options as fi_options 
              FROM $frmdb->entry_metas it LEFT OUTER JOIN $frmdb->fields fi ON it.field_id=fi.id" . 
              $frm_app_helper->prepend_and_or_where(' WHERE ', $where) . $order_by . $limit;

    if ($limit == ' LIMIT 1')
        $results = $wpdb->get_row($query);
    else    
        $results = $wpdb->get_results($query);
    return $results;     
  }
  
  function getEntryIds($where = '', $order_by = '', $limit = '', $unique=true){
    global $wpdb, $frmdb, $frm_app_helper;
    $query = "SELECT ";
    $query .= ($unique) ? "DISTINCT(it.item_id)" : "it.item_id";
    $query .= " FROM $frmdb->entry_metas it LEFT OUTER JOIN $frmdb->fields fi ON it.field_id=fi.id". $frm_app_helper->prepend_and_or_where(' WHERE ', $where) . $order_by . $limit;
    if ($limit == ' LIMIT 1')
        $results = $wpdb->get_var($query);
    else    
        $results = $wpdb->get_col($query);
    
    return $results;     
  }
  
  function getRecordCount($where=""){
    global $wpdb, $frmdb, $frm_app_helper;
    $query = "SELECT COUNT(*) FROM $frmdb->entry_metas it LEFT OUTER JOIN  $frmdb->fields fi ON it.field_id=fi.id" .
        $frm_app_helper->prepend_and_or_where(' WHERE ', $where);
    return $wpdb->get_var($query);
  }
  
  function search_entry_metas($search, $field_id='', $operator){
      global $wpdb, $frmdb, $frm_app_helper;
      if (is_array($search)){
          $where = '';
            foreach ($search as $field => $value){
              if ($field == 'year' and $value > 0)
                  $where .= " meta_value {$operator} '%{$value}' and";
              if ($field == 'month' and $value > 0)
                  $where .= " meta_value {$operator} '{$value}%' and";
              if ($field == 'day' and $value > 0)
                  $where .= " meta_value {$operator} '%/{$value}/%' and";      
            }
            $where .= " field_id='{$field_id}'";
            $query = "SELECT DISTINCT item_id FROM $frmdb->entry_metas". $frm_app_helper->prepend_and_or_where(' WHERE ', $where);
        }else{
            if ($operator == 'LIKE')
                $search = "%{$search}%";
            $query = $wpdb->prepare("SELECT DISTINCT item_id FROM $frmdb->entry_metas WHERE meta_value {$operator} %s and field_id = %d", $search, $field_id);
      }
      return $wpdb->get_col($query, 0);
  }

}
?>