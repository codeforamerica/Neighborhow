<?php
/**
 * @package Formidable
 */
 
class FrmApiController{
    
    /********* DISPLAY DATA *************/
    function frm_filter_content($args){
        global $frm_entry;
        $args = explode(",",$args[1]);

        $form_key = sanitize_title($args[0]);

        $where = '';//" fr.form_key = '$form_key'";
    	$items = $frm_entry->getAll($where);

    	$list = $form_key;
    	foreach ($items as $item){
    	    $list .= $item->name;
    	}

    	return $list;
    }

    function get_frm_items($args = null){ 
        global $frm_entry, $frm_form, $frm_entry_meta;

        $defaults = array(
        	'form_key' => '', 'order' => '', 'limit' => '',
        	'search' =>'', 'search_type' => '',
        	'search_field' => '', 'search_operator' => 'LIKE'
        );

        $r = wp_parse_args( $args, $defaults ); 

        $form = $frm_form->getOne($r['form_key']);

        $where = " (it.form_id='". $form->id ."')";

        if (!($r['order'] == ''))
            $r['order'] = " ORDER BY {$r['order']}";

        if (!($r['limit'] == ''))
            $r['limit'] = " LIMIT {$r['limit']}";

        if (!($r['search'] == '') and $r['search_type'] == '')
            $where .= " and (it.item_key LIKE '%{$r['search']}%' or it.description LIKE '%{$r['search']}%' or it.name LIKE '%{$r['search']}%')";

    	$items = $frm_entry->getAll($where, $r['order'], $r['limit']);

    	if (!($r['search'] == '') and $r['search_type'] == 'meta'){ //search meta values
    	    $item_ids = $frm_entry_meta->search_entry_metas($r['search'], $r['search_field'], $r['search_operator']);
            $item_list = array();
            foreach ($items as $item){
                if (in_array($item->id, $item_ids))
                    $item_list[] = $item;
            }
            return $item_list;
    	}else
            return $items; 
    }

    function get_frm_item($item_key){
        global $frm_entry;
        return $frm_entry->getOne( $item_key );
    }

    function get_frm_item_by_id($id){
        global $frm_entry;
        return $frm_entry->getOne( $id );
    }

}

?>