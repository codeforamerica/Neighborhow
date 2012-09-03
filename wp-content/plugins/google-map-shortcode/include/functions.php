<?php 
/**
 * Google Map Shortcode 
 * Version: 3.1.1
 * Author: Alain Gonzalez
 * Plugin URI: http://web-argument.com/google-map-shortcode-wordpress-plugin/
*/

/**
  * Generating Map 
  *
  */  
function gmshc_generate_map($map_points, $atts) {

	  extract($atts);				
	  if ($canvas == "") $canvas = "canvas_".wp_generate_password(6, false);

	  $output ='<div id="'.$canvas.'" class = "gmsc" style="width:'.$width.'px; height:'.$height.'px; ';
	  switch ($align) {
		  case "left" :		  
	  	  $output .= 'float:left; margin:'.$margin.'px;"';
		  break;
		  case "right" :		  
	  	  $output .= 'float:right; margin:'.$margin.'px;"';
		  break;
		  case "center" :		  
	  	  $output .= 'clear:both; overflow:hidden; margin:'.$margin.'px auto;"';
		  break;
		  default:
		  $output .= 'clear:both; overflow:hidden; margin:'.$margin.'px auto;"';
		  break;		  	  
	  }

	  $output .= "></div>";
	  
	  $output .= "<script type=\"text/javascript\">\n";
	  $output .= "var map_points_".$canvas." =  new Array();\n";
	  
	  $i = 0;
	  		  
	  foreach ($map_points as $point){	  	 
	  
	  	  $post_categories = wp_get_post_categories( $point->post_id );  
		  $terms = implode(",",$post_categories);		   	
		  
		  list($lat,$lng) = explode(",",$point->ltlg);		  
		  		  
		  $output .= "map_points_".$canvas."[".$i."] = \n";
		  $output .= "{\"address\":\"".$point->address."\",\n";
		  $output .= "\"lat\":\"".$lat."\",\n";
		  $output .= "\"lng\":\"".$lng."\",\n";
		  
		  $output .= "\"info\":\"".gmshc_get_windowhtml($point)."\",\n";
		  
		  $output .= "\"cat\":\"".$terms."\",\n";
		  $output .= "\"icon\":\"".$point->icon."\"};\n";
		  $i ++;
		  
	  }	  
	  
	  $output .= "var options_".$canvas." = {\n";
	  $output .= "'mapID':'map_".$canvas."',\n";
	  $output .= "'zoom':".$zoom.",\n";
	  $output .= "'markers':map_points_".$canvas.",\n";
	  $output .= "'mapContainer':'".$canvas."',\n";
	  $output .= "'focusType':'".$focus_type."',\n";			  
	  $output .= "'type':'".$type."',\n";	  
 
	  switch ($focus) {
		case "0" : 
		break;		  
		case "all" :  
		$output .= "'circle':true,\n";
		break;
		default:
		$output .= "'focusPoint':".($focus-1).",\n";
	  }	 
	    
	  $output .= "'animateMarkers':".$animate.",\n";
	  $output .= "'interval':'".$interval."'\n";
	  $output .= "};\n"; 
		  
	  $output .= "var map_".$canvas." = new gmshc.Map(options_".$canvas.");\n";
	  $output .= "var trigger_".$canvas." = function(){map_".$canvas.".init()};\n";
	  $output .= "gmshc.addLoadEvent(trigger_".$canvas.");\n";  
	  $output .= "</script>\n";	
	
	  $final_output = apply_filters('gmshc_generate_map',$output,$map_points,$atts,$canvas);
	    
	  return $final_output;
}


/**
 * Get the html info
 *  
 */
 
function gmshc_get_windowhtml(&$point) {
    
	$windowhtml = "";
	$output = apply_filters('gmshc_get_windowhtml',$windowhtml,$point);
    
	if ( $output != '' )
		return $output;	
   
	$options = get_gmshc_options();	
	$windowhtml_frame = $options['windowhtml'];	

	$open_map_url = "http://maps.google.com/?q=".urlencode($point->address);
	$point_title = $point->title;
	 
	if (($point->post_id) > 0)	$point_link = get_permalink($point->post_id);
	else $point_link = "";
	
	$point_thumbnail = "";
	if ($point->thumbnail != "") {
		if(is_numeric($point->thumbnail)){
			$thumb = wp_get_attachment_image_src($point->thumbnail, 'thumbnail');
			$point_thumbnail = $thumb[0];
		}else{
			$point_thumbnail = $point->thumbnail;
		}
	}
	
	$point_img_url = ($point_thumbnail != "")? $point_thumbnail : gmshc_post_img($point->post_id);
	
	$point_excerpt = gmshc_get_excerpt($point->post_id);

	$point_description = ($point->description != "") ? $point->description : $point_excerpt;
	$point_address = $point->address;

	if(isset($point_img_url)) {
		$point_img = "<img src='".$point_img_url."' style='margin:8px 0 0 8px; width:90px; height:90px'/>";
		$html_width = "310px";
	} else {
		$point_img = "";
		$html_width = "auto";
	}				
				
	$find = array("%title%","%link%","%thubnail%", "%excerpt%","%description%","%address%","%open_map%","%width%","\r\n","\f","\v","\t","\r","\n","\\","\"");
	$replace  = array($point_title,$point_link,$point_img,$point_excerpt,$point_description,$point_address,$open_map_url,$html_width,"","","","","","","","'");
	
	$windowhtml = str_replace( $find,$replace, $windowhtml_frame);
				
	return $windowhtml;
	
}

function gmshc_stripslashes_deep($value)
{
    $value = is_array($value) ? 
                array_map('gmshc_stripslashes_deep', $value) :
                gmshc_clean_string($value);

    return $value;
}

/**
 * Get all the thumbnails from post
 */
function gmshc_all_post_thumb($the_parent){

	$attachments_id = array();
	$attachments = get_children( array(
										'post_parent' => $the_parent, 
										'post_type' => 'attachment', 
										'post_mime_type' => 'image',
										'orderby' => 'menu_order', 
										'order' => 'ASC',
										'numberposts' => 10) );									
						
											
	if($attachments == true) :
		foreach($attachments as $attachment) :
			//$img = wp_get_attachment_image_src($id, 'thumbnail');
		    array_push($attachments_id,$attachment->ID);
		endforeach;		
	endif;

	return $attachments_id; 
}

/**
 * Get the thumbnail from post
 */
function gmshc_post_img($the_parent,$size = 'thumbnail'){
	
	if( function_exists('has_post_thumbnail') && has_post_thumbnail($the_parent)) {
	    $thumbnail_id = get_post_thumbnail_id( $the_parent );
		if(!empty($thumbnail_id))
		$img = wp_get_attachment_image_src( $thumbnail_id, $size );	
	} else {
	$attachments = get_children( array(
										'post_parent' => $the_parent, 
										'post_type' => 'attachment', 
										'post_mime_type' => 'image',
										'orderby' => 'menu_order', 
										'order' => 'ASC', 
										'numberposts' => 1) );
	if($attachments == true) :
		foreach($attachments as $id => $attachment) :
			$img = wp_get_attachment_image_src($id, $size);			
		endforeach;		
	endif;
	}
	if (isset($img[0])) return $img[0]; 
}

/**
 * Get the excerpt from content
 */
function gmshc_get_excerpt($post_id) { // Fakes an excerpt if needed

	$content_post = get_post($post_id);
	$content = $content_post->post_content;

	if ( '' != $content ) {
        
		$content = strip_shortcodes( $content ); 
		
		//$content = apply_filters('the_content', $content);
		//wp_die("--");
		
		$content = str_replace(']]>', ']]&gt;', $content);
		$content = strip_tags($content);
		$excerpt_length = 10;
		$words = explode(' ', $content, $excerpt_length + 1);
		if (count($words) > $excerpt_length) {
			array_pop($words);
			array_push($words, '[...]');
			$content = implode(' ', $words);
		}
	}
	return $content;
}

/**
 * Deploy the icons list to select one
 */
function gmshc_deploy_icons(){ 
	
	$options = get_gmshc_options();
	$icon_path = GMSC_PLUGIN_URL.'/images/icons/';
	$icon_dir = GMSC_PLUGIN_DIR.'/images/icons/';	
	
	$icons_array = $options['icons'];
	$default_icon = $options['default_icon'];
	
	if ($handle = opendir($icon_dir)) {
		
		while (false !== ($file = readdir($handle))) {
	
			$file_type = wp_check_filetype($file);
	
			$file_ext = $file_type['ext'];
		
			if ($file != "." && $file != ".." && ($file_ext == 'gif' || $file_ext == 'jpg' || $file_ext == 'png') ) {
				array_push($icons_array,$icon_path.$file);
			}
		}
	}
	?>
        <div class="gmshc_label">
        	<?php _e("Select the marker by clicking on the images","google-map-sc"); ?> 
        </div>    	   
		<div id="gmshc_icon_cont">
        <input type="hidden" name="default_icon" value="<?php echo $default_icon ?>" id="default_icon" />			
		<?php foreach ($icons_array as $icon){ ?>
		  <div class="gmshc_icon <?php if ($default_icon == $icon) echo "gmshc_selected" ?>">
		  <img src="<?php echo $icon ?>" /> 
		  </div>
		<?php } ?>
		 </div> 
         <div id="icon_credit"><span><?php _e("Powered by","google-map-sc"); ?></span><a href="http://mapicons.nicolasmollet.com" target="_blank"><img src="<?php echo GMSC_PLUGIN_URL ?>/images/miclogo-88x31.gif" /></a>
         </div> 	
	<?php
}


/**
 * Get post points form the post custom field 'google-map-sc'
 */
function gmshc_get_points($post_id) {
    
	// check for old custom google-map-sc
	$post_data = get_post_meta($post_id,'google-map-sc',true);
	$post_points = array();
	if($post_data != ""){
		$points = json_decode(urldecode($post_data), true);
		if(is_array($points)){
			foreach($points as $point){
				$point_obj = new GMSHC_Point();
				if ($point_obj -> create_point("",$point['address'],$point['ltlg'],$point['title'],$point['description'],$point['icon'],$point['thumbnail'],$post_id))
				gmshc_insert_db_point($point_obj);
			}
		}
	
	} 
		
	/**  checking for old custom fields google-map-sc-address **/
	$post_data_address = get_post_meta($post_id,'google-map-sc-address');
	
	$options = get_gmshc_options();		
	$default_icon = $options['default_icon'];	
	$post_title = get_the_title($post_id);	
	
	if (is_array($post_data_address) && count($post_data_address) > 0) { 
	
		foreach ($post_data_address as $point_address){
			$point_obj_address = new GMSHC_Point();
			if ($point_obj_address -> create_point("",gmshc_clean_string($point_address),"",$post_title,"",$default_icon,"",$post_id,true)){		
				gmshc_insert_db_point($point_obj_address);
			}
		}
	}
	
	/**  checking for old custom fields google-map-sc-latlng **/
	$post_data_ltlg = get_post_meta($post_id,'google-map-sc-latlng');
	
	if (count($post_data_ltlg) > 0) { 

		foreach ($post_data_ltlg as $point_ltlg){
			$point_obj_ltlg = new GMSHC_Point();
			if ($point_obj_ltlg -> create_point("","",$point_ltlg,$post_title,"",$default_icon,"",$post_id)){		
				gmshc_insert_db_point($point_obj_ltlg);
			}
		}
	}
	
	$db_points = gmshc_get_bd_points($post_id);
	
	if (count($db_points)>0){
	
		foreach ($db_points as $point){
			$point_obj = new GMSHC_Point();

			if ($point_obj -> create_point($point['id'],$point['address'],$point['ltlg'],$point['title'],$point['description'],$point['icon'],$point['thumbnail'],$post_id)){	
		
				array_push($post_points,$point_obj);
			}
		}	
	}
	
	return $post_points;


}


/**
 * Get the point from geocoding from address or latitude,longitude
 * http://code.google.com/apis/maps/documentation/geocoding/
 */
 
function gmshc_point($address,$ltlg){

	$formatted_address = "";
	$point = "";
	$response = false;
	
	if (!empty($ltlg)) {
	
		$query = $ltlg;
		$type = "latlng";
		
	} else if (!empty($address)) { 
	
		$find = array("\n","\r"," ");
		$replace = array("","","+");					
		$address = str_replace($find,$replace, $address);
			
		$query = $address;
		$type = "address";
	}
	
	else return false;	
	    
	$options = get_gmshc_options();
	$api_url = "http://maps.googleapis.com/maps/api/geocode/json?".$type."=".urlencode($query)."&sensor=false&language=".$options['language'];
	
	$json_answ = @file_get_contents($api_url);

	if (empty($json_answ)) {
		if(function_exists('curl_init')){	
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $api_url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			$json_answ = curl_exec($ch);
			curl_close($ch);
		} else {		
			echo "<div class='error'><p>".__("The Point can't be added, <strong>php_curl.dll</strong> is not installed on your server and <strong>allow_url_fopen</strong> is disabled.")."</p></div>";
			return false;						
		}
	}	
	
	 
	 
	$answ_arr = json_decode($json_answ,true);
	
	if (isset($answ_arr["status"]) && $answ_arr["status"] == "OK"){		
		$formatted_address = gmshc_clean_string($answ_arr["results"]["0"]["formatted_address"]);

		$point = $answ_arr["results"]["0"]["geometry"]["location"]["lat"].",".$answ_arr["results"]["0"]["geometry"]["location"]["lng"];		
		
		if (!empty($point) && !empty($formatted_address)){
		
			$response = array('point'=>$point,'address'=>$formatted_address);
			
		}

		return $response;
		
	} else {
		return false;
	}

}

function gmshc_clean_string($str){
	$find = array("\r\n","\f","\v","\t","\r","\n","\\","\"","  ");
	$replace  = array("","","","","","","","'"," ");
	
	$clean_str = str_replace( $find,$replace, $str);	
	return htmlentities(html_entity_decode(stripslashes($clean_str),ENT_QUOTES,"UTF-8"),ENT_QUOTES,"UTF-8");
}

/**
 * Save the json data into the post custom field 'google-map-sc' - deprecated after 2.2.3
 */

function gmshc_insert_db_point($point){

   global $wpdb;
   
   $table_name = $wpdb->prefix . "gmshc";
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
	   return false;
	   
	} else {
	   
		// Checking if point exist
		$sql = "SELECT * FROM `". $table_name ."` WHERE ltlg = '".$wpdb->escape($point->ltlg)."' AND post_id = '".$point->post_id."';";

		$ltlg_check = $wpdb->get_row($sql, ARRAY_N);

		if (count($ltlg_check) > 0) {
			return false;
		}
		else {
				
			$insert = "INSERT INTO `" . $table_name .
						"` (
						`address`, 
						`ltlg`,
						`post_id`,
						`time`,
						`title`,
						`description`,
						`icon`,
						`thumbnail`				
						) " .
						"VALUES (
						'" .$wpdb->escape($point->address). "',
						'" .$wpdb->escape($point->ltlg) . "',
						'" .$wpdb->escape($point->post_id) . "',
						'" .current_time('mysql'). "',
						'" .$wpdb->escape($point->title) . "',
						'" .$wpdb->escape($point->description). "',				
						'" .$wpdb->escape($point->icon). "',
						'" .$wpdb->escape($point->thumbnail) . "'				
						);";
						
			  $results = $wpdb->query( $insert );
			  if ($results == 1) return true;
			  else  return false;
			  }
	  
	  }
}

function gmshc_update_db_point($point){

   global $wpdb;
   
   $table_name = $wpdb->prefix . "gmshc";
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
	   return false;
	   
	} else {
	   
			$update = "UPDATE `". $table_name ."` SET
						address = '" .$wpdb->escape($point->address). "', 
						ltlg = '" .$wpdb->escape($point->ltlg) . "',
						post_id = '" .$wpdb->escape($point->post_id) . "',
						time = '" .current_time('mysql'). "',
						title = '" .$wpdb->escape($point->title) . "',
						description = '" .$wpdb->escape($point->description). "',
						icon = '" .$wpdb->escape($point->icon). "',
						thumbnail = '" .$wpdb->escape($point->thumbnail) . "'				
						WHERE id = '".$point->id."';";
		
			  $results = $wpdb->query( $update );
			  if ($results == 1) return true;
			  else  return false;
 
	  }
}

function gmshc_delete_db_point($id){

   global $wpdb;
   
   $table_name = $wpdb->prefix . "gmshc";
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
	   return false;
	   
	} else {
	   
			$delete = "DELETE FROM `". $table_name ."` WHERE id = '".$id."';";
			  $results = $wpdb->query( $delete );
			  if ($results == 1) return true;
			  else  return false;
 
	  }
}

function gmshc_get_bd_points($post_id) {

   global $wpdb;
   
   $table_name = $wpdb->prefix . "gmshc";
   
   if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
	   
	   if ( defined( 'DB_CHARSET' ) ) $charset = DB_CHARSET;
	   else $charset = 'utf8';	   
		  
	   $sql = "CREATE TABLE " . $table_name . " (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  post_id mediumint(9) NOT NULL,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  address VARCHAR(200) DEFAULT '' NOT NULL,
		  ltlg VARCHAR(55) DEFAULT '' NOT NULL,
		  title text NOT NULL,
		  description text NOT NULL,
		  icon text NOT NULL,
		  thumbnail text NOT NULL,
		  UNIQUE KEY id (id)
		) COLLATE utf8_general_ci CHARACTER SET ".$charset.";";

	   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	   dbDelta($sql);
	   
	} else {		
		
		$sql = "SELECT * FROM ". $table_name ." WHERE post_id = ".$post_id;
		return $wpdb->get_results($sql,ARRAY_A);
	
	}

}

/**
 * Modules functions
 */
function gmshc_load_modules() {

	$gmshc_modules = array ();
	$mod_root = GMSC_PLUGIN_DIR."/modules";

	// Files in google-map-shortcode/modules directory
	$mod_dir = @ opendir( $mod_root);
	$mod_files = array();
	if ( $mod_dir ) {
		while (($file = readdir( $mod_dir ) ) !== false ) {
			if ( substr($file, 0, 1) == '.' )
				continue;							
			if ( is_dir( $mod_root.'/'.$file ) ) {				
				$mod_subdir = @ opendir( $mod_root.'/'.$file );				
				if ( $mod_subdir ) {
					while (($subfile = readdir( $mod_subdir ) ) !== false ) {
						
						if ( substr($subfile, 0, 1) == '.' )
							continue;
						if ( substr($subfile, -4) == '.php' )
							$gmshc_modules[] = "$file";
					}
					closedir( $mod_subdir );
				}
			} 
		}
		closedir( $mod_dir );
	}

	if ( count($gmshc_modules) > 0 ) {
		foreach ($gmshc_modules as $module) {
			if (gmshc_check_available_modules($module)) {
					require_once (GMSC_PLUGIN_DIR."/modules/".$module."/functions.php");

			}
		}
	}	

}

function gmshc_available_modules(){
	$modules = array(
					array(
											"id" => "slideshow",
											"name" => __("Slideshow"),
											"copy" => __("Allow more interaction with your maps, including a slideshow with the points images."),
											"url" => "http://web-argument.com/google-map-shortcode-modules/#slideshow",
											"image" => GMSC_PLUGIN_URL."/images/slideshow.jpg"
					),
					array(
											"id" => "scroller",
											"name" => __("Scroller"),
											"copy" => __("Access the maps points easily, adding custom scrollbar with the points images."),
											"url" => "http://web-argument.com/google-map-shortcode-modules/#scroller",
											"image" => GMSC_PLUGIN_URL."/images/scroller.jpg"															
					));
						
	return $modules; 
}

function gmshc_check_available_modules($module_id){
	    
		$gmshc_av_modules = gmshc_available_modules();
		
		foreach ($gmshc_av_modules as $av_modules) {
			if ($av_modules["id"] == $module_id ){
				return true;
			}
		}
		return false;	
}

function gmshc_update_module($module){	
	
	if (!gmshc_check_available_modules($module["id"])) {
		return false;
	}	   
	$gmshc_options = get_gmshc_options();
	$reg_mod = gmshc_is_module_register($module["id"]);
	
	if (is_numeric($reg_mod)) {
				 
		$modules = $gmshc_options["modules"];	
		$modules[$reg_mod] = $module; 
		$gmshc_options["modules"] = $modules;		
		update_option('gmshc_op', $gmshc_options);
		return true;

	} else {
		$modules = (isset($gmshc_options["modules"])) ? $gmshc_options["modules"] : array();
		$modules[] = $module;		
	} 
	
	$gmshc_options["modules"] = $modules;

	update_option('gmshc_op', $gmshc_options);
		
}

function gmshc_register_module($module){	
	
	if (!gmshc_check_available_modules($module["id"])) {
		return false;
	}  
	
	$reg_mod = gmshc_is_module_register($module["id"]);

	if (is_numeric($reg_mod)) {
		return true;
	} else {
		gmshc_update_module($module);
	}

}

function gmshc_modules_setting() {
	
	$modules = gmshc_available_modules();
	$output = "<div id='gmshc_mod_container'>";
	foreach ($modules as $module){
		$output .= gmshc_render_module($module);
	}
	$output .= "</div>";
	return $output;	
}

function gmshc_render_module($module) {

	$output = "<div class='gmshc_mod_box'>";
	$output .= "<h4>".$module['name']."</h4>";
	$output .= "<p>".$module['copy']."<p>";
	$output .= "<p align='center'><a href='".$module['url']."' target='_blank' title='".$module['name']."'><img src='".$module['image']."' /></a></p>";
	$output .= "<p align='left'><a href='".$module['url']."' target='_blank' title='".$module['name']."'><input type='button' value='".__('Install')."' class='button' /></a></p>";
	$output .= "</div>";
				
	$output = apply_filters('gmshc_render_module',$output,$module['id']);
		
	return $output;	
}

function gmshc_get_module_options($mod_id){
	
		$gmshc_options = get_gmshc_options();

		if(isset($gmshc_options['modules'])){
			$modules = $gmshc_options['modules'];
			if (count($modules) > 0){
				$i = 0;
				foreach($modules as $module){
					if ($module["id"] == $mod_id){
						return $modules[$i];	
					}
					$i ++;			
				}
			}
		}
}

function gmshc_is_module_register($mod_id){
	
	$gmshc_options = get_gmshc_options();
	
	if(isset($gmshc_options["modules"]) && count($gmshc_options["modules"]) > 0){
		$modules = $gmshc_options["modules"];

		foreach($modules as $id => $reg_module){

			if ($reg_module["id"] == $mod_id){
				return $id;
			} 
		}
	}
	
}

function gmshc_is_module_enabled($mod_id){

	$gmshc_options = get_gmshc_options();
	
	if(isset($gmshc_options["modules"]) && count($gmshc_options["modules"]) > 0){
		$modules = $gmshc_options["modules"];

		foreach($modules as $id => $reg_module){

			if ($reg_module["id"] == $mod_id && $reg_module["enable"]){
				return true;
			} 
		}
	}
	return false;	
	
}

function gmshc_get_active_modules(){
	
	$gmshc_options = get_gmshc_options();
	$active_mods = array();
	
	if(isset($gmshc_options["modules"]) && count($gmshc_options["modules"]) > 0){
		$modules = $gmshc_options["modules"];
        
		foreach($modules as $id => $reg_module){
			if ($reg_module["enable"] == 1){
				$active_mods[] = $reg_module;
			} 
		}
		
	}
	
	return $active_mods;
	
}
?>