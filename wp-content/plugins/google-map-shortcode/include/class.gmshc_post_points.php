<?php
/**
 * Google Map Shortcode 
 * Version: 3.0
 * Author: Alain Gonzalez
 * Plugin URI: http://web-argument.com/google-map-shortcode-wordpress-plugin/
*/

class GMSHC_Post_Map
{
	var $post_id;
	var $points = array();
	var $points_number;
	
	function create_post_map($post_id) {
		$this->post_id = $post_id;		
	}
	
	function add_point($point){
		return gmshc_insert_db_point($point);		  
	}

	function delete_point($id){	
		return gmshc_delete_db_point($id);		
	}
	
	function update_point($id_list,$address_list,$ltlg_list,$title_list,$desc_list,$icon_list,$thumb_list){
	    $i = 0;
	    foreach($id_list as $id){
			$new_point = new GMSHC_Point();
			$new_point -> create_point($id,$address_list[$i],$ltlg_list[$i],$title_list[$i],$desc_list[$i],$icon_list[$i],$thumb_list[$i],$this->post_id);
			if (!gmshc_update_db_point($new_point)) return false;
			$i ++;
		}
		return true;
	}	
	
	function load_points(){
		$this->points = gmshc_get_points($this->post_id);
		$this->points_number = count($this->points);
		return $this->points;
	}
	
}
?>