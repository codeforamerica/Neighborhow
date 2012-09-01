<div class="postbox ">
<h3 class="hndle"><span><?php _e('Advanced Customization Options', 'formidable') ?></span></h3>
<div class="inside">
    <table width="100%">
    <tr><th width="110px"><?php _e('Code', 'formidable') ?></th><th><?php _e('Use', 'formidable') ?></th></tr>
    <tr><td><code>sep</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Use a different separator for checkbox fields', 'formidable') ?>" /></td><td>[125 sep=", "]</td></tr>
    <tr><td><code>sanitize</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Replaces spaces with dashes and lowercases all. Use if adding an HTML class or ID', 'formidable') ?>" /></td><td>[125 sanitize=1]</td></tr> 
    <tr><td><code>sanitize_url</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Replaces all HTML entities with a URL safe string.', 'formidable') ?>" /></td><td>[125 sanitize_url=1]</td></tr>
    <tr><td><code>truncate</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Truncate text with a link to view more. If using Both (dynamic), the link goes to the detail page. Otherwise, it will show in-place.', 'formidable') ?>" /></td><td>[125 truncate=100]</td></tr>
    <tr><td><code>more_text</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Specify the more link text.', 'formidable') ?>" /></td><td>[125 truncate=100 more_text="More"]</td></tr>
    <tr><td><code>clickable</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Automatically turn URLs and emails into links.', 'formidable') ?>" /></td><td>[125 clickable=1]</td></tr>
    <tr><td><code>format</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Format your dates in a format other than what is set in your WordPress settings.', 'formidable') ?>" /></td><td>[125 format="d-m-Y"]</td></tr> 
    <tr><td><code>time_ago</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('How long ago a date was in minutes, hours, days, months, or years.', 'formidable') ?>" /></td><td>[125 time_ago=1]</td></tr> 
    <tr><td><code>size</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Change the displayed size of your uploaded image: thumbnail, medium, large, or full. Some themes add extra sizes as well. Defaults to thumbnail.', 'formidable') ?>" /></td><td>&lt;img src="[125 size="thumbnail"]" /></td></tr> 
    <tr><td valign="top"><code>show</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Show a different linked field for Data from entries fields. Show any user meta or avatar for User ID fields. Show the field label for any field.', 'formidable') ?>" /></td><td>[125 show="field_label"]</td></tr>
    <tr><td valign="top"><code style="visibility:hidden;">show</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Show the saved value for fields with separate values.', 'formidable') ?>" /></td><td>[125 show="value"]<br/> <span class="howto"><?php _e('See more "show" examples below.', 'formidable') ?></span></td></tr>
    
    <tr><th style="padding-top:10px"><?php _e('Conditionals', 'formidable') ?></th>
        <th style="padding-top:10px"><?php _e('Use', 'formidable') ?></th></tr>
    <tr><td colspan="2"><span class="howto"><?php _e('Conditionally hide or show something.<br/> [if 125 equals="hello"]Show me if field #125 is exactly equal to "hello".[/if 25]', 'formidable') ?></span></td></tr>
    <tr><td><code>equals</code></td><td>[if 125 equals="hello"]</td></tr> 
    <tr><td><code>not_equal</code></td><td>[if 125 not_equal="hello"]</td></tr> 
    <tr><td><code>like</code></td><td>[if 125 like="hello"]</td></tr> 
    <tr><td><code>not_like</code></td><td>[if 125 not_like="hello"]</td></tr> 
    <tr><td><code>greater_than</code></td><td>[if 125 greater_than="3"]</td></tr> 
    <tr><td><code>less_than</code></td><td>[if 125 less_than="-1 month"]</td></tr>
    </table>
</div>
</div>


<div class="postbox ">
<h3 class="hndle"><span><?php _e('Data From Entries', 'formidable') ?></span></h3>
<div class="inside">
	<p class="howto"><?php _e('Specify the data shown for a "Data From Entries" field', 'formidable') ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Linked Entry id: id, Entry key: key, Linked entry created at: created_at, a field from the entry: use the id or key from the field in you other form.', 'formidable') ?>" /></p>
	<p><?php _e('Add', 'formidable') ?> <code>show=(id, key, created-at, *<?php _e('Field ID', 'formidable') ?>*, *<?php _e('Field Key', 'formidable') ?>*)</code><br/>
    <?php _e('Example', 'formidable') ?>: <code>[125 show="other-key"]</code></p>
</div>
</div>


<div class="postbox ">
<h3 class="hndle"><span><?php _e('User Information', 'formidable') ?></span></h3>
<div class="inside">
	<p class="howto"><?php _e('Use a field other than user Display Name if there is a User ID field in your form', 'formidable') ?></p>
	<p><?php _e('Add', 'formidable') ?> <code>show=(id, first_name, last_name, display_name, user_login, user_email, avatar)</code><br/>
    <?php _e('Example', 'formidable') ?>: <code>[125 show="first_name"]</code><br/>
    <?php _e('Leave blank instead of defaulting to User Login', 'formidable') ?>: <code>blank=1</code></p>
</div>
</div>