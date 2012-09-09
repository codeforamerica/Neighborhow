<?php
/*
Plugin Name: Google Map Shortcode
Plugin URI: http://web-argument.com/google-map-shortcode-wordpress-plugin/
Description: Include Google Maps in your blogs easily and allow multiple interactions.  
Version: 3.1.1
Author: Alain Gonzalez
Author URI: http://web-argument.com/
*/

/*  Copyright 2011  Alain Gonzalez-Garcia  (email : alaingoga@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('GMSC_PLUGIN_DIR', WP_PLUGIN_DIR."/".dirname(plugin_basename(__FILE__)));
define('GMSC_PLUGIN_URL', WP_PLUGIN_URL."/".dirname(plugin_basename(__FILE__)));
define('GMSHC_VERSION_CURRENT','3.2');
define('GMSHC_VERSION_CHECK','2.2');

require(GMSC_PLUGIN_DIR."/include/functions.php");
require(GMSC_PLUGIN_DIR."/include/class.gmshc_point.php");
require(GMSC_PLUGIN_DIR."/include/class.gmshc_post_points.php");

gmshc_load_modules();

add_action( 'admin_init', 'gmshc_admin_init' );

function gmshc_admin_init(){
	load_plugin_textdomain( 'google-map-sc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );	
}

/**
 * Default Options
 */
function get_gmshc_options ($default = false){


	$gmshc_default = array(
							'zoom' => '10',
							'width' => '450',
							'height' => '450',
							'margin' => '10',
							'align' => 'center',									
							'language' => 'en',
							'windowhtml' => gmshc_defaul_windowhtml(),
							'icons' => array(),
							'default_icon' => GMSC_PLUGIN_URL.'/images/icons/marker.png',
							'interval' => 5000,
							'focus' => '0',
							'type' => 'ROADMAP',
							'animate' => true,
 							'focus_type' => 'open',
							'modules' => array(),
							'version' => GMSHC_VERSION_CURRENT
							);
							
    	
	if ($default) {
	update_option('gmshc_op', $gmshc_default);
	return $gmshc_default;
	}
	
	$options = get_option('gmshc_op');
	if (isset($options)){
	    if (isset($options['version'])) {			
			$chk_version = version_compare(GMSHC_VERSION_CHECK,$options['version']);
			if ($chk_version <= 0 ) return $options;
			else $options = $gmshc_default;
        } else {
		$options = $gmshc_default;
		}
	}	
	update_option('gmshc_op', $options);
	return $options;
}


/**
 * Inserting files on the header
 */
function gmshc_enqueue_scripts() {
	
	wp_enqueue_script( 'gmshc', GMSC_PLUGIN_URL.'/js/gmshc.2.3.min.js');

	$options = get_gmshc_options();
	$language = $options['language'];
	
	$google_map_api = "http://maps.google.com/maps/api/js?sensor=false";
	if(isset($language)) 
	$google_map_api .= "&language=".$language;	
		
	wp_enqueue_script( 'gmshc_google_api',$google_map_api);
}


add_action('wp_enqueue_scripts', 'gmshc_enqueue_scripts');



/**
 * Default Open Window Html
 *
 * Allows a plugin to replace the html that would otherwise be returned. The
 * filter is 'gmshc_get_windowhtml' and passes the point.

 * add_filter('gmshc_defaul_windowhtml','default_html',1,2);
 * 
 * function default_html($windowhtml,$point){
 * 	return "this is the address".$point->address;
 * } 
 */
 function gmshc_defaul_windowhtml(){
    
	$defaul_gmshc_windowhtml = "";
	$output = apply_filters('gmshc_defaul_windowhtml',$defaul_gmshc_windowhtml);

	if ( $output != '' )
		return $output;		 
 
	$defaul_gmshc_windowhtml = "<div style='margin:0; padding:0px; height:125px; width:%width%; overflow:hidden; font-size:11px; clear:both; line-height:13px;'>\n";
	$defaul_gmshc_windowhtml .= "<div style='float:left; width:200px'>\n";
	$defaul_gmshc_windowhtml .= "<a class='title' href='%link%' style='clear:both; display:block; font-size:12px; line-height: 18px; font-weight:bold;'>%title%</a>\n";
	$defaul_gmshc_windowhtml .= "<div><strong style='font-size:9px'>%address%</strong></div>\n";
	$defaul_gmshc_windowhtml .= "<div style='font-size:10px'>%description%</div>\n";
	$defaul_gmshc_windowhtml .= "<a href='%link%' style='font-size:11px; float:left; display:block'>more &raquo;</a>\n";
	$defaul_gmshc_windowhtml .= "<img src='".GMSC_PLUGIN_URL."/images/open.jpg' style='float: right; margin-right:5px'/> \n";
	$defaul_gmshc_windowhtml .= "<a href='%open_map%' target='_blank' style='font-size:11px; float: right; display:block;'>Open Map</a>\n";
	$defaul_gmshc_windowhtml .= "</div>\n";
	$defaul_gmshc_windowhtml .= "<div style='float:left'><a title='%link%' href='%link%'>%thubnail%</a></div>\n";	
	$defaul_gmshc_windowhtml .= "</div>\n";
	
	return $defaul_gmshc_windowhtml;

}

/**
  * The Sortcode
  *
*/  
add_shortcode('google-map-sc', 'gmshc_sc');

function gmshc_sc($atts) {
	
	global $post;
	$options = get_gmshc_options();	
	
	$width = $options['width'];
	$height = $options['height'];
	$margin = $options['margin'];
	$align = $options['align'];
	 
	$zoom = $options['zoom']; 
	$icon = $options['default_icon'];
	$language = $options['language'];
	$type = $options['type'];
	$interval = $options['interval'];
	$focus = $options['focus'];
	$animate = $options['animate'];	
	$focus_type = $options['focus_type'];		

	$the_items = array();
	
	$gmshc_default_sc =array(
						'address' => '',
						'title' =>'',
						'description' => '',
						'icon' => $icon,
						'thumbnail' => '',	
						'id' => '',
						'cat' => '',
						'zoom' => $zoom,
						'width' => $width,
						'height' => $height,
						'margin' => $margin,
						'align' => $align,		
						'language' => $language,
						'type' => $type,
						'interval' => $interval,
						'focus' => $focus,
						'animate' => $animate,
						'focus_type' => $focus_type,
						'module' => '', 
						'canvas' => ''	
						);	
						
	$gmshc_shortcode = apply_filters('gmshc_default_shortcode',$gmshc_default_sc);						
	
	$final_atts = apply_filters('gmshc_shortcode_atts',shortcode_atts($gmshc_shortcode, $atts));	
		
	extract($final_atts);		

	$map_points = array();

	// When address is set
	 if (!empty($address)) {			 
		//create single point object id = -1
		$new_point = new GMSHC_Point();	
		if($new_point -> create_point("",$address,"",$title,$description,$icon,$thumbnail,-1,true)) $map_points[0]=$new_point;	
	
	 // When id is set
	 } else if (!empty($id)) {
	 
		$post_points = new GMSHC_Post_Map();
		$post_points -> create_post_map($id);				
		$map_points = $post_points -> load_points();
		
	 } else if ($cat != '') {
	
		$categories = split (",",$cat); 
		
		$post_obj = get_posts(array('category__in'=>$categories,'numberposts'=>-1));
		foreach ($post_obj as $post_item) {	
		  //create points object by cat
		  $post_points = new GMSHC_Post_Map();

		  $post_points -> create_post_map($post_item->ID);
		  $post_points -> load_points();
		  if ($post_points->points_number >0) {
			foreach ($post_points->points as $point) { 			  
			  array_push($map_points,$point);  
			}
		  }
		}			
	
	 }  else {
	
		//create points for the current post_id	
		$post_points = new GMSHC_Post_Map();
		$post_points -> create_post_map($post->ID);	
		$post_points -> load_points();
		$map_points = $post_points->points;	 
	
	}
    
	//Map Point array filled		
	if ( count($map_points) > 0 ) {
         
		//Generate Map form points   
		$output = gmshc_generate_map($map_points, $final_atts);					
    } else { 
		$output = __("There is not points to locate on the map","google-map-sc");
	}    

	return $output;	
}


/**
 * Google Map SC Editor Button
 */
 
add_action('media_buttons', 'gmshc_media_buttons', 20);
function gmshc_media_buttons($admin = true)
{
	global $post_ID, $temp_ID;

	$media_upload_iframe_src = get_option('siteurl').'/wp-admin/media-upload.php?post_id=$uploading_iframe_ID';

	$iframe_title = __('Google Map Shortcode','google-map-sc');

	echo "<a class=\"thickbox\" href=\"media-upload.php?post_id={$post_ID}&amp;tab=gmshc&amp;TB_iframe=true&amp;height=500&amp;width=680\" title=\"$iframe_title\"><img src=\"".GMSC_PLUGIN_URL."/images/marker.png\" alt=\"$iframe_title\" /></a>";
	
}

add_action('media_upload_gmshc', 'gmshc_tab_handle');

function gmshc_tab_handle() {
	return wp_iframe('gmshc_tab_process');
}

/**
 * Inserting files on the header
 */
function gmshc_head() {

	$options = get_gmshc_options();
	$language = $options['language'];
	
	$gmshc_header = "<script src=\"http://maps.google.com/maps/api/js?sensor=false";
	if(isset($language)) 
	$gmshc_header .= "&language=".$language;
	$gmshc_header .="\" type=\"text/javascript\"></script>\n";	
	$gmshc_header .= "<script type=\"text/javascript\" src=\"".GMSC_PLUGIN_URL."/js/gmshc.2.3.min.js\"></script>\n";	
		
	print($gmshc_header);

}

function gmshc_tab_process(){
	
	gmshc_head();
    
	if(!isset($_REQUEST['map'])) {     
	
	 media_upload_header();
		 
	$options = get_gmshc_options();	

	$post_id = $_REQUEST["post_id"];
	$custom_fieds = get_post_custom($post_id);
	
	$address = isset($_REQUEST['new_address'])? gmshc_clean_string($_REQUEST['new_address']) : "";
	$latitude = isset($_REQUEST['latitude'])? gmshc_clean_string($_REQUEST['latitude']) : "";
	$longitude = isset($_REQUEST['longitude'])? gmshc_clean_string($_REQUEST['longitude']) : "";
	$verify = isset($_REQUEST['verify'])? true : false;
	$verify_point = true;
		
	if ($latitude != "" && $longitude != "") {
		$ltlg = $latitude.",".$longitude;
		if ($verify) $verify_point = true;
		else $verify_point = false;
	}
	else $ltlg = "";
	

	$title = isset($_REQUEST['new_title'])? gmshc_clean_string($_REQUEST['new_title']) : get_the_title($post_id);
	$description = isset($_REQUEST['new_description'])? gmshc_clean_string($_REQUEST['new_description']) : "";
	$icon = isset($_REQUEST['default_icon'])?stripslashes($_REQUEST['default_icon']) : "";
	$selected_thumbnail = isset($_REQUEST['selected_thumbnail'])? stripslashes($_REQUEST['selected_thumbnail']) : "";

	$add_point = isset($_REQUEST['add_point']) ? $_REQUEST['add_point'] : '';
	$del_point = isset($_REQUEST['delp']) ? $_REQUEST['delp'] : '';
	$update_point = isset($_REQUEST['update']) ? $_REQUEST['update'] : '';
	
	$width = isset($_REQUEST['width']) ? $_REQUEST['width'] : $options['width'];
	$height = isset($_REQUEST['height']) ? $_REQUEST['height'] : $options['height'];
	$margin = isset($_REQUEST['margin']) ? $_REQUEST['margin'] : $options['margin'];
	$align = isset($_REQUEST['align']) ? $_REQUEST['align'] : $options['align'];
	
	$zoom = isset($_REQUEST['zoom']) ? $_REQUEST['zoom'] : $options['zoom'];	
	$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : $options['type'];
	
	$default_icon = $options['default_icon'];
	
	$focus = isset($_REQUEST['focus']) ? $_REQUEST['focus'] : $options['focus'];
	$focus_type = isset($_REQUEST['focus_type']) ? $_REQUEST['focus_type'] : $options['focus_type'];	
	
	$id_list = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : "";
	$address_list = isset($_REQUEST['addr']) ? gmshc_stripslashes_deep($_REQUEST['addr']) : "";
	$title_list = isset($_REQUEST['title']) ? gmshc_stripslashes_deep($_REQUEST['title']) : "";	
	$desc_list = isset($_REQUEST['desc']) ? gmshc_stripslashes_deep($_REQUEST['desc']) : "";	
	$ltlg_list = isset($_REQUEST['ltlg']) ? $_REQUEST['ltlg'] : "";	
	$icon_list = isset($_REQUEST['icon']) ? $_REQUEST['icon'] : "";	
	$thumb_list = isset($_REQUEST['thumb'])? $_REQUEST['thumb'] : "";

    $post_points = new GMSHC_Post_Map();
	$post_points -> create_post_map($post_id);	
	
	if (!empty($add_point)) {	        		
			$new_point = new GMSHC_Point();
	        if($new_point -> create_point($ltlg,$address,$ltlg,$title,$description,$icon,$selected_thumbnail,$post_id,$verify_point)){
				if($post_points -> add_point($new_point)) 
					echo "<div class='updated'><p>".__("The Point was added.","google-map-sc")."</p></div>";
				else 
					echo "<div class='error'><p>".__("The point can't be saved or already exist.","google-map-sc")."</p></div>";
			}
			else 
				echo "<div class='error'><p>".__("The Address can't be located.","google-map-sc")."</p></div>";
	}

	else if (!empty($update_point)) {	
		if ( $post_points -> update_point($id_list,$address_list,$ltlg_list,$title_list,$desc_list,$icon_list,$thumb_list))
			echo "<div class='updated'><p>".__("The Point was updated.","google-map-sc")."</p></div>";
	    else echo "<div class='error'><p>".__("The Points can't be updated.","google-map-sc")."</p></div>";
	}
	
	else if ($del_point != "") {
		if($post_points -> delete_point($del_point))		
		echo "<div class='updated'><p>".__("The Point was deleted.","google-map-sc")."</p></div>";
	}	

	?>
    
    <?php $post_points -> load_points(); ?>
        
    <script type="text/javascript" src="<?php echo GMSC_PLUGIN_URL ?>/js/gmshc-admin.js"></script>
        
    <link href="<?php echo GMSC_PLUGIN_URL ?>/styles/gmshc-admin-styles.css" rel="stylesheet" type="text/css"/>       
       
       
        <div style="width:620px; margin:10px auto">
       
        <?php echo gmshc_plugin_menu(); ?>
    
        <form  action="#" method="post">
           <input id="default_width" type="hidden" value="<?php echo $width ?>"/>
           <input id="default_height" type="hidden" value="<?php echo $height ?>"/>
           <input id="default_margin" type="hidden" value="<?php echo $margin ?>"/>
           <input id="default_align" type="hidden" value="<?php echo $align ?>"/>
           <input id="default_zoom" type="hidden" value="<?php echo $zoom ?>"/>
           <input id="default_focus" type="hidden" value="<?php echo $focus ?>"/>
           <input id="default_focus_type" type="hidden" value="<?php echo $focus_type ?>"/>           
           <input id="default_type" type="hidden" value="<?php echo $type ?>"/>
           
           <table width="620" border="0" cellspacing="5" cellpadding="5">
            <tr>
                <td colspan="2">
                <h3 class="gmshc_editor"><?php _e("Add New Point","google-map-sc"); ?></h3>
                <p><?php _e("Points can be added using Address or Lat/Long.","google-map-sc"); ?></p>
                </td>
           </tr> 
            <tr>
                <td align="right" valign="top">
                <strong><?php _e("Full Address","google-map-sc"); ?></strong>
                </td>
				<td valign="top">    
				<textarea name="new_address" cols="32" rows="2" id="new_address"></textarea>
				</td>
            </tr>           
            <tr>
                <td align="right" valign="top">
                <strong><?php _e("Latitude ","google-map-sc"); ?></strong>
                </td>
				<td valign="top">    
				<input name="latitude" size="35" id="latitude" />
				</td>
            </tr>
            <tr>
                <td align="right" valign="top">
                <strong><?php _e("Longitude","google-map-sc"); ?></strong>
                </td>
				<td valign="top">    
				<input name="longitude" size="35" id="longitude" />
				</td>
            </tr>
            <tr>
                <td align="right" valign="top">
                <input type="checkbox" value="1" name="verify" />
                </td>
				<td valign="top">    
				<?php _e("Verify this latitude and longitude using Geocoding. This could overwrite the point address.","google-map-sc"); ?>
				</td>
            </tr>            
            <tr>
                <td align="right" valign="top">
                <strong><?php _e("Title","google-map-sc"); ?></strong>
                </td>
				<td valign="top">    
				<input name="new_title"  size="54" id="new_title" value="<?php echo $title ?>" />
				</td>
            </tr> 
            <tr>
                <td align="right" valign="top">
                <strong><?php _e("Description","google-map-sc"); ?></strong>
                </td>
				<td valign="top">    
				<textarea name="new_description" cols="47" rows="2" id="new_description"></textarea>
				</td>
            </tr>	

            <tr>
				<td align="right" valign="top" colspan="2">
					<?php gmshc_deploy_icons() ?>
				</td>
            </tr> 
            <tr>
				<td align="center" valign="top" colspan="2">
                	<?php 
					$attch_list = gmshc_all_post_thumb($post_id);
					
					if (count($attch_list) > 0) { 
					?>
                        <div class="gmshc_label">
                            <?php _e("Select the thumbnail by clicking on the images","google-map-sc"); ?>
                        </div>
                        <div id="gmshc_thumb_cont">
                        <input type="hidden" name="selected_thumbnail" value="" id="selected_thumbnail" />
                        <?php 
							foreach ($attch_list as $attch) { 
						       $thumbnail = wp_get_attachment_image_src($attch, 'thumbnail');
						?>
                            <div class="gmshc_thumb">
                                <img attch="<?php echo $attch ?>" src="<?php echo $thumbnail[0] ?>" width="40" height="40" />
                            </div>
                        <?php  } ?>
                        </div>
					<?php } else { ?>
                        <div class="gmshc_label">
                            <strong><?php _e("Thumbnail: ","google-map-sc"); ?></strong><?php _e("If you want to attach an image to the point you need to upload it first to the post gallery","google-map-sc"); ?>
                        </div> 
                    <?php  } ?> 
                    <p align="left"><a class="button" href = "?post_id=<?php echo $post_id ?>&type=image" title="Upload Images"><?php _e("Upload Images","google-map-sc") ?></a></p>                     
				    <p class="endbox"><input class="button-primary" value="<?php _e("Add Point","google-map-sc") ?>" name="add_point" type="submit"></p>
                </td>
            </tr>
            <?php if ($post_points->points_number > 0) { ?>            
            <tr>
                <td colspan="2">
                <h3 class="gmshc_editor"><?php _e("Map Configuration","google-map-sc"); ?></h3>
                </td>
            </tr>  
            <tr>
                <td align="right"><?php _e("Width","google-map-sc"); ?></td>
                <td valign="top"><input name="width" type="text" id="width" size="10" value = "<?php echo $width ?>"/></td>
            </tr>  
            <tr>
                <td align="right"><?php _e("Height","google-map-sc"); ?></td>
                <td valign="top"><input name="height" type="text" id="height" size="10" value = "<?php echo $height ?>" /></td>
            </tr>
            <tr>
              <td align="right"><?php _e("Margin","google-map-sc") ?></td>
              <td><input name="margin" id="margin" type="text" size="6" value="<?php echo $margin ?>" /></td>
            </tr>  
            <tr>
              <td align="right"><?php _e("Align","google-map-sc") ?></td>
              <td>
                  <input name="align" type="radio" id="aleft" value="left" <?php echo ($align == "left" ? "checked = 'checked'" : "") ?> /> <?php _e("left","google-map-sc") ?>
                  <input name="align" type="radio" id="acenter" value="center" <?php echo ($align == "center" ? "checked = 'checked'" : "") ?> /> <?php _e("center","google-map-sc") ?>
                  <input name="align" type="radio" id="aright" value="right" <?php echo ($align == "right" ? "checked = 'checked'" : "") ?> /> <?php _e("right","google-map-sc") ?>        
            </tr>            
            <tr>
              <td align="right"><?php _e("Zoom","google-map-sc") ?></td>
              <td>              
              <select name="zoom" id="zoom">              
                  <?php for ($i = 0; $i <= 20; $i ++){ ?>
                      <option value="<?php echo $i ?>" <?php echo ($i == $zoom ? "selected" : "") ?> ><?php echo $i ?></option>
                  <?php } ?>
              </select>         
            </tr> 
            <tr>
              <td align="right"><?php _e("Maps Type","google-map-sc") ?></td>
              <td>
                  <select name="type" id="type">
                      <option value="ROADMAP" <?php if ($type == "ROADMAP") echo "selected" ?>><?php _e("ROADMAP - Displays a normal street map","google-map-sc") ?></option>
                      <option value="SATELLITE" <?php if ($type == "SATELLITE") echo "selected" ?>><?php _e("SATELLITE - Displays satellite images","google-map-sc") ?></option>
                      <option value="TERRAIN" <?php if ($type == "TERRAIN") echo "selected" ?>><?php _e("TERRAIN - Displays maps with physical features such as terrain and vegetation","google-map-sc") ?></option>
                      <option value="HYBRID" <?php if ($type == "HYBRID") echo "selected" ?>><?php _e("HYBRID - Displays a transparent layer of major streets on satellite images","google-map-sc") ?></option>
                  </select>
              </td>        
            </tr>  
            <tr>
              <td align="right"><?php _e("Focus","google-map-sc") ?></td>
              <td>
                  <select name="focus" id="focus">
                      <option value="0" <?php if ($focus == 0) echo "selected" ?>><?php _e("None","google-map-sc") ?></option>
                      <?php for ($i = 1; $i <= $post_points->points_number; $i ++){ ?>
                      	<option value="<?php echo $i ?>" <?php if ($focus == $i) echo "selected" ?>><?php echo $i ?></option>
                      <?php } ?>
                      <?php if ($post_points->points_number > 1) { ?>
                      <option value="all" <?php if ($focus == "all") echo "selected" ?>><?php _e("All","google-map-sc") ?></option>
                      <?php } ?>                      
                  </select>
                  <em><?php _e("Select the point to be focused after loading the map","google-map-sc") ?></em>
              </td>        
            </tr> 
            <tr>
              <td align="right" valign="top"><?php _e("Focus Type","google-map-sc") ?></td>
              <td>
              <select name="focus_type" id="focus_type">
                 <option value="open" <?php echo ($focus_type == "open" ? "selected" : "") ?> ><?php _e("Open Markers","google-map-sc") ?></option>
                 <option value="center" <?php echo ($focus_type == "center" ? "selected" : "") ?> ><?php _e("Center Markers","google-map-sc") ?></option>
              </select>
              </td>        
            </tr> 
            <tr>
              <td align="right" valign="top"><?php _e("Module","google-map-sc") ?></td>
              <td>
              <select name="module" id="module">
                 <option value=""><?php _e("none","google-map-sc") ?></option>
              <?php 
			  	$modules = gmshc_get_active_modules();

				if (count($modules) > 0) { 
					foreach($modules as $mod){ ?>
						<option value="<?php echo $mod['id'] ?>"><?php echo $mod['name'] ?></option>
					<?php }                   
			    } ?>
              </select>
              <a href="http://web-argument.com/google-map-shortcode-modules/" target="_blank"><em><?php _e("Improve user interface by adding new modules") ?></em></a>
	  

              </td>        
            </tr>            
                                              
            <?php } ?>			
            </table>
            
			<?php if ($post_points->points_number > 0) { ?>  
                
                <p class="endbox"><input class="button-primary insert_map" value="<?php _e("Insert Map","google-map-sc"); ?>" type="button" \> 
                   <a class="button gmshc_show" href="#gmshc_map" show="<?php _e("Show Map","google-map-sc") ?>" hide="<?php _e("Hide Map","google-map-sc") ?>">
                    <?php _e("Show Map","google-map-sc") ?>
                   </a>&nbsp;  
                   <a class="button gmshc_refresh" href="#gmshc_map">
                    <?php _e("Refresh Map","google-map-sc") ?>
                   </a>
                </p>
                                     
                <p><?php echo $post_points->points_number." ".__("Points Added","google-map-sc") ?></p>
                <p><em><?php _e("Click on the icons or thumbnails to edit.","google-map-sc") ?></em></p>                    
            <?php } ?>
            
			<?php
            if ( count($post_points -> points) > 0 ){
			rsort($post_points -> points);
            ?>
     
            <table class="widefat gmshc_points" cellspacing="0">
                <thead>
                <tr>
                <th><?php _e("#"); ?></th>
                <th><?php _e("Marker","google-map-sc"); ?></th>
                <th><?php _e("Thumbnail","google-map-sc"); ?></th>
                <th><?php _e("Title/Description","google-map-sc"); ?></th>
                <th width="140"><?php _e("Address/LtLg","google-map-sc"); ?></th>
                </tr>
                </thead>
                <tbody class="media-item-info">   
                
                <?php 
				$i = 0;
				foreach ($post_points->points as $point ) {				 
				?>                     
                    <tr>
                      <td><?php echo ($post_points->points_number - $i) ?></td>	
                      <td>
                          <div class="gmshc_list_icon" title="<?php _e("Change") ?>">
                            <img src="<?php echo $point->icon ?>" atl="<?php _e("Icon","google-map-sc") ?>" />
                            <input name="icon[]" type="hidden" id="icon_<?php echo $i ?>" size="30" value = "<?php echo $point->icon ?>"/>                            
                          </div>
                          <input name="pid[]" type="hidden" id="id_<?php echo $i ?>" size="30" value = "<?php echo $point->id ?>"/>
                      </td>                    
                      <td>
                          <div class="gmshc_list_thumb" title="<?php _e("Change") ?>">	
							<?php 
                            $point_thumbnail = "";
                            if ($point->thumbnail != "") {
                                if(is_numeric($point->thumbnail)){
                                    $thumb = wp_get_attachment_image_src($point->thumbnail, 'thumbnail');
                                    $point_thumbnail = $thumb[0];
                                }else{
                                    $point_thumbnail = $point->thumbnail;
                                }
                            } 
                            ?>                             		       
                              <img src="<?php echo $point_thumbnail ?>" atl="<?php _e("Thumbnail","google-map-sc") ?>" width = "40" height="40" />
                              <input name="thumb[]" type="hidden" id="thumb_<?php echo $i ?>" size="30" value = "<?php echo $point->thumbnail ?>"/>
                           </div>                  
                      </td>
                      <td>
						<input name="title[]" type="text" id="title_<?php echo $i ?>" size="33" value = "<?php echo $point->title ?>"/>
                        <textarea name="desc[]" cols="30" rows="2" id="desc_<?php echo $i ?>"><?php echo $point->description ?></textarea>							
                      </td>
                      <td>
                      	<textarea name="addr[]" cols="30" rows="2" id="addr_<?php echo $i ?>"><?php echo $point->address ?></textarea>
						<input name="ltlg[]" type="hidden" id="ltlg_<?php echo $i ?>" size="30" value = "<?php echo $point->ltlg ?>"/>
                        <p><?php echo $point->ltlg ?></p>                        
                        <div>                        
                        <input class="button" value="<?php _e("Update","google-map-sc"); ?>" name="update" type="submit"> 
                        <a href="?post_id=<?php echo $post_id ?>&tab=gmshc&delp=<?php echo $point->id ?>" class="delete_point" onclick="if(confirm('<?php _e("You will not be able to roll back deletion. Are you sure?","google-map-sc") ?>')) return true; else return false"><?php _e("Delete","google-map-sc"); ?></a>
                        </div>
                      </td>
                    </tr>
                 <?php 
				 	$i++;
				}
				?>          
                </tbody> 	    
            </table>
            <div id="gmshc_list_icon_cont">
            	<div class="gmshc_bx_tl"><span><?php _e("Click to Select")?></span><a href="" class="gmshc_box_close"><?php _e("Close") ?></a></div>
                <div class="gmshc_bx"></div>                
            </div>
            <div id="gmshc_list_thumb_cont">
            	<div class="gmshc_bx_tl"><span><?php _e("Click to Select")?></span><a href="" class="gmshc_box_close"><?php _e("Close") ?></a></div>
                <div class="gmshc_bx"></div>
            </div>
            
   	    <p><input class="button-primary insert_map" value="<?php _e("Insert Map","google-map-sc"); ?>" type="button" \> 
           <a class="button gmshc_show" href="#gmshc_map" show="<?php _e("Show Map","google-map-sc") ?>" hide="<?php _e("Hide Map","google-map-sc") ?>">
		   	<?php _e("Show Map","google-map-sc") ?>
           </a>&nbsp; 
           <a class="button gmshc_refresh" href="#gmshc_map">
		   	<?php _e("Refresh Map","google-map-sc") ?>
           </a>
        </p>        
        </div>
		
        <div id="gmshc_map" style="height:0px; overflow:hidden;" tabindex="100">
        <input type="hidden" id="iframe_url" value="?post_id=<?php echo $post_id ?>&tab=gmshc&"  />
        <iframe id = "iframe_sc" src="" width="600" height="420" scrolling="no" />        
        </div>
        <br />        
			<?php  } ?>
		</form>
        <?php } else {			
		
			$map_attr = array("post_id","type","zoom","focus","focus_type");
			
			$shc_attr = "";
			
			foreach($map_attr as $attr) {
				if (isset($_REQUEST[$attr])) $shc_attr .= " ".(($attr=="post_id")?"id":$attr)."=".$_REQUEST[$attr];				
			}
			
     		echo do_shortcode("[google-map-sc width=600 height=420 align=center margin=0".$shc_attr."]");
			
		}
   
}

/**
 * Settings
 */  

add_action('admin_menu', 'gmshc_set');

function gmshc_set() {
	
	$plugin_page = add_options_page('Google Map Shortcode', 'Google Map Shortcode', 'administrator', 'google-map-shortcode', 'gmshc_options_page');	 
	add_action( 'admin_head-'.$plugin_page, 'gmshc_admin_script' );	
}

/**
 * Inserting files on the admin header
 */
function gmshc_admin_script() {

	$gmshc_admin_header = "<script type=\"text/javascript\" src=\"".GMSC_PLUGIN_URL."/js/gmshc-admin.js\"></script>\n";
	$gmshc_admin_header .= "<link href=\"".GMSC_PLUGIN_URL."/styles/gmshc-admin-styles.css\" rel=\"stylesheet\" type=\"text/css\"/>\n";
	
    gmshc_enqueue_scripts();		
	print($gmshc_admin_header);

}

function gmshc_options_page() {

	if(isset($_POST['Restore_Default'])) $options = get_gmshc_options(true); 

	do_action('gmshc_register_module');
	
	$options = get_gmshc_options();  
    
    do_action('gmshc_validate_module');   

	?>
    
	<div class="wrap">   
	
	<h2><?php _e("Google Map Shortcode Settings","google-map-sc") ?></h2>

	<?php echo gmshc_plugin_menu(); ?>
	
	<?php 

	if(isset($_POST['Submit'])){
	
     		$newoptions['width'] = isset($_POST['width'])?$_POST['width']:$options['width'];
			$newoptions['height'] = isset($_POST['height'])?$_POST['height']:$options['height'];
			$newoptions['margin'] = isset($_POST['margin'])?$_POST['margin']:$options['margin'];
			$newoptions['align'] = isset($_POST['align'])?$_POST['align']:$options['align'];
			
			$newoptions['zoom'] = isset($_POST['zoom'])?$_POST['zoom']:$options['zoom'];
			$newoptions['language'] = isset($_POST['language'])?$_POST['language']:$options['language'];
			$newoptions['type'] = isset($_POST['type'])?$_POST['type']:$options['type'];
			$newoptions['interval'] = isset($_POST['interval'])?$_POST['interval']:$options['interval'];
			$newoptions['focus'] = isset($_POST['focus'])?$_POST['focus']:"0";
			$newoptions['animate'] = isset($_POST['animate'])?$_POST['animate']:"false";
			$newoptions['focus_type'] = isset($_POST['focus_type'])?$_POST['focus_type']:"open";
		
			$newoptions['windowhtml'] = isset($_POST['windowhtml'])? $_POST['windowhtml']:$options['windowhtml'];	

			$newoptions['default_icon'] = isset($_POST['default_icon'])?$_POST['default_icon']:$options['default_icon'];
			$newoptions['icons'] = $options['icons'];	
					
			$newoptions['version'] = GMSHC_VERSION_CURRENT;
            $newoptions['modules'] = isset($options['modules'])?$options['modules']:array();
			
			if ( $options != $newoptions ) {
				$options = $newoptions;
				update_option('gmshc_op', $options);			
			}			
	    
 	} 

	if(isset($_POST['Use_Default'])){

		$options['windowhtml'] = gmshc_defaul_windowhtml();
        update_option('gmshc_op', $options);
	
    }

	$upload_icons = $options['icons'];

	if(isset($_POST['upload'])) {
		if ($_FILES['datafile']['error'] == 0){

		   $filename = $_FILES["datafile"]["name"];
	 
		   $upload = wp_upload_bits($filename, NULL, file_get_contents($_FILES["datafile"]["tmp_name"]));

			if ( ! empty($upload['error']) ) {
				$errorString = sprintf(__('Could not write file %1$s (%2$s)','google-map-sc'), $filename, $upload['error']);
				echo "<div class='error'><p><strong>".$errorString."</strong></p></div>";
			}  else {		
				array_unshift($upload_icons,$upload['url']);
				$options['icons'] = $upload_icons;
				update_option('gmshc_op', $options);		
			}
		
		} else {
			echo "<div class='error'><p><strong>".__("Please upload a valid file","google-map-sc")."</strong></p></div>";
		}
	}

	$width = $options['width'];
	$height = $options['height'];
	$margin = $options['margin'];
	$align = $options['align'];
	
	$zoom = $options['zoom'];
	$language = $options['language'];
	$type = $options['type'];
	
	$interval = $options['interval'];
	$focus = $options['focus'];
	$animate = $options['animate'];
	$focus_type = $options['focus_type'];

	$windowhtml = $options['windowhtml'];	
	$default_icon = $options['default_icon'];

	?>  
	
	<form method="POST" name="options" target="_self" enctype="multipart/form-data">
	
	<h3><?php _e("Maps Default Configuration","google-map-sc") ?></h3>
	
	<p><?php _e("The shortcode attributes overwrites these options.","google-map-sc") ?></p>
	
    <table width="80%" border="0" cellspacing="10" cellpadding="0">
      <tr>
        <td width="200" align="right" height="40"><strong><?php _e("Width","google-map-sc") ?></strong></td>
        <td><input name="width" type="text" size="6" value="<?php echo $width ?>" /></td>
      </tr>
      <tr>
        <td align="right"><strong><?php _e("Height","google-map-sc") ?></strong></td>
        <td><input name="height" type="text" size="6" value="<?php echo $height ?>" /></td>
      </tr>
      <tr>
        <td align="right"><strong><?php _e("Margin","google-map-sc") ?></strong></td>
        <td><input name="margin" type="text" size="6" value="<?php echo $margin ?>" /></td>
      </tr>  
      <tr>
        <td align="right"><strong><?php _e("Align","google-map-sc") ?></strong></td>
        <td>
        	<input name="align" type="radio" value="left" <?php echo ($align == "left" ? "checked = 'checked'" : "") ?> /> <?php _e("left","google-map-sc") ?>
            <input name="align" type="radio" value="center" <?php echo ($align == "center" ? "checked = 'checked'" : "") ?> /> <?php _e("center","google-map-sc") ?>
            <input name="align" type="radio" value="right" <?php echo ($align == "right" ? "checked = 'checked'" : "") ?> /> <?php _e("right","google-map-sc") ?>        
      </tr>          
      <tr>
        <td align="right"><strong><?php _e("Zoom","google-map-sc") ?></strong></td>
        <td>
        <select name="zoom" id="zoom">
            <?php for ($i = 0; $i <= 20; $i ++){ ?>
                <option value="<?php echo $i ?>" <?php echo ($i == $zoom ? "selected" : "") ?> ><?php echo $i ?></option>
            <?php } ?>
        </select>         
      </tr>  
      <tr>
        <td align="right"><strong><?php _e("Maps Type","google-map-sc") ?></strong></td>
        <td>
        	<select name="type">
        		<option value="ROADMAP" <?php if ($type == "ROADMAP") echo "selected" ?>><?php _e("ROADMAP - Displays a normal street map","google-map-sc") ?></option>
            	<option value="SATELLITE" <?php if ($type == "SATELLITE") echo "selected" ?>><?php _e("SATELLITE - Displays satellite images","google-map-sc") ?></option>
                <option value="TERRAIN" <?php if ($type == "TERRAIN") echo "selected" ?>><?php _e("TERRAIN - Displays maps with physical features such as terrain and vegetation","google-map-sc") ?></option>
                <option value="HYBRID" <?php if ($type == "HYBRID") echo "selected" ?>><?php _e("HYBRID - Displays a transparent layer of major streets on satellite images","google-map-sc") ?></option>
            </select>
        </td>        
      </tr>      
      <tr>
        <td align="right" valign="top"><strong><?php _e("Select Language","google-map-sc") ?></strong></td>
        <td>  
        <?php 
        $lang_array = array(
							"ar"=>__("ARABIC","google-map-sc"),
							"eu"=>__("BASQUE","google-map-sc"),
							"bg"=>__("BULGARIAN","google-map-sc"),
							"bn"=>__("BENGALI","google-map-sc"),
							"ca"=>__("CATALAN","google-map-sc"),
							"cs"=>__("CZECH","google-map-sc"),
							"da"=>__("DANISH","google-map-sc"),
							"de"=>__("GERMAN","google-map-sc"),
							"el"=>__("GREEK","google-map-sc"),
							"en"=>__("ENGLISH","google-map-sc"),
							"en-AU"=>__("ENGLISH (AUSTRALIAN)","google-map-sc"),
							"en-GB"=>__("ENGLISH (GREAT BRITAIN)","google-map-sc"),
							"es"=>__("SPANISH","google-map-sc"),
							"eu"=>__("BASQUE","google-map-sc"),
							"fa"=>__("FARSI","google-map-sc"),
							"fi"=>__("FINNISH","google-map-sc"),
							"fil"=>__("FILIPINO","google-map-sc"),
							"fr"=>__("FRENCH","google-map-sc"),
							"gl"=>__("GALICIAN","google-map-sc"),
							"gu"=>__("GUJARATI","google-map-sc"),
							"hi"=>__("HINDI","google-map-sc"),
							"hr"=>__("CROATIAN","google-map-sc"),
							"hu"=>__("HUNGARIAN","google-map-sc"),
							"id"=>__("INDONESIAN","google-map-sc"),
							"it"=>__("ITALIAN","google-map-sc"),
							"iw"=>__("HEBREW","google-map-sc"),
							"ja"=>__("JAPANESE","google-map-sc"),
							"kn"=>__("KANNADA","google-map-sc"),
							"ko"=>__("KOREAN","google-map-sc"),
							"lt"=>__("LITHUANIAN","google-map-sc"),
							"lv"=>__("LATVIAN","google-map-sc"),
							"ml"=>__("MALAYALAM","google-map-sc"),
							"mr"=>__("MARATHI","google-map-sc"),
							"nl"=>__("DUTCH","google-map-sc"),
							"no"=>__("NORWEGIAN","google-map-sc"),
							"or"=>__("ORIYA","google-map-sc"),
							"pl"=>__("POLISH","google-map-sc"),
							"pt"=>__("PORTUGUESE","google-map-sc"),
							"pt-BR"=>__("PORTUGUESE (BRAZIL)","google-map-sc"),
							"pt-PT"=>__("PORTUGUESE (PORTUGAL)","google-map-sc"),
							"ro"=>__("ROMANIAN","google-map-sc"),
							"ru"=>__("RUSSIAN","google-map-sc"),
							"sk"=>__("SLOVAK","google-map-sc"),
							"sl"=>__("SLOVENIAN","google-map-sc"),
							"sr"=>__("SERBIAN","google-map-sc"),
							"sv"=>__("SWEDISH","google-map-sc"),
							"tl"=>__("TAGALOG","google-map-sc"),
							"ta"=>__("TAMIL","google-map-sc"),
							"te"=>__("TELUGU","google-map-sc"),
							"th"=>__("THAI","google-map-sc"),
							"tr"=>__("TURKISH","google-map-sc"),
							"uk"=>__("UKRAINIAN","google-map-sc"),
							"vi"=>__("VIETNAMESE","google-map-sc"),
							"zh-CN"=>__("CHINESE (SIMPLIFIED)","google-map-sc"),
							"zh-TW"=>__("CHINESE (TRADITIONAL)","google-map-sc")
                                                
        ); 
        ?> 
        <select name="language" id="language">
            <?php foreach($lang_array  as $lg => $lg_name){ ?>
                <option value="<?php echo $lg ?>" <?php echo ($lg == $language ? "selected" : "") ?> ><?php echo $lg_name ?></option>
            <?php } ?>
        </select>   
        </td>
      </tr>       
      <tr>
        <td align="right" valign="top"><strong><?php _e("Circle","google-map-sc") ?></strong></td>
        
        <td><input name="focus" type="checkbox" value="all" <?php if ($focus == "all") echo "checked = \"checked\"" ?> /> <?php _e(" Check if you want to focus all the map's points automatically with an interval of <br /><br />","google-map-sc") ?><input name="interval" type="text" size="6" value="<?php echo $interval ?>" /> <?php _e("milliseconds.","google-map-sc") ?></td>
      </tr>
      <tr>
        <td align="right" valign="top"> <strong><?php _e("Focus Type","google-map-sc") ?></strong></td>
        <td>
        <select name="focus_type" id="focus_type">
           <option value="open" <?php echo ($focus_type == "open" ? "selected" : "") ?> ><?php _e("Open Markers","google-map-sc") ?></option>
           <option value="center" <?php echo ($focus_type == "center" ? "selected" : "") ?> ><?php _e("Center Markers","google-map-sc") ?></option>
        </select>
        </td>        
      </tr>        
      <tr>
        <td align="right" valign="top"> <strong><?php _e("Animation","google-map-sc") ?></strong></td>
        <td><input name="animate" type="checkbox" value="true" <?php if ($animate == "true") echo "checked = \"checked\"" ?> /> <?php _e(" Check if you want to animate the markers.","google-map-sc") ?></td>        
      </tr>        
    </table> 
         
    <p class="submit">
    <input type="submit" name="Submit" value="<?php _e("Update") ?>" class="button-primary" />
    </p>
    
    <h3 style="padding-top:30px; margin-top:30px; border-top:1px solid #CCCCCC;"><?php _e("Markers","google-map-sc") ?></h3>
      
    <table width="80%" border="0" cellspacing="10" cellpadding="0">             
      <tr>
        <td align="right" valign="top" colspan="2">

		<?php gmshc_deploy_icons(); ?>
		
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" colspan="2">
            <?php _e("To include new icons just specify the file location:","google-map-sc") ?><br />
            <input type="file" name="datafile" size="40" /> <input type="submit" name="upload" value="Upload" class="button" />
        </td>
      </tr> 
    </table>
    
    <p class="submit">
    <input type="submit" name="Submit" value="<?php _e("Update") ?>" class="button-primary" />
    </p>
    
    <h3 style="padding-top:30px; margin-top:30px; border-top:1px solid #CCCCCC;"><?php _e("Modules","google-map-sc") ?></h3>
    <p><a href="http://web-argument.com/google-map-shortcode-modules/" target="_blank"><?php _e("Improve user interface by adding new modules") ?></a></p>
    
    <?php echo gmshc_modules_setting() ?>
    
    <h3 style="padding-top:30px; margin-top:30px; border-top:1px solid #CCCCCC;"><?php _e("Info Windows","google-map-sc") ?></h3>
    
    <p><?php _e("This is the html of the Info Window opened from the markers.","google-map-sc") ?></p>

    <div id="gmshc_html">
        <div id="gmshc_previews">
        <?php 
            $current_html = gmshc_defaul_windowhtml();
            if  (!empty($windowhtml)) $current_html = str_replace("\\", "",$windowhtml);
		?>
            <p><strong><?php _e("Previews","google-map-sc") ?></strong></p> 
            <div id="gmshc_html_previews">       
            <?php echo $current_html; ?>
            </div>
        </div>
        <div id="gmshc_html_cont">
            <p><strong><?php _e("Custom Html","google-map-sc") ?></strong></p>
            <textarea name="windowhtml" cols="60" rows="12" id="windowhtml">
            <?php echo $current_html; ?>
            </textarea>
        </div>        
    </div>
    
    <p><?php _e("The following tags can be used.","google-map-sc") ?></p>    
    <table width="80%" border="0" cellspacing="10" cellpadding="0">
      <tr>
        <td width="60" align="right"><strong>%title%</strong></td>
        <td><?php _e("Custom title of the point","google-map-sc") ?></td>
      </tr>
      <tr>
         <td align="right"><strong>%link%</strong></td>
        <td><?php _e("Permanet Link of the post where the point is attached","google-map-sc") ?></td>
      </tr>
      <tr>
        <td align="right"><strong>%thubnail%</strong></td>
        <td><?php _e("Thubnail attached to the point","google-map-sc") ?></td>
      </tr>
      <tr>
        <td align="right"><strong>%description%</strong></td>
        <td><?php _e("Description of the point","google-map-sc") ?></td>
      </tr>      
      <tr>
        <td align="right"><strong>%excerpt%</strong></td>
        <td><?php _e("Excerpt of the post where the point is attached","google-map-sc") ?></td>
      </tr>  
      <tr>
        <td align="right"><strong>%address%</strong></td>
        <td><?php _e("The address of this point","google-map-sc") ?></td>
      </tr>
      <tr>
        <td align="right"><strong>%open_map%</strong></td>
        <td><?php _e("Open this point on Google Map","google-map-sc") ?></td>
      </tr> 
      <tr>
        <td align="right"><strong>%width%</strong></td>
        <td><?php _e("Info Html width","google-map-sc") ?></td>
      </tr>           
    </table>


    <p class="submit">
    <input type="submit" name="Submit" value="<?php _e("Update") ?>" class="button-primary" /><input type="submit" name="Restore_Default" value="<?php _e("Restore Default","google-map-sc") ?>" class="button" />
    </p>
    </form>
    
    <?php echo gmshc_plugin_menu(); ?>
    
    </div>


<?php } 

/**
 * Adding media tab
 */
function gmshc_media_menu($tabs) {
$newtab = array('gmshc' => __('Google Map Shortcode', 'google-map-sc'));
return array_merge($tabs, $newtab);
}

add_filter('media_upload_tabs', 'gmshc_media_menu');

function gmshc_plugin_menu(){ 

   $links_arr = array(
   						array("text"=>__("Plugin Page","google-map-sc"),"url"=>"http://web-argument.com/google-map-shortcode-wordpress-plugin/"),
						array("text"=>__("How To Use","google-map-sc"),"url"=>"http://web-argument.com/google-map-shortcode-how-to-use/"),
						array("text"=>__("Shortcode Reference","google-map-sc"),"url"=>"http://web-argument.com/google-map-shortcode-reference/"),
						array("text"=>__("Modules","google-map-sc"),"url"=>"http://web-argument.com/google-map-shortcode-modules/"),						
						array("text"=>__("Examples","google-map-sc"),"url"=>"http://web-argument.com/google-map-shortcode-wordpress-plugin/#examples"),
						array("text"=>__("Donate","google-map-sc"),"url"=>"https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=support%40web%2dargument%2ecom&lc=US&item_name=Web%2dArgument%2ecom&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted")
						);
						
   $output = "<p align='center' style='font-size:14px;'>";
   						
   foreach ($links_arr as $link){
	   $output .= "<a href=".$link['url']." target='_blank'>".$link['text']."</a> &nbsp; ";	   
   }
   
   $output .= "</p>";
   
   return $output;   	

}

?>
