<?php

class FrmListHelper extends WP_List_Table {
    var $table_name;
    var $page_name;
    var $params;
    
	function FrmListHelper($args) {
	    global $frm_settings;
	    
	    $args = wp_parse_args( $args, array(
			'ajax' => false,
			'table_name' => '',
			'page_name' => '',
			'params' => array()
		) );
		$this->table_name = $args['table_name'];
		$this->page_name = $args['page_name'];
		$this->params = $args['params'];
		
	    $screen = get_current_screen();

		parent::__construct( $args );
	}

	function ajax_user_can() {
		return current_user_can( 'administrator' );
	}

	function prepare_items() {
	    global $frmdb, $wpdb, $per_page, $frm_settings, $frm_form, $frm_app_helper;
		$paged = $this->get_pagenum();
		$default_orderby = 'name';
		$default_order = 'ASC';
		
        $orderby = ( isset( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : $default_orderby;
		$order = ( isset( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : $default_order;
		
		$page = $this->get_pagenum();
		$default_count = empty($this->page_name) ? 20 : 10;
		$per_page = $this->get_items_per_page( 'formidable_page_formidable'. str_replace('-', '_', $this->page_name) .'_per_page', $default_count);

		$start = ( isset( $_REQUEST['start'] ) ) ? $_REQUEST['start'] : (( $page - 1 ) * $per_page);
		$s = isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : '';
		$fid = isset( $_REQUEST['fid'] ) ? $_REQUEST['fid'] : '';
		if($s != ''){
		    $s = stripslashes($s);
		    preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $s, $matches);
		    $search_terms = array_map('_search_terms_tidy', $matches[0]);
		}
		
		$s_query =  " (status is NULL OR status = '' OR status = 'published') AND default_template=0 AND is_template = ". (int)$this->params['template'];

	    if($s != ''){
	        foreach((array)$search_terms as $term){
	            $term = esc_sql( like_escape( $term ) );
                if(!empty($s_query))
                    $s_query .= " AND";

                $s_query .= " (name like '%$term%' OR description like '%$term%' OR created_at like '%$term%')";
                unset($term);
            }
	    }
	    
        $this->items = $frm_form->getAll($s_query, " ORDER BY $orderby $order", " LIMIT $start, $per_page", true, false);
        $total_items = $frm_app_helper->getRecordCount($s_query, $this->table_name);
		

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page' => $per_page
		) );
	}
	
	function no_items() {
	    if ($this->params['template']){
            _e('No Templates Found', 'formidable') ?>. 
            <br/><br/><?php _e('To add a new template','formidable') ?>:
            <ol><li style="list-style:decimal;">Create a <a href="?page=formidable-new">new form</a>.</li>
                <li style="list-style:decimal;">After your form is created, go to Formidable -> <a href="?page=formidable">Forms</a>.</li>
                <li style="list-style:decimal;"><?php _e('Place your mouse over the name of the form you just created, and click the "Create Template" link.', 'formidable') ?></li>
            </ol>
<?php   }else{ 
            _e('No Forms Found', 'formidable') ?>. 
            <a href="?page=formidable-new"><?php _e('Add New', 'formidable'); ?></a>
<?php   }
	}
	
	function get_bulk_actions(){
	    global $frmpro_is_installed;
	    
	    $actions = array();
	    if($frmpro_is_installed){
            $actions['bulk_delete'] = __('Delete', 'formidable');
            $actions['bulk_export'] = __('Export to XML', 'formidable');
        }
            
        return $actions;
    }

	function display_rows() {
		$style = '';
		foreach ( $this->items as $item ) {
			$style = ( ' class="alternate"' == $style ) ? '' : ' class="alternate"';
			echo "\n\t", $this->single_row( $item, $style );
		}
	}
	
	function single_row( $item, $style='') {
	    global $frmpro_is_installed, $frm_entry;
		$checkbox = '';
		
		// Set up the hover actions for this user
		$actions = array();
		$title = esc_attr(strip_tags(stripslashes($item->name)));
		
		$edit_link = "?page=formidable&frm_action=edit&id={$item->id}";
		$actions['edit'] = "<a href='" . wp_nonce_url( $edit_link ) . "'>". __('Edit', 'formidable') ."</a>";
		
		$duplicate_link = "?page=formidable&frm_action=duplicate&id={$item->id}";
		
		$view_link = "?page=formidable-{$this->page_name}&frm_action=show&id={$item->id}";
		
		
		if ($this->params['template']){
		    $actions['duplicate'] = "<a href='" . wp_nonce_url( $duplicate_link ) . "'>". __('Create Form from Template', 'formidable') ."</a>";
        }else{
    		$actions['settings'] = "<a href='" . wp_nonce_url( "?page=formidable&frm_action=settings&id={$item->id}" ) . "'>". __('Settings', 'formidable') ."</a>";
    		
    		//$actions['entries'] = "<a href='" . wp_nonce_url( "?page=formidable-entries&form={$item->id}" ) . "' title='$title ". __('Entries', 'formidable') ."'>". __('Entries', 'formidable') ."</a>";
    		
    		$actions['reports'] = "<a href='" . wp_nonce_url( "?page=formidable-reports&form={$item->id}" ) . "' title='$title ". __('Reports', 'formidable') ."'>". __('Reports', 'formidable') ."</a>";
    		
    		if($frmpro_is_installed and current_user_can('frm_create_entries')){
        		$actions['entry'] = "<a href='" . wp_nonce_url( "?page=formidable-entries&frm_action=new&form={$item->id}" ) . "' title='". __('New', 'formidable') ." $title ". __('Entry', 'formidable') ."'>". __('New Entry', 'formidable')  ."</a>";
        	}
        	
        	$actions['duplicate'] = "<a href='" . wp_nonce_url( $duplicate_link ) . "' title='". __('Copy', 'formidable') ." $title'>". __('Duplicate', 'formidable') ."</a>";
        	
        	if($frmpro_is_installed){
        	    $actions['template'] = "<a href='" . wp_nonce_url( "?page=formidable&frm_action=duplicate&id={$item->id}&template=1" ) . "' title='". __('Create Template', 'formidable') ."'>". __('Create Template', 'formidable') ."</a>";
        	    
        	}
        }
        
        if($frmpro_is_installed){
    	    $actions['export_template'] = "<a href='" . wp_nonce_url( FRM_SCRIPT_URL ."&controller=forms&frm_action=export&id={$item->id}" ) . "' title='$title ". __('Export Template', 'formidable') ."'>". __('Export Template', 'formidable') ."</a>";
    	    
    	}
        
        $delete_link = "?page=formidable&frm_action=destroy&id={$item->id}";
		$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( $delete_link ) . "' onclick='return confirm(\"". __('Are you sure you want to delete that?', 'formidable') ."\")'>" . __( 'Delete', 'formidable' ) . "</a>";
        
        if(!current_user_can('frm_view_entries')){
            if(isset($actions['entries']))
                unset($actions['entries']);
                
            if(isset($actions['reports']))
                unset($actions['reports']);
        }
        
        if(!current_user_can('frm_edit_forms')){
            unset($actions['edit']);
            unset($actions['duplicate']);
            if(isset($actions['settings']))
                unset($actions['settings']);
                
            if(!$frmpro_is_installed){
                unset($actions['duplicate']);
            }
        }
        
        if(!current_user_can('frm_delete_forms')){
            unset($actions['delete']);       
        }
        
        $action_links = $this->row_actions( $actions );
        
		// Set up the checkbox ( because the user is editable, otherwise its empty )
		$checkbox = "<input type='checkbox' name='item-action[]' id='cb-item-action-{$item->id}' value='{$item->id}' />";

		$r = "<tr id='item-action-{$item->id}'$style>";

		list( $columns, $hidden ) = $this->get_column_info();
        $action_col = false;

		foreach ( $columns as $column_name => $column_display_name ) {
			$class = "class=\"$column_name column-$column_name\"";

			$style = '';
			if ( in_array( $column_name, $hidden ) )
				$style = ' style="display:none;"';
			else if(!$action_col and !in_array($column_name, array('cb', 'id')))
			    $action_col = $column_name;

			$attributes = "$class$style";

			switch ( $column_name ) {
				case 'cb':
					$r .= "<th scope='row' class='check-column'>$checkbox</th>";
					break;
				case 'id':
				case 'form_key':
				    $val = stripslashes($item->{$column_name});
				    break;
				case 'name':
				case 'description':
				    $val = FrmAppHelper::truncate(strip_tags(stripslashes($item->{$column_name})), 100);
				    break;
				case 'created_at':
				    $format = 'Y/m/d'; //get_option('date_format');
				    $date = date($format, strtotime($item->{$column_name}));
					$val = "<abbr title='". date($format .' g:i:s A', strtotime($item->{$column_name})) ."'>". $date ."</abbr>";
					break;
				case 'shortcode':
				    $val = "<input type='text' style='font-size:10px;width:100%;' readonly='true' onclick='this.select();' onfocus='this.select();' value='[formidable id={$item->id}]' /><br/>";
				    $val .= "<input type='text' style='font-size:10px;width:100%;' readonly='true' onclick='this.select();' onfocus='this.select();' value='[formidable key={$item->form_key}]' />";
				    
			        break;
			    case 'entries':
			        $text = $frm_entry->getRecordCount($item->id);
                    $text = sprintf(_n( '%1$s Entry', '%1$s Entries', $text, 'formidable' ), $text);
                    $val = (current_user_can('frm_view_entries')) ? '<a href="'. esc_url(admin_url('admin.php') .'?page=formidable-entries&form='. $item->id ) .'">'. $text .'</a>' : $text;
                    unset($text);
                    
			        break;
			    case 'link':
			        $target_url = FrmFormsHelper::get_direct_link($item->form_key, $item->prli_link_id);
                    $val = '<input type="text" style="font-size:10px;width:100%;" readonly="true" onclick="this.select();" onfocus="this.select();" value="'. esc_html($target_url) .'" /><br/><a href="'. esc_html($target_url) .'" target="blank">'. __('View Form', 'formidable') .'</a>';
                    unset($target_url);
                    break;
				default:
				    $val = $column_name;
				break;
			}
			
			if(isset($val)){
			    $r .= "<td $attributes>";
			    if($column_name == $action_col){                              
			        $r .= '<a class="row-title" href="'. (isset($actions['edit']) ? $edit_link : $view_link) .'">'. $val .'</a> ';
			        $r .= $action_links;
			    }else{
			        $r .= $val;
			    }
			    $r .= '</td>';
			}
			unset($val);
		}
		$r .= '</tr>';

		return $r;
	}
	

}

?>