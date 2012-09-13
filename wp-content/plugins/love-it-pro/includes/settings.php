<?php

// this file contains all settings pages and options

function lip_settings_page()
{
	global $lip_options;
		
	?>
	<div class="wrap">
		<div id="upb-wrap" class="upb-help">
			<h2><?php _e('Love It Pro Settings', 'love_it'); ?></h2>
			<?php
			if ( ! isset( $_REQUEST['updated'] ) )
				$_REQUEST['updated'] = false;
			?>
			<?php if ( false !== $_REQUEST['updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
			<?php endif; ?>
			<form method="post" action="options.php">

				<?php settings_fields( 'lip_settings_group' ); ?>
				
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<?php _e('Show on Post Types', 'love_it'); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<span><?php _e('Show on Post Types', 'love_it'); ?></span>
									</legend>
									<label for="lip_settings[post_types][]">
										<?php
										$post_types = get_post_types(array('public' => true), 'objects');
										$saved_post_types = isset($lip_options['post_types']) ? $lip_options['post_types'] : array();
										foreach($post_types as $post_type) { ?>
											<input id="lip_settings[post_types][<?php echo $post_type->name; ?>]" name="lip_settings[post_types][<?php echo $post_type->name; ?>]" type="checkbox" value="<?php echo $post_type->name; ?>" <?php checked(true, in_array($post_type->name, $saved_post_types) ); ?>/><span><?php echo $post_type->labels->name; ?></span><br/>
										<?php } ?>
										<?php _e( 'Display Love It link at top or bottom of the post/page content?', 'love_it' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php _e('Love It Links', 'love_it'); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<span><?php _e('Love It Links', 'love_it'); ?></span>
									</legend>
									<label for="lip_settings[show_links]">
										<input id="lip_settings[show_links]" name="lip_settings[show_links]" type="checkbox" value="1" <?php checked( '1', $lip_options['show_links'] ); ?>/>
										<?php _e( 'Display Love It link at top or bottom of the post/page content?', 'love_it' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php _e('Position', 'love_it'); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<span><?php _e('Position', 'love_it'); ?></span>
									</legend>
									<label for="lip_settings[post_position]">
										<?php $positions = array('top' => __('Top', 'love_it'), 'bottom' => __('Bottom', 'love_it')); ?>
										<select name="lip_settings[post_position]">
											<?php foreach ($positions as $key => $option) { ?>
												<option <?php selected($lip_options['post_position'], $key); ?> value="<?php echo $key; ?>"><?php echo $option; ?></option>
											<?php } ?>
										</select>
										<?php _e( 'Display Love It link at top or bottom of the post/page content?', 'love_it' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php _e('Love It Links', 'love_it'); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<span><?php _e('Love It Links', 'love_it'); ?></span>
									</legend>
									<label for="lip_settings[love_it_text]">
										<input id="lip_settings[love_it_text]" name="lip_settings[love_it_text]" type="text" value="<?php echo $lip_options['love_it_text'];?>" />
										<?php _e( 'Enter the text you\'d like to use for the "Love It" link', 'love_it' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php _e('Already Loved Text', 'love_it'); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<span><?php _e('Love It Links', 'love_it'); ?></span>
									</legend>
									<label for="lip_settings[already_loved]">
										<input id="lip_settings[already_loved]" name="lip_settings[already_loved]" type="text" value="<?php echo $lip_options['already_loved'];?>" />
										<?php _e( 'Enter the text you\'d like to use for the "You have already loved this item" text', 'love_it' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php _e('Custom CSS', 'love_it'); ?>
							</th>
							<td>
								<fieldset>
									<legend class="screen-reader-text">
										<span><?php _e('Custom CSS', 'love_it'); ?></span>
									</legend>
									<label for="lip_settings[custom_css]">
										<textarea id="lip_settings[custom_css]" style="width: 400px; height: 150px;" name="lip_settings[custom_css]" type="text"><?php echo $lip_options['custom_css'];?></textarea><br/>
										<?php _e( 'Enter custom CSS here to customize the appearance of this plugin. To assist you, a list of available class names are available in the Help tab in the top right.', 'love_it' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
					</tbody>
				</table>
				
				<!-- save the options -->
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'love_it' ); ?>" />
				</p>
										
			</form>
		</div><!--end sf-wrap-->
	</div><!--end wrap-->		
	<?php
}

// register the plugin settings
function lip_register_settings() {

	// create whitelist of options
	register_setting( 'lip_settings_group', 'lip_settings' );
}
//call register settings function
add_action( 'admin_init', 'lip_register_settings' );


function lip_settings_menu() {
	global $love_it_page;
	// add settings page
	$love_it_page = add_submenu_page('options-general.php', __('Love It Settings', 'love_it'), __('Love It Settings', 'love_it'),'manage_options', 'love-it-settings', 'lip_settings_page');
	
	// load each of the help tabs
	add_action("load-$love_it_page", "lip_contextual_help");
}
add_action('admin_menu', 'lip_settings_menu');


function lip_contextual_help($hook) {
	global $love_it_page;
	$screen = get_current_screen();
	if(!is_object($screen))
		return;
		
	switch($screen->id) :

		case $love_it_page :
			$screen->add_help_tab(
				array(
					'id' => 'general',
					'title' => __('General', 'love_it'),
					'content' => lip_render_help_tab('general')
				)
			);
			$screen->add_help_tab(
				array(
					'id' => 'template_tags',
					'title' => __('Template Tags', 'love_it'),
					'content' => lip_render_help_tab('template_tags')
				)
			);
			$screen->add_help_tab(
				array(
					'id' => 'custom_css',
					'title' => __('Custom CSS', 'love_it'),
					'content' => lip_render_help_tab('custom_css')
				)
			);
		break;
	endswitch;
}
add_action('admin_menu', 'lip_contextual_help', 100);

function lip_render_help_tab($tab_id) {

	switch($tab_id) :
	
		case 'general' :
			ob_start(); ?>
			<p>Love It Pro allows you to add "Love It" links to your posts, pages, and custom post types. The Love It links function must like Facebook's Like button: they allow your users to show their appreciation.</p>
			<p>When a user clicks "Love It", the love count for the item is increased by one. The total number of loves on a psot or page can then be used to display your "most loved items".</p>
			<p>Love It Pro is great way to give your users a simple, but great way of interacting with your site a little more, and it provides very valuble feedback to you as a site administrator.</p>
			<?php
			break;
		case 'template_tags' :
			ob_start(); ?>
			<p>There are three template tags you can use with this plugin if you wish to integrate it more fully into your site.</p>
			<p><strong>lip_love_it_link()</strong> - this is the function that can be used to display the Love It link / Already Love This text. It also outputs the Love count.</p>
			<p>The function has four parameters: <em>lip_love_it_link($post_id = null, $link_text, $already_loved_text, $echo = true)</em>:</p>
			<ul>
				<li><em>$post_id</em> - the ID of the item to love (for a post, page, or CPT)</li>
				<li><em>$link_text</em> - the text to show for the "Love It" link</li>
				<li><em>$already_loved_text</em> - the text to show for the "Already Loved This" message</li>
				<li><em>$echo</em> - whether to echo or return the final link HTML</li>
			</ul>
			<p><strong>li_get_love_count($post_id)</strong> - this will retrieve the total love count of the specified item ID. The value is returned, so you must echo it to display the count.</p>
			<p><strong>li_user_has_loved_post($user_id, $post_id)</strong> - this can be used to determine if a user has loved an item or not. It should be used as a conditional, like this: <em>if(li_user_has_loved_post($user_id, $post_id)) { // show something if the user has loved the item }</em></p>
			<?php
			break;
		case 'custom_css' :
			ob_start(); ?>
			<p>If you wish to modify the appearance of the plugin, you may do so by adding custom CSS to the box below. Here is a list of HTML elements and class names:</p>
			<ul>
				<li><em>div.love-it-wrapper</em> - the DIV taht wraps the Love it link</li>
				<li><em>a.love-it</em> - the anchor tag for the Love It link</li>
				<li><em>span.love-count</em> - the span tag that contains the total love count</li>
				<li><em>span.loved</em> - the span tag that contains the text for an item that has been loved</li>
			</ul>
			<?php
			break;
		default;
			break;
			
	endswitch;
	
	return ob_get_clean();
}