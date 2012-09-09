<?php
/**
 * Google Map Shortcode 
 * Version: 2.3
 * Author: Alain Gonzalez
 * Plugin URI: http://web-argument.com/google-map-shortcode-wordpress-plugin/
*/

class GMSHC_Point {

	var $id;
	var $address;
	var $ltlg;
	var $title;
	var $description;
	var $icon;
	var $thumbnail;
	var $post_id;

    function create_point($id,$address,$ltlg,$title,$description,$icon,$thumbnail,$post_id,$check = false){ 

		if ($check)	{
			$temp_point = gmshc_point($address,$ltlg);
			if(!$temp_point) {
				return false;
			} else {
				$temp_address = $temp_point['address'];
				$temp_ltlg = $temp_point['point'];
			}				
		}
		else {
			$temp_address = $address;
			$temp_ltlg = $ltlg;		
		}
			$this->id = $id;
			$this->address = $temp_address;
			$this->ltlg = $temp_ltlg;
			$this->title = $title;
			$this->description = $description;	
			$this->icon = $icon;
			$this->thumbnail = $thumbnail;
			$this->post_id = $post_id;
			return true;
    }
	
}
?>