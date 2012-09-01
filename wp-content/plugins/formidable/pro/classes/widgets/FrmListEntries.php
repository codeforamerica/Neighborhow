<?php
    
class FrmListEntries extends WP_Widget {

	function FrmListEntries() {
		$widget_ops = array( 'description' => __( "Display a list of Formidable entries", 'formidable') );
		$this->WP_Widget('frm_list_items', __('Formidable Entries List', 'formidable'), $widget_ops);
	}

	function widget( $args, $instance ) {
        global $frmdb, $frm_entry, $frmpro_display, $frm_entry_meta;
        
        extract($args);
        $display = $frmpro_display->getOne($instance['display_id']);
		$title = apply_filters('widget_title', (empty($instance['title']) and $display) ? $display->name : $instance['title']);
        $limit = empty($instance['limit']) ? '' : " LIMIT {$instance['limit']}";
        $post_id = (!$display or empty($display->post_id)) ? $instance['post_id'] : $display->post_id;
        $page_url = get_permalink($post_id);
        
        $order_by = '';
        
        if ($display and is_numeric($display->form_id)){
            $options = maybe_unserialize($display->options);
            
            if (is_array($options)){                    
                if (isset($options['order_by']) && $options['order_by'] != ''){
                    $order = (isset($options['order'])) ? ' '.$options['order'] : '';
                    if ($options['order_by'] == 'rand')
                        $order_by = ' RAND()';
                    else if (is_numeric($options['order_by'])){
                        global $frm_entry_meta;
                        $metas = $frm_entry_meta->getAll('fi.form_id='.$display->form_id.' and fi.id='.$options['order_by'], ' ORDER BY meta_value'.$order, $limit);
                        
                        if (is_array($metas) and !empty($metas)){
                            $rev_order = ($order == 'DESC' or $order == '') ? ' ASC' : ' DESC';
                            foreach ($metas as $meta)
                                $order_by .= 'it.id='.$meta->item_id . $rev_order.', ';
                            
                            $order_by = rtrim($order_by, ', ');  
                        }else
                            $order_by .= 'it.created_at'.$order;
                    }else
                        $order_by = 'it.'.$options['order_by'].$order;
                    $order_by = ' ORDER BY '.$order_by;
                }
                
                if (isset($instance['cat_list']) and (int)$instance['cat_list'] == 1 and is_numeric($instance['cat_id'])){
                    global $frm_field;
                    if ($cat_field = $frm_field->getOne($instance['cat_id']))
                        $categories = maybe_unserialize($cat_field->options);
                }
            }
        }
       
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
        
        echo "<ul id='frm_entry_list". (($display) ? $display->form_id : '') ."'>\n";
        if (isset($instance['cat_list']) and (int)$instance['cat_list'] == 1 and is_array($categories)){
            foreach ($categories as $cat_order => $cat){
                if ($cat == '') continue;
                echo "<li>";
                
                if (isset($instance['cat_name']) and (int)$instance['cat_name'] == 1)
                    echo "<a href='".add_query_arg(array('frm_cat' => $cat_field->field_key, 'frm_cat_id' => $cat_order), $page_url)."'>";
                
                echo stripslashes($cat);
                
                if (isset($instance['cat_count']) and (int)$instance['cat_count'] == 1)
                    echo ' ('. $frmdb->get_count($frmdb->entry_metas, array("meta_value" => $cat, "field_id" => $instance['cat_id'])) .')';
                
                if (isset($instance['cat_name']) and (int)$instance['cat_name'] == 1)
                    echo "</a>";
                else{
                    $entry_ids = $frm_entry_meta->getEntryIds("meta_value LIKE '%$cat%' and fi.id=".$instance['cat_id']);
                    $items = false;
                    if ($entry_ids)
                        $items = $frm_entry->getAll("it.id in (".implode(',', $entry_ids).") and it.form_id =". (int)$display->form_id, $order_by, $limit);                
                        
                    if ($items){
                        echo '<ul>';
                        foreach ($items as $item){
                            $url_id = $display->type == 'id' ? $item->id : $item->item_key;
                            $current = (isset($_GET[$display->param]) and $_GET[$display->param] == $url_id) ? ' class="current_page"' : '';

                            if($item->post_id)
                                $entry_link = get_permalink($item->post_id);
                            else
                                $entry_link = add_query_arg(array($display->param => $url_id), $page_url);
                            
                            echo "<li". $current ."><a href='".$entry_link."'>".stripslashes($item->name) ."</a></li>\n";
                        }
                        echo '</ul>';
                    }
                }
                echo '</li>';
             }  
         }else{
             if($display)
                 $items = $frm_entry->getAll(array('it.form_id' => $display->form_id), $order_by, $limit);
             else
                $items = array();
                
             foreach ($items as $item){
                  $url_id = $display->type == 'id' ? $item->id : $item->item_key;
                  $current = (isset($_GET[$display->param]) and $_GET[$display->param] == $url_id) ? ' class="current_page"' : '';

                  echo "<li". $current ."><a href='".add_query_arg(array($display->param => $url_id), $page_url)."'>".stripslashes($item->name) ."</a></li>\n";
              }
         }
         
         echo "</ul>\n";
        
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) { 
	    global $frmpro_display, $frm_ajax_url; 
        $pages = get_posts( array('post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 999, 'order_by' => 'post_title', 'order' => 'ASC'));
        $displays = $frmpro_display->getAll("show_count = 'dynamic'");
        
        //Defaults
		$instance = wp_parse_args( (array) $instance, array('title' => false, 'display_id' => false, 'post_id' => false, 'title_id' => false, 'cat_list' => false, 'cat_name' => false, 'cat_count' => false, 'cat_id' => false, 'limit' => false) );
		
		$cat_opts = false;
		if ($instance['display_id']){
		    global $frm_field;
		    $selected_display = $frmpro_display->getOne($instance['display_id']);
		    $title_opts = $frm_field->getAll("fi.form_id=$selected_display->form_id and type not in ('divider','captcha','break','html')", ' ORDER BY field_order');
		}
?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'formidable') ?>:</label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( stripslashes($instance['title']) ); ?>" /></p>
	
	<p><label for="<?php echo $this->get_field_id('display_id'); ?>"><?php _e('Use Settings from Display', 'formidable') ?>:</label>
	    <select name="<?php echo $this->get_field_name('display_id'); ?>" id="<?php echo $this->get_field_id('display_id'); ?>" class="widefat" onchange="frm_get_display_fields(this.value)">
	        <option value=""></option>
            <?php foreach ($displays as $display)
                echo "<option value=". $display->id . selected( $instance['display_id'], $display->id ) . ">" . $display->name . "</option>"; 
            ?>
        </select>
	</p>
	<p class="description"><?php _e('Only custom displays will show here if they are showing "Both (Dynamic)" data.', 'formidable') ?></p>

	<p><label for="<?php echo $this->get_field_id('post_id'); ?>"><?php _e('Page if not specified in display settings', 'formidable') ?>:</label>
        <select name="<?php echo $this->get_field_name('post_id'); ?>" id="<?php echo $this->get_field_id('post_id'); ?>" class="widefat">
	        <option value=""></option>
            <?php foreach ($pages as $page)
                echo "<option value=". $page->ID . selected( $instance['post_id'], $page->ID ) . ">" . $page->post_title . "</option>"; 
            ?>
        </select>
    </p>
    
    <p><label for="<?php echo $this->get_field_id('title_id'); ?>"><?php _e('Title Field', 'formidable') ?>:</label>
        <select name="<?php echo $this->get_field_name('title_id'); ?>" id="<?php echo $this->get_field_id('title_id'); ?>" class="widefat">
	        <option value=""></option>
            <?php 
            if (isset($title_opts) and $title_opts){
                foreach ($title_opts as $title_opt)
                if($title_opt->type != 'checkbox')
                    echo "<option value=". $title_opt->id . selected( $instance['title_id'], $title_opt->id ) . ">" . stripslashes($title_opt->name) . "</option>"; 
            }
            ?>
        </select>
	</p>
	
    <p><input class="checkbox" type="checkbox" <?php checked($instance['cat_list'], true) ?> id="<?php echo $this->get_field_id('cat_list'); ?>" name="<?php echo $this->get_field_name('cat_list'); ?>" value="1" onclick="frm_toggle_cat_opt(this.checked)"/>
	<label for="<?php echo $this->get_field_id('cat_list'); ?>"><?php _e('List Entries by Category', 'formidable') ?></label></p>
    
    <div id="<?php echo $this->get_field_id('hide_cat_opts'); ?>">
    <p><label for="<?php echo $this->get_field_id('cat_id'); ?>"><?php _e('Category Field', 'formidable') ?>:</label>
	    <select name="<?php echo $this->get_field_name('cat_id'); ?>" id="<?php echo $this->get_field_id('cat_id'); ?>" class="widefat">
	        <option value=""></option>
	        <?php 
            if (isset($title_opts) and $title_opts){
                foreach ($title_opts as $title_opt){
                    if(in_array($title_opt->type, array('select', 'radio', 'checkbox')))
                    echo "<option value=". $title_opt->id . selected( $instance['cat_id'], $title_opt->id ) . ">" . $title_opt->name . "</option>"; 
                }
            }
            ?>
        </select>
	</p>
	
	<p><input class="checkbox" type="checkbox" <?php checked($instance['cat_count'], true) ?> id="<?php echo $this->get_field_id('cat_count'); ?>" name="<?php echo $this->get_field_name('cat_count'); ?>" value="1" />
	<label for="<?php echo $this->get_field_id('cat_count'); ?>"><?php _e('Show Entry Counts', 'formidable') ?></label></p>
	
	<p><input class="checkbox" type="radio" <?php checked($instance['cat_name'], 1) ?> id="<?php echo $this->get_field_id('cat_name'); ?>" name="<?php echo $this->get_field_name('cat_name'); ?>" value="1" />
	<label for="<?php echo $this->get_field_id('cat_name'); ?>"><?php _e('Show Only Category Name', 'formidable') ?></label><br/>
	
	<input class="checkbox" type="radio" <?php checked($instance['cat_name'], 0) ?> id="<?php echo $this->get_field_id('cat_name'); ?>" name="<?php echo $this->get_field_name('cat_name'); ?>" value="0" />
	<label for="<?php echo $this->get_field_id('cat_name'); ?>"><?php _e('Show Entries Beneath Categories', 'formidable') ?></label></p>
	</div>
	
	<p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Entry Limit (leave blank to list all)', 'formidable') ?>:</label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" value="<?php echo esc_attr( $instance['limit'] ); ?>" /></p>
	
<script type="text/javascript">
jQuery(document).ready(function($){
jQuery("#<?php echo $this->get_field_id('hide_cat_opts') ?>").hide();
if (jQuery("#<?php echo $this->get_field_id('cat_list'); ?>").attr("checked"))
    jQuery("#<?php echo $this->get_field_id('hide_cat_opts') ?>").show();
});

function frm_toggle_cat_opt(checked){
    if (checked) jQuery("#<?php echo $this->get_field_id('hide_cat_opts') ?>").fadeIn('slow');
    else jQuery("#<?php echo $this->get_field_id('hide_cat_opts') ?>").fadeOut('slow');
}

function frm_get_display_fields(display_id){
    if (display_id != ''){
      jQuery.ajax({ type:"POST", url:"<?php echo $frm_ajax_url ?>",
         data:"action=frm_get_cat_opts&display_id="+display_id,
         success:function(msg){jQuery("#<?php echo $this->get_field_id('cat_id'); ?>").html(msg);}
      });
      jQuery.ajax({ type:"POST", url:"<?php echo $frm_ajax_url ?>",
           data:"action=frm_get_title_opts&display_id="+display_id,
           success:function(msg){jQuery("#<?php echo $this->get_field_id('title_id'); ?>").html(msg);}
       });
  }
}

</script>
	
<?php  	
	}
}

?>