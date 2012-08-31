<?php

class FrmProListHelper extends WP_List_Table {
    var $plural;
    var $singular;
    var $table_name;
    var $page_name;
    var $params;
    
	function FrmProListHelper($args) {
	    $args = wp_parse_args( $args, array(
			'plural' => '',
			'singular' => '',
			'ajax' => false,
			'table_name' => '',
			'page_name' => '',
			'params' => array()
		) );
		$this->plural = $args['plural'];
		$this->singular = $args['singular'];
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
	    global $frmdb, $wpdb, $per_page, $frm_settings;
		$paged = $this->get_pagenum();
		$default_orderby = 'name';
		$default_order = 'ASC';
		if($this->plural == 'entries'){
		    $default_orderby = 'id';
		    $default_order = 'DESC';
		}
		
        $orderby = ( isset( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : $default_orderby;
		$order = ( isset( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : $default_order;
		
		$page = $this->get_pagenum();
		$per_page = $this->get_items_per_page( 'formidable_page_formidable_'. str_replace('-', '_', $this->page_name) .'_per_page');

		$start = ( isset( $_REQUEST['start'] ) ) ? $_REQUEST['start'] : (( $page - 1 ) * $per_page);
		$s = isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : '';
		$fid = isset( $_REQUEST['fid'] ) ? $_REQUEST['fid'] : '';
		if($s != ''){
		    $s = stripslashes($s);
		    preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $s, $matches);
		    $search_terms = array_map('_search_terms_tidy', $matches[0]);
		}
		$s_query = '';
		
		if($this->plural == 'entries'){
		    global $frm_entry, $frmpro_entries_controller;
		    $form_id = $this->params['form'];
		    $s_query = 'it.form_id='. (int)$form_id;
		    if($s != '')
		        $s_query = $frmpro_entries_controller->get_search_str($s_query, $s, $form_id, $fid);
		    
            $this->items = $frm_entry->getAll($s_query, " ORDER BY $orderby $order", " LIMIT $start, $per_page", true, false);
	        $total_items = $frm_entry->getRecordCount($s_query);
		}else if($this->plural == 'displays'){
		    global $frmpro_display, $frm_app_helper;

            if(isset($_REQUEST['form']) and is_numeric($_REQUEST['form'])){
                $s_query .= "form_id=". (int)$_REQUEST['form'];
            }
            
		    if($s != ''){
		        foreach((array)$search_terms as $term){
		            $term = esc_sql( like_escape( $term ) );
                    if(!empty($s_query))
                        $s_query .= " AND";

                    $s_query .= " (name like '%$term%' OR description like '%$term%' OR created_at like '%$term%' OR content like '%$term%' OR dyncontent like '%$term%')";
                    unset($term);
                }
		    }
		    
            $this->items = $frmpro_display->getAll($s_query, " ORDER BY $orderby $order", " LIMIT $start, $per_page", true, false);
	        $total_items = $frm_app_helper->getRecordCount($s_query, $this->table_name);
		}

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page' => $per_page
		) );
	}
	
	function no_items() {
	    switch($this->plural){
	        case 'entries':
    	        $s = isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : '';
    		    if(!empty($s)){
                    _e('No Entries Found', 'formidable');
                }else{
                    global $frm_form;
                    $form_id = $this->params['form'];
                    $form = $frm_form->getOne($form_id);
                    $colspan = $this->get_column_count();
                    include(FRM_VIEWS_PATH .'/frm-entries/no_entries.php');
                }
            break;
            case 'displays':
                _e('No Custom Displays Found.', 'formidable');
                echo ' <a href="?page=formidable-entry-templates&amp;frm_action=new" class="button-secondary">'. __('Add New', 'formidable') .'</a>';
            break;
            default:
                parent::no_items();
        }
	}
	
	function search_box( $text, $input_id ) {
    	if ( !$this->has_items() and !isset( $_REQUEST['s'] ))
    		return;

        if($this->plural == 'entries'){
            global $frm_form, $frm_field;
            if(isset($this->params['form']))
                $form = $frm_form->getOne($this->params['form']);
            else
                $form = $frm_form->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1');
            if($form){
                $field_list = $frm_field->getAll("fi.type not in ('divider','captcha','break','html') and fi.form_id=". $form->id, 'field_order');
                $fid = isset($_REQUEST['fid']) ? esc_attr( stripslashes( $_REQUEST['fid'] ) ) : '';
            }
        }
        
    	$input_id = $input_id . '-search-input';
        $search_str = isset($_REQUEST['s']) ? esc_attr( stripslashes( $_REQUEST['s'] ) ) : '';
        
    	if ( ! empty( $_REQUEST['orderby'] ) )
    		echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
    	if ( ! empty( $_REQUEST['order'] ) )
    		echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
?>
<p class="search-box">
    <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
    <?php if(isset($field_list) and !empty($field_list)){ ?>
    <select name="fid">
        <option value="">- <?php _e('All Fields', 'formidable') ?> -</option>
        <option value="created_at" <?php selected($fid, 'created_at') ?>><?php _e('Entry creation date', 'formidable') ?></option>
        <?php foreach($field_list as $f){ ?>
        <option value="<?php echo ($f->type == 'user_id') ? 'user_id' : $f->id ?>" <?php selected($fid, $f->id) ?>><?php echo FrmAppHelper::truncate($f->name, 30);  ?></option>
        <?php } ?>
    </select>
    <?php } ?>
    <input type="text" id="<?php echo $input_id ?>" name="s" value="<?php echo $search_str; ?>" />
    <?php submit_button( $text, 'button', false, false, array('id' => 'search-submit') ); ?>
    <?php if(!empty($search_str)){ ?>
    or <a href=""><?php _e('Reset', 'formidable') ?></a>
    <?php } ?>
</p>
<?php
	}
	
	function get_bulk_actions(){
        $actions = array( 'bulk_delete' => __('Delete', 'formidable'));
        $actions['bulk_export'] = __('Export to XML', 'formidable');
        
        if($this->plural == 'entries')
            $actions['bulk_csv'] = __('Export to CSV', 'formidable');
            
        return $actions;
    }
    
    function extra_tablenav( $which ) {
        $footer = ($which == 'top') ? false : true;
        FrmProEntriesHelper::before_table($footer, $this->params['form']);
	}

	function display_rows() {
		$style = '';
		foreach ( $this->items as $item ) {
			$style = ( ' class="alternate"' == $style ) ? '' : ' class="alternate"';
			echo "\n\t", $this->single_row( $item, $style );
		}
	}
	
	function single_row( $item, $style='') {
	    global $frmpro_settings;
		$checkbox = '';
		
		// Set up the hover actions for this user
		$actions = array();
		
		
		$edit_link = "?page=formidable-{$this->page_name}&frm_action=edit&id={$item->id}";
		$actions['edit'] = "<a href='" . wp_nonce_url( $edit_link ) . "'>". __('Edit', 'formidable') ."</a>";
		
		$duplicate_link = "?page=formidable-{$this->page_name}&frm_action=duplicate&id={$item->id}";
		$delete_link = "?page=formidable-{$this->page_name}&frm_action=destroy&id={$item->id}";
		
		
		if($this->plural == 'entries'){
		    $duplicate_link .= "&form=". $this->params['form'];
		    $delete_link .= "&form=". $this->params['form'];
		    $view_link = "?page=formidable-{$this->page_name}&frm_action=show&id={$item->id}";
		    $actions['view'] = "<a href='" . wp_nonce_url( $view_link ) . "'>". __('View', 'formidable') ."</a>";
        }
        
        $actions['duplicate'] = "<a href='" . wp_nonce_url( $duplicate_link ) . "'>". __('Duplicate', 'formidable') ."</a>";
		$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( $delete_link ) . "' onclick='return confirm(\"". __('Are you sure you want to delete that?', 'formidable') ."\")'>" . __( 'Delete', 'formidable' ) . "</a>";
		
	    if($this->plural == 'displays' and !current_user_can('frm_edit_displays')){
	        $actions = array();
	    }else if($this->plural == 'entries'){
    		if(!current_user_can('frm_edit_entries'))
    		    unset($actions['edit']);
    		if(!current_user_can('frm_create_entries'))
    		    unset($actions['duplicate']);
    		if(!current_user_can('frm_delete_entries')) 
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
			else if(!$action_col and !in_array($column_name, array('cb', 'id', 'form_id', 'post_id')))
			    $action_col = $column_name;

			$attributes = "$class$style";

            $col_name = preg_replace('/^('. $this->params['form'] .'_)/', '', $column_name);
			switch ( $col_name ) {
				case 'cb':
					$r .= "<th scope='row' class='check-column'>$checkbox</th>";
					break;
				case 'ip':
				case 'id':
				case 'item_key':
				case 'display_key':
				case 'show_count':
				    $val = stripslashes($item->{$col_name});
				    break;
				case 'name':
				case 'description':
				case 'content':
				case 'dyncontent':
				    $val = FrmAppHelper::truncate(strip_tags(stripslashes($item->{$col_name})), 100);
				    break;
				case 'created_at':
				case 'updated_at':
				    $date = date($frmpro_settings->date_format, strtotime($item->{$col_name}));
					$val = "<abbr title='". date($frmpro_settings->date_format .' g:i:s A', strtotime($item->{$col_name})) ."'>". $date ."</abbr>";
					break;
				case 'form_id':
				    global $frm_form;
				    $form = $frm_form->getName($item->form_id);
				    if($form)
				        $val = '<a href="'. admin_url('admin.php') .'?page=formidable&frm_action=edit&id='. $item->form_id .'">'. FrmAppHelper::truncate(stripslashes($form), 40) .'</a>';
    				else
    				    $val = '';
    				break; 
				case 'post_id':
				    if($this->plural == 'displays' and $item->insert_loc == 'none'){
				        $val = '';
				        break;
				    }
				        
				    $post = get_post($item->{$col_name});
				    if($post)
				        $val = '<a href="'. admin_url('post.php') .'?post='. $item->{$col_name} .'&amp;action=edit">'. FrmAppHelper::truncate($post->post_title, 50) .'</a>';
				    else
				        $val = '';
				    break;
				case 'user_id':
				    $user = get_userdata($item->user_id);
				    $val = $user->user_login;
				    break;
				case 'shortcode':
				    if($this->plural == 'displays')
				        $code = "[display-frm-data id={$item->id} filter=1]";
				    else
				        $code = '';
				    
				    $val = "<input type='text' style='font-size:10px;width:100%;' readonly='true' onclick='this.select();' onfocus='this.select();' value='{$code}' />";
			        break;
				default:
				
				    if($this->plural == 'entries'){
    				    global $frm_field;
    				    $col = $frm_field->getOne($col_name);

    				    $field_value = isset($item->metas[$col->id]) ? $item->metas[$col->id] : false;
                        $col->field_options = maybe_unserialize($col->field_options);

                        if(!$field_value and $col->type == 'data' and $col->field_options['data_type'] == 'data' and
                         isset($col->field_options['hide_field'])){
                             $field_value = array();
                             foreach((array)$col->field_options['hide_field'] as $hfield ){
                                 if(isset($item->metas[$hfield]))
                                     $field_value[] = maybe_unserialize($item->metas[$hfield]);
                             }
                        }

    					$val = FrmProEntryMetaHelper::display_value($field_value, $col, array('type' => $col->type, 'truncate' => true, 'post_id' => $item->post_id, 'entry_id' => $item->id));
				    }else{
				        $val = $col_name;
				    }
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