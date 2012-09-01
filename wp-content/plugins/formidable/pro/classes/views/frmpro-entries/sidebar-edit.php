<div id="postbox-container-1" class="<?php echo FrmAppController::get_postbox_class(); ?>">
    <div id="submitdiv" class="postbox ">
    <h3 class="hndle"><span><?php _e('Publish', 'formidable') ?></span></h3>
    <div class="inside">
        <div class="submitbox">
        <div id="minor-publishing" style="border:none;">
        <div class="misc-pub-section">
            <?php if($record->post_id){ ?>
            <a href="<?php echo get_permalink($record->post_id) ?>" class="button-secondary alignright" style="margin-left:10px"><?php _e('View Post', 'formidable') ?></a>
            <?php } ?>
            <a href="?page=formidable-entries&amp;frm_action=show&amp;id=<?php echo $record->id; ?>" class="button-secondary alignright"><?php _e('View', 'formidable') ?></a>
            <a href="?page=formidable-entries&amp;frm_action=duplicate&amp;form=<?php echo $form->id ?>&amp;id=<?php echo $record->id; ?>" class="button-secondary alignright" style="margin-right:10px"><?php _e('Duplicate', 'formidable') ?></a>
            <div class="clear"></div>

            <p class="howto">
            <?php FrmProEntriesHelper::resend_email_links($record->id, $form->id); ?>
            </p>
            
        </div>
        </div>
        
        <div id="major-publishing-actions">
    	    <div id="delete-action">
    	    <a class="submitdelete deletion" href="?page=formidable-entries&amp;frm_action=destroy&amp;id=<?php echo $record->id; ?>&amp;form=<?php echo $form->id ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this entry?', 'formidable') ?>);" title="<?php _e('Delete', 'formidable') ?>"><?php _e('Delete', 'formidable') ?></a>
    	    </div>
    	    <div id="publishing-action">
            <input type="submit" value="<?php echo esc_attr($submit) ?>" class="button-primary" />
            </div>
            <div class="clear"></div>
        </div>
        </div>
    </div>
    </div>
</div>