<div id="postbox-container-1" class="<?php echo FrmAppController::get_postbox_class(); ?> frm_html_legend postbox" style="width:240px;min-width:100px;">
    <h3><?php _e('Key', 'formidable') ?></h3>
    <div class="inside">
    <ul>
        <li><b><?php _e('Form Name', 'formidable') ?>:</b> <pre>[form_name]</pre></li>
        <li><b><?php _e('Form Description', 'formidable') ?>:</b> <pre>[form_description]</pre></li>
        <li><b><?php _e('Form Key', 'formidable') ?>:</b> <pre>[form_key]</pre></li>
        <li><b><?php _e('Delete Entry Link', 'formidable') ?>:</b> <pre>[deletelink]</pre></li>
    </ul>
    <ul>
        <li><b><?php _e('Field Id', 'formidable') ?>:</b> <pre>[id]</pre></li>
        <li><b><?php _e('Field Key', 'formidable') ?>:</b> <pre>[key]</pre></li>
        <li><b><?php _e('Field Name', 'formidable') ?>:</b> <pre>[field_name]</pre></li>
        <li><b><?php _e('Field Description', 'formidable') ?>:</b> <pre>[description]</pre></li>
        <li><b><?php _e('Label Position', 'formidable') ?>:</b> <pre>[label_position]</pre></li>
        <li><b><?php _e('Required label', 'formidable') ?>:</b> <pre>[required_label]</pre></li>
        <li><b><?php _e('Input Field', 'formidable') ?>:</b> <pre>[input]</pre><br/>
            <?php _e('Show a single radio or checkbox option by replacing "1" with the order of the option', 'formidable') ?>: <pre>[input opt=1]</pre><br/>
            <?php _e('Hide the option labels', 'formidable') ?>: <pre>[input label=0]</pre>
        </li>
        <li><b><?php _e('Add class name if field is required', 'formidable') ?>:</b> <pre>[required_class]</pre></li>
        <li><b><?php _e('Add class name if field has an error on form submit', 'formidable') ?>:</b> <pre>[error_class]</pre></li>
    </ul>
    </div>
</div>