<div id="postbox-container-1" class="<?php echo FrmAppController::get_postbox_class(); ?>">
    <div id="submitdiv" class="postbox ">
    <h3 class="hndle"><span><?php _e('Shortcode Options', 'formidable') ?></span></h3>
    <div class="inside">
        <div class="submitbox">
        <div id="minor-publishing">
        <div class="misc-pub-section">
            
        <p class="howto"><?php _e('Insert display', 'formidable'); ?>
            <select id="insert_loc" name="insert_loc" onchange="frm_show_loc(this.value)">
                <option value="after" <?php selected($values['insert_loc'], 'after') ?>><?php _e('After page content', 'formidable') ?></option>
                <option value="before" <?php selected($values['insert_loc'], 'before') ?>><?php _e('Before page content', 'formidable') ?></option>
                <option value="replace" <?php selected($values['insert_loc'], 'replace') ?>><?php _e('Replace page content', 'formidable') ?></option>
                <option value="none" <?php selected($values['insert_loc'], 'none') ?>><?php _e('Don\'t insert automatically', 'formidable') ?></option>
            </select><br/>

            <span id="post_select_container">
                <?php _e('on page', 'formidable'); ?>
                    <!-- <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('If you would like the content to be inserted automatically, you must then select the page in which to insert it.', 'formidable') ?>" /><br/> -->
                <?php FrmAppHelper::wp_pages_dropdown( 'post_id', $values['post_id'], 35 ); ?>
            </span>
            <?php if($values['insert_loc'] != 'none' and is_numeric($values['post_id'])){ ?>
            <a href="<?php echo get_permalink($values['post_id']) ?>" target="_blank" class="button-secondary"><?php _e('View Post', 'formidable') ?></a>
            <?php } ?>
        </p>
        
        <p class="howto"><?php _e('Insert position', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('If the custom display doesn\'t show automatically when it should, insert a higher number here.', 'formidable') ?>" />
            <input type="number" id="insert_pos" name="options[insert_pos]" min="1" max="15" step="1" value="<?php echo esc_attr($values['insert_pos']) ?>" style="width:30px;float:none;"/> 
        </p>
        </div>
        
        <div class="misc-pub-section-last" style="text-align:center;">
    	<p class="howto"><?php _e('Insert on a page, post, or text widget', 'formidable') ?>:</p>
    	<p><input type="text" style="font-weight:bold;width:98%;text-align:center;" readonly="true" onclick='this.select();' onfocus='this.select();' value='[display-frm-data id=<?php echo (isset($id)) ? $id : __('Save to get ID', 'formidable') ?>]' />
    	<input type="text" style="font-weight:bold;width:98%;text-align:center;margin-top:4px;" readonly="true" onclick='this.select();' onfocus='this.select();' value='[display-frm-data id=<?php echo (isset($values['display_key']) and $values['display_key'] != '') ? $values['display_key'] : '??' ?>]' /></p>
    	
    	<p class="howto"><?php _e('Insert in a template', 'formidable') ?>:</p>
    	<p><input type="text" style="font-size:10px;width:98%;text-align:center;" readonly="true" onclick='this.select();' onfocus='this.select();' value="&lt;?php echo FrmProDisplaysController::get_shortcode(array('id' => <?php echo (isset($id)) ? $id : '??' ?>)) ?&gt;" /></p>
        
    	</div>
    	</div>
    	<div id="major-publishing-actions">
    	    <div id="delete-action">
    	    <?php if(isset($id)){ ?>
    	    <a class="submitdelete deletion" href="?page=formidable-entry-templates&amp;frm_action=destroy&amp;id=<?php echo $id; ?>" onclick="return confirm('<?php printf(__('Are you sure you want to delete your %1$s display?', 'formidable'), esc_attr(stripslashes($values['name']))) ?>);" title="<?php _e('Delete', 'formidable') ?>"><?php _e('Delete', 'formidable') ?></a>
    	    <?php }else{ ?>
    	    <a class="submitdelete deletion" href="?page=formidable-entry-templates"><?php _e('Cancel', 'formidable') ?></a>
    	    <?php } ?>
    	    </div>
    	    <div id="publishing-action">
            <input type="submit" value="<?php echo esc_attr($submit) ?>" class="button-primary" />
            </div>
            <div class="clear"></div>
        </div>
        </div>
    </div>
    </div>
    
    <div id="frm_form_tags"><?php include(FRMPRO_VIEWS_PATH .'/displays/tags.php'); ?></div>
</div>