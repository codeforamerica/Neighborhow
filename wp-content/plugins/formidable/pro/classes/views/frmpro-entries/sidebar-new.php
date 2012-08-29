<div id="postbox-container-1" class="<?php echo FrmAppController::get_postbox_class(); ?>">
    <div id="submitdiv" class="postbox ">
    <h3 class="hndle"><span><?php _e('Publish', 'formidable') ?></span></h3>
    <div class="inside">
        <div class="submitbox">

        <div id="major-publishing-actions">
    	    <div id="delete-action">
    	    <a class="submitdelete deletion" onclick="history.back(-1)" title="<?php _e('Cancel', 'formidable') ?>"><?php _e('Cancel', 'formidable') ?></a>
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