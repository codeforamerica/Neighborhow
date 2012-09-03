<?php
class FrmPlusEntriesHelper{
    function FrmPlusEntriesHelper(){
	}

	/** 
	 * Returns all entries for a given $user_id
	 *  - $user_id can either be a single value or an array
	 *  - if $user_id is blank, it returns all entries which have a user_id field.  
	 *  - if $form_id is not blank, it limits entries to a particular form
	 */
	function get_entries_with_user_id($user_id = "",$form_id = ""){
		global $frm_entry_meta,$wpdb;
		
		$where_meta = "fi.type = 'user_id'";
		if ($user_id == ""){
			// continue
		}
		elseif (is_array($user_id)){
			$where_meta.= $wpdb->prepare(" and it.meta_value in (%s".str_repeat(",%s",count($user_id) - 1).")",$user_id);
		}
		else{
			$where_meta.= $wpdb->prepare(" and it.meta_value = %s",$user_id);
		}
		if ($form_id == ""){
			// continue
		}
		elseif (is_array($form_id)){
			$where_meta.= $wpdb->prepare(" and fi.form_id in (%s".str_repeat(",%s",count($form_id) - 1).")",$form_id);
		}
		else{
			$where_meta.= $wpdb->prepare(" and fi.form_id = %s",$form_id);
		}
		$entry_metas = $frm_entry_meta->getAll($where_meta,' ORDER BY it.meta_value ASC, it.created_at DESC');
		$return = array();
		
		if (!count($entry_metas)){
			return $return;
		}
		
		// Now that we've got the entry metas, let's get the entries
		// I'm doing this so that I can have a distinction between the created_at date (from the FrmEntry table) vs. a modified_at date (from the FrmEntryMeta table)
		$entry_ids = array();
		foreach ($entry_metas as $e){
			$entry_ids[] = $e->item_id;
		}
		global $frm_entry;
		$where_meta = $wpdb->prepare(" it.id in (%s".str_repeat(",%s",count($entry_ids) - 1).")",$entry_ids);
		$_entries = $frm_entry->getAll($where_meta,' ORDER BY it.created_at DESC');
		$entries = array();
		foreach ($_entries as $e){
			$entries[$e->id] = (object) array('created_at' => $e->created_at, 'name' => $e->name);
		}

		if ($user_id == "" or is_array($user_id)){
			// If (potentially) more than one user_id, create the return value as array('user_id1' => {$entries}, 'user_id2' => {$entries})
			foreach ($entry_metas as $e){
				if (!array_key_exists($e->meta_value,$return)){
					$return[$e->meta_value] = array();
				}
				$return[$e->meta_value][] = (object) array('id' => $e->item_id, 'name' => $entries[$e->item_id]->name, 'form_id' => $e->field_form_id, 'created_at' => $entries[$e->item_id]->created_at, 'modified_at' => $e->created_at);
			}
		}
		else{

			foreach ($entry_metas as $e){
				$return[] = (object) array('id' => $e->item_id, 'name' => $entries[$e->item_id]->name, 'form_id' => $e->field_form_id, 'created_at' => $entries[$e->item_id]->created_at, 'modified_at' => $e->created_at);
			}
		}
		return $return;
	}
		
}
?>