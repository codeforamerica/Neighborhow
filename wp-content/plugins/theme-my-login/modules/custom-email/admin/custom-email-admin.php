<?php

if ( !class_exists( 'Theme_My_Login_Custom_Email_Admin' ) ) :
/**
 * Theme My Login Custom Email module admin class
 *
 * @since 6.0
 */
class Theme_My_Login_Custom_Email_Admin extends Theme_My_Login_Module {
	/**
	 * Sanitizes settings
	 *
	 * Callback for "tml_save_settings" hook in method Theme_My_Login_Admin::save_settings()
	 *
	 * @see Theme_My_Login_Admin::save_settings()
	 * @since 6.0
	 * @access public
	 *
	 * @param string|array $settings Settings passed in from filter
	 * @return string|array Sanitized settings
	 */
	function save_settings( $settings ) {
		global $theme_my_login;
		// Checkboxes
		$settings['email']['new_user']['admin_disable'] = isset( $_POST['theme_my_login']['email']['new_user']['admin_disable'] );
		$settings['email']['reset_pass']['admin_disable'] = isset( $_POST['theme_my_login']['email']['reset_pass']['admin_disable'] );
		if ( $theme_my_login->is_module_active( 'user-moderation/user-moderation.php' ) )
			$settings['email']['user_approval']['admin_disable'] = isset( $_POST['theme_my_login']['email']['user_approval']['admin_disable'] );
		return $settings;
	}

	/**
	 * Outputs new user notification e-mail settings
	 *
	 * Callback for "$hookname" hook in method Theme_My_Login_Admin::add_submenu_page()
	 *
	 * @see Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.0
	 * @access public
	 */
	function display_new_user_settings() {
		global $theme_my_login;
?><table class="form-table">
    <tr>
		<td>
			<h3><?php _e( 'User Notification', 'theme-my-login' ); ?></h3>

			<p class="description">
				<?php _e( 'This e-mail will be sent to a new user upon registration.', 'theme-my-login' ); ?>
				<?php _e( 'Please be sure to include the variable %user_pass% if using default passwords or else the user will not know their password!', 'theme-my-login' ); ?>
				<?php _e( 'If any field is left empty, the default will be used instead.', 'theme-my-login' ); ?>
			</p>

			<p><label for="theme_my_login_new_user_mail_from_name"><?php _e( 'From Name', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][new_user][mail_from_name]" type="text" id="theme_my_login_new_user_mail_from_name" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'new_user', 'mail_from_name' ) ); ?>" class="extended-text" /></p>

			<p><label for="theme_my_login_new_user_mail_from"><?php _e( 'From E-mail', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][new_user][mail_from]" type="text" id="theme_my_login_new_user_mail_from" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'new_user', 'mail_from' ) ); ?>" class="extended-text" /></p>

            <p><label for="theme_my_login_new_user_mail_content_type"><?php _e( 'E-mail Format', 'theme-my-login' ); ?></label><br />
            <select name="theme_my_login[email][new_user][mail_content_type]" id="theme_my_login_new_user_mail_content_type">
            <option value="plain"<?php if ( 'plain' == $theme_my_login->options->get_option( array( 'email', 'new_user', 'mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'Plain Text', 'theme-my-login' ); ?></option>
            <option value="html"<?php if ( 'html' == $theme_my_login->options->get_option( array( 'email', 'new_user', 'mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'HTML', 'theme-my-login' ); ?></option>
            </select></p>

			<p><label for="theme_my_login_new_user_title"><?php _e( 'Subject', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][new_user][title]" type="text" id="theme_my_login_new_user_title" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'new_user', 'title' ) ); ?>" class="full-text" /></p>

			<p><label for="theme_my_login_new_user_message"><?php _e( 'Message', 'theme-my-login' ); ?></label><br />
			<textarea name="theme_my_login[email][new_user][message]" id="theme_my_login_new_user_message" class="large-text" rows="10"><?php echo $theme_my_login->options->get_option( array( 'email', 'new_user', 'message' ) ); ?></textarea></p>

			<p class="description"><?php _e( 'Available Variables', 'theme-my-login' ); ?>: %blogname%, %siteurl%, %user_login%, %user_email%, %user_pass%, %user_ip%</p>
		</td>
	</tr>
	<tr>
		<td>
			<h3><?php _e( 'Admin Notification', 'theme-my-login' ); ?></h3>

			<p class="description">
				<?php _e( 'This e-mail will be sent to the e-mail address or addresses (multiple addresses may be separated by commas) specified below, upon new user registration.', 'theme-my-login' ); ?>
				<?php _e( 'If any field is left empty, the default will be used instead.', 'theme-my-login' ); ?>
			</p>

			<p><label for="theme_my_login_new_user_admin_mail_to"><?php _e( 'To', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][new_user][admin_mail_to]" type="text" id="theme_my_login_new_user_admin_mail_to" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'new_user', 'admin_mail_to' ) ); ?>" class="extended-text" /></p>

			<p><label for="theme_my_login_new_user_admin_mail_from_name"><?php _e( 'From Name', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][new_user][admin_mail_from_name]" type="text" id="theme_my_login_new_user_admin_mail_from_name" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'new_user', 'admin_mail_from_name' ) ); ?>" class="extended-text" /></p>

			<p><label for="theme_my_login_new_user_admin_mail_from"><?php _e( 'From E-mail', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][new_user][admin_mail_from]" type="text" id="theme_my_login_new_user_admin_mail_from" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'new_user', 'admin_mail_from' ) ); ?>" class="extended-text" /></p>

            <p><label for="theme_my_login_new_user_admin_mail_content_type"><?php _e( 'E-mail Format', 'theme-my-login' ); ?></label><br />
            <select name="theme_my_login[email][new_user][admin_mail_content_type]" id="theme_my_login_new_user_admin_mail_content_type">
            <option value="plain"<?php if ( 'plain' == $theme_my_login->options->get_option( array( 'email', 'new_user', 'admin_mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'Plain Text', 'theme-my-login' ); ?></option>
            <option value="html"<?php if ( 'html' == $theme_my_login->options->get_option( array( 'email', 'new_user', 'admin_mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'HTML', 'theme-my-login' ); ?></option>
            </select></p>

			<p><label for="theme_my_login_new_user_admin_title"><?php _e( 'Subject', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][new_user][admin_title]" type="text" id="theme_my_login_new_user_admin_title" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'new_user', 'admin_title' ) ); ?>" class="full-text" /></p>

			<p><label for="theme_my_login_new_user_admin_message"><?php _e( 'Message', 'theme-my-login' ); ?></label><br />
			<textarea name="theme_my_login[email][new_user][admin_message]" id="theme_my_login_new_user_admin_message" class="large-text" rows="10"><?php echo $theme_my_login->options->get_option( array( 'email', 'new_user', 'admin_message' ) ); ?></textarea></p>

			<p class="description"><?php _e( 'Available Variables', 'theme-my-login' ); ?>: %blogname%, %siteurl%, %user_login%, %user_email%, %user_ip%</p>

			<p><label for="theme_my_login_new_user_admin_disable"><input name="theme_my_login[email][new_user][admin_disable]" type="checkbox" id="theme_my_login_new_user_admin_disable" value="1"<?php checked( 1, $theme_my_login->options->get_option( array( 'email', 'new_user', 'admin_disable' ) ) ); ?> /> Disable Admin Notification</label></p>
		</td>
	</tr>
</table><?php
	}

	/**
	 * Outputs password retrieval e-mail settings
	 *
	 * Callback for "$hookname" hook in method Theme_My_Login_Admin::add_submenu_page()
	 *
	 * @see Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.0
	 * @access public
	 */
	function display_retrieve_pass_settings() {
		global $theme_my_login;
?><table class="form-table">
	<tr>
		<td>
			<p class="description">
				<?php _e( 'This e-mail will be sent to a user when they attempt to recover their password.', 'theme-my-login' ); ?>
				<?php _e( 'Please be sure to include the variable %reseturl% or else the user will not be able to recover their password!', 'theme-my-login' ); ?>
				<?php _e( 'If any field is left empty, the default will be used instead.', 'theme-my-login' ); ?>
			</p>

			<p><label for="theme_my_login_retrieve_pass_mail_from_name"><?php _e( 'From Name', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][retrieve_pass][mail_from_name]" type="text" id="theme_my_login_retrieve_pass_mail_from_name" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'retrieve_pass', 'mail_from_name' ) ); ?>" class="extended-text" /></p>

			<p><label for="theme_my_login_retrieve_pass_mail_from"><?php _e( 'From E-mail', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][retrieve_pass][mail_from]" type="text" id="theme_my_login_retrieve_pass_mail_from" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'retrieve_pass', 'mail_from' ) ); ?>" class="extended-text" /></p>

            <p><label for="theme_my_login_retrieve_pass_mail_content_type"><?php _e( 'E-mail Format', 'theme-my-login' ); ?></label><br />
            <select name="theme_my_login[email][retrieve_pass][mail_content_type]" id="theme_my_login_retrieve_pass_mail_content_type">
            <option value="plain"<?php if ( 'plain' == $theme_my_login->options->get_option( array( 'email', 'retrieve_pass', 'mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'Plain Text',' theme-my-login' ); ?></option>
            <option value="html"<?php if ( 'html' == $theme_my_login->options->get_option( array( 'email', 'retrieve_pass', 'mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'HTML', 'theme-my-login' ); ?></option>
            </select></p>

			<p><label for="theme_my_login_retrieve_pass_title"><?php _e( 'Subject', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][retrieve_pass][title]" type="text" id="theme_my_login_retrieve_pass_title" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'retrieve_pass', 'title' ) ); ?>" class="full-text" /></p>

			<p><label for="theme_my_login_retrieve_pass_message"><?php _e( 'Message', 'theme-my-login' ); ?></label><br />
			<textarea name="theme_my_login[email][retrieve_pass][message]" id="theme_my_login_retrieve_pass_message" class="large-text" rows="10"><?php echo $theme_my_login->options->get_option( array( 'email', 'retrieve_pass', 'message' ) ); ?></textarea></p>

			<p class="description"><?php _e( 'Available Variables', 'theme-my-login' ); ?>: %blogname%, %siteurl%, %reseturl%, %user_login%, %user_email%, %user_ip%</p>
		</td>
	</tr>
</table><?php
	}

	/**
	 * Outputs password reset e-mail settings
	 *
	 * Callback for "$hookname" hook in method Theme_My_Login_Admin::add_submenu_page()
	 *
	 * @see Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.0
	 * @access public
	 */
	function display_reset_pass_settings() {
		global $theme_my_login;
?><table class="form-table">
	<tr>
		<td>
			<h3><?php _e( 'Admin Notification', 'theme-my-login' ); ?></h3>

			<p class="description">
				<?php _e( 'This e-mail will be sent to the e-mail address or addresses (multiple addresses may be separated by commas) specified below, upon user password change.', 'theme-my-login' ); ?>
				<?php _e( 'If any field is left empty, the default will be used instead.', 'theme-my-login' ); ?>
			</p>

			<p><label for="theme_my_login_reset_pass_admin_mail_to"><?php _e( 'To', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][reset_pass][admin_mail_to]" type="text" id="theme_my_login_reset_pass_admin_mail_to" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'reset_pass', 'admin_mail_to' ) ); ?>" class="extended-text" /></p>

			<p><label for="theme_my_login_reset_pass_admin_mail_from_name"><?php _e( 'From Name', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][reset_pass][admin_mail_from_name]" type="text" id="theme_my_login_reset_pass_admin_mail_from_name" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'reset_pass', 'admin_mail_from_name' ) ); ?>" class="extended-text" /></p>

			<p><label for="theme_my_login_reset_pass_admin_mail_from"><?php _e( 'From E-mail', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][reset_pass][admin_mail_from]" type="text" id="theme_my_login_reset_pass_admin_mail_from" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'reset_pass', 'admin_mail_from' ) ); ?>" class="extended-text" /></p>

            <p><label for="theme_my_login_reset_pass_admin_mail_content_type"><?php _e( 'E-mail Format', 'theme-my-login' ); ?></label><br />
            <select name="theme_my_login[email][reset_pass][admin_mail_content_type]" id="theme_my_login_reset_pass_admin_mail_content_type">
            <option value="plain"<?php if ( 'plain' == $theme_my_login->options->get_option( array( 'email', 'reset_pass', 'admin_mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'Plain Text', 'theme-my-login' ); ?></option>
            <option value="html"<?php if ( 'html' == $theme_my_login->options->get_option( array( 'email', 'reset_pass', 'admin_mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'HTML', 'theme-my-login' ); ?></option>
            </select></p>

			<p><label for="theme_my_login_reset_pass_admin_title"><?php _e( 'Subject', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][reset_pass][admin_title]" type="text" id="theme_my_login_reset_pass_admin_title" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'reset_pass', 'admin_title' ) ); ?>" class="full-text" /></p>

			<p><label for="theme_my_login_reset_pass_admin_message"><?php _e( 'Message', 'theme-my-login' ); ?></label><br />
			<textarea name="theme_my_login[email][reset_pass][admin_message]" id="theme_my_login_reset_pass_admin_message" class="large-text" rows="10"><?php echo $theme_my_login->options->get_option( array( 'email', 'reset_pass', 'admin_message' ) ); ?></textarea></p>

			<p class="description"><?php _e( 'Available Variables', 'theme-my-login' ); ?>: %blogname%, %siteurl%, %user_login%, %user_email%, %user_ip%</p>

			<p><label for="theme_my_login_reset_pass_admin_disable"><input name="theme_my_login[email][reset_pass][admin_disable]" type="checkbox" id="theme_my_login_reset_pass_admin_disable" value="1"<?php checked( 1, $theme_my_login->options->get_option( array( 'email', 'reset_pass', 'admin_disable' ) ) ); ?> /> <?php _e( 'Disable Admin Notification', 'theme-my-login' ); ?></label></p>
		</td>
	</tr>
</table><?php
	}

	/**
	 * Outputs user activation e-mail settings
	 *
	 * Callback for "$hookname" hook in method Theme_My_Login_Admin::add_submenu_page()
	 *
	 * @see Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.0
	 * @access public
	 */
	function display_user_activation_settings() {
		global $theme_my_login;
?><table class="form-table">
    <tr>
		<td>
			<p class="description">
				<?php _e( 'This e-mail will be sent to a new user upon registration when "E-mail Confirmation" is checked for "User Moderation".', 'theme-my-login' ); ?>
				<?php _e( 'Please be sure to include the variable %activateurl% or else the user will not be able to activate their account!', 'theme-my-login' ); ?>
				<?php _e( 'If any field is left empty, the default will be used instead.', 'theme-my-login' ); ?>
			</p>

			<p><label for="theme_my_login_user_activation_mail_from_name"><?php _e( 'From Name', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_activation][mail_from_name]" type="text" id="theme_my_login_user_activation_mail_from_name" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_activation', 'mail_from_name' ) ); ?>" class="extended-text" /></p>

			<p><label for="theme_my_login_user_activation_mail_from"><?php _e( 'From E-mail', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_activation][mail_from]" type="text" id="theme_my_login_user_activation_mail_from" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_activation', 'mail_from' ) ); ?>" class="extended-text" /></p>

            <p><label for="theme_my_login_user_activation_mail_content_type"><?php _e( 'E-mail Format', 'theme-my-login' ); ?></label><br />
            <select name="theme_my_login[email][user_activation][mail_content_type]" id="theme_my_login_user_activation_mail_content_type">
            <option value="plain"<?php if ( 'plain' == $theme_my_login->options->get_option( array( 'email', 'user_activation', 'mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'Plain Text', 'theme-my-login' ); ?></option>
            <option value="html"<?php if ( 'html' == $theme_my_login->options->get_option( array( 'email', 'user_activation', 'mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'HTML', 'theme-my-login' ); ?></option>
            </select></p>

			<p><label for="theme_my_login_user_activation_title"><?php _e( 'Subject', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_activation][title]" type="text" id="theme_my_login_user_activation_title" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_activation', 'title' ) ); ?>" class="full-text" /></p>

			<p><label for="theme_my_login_user_activation_message"><?php _e( 'Message', 'theme-my-login' ); ?></label><br />
			<textarea name="theme_my_login[email][user_activation][message]" id="theme_my_login_user_activation_message" class="large-text" rows="10"><?php echo $theme_my_login->options->get_option( array( 'email', 'user_activation', 'message' ) ); ?></textarea></p>

			<p class="description"><?php _e( 'Available Variables', 'theme-my-login' ); ?>: %blogname%, %siteurl%, %activateurl%, %user_login%, %user_email%, %user_ip%</p>
		</td>
	</tr>
</table><?php
	}

	/**
	 * Outputs user approval e-mail settings
	 *
	 * Callback for "$hookname" hook in method Theme_My_Login_Admin::add_submenu_page()
	 *
	 * @see Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.0
	 * @access public
	 */
	function display_user_approval_settings() {
		global $theme_my_login;
?><table class="form-table">
    <tr>
		<td>
			<h3><?php _e( 'User Notification', 'theme-my-login' ); ?></h3>

			<p class="description">
				<?php _e( 'This e-mail will be sent to a new user upon admin approval when "Admin Approval" is checked for "User Moderation".', 'theme-my-login' ); ?>
				<?php _e( 'Please be sure to include the variable %user_pass% if using default passwords or else the user will not know their password!', 'theme-my-login' ); ?>
				<?php _e( 'If any field is left empty, the default will be used instead.', 'theme-my-login' ); ?>
			</p>

			<p><label for="theme_my_login_user_approval_mail_from_name"><?php _e( 'From Name', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_approval][mail_from_name]" type="text" id="theme_my_login_user_approval_mail_from_name" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_approval', 'mail_from_name' ) ); ?>" class="extended-text" /></p>

			<p><label for="theme_my_login_user_approval_mail_from"><?php _e( 'From E-mail', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_approval][mail_from]" type="text" id="theme_my_login_user_approval_mail_from" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_approval', 'mail_from' ) ); ?>" class="extended-text" /></p>

            <p><label for="theme_my_login_user_approval_mail_content_type"><?php _e( 'E-mail Format', 'theme-my-login' ); ?></label><br />
            <select name="theme_my_login[email][user_approval][mail_content_type]" id="theme_my_login_user_approval_mail_content_type">
            <option value="plain"<?php if ( 'plain' == $theme_my_login->options->get_option( array( 'email', 'user_approval', 'mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'Plain Text', 'theme-my-login' ); ?></option>
            <option value="html"<?php if ( 'html' == $theme_my_login->options->get_option( array( 'email', 'user_approval', 'mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'HTML', 'theme-my-login' ); ?></option>
            </select></p>

			<p><label for="theme_my_login_user_approval_title"><?php _e( 'Subject', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_approval][title]" type="text" id="theme_my_login_user_approval_title" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_approval', 'title' ) ); ?>" class="full-text" /></p>

			<p><label for="theme_my_login_user_approval_message"><?php _e( 'Message', 'theme-my-login' ); ?></label><br />
			<textarea name="theme_my_login[email][user_approval][message]" id="theme_my_login_user_approval_message" class="large-text" rows="10"><?php echo $theme_my_login->options->get_option( array( 'email', 'user_approval', 'message' ) ); ?></textarea></p>

			<p class="description"><?php _e( 'Available Variables', 'theme-my-login' ); ?>: %blogname%, %siteurl%, %loginurl%, %user_login%, %user_email%, %user_pass%</p>
		</td>
	</tr>
	<tr>
		<td>
			<h3><?php _e( 'Admin Notification', 'theme-my-login' ); ?></h3>

			<p class="description">
				<?php _e( 'This e-mail will be sent to the e-mail address or addresses (multiple addresses may be separated by commas) specified below upon user registration when "Admin Approval" is checked for "User Moderation".', 'theme-my-login' ); ?>
				<?php _e( 'If any field is left empty, the default will be used instead.', 'theme-my-login' ); ?>
			</p>

			<p><label for="theme_my_login_user_approval_admin_mail_to"><?php _e( 'To', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_approval][admin_mail_to]" type="text" id="theme_my_login_user_approval_admin_mail_to" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_approval', 'admin_mail_to' ) ); ?>" class="extended-text" /></p>

			<p><label for="theme_my_login_user_approval_admin_mail_from_name"><?php _e( 'From Name', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_approval][admin_mail_from_name]" type="text" id="theme_my_login_user_approval_admin_mail_from_name" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_approval', 'admin_mail_from_name' ) ); ?>" class="extended-text" /></p>

			<p><label for="theme_my_login_user_approval_admin_mail_from"><?php _e( 'From E-mail', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_approval][admin_mail_from]" type="text" id="theme_my_login_user_approval_admin_mail_from" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_approval', 'admin_mail_from' ) ); ?>" class="extended-text" /></p>

            <p><label for="theme_my_login_user_approval_admin_mail_content_type"><?php _e( 'E-mail Format', 'theme-my-login' ); ?></label><br />
            <select name="theme_my_login[email][user_approval][admin_mail_content_type]" id="theme_my_login_user_approval_admin_mail_content_type">
            <option value="plain"<?php if ( 'plain' == $theme_my_login->options->get_option( array( 'email', 'user_approval', 'admin_mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'Plain Text', 'theme-my-login' ); ?></option>
            <option value="html"<?php if ( 'html' == $theme_my_login->options->get_option( array( 'email', 'user_approval', 'admin_mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'HTML', 'theme-my-login' ); ?></option>
            </select></p>

			<p><label for="theme_my_login_user_approval_admin_title"><?php _e( 'Subject', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_approval][admin_title]" type="text" id="theme_my_login_user_approval_admin_title" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_approval', 'admin_title' ) ); ?>" class="full-text" /></p>

			<p><label for="theme_my_login_user_approval_admin_message"><?php _e( 'Message', 'theme-my-login' ); ?></label><br />
			<textarea name="theme_my_login[email][user_approval][admin_message]" id="theme_my_login_user_approval_admin_message" class="large-text" rows="10"><?php echo $theme_my_login->options->get_option( array( 'email', 'user_approval', 'admin_message' ) ); ?></textarea></p>

			<p class="description"><?php _e( 'Available Variables', 'theme-my-login' ); ?>: %blogname%, %siteurl%, %pendingurl%, %user_login%, %user_email%, %user_ip%</p>

			<p><label for="theme_my_login_user_approval_admin_disable"><input name="theme_my_login[email][user_approval][admin_disable]" type="checkbox" id="theme_my_login_user_approval_admin_disable" value="1"<?php checked( 1, $theme_my_login->options->get_option( array( 'email', 'user_approval', 'admin_disable' ) ) ); ?> /> <?php _e( 'Disable Admin Notification', 'theme-my-login' ); ?></label></p>
		</td>
	</tr>
</table><?php
	}

	/**
	 * Outputs user denial e-mail settings
	 *
	 * Callback for "$hookname" hook in method Theme_My_Login_Admin::add_submenu_page()
	 *
	 * @see Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.0
	 * @access public
	 */
	function display_user_denial_settings() {
		global $theme_my_login;
?><table class="form-table">
    <tr>
		<td>
			<p class="description">
				<?php _e( 'This e-mail will be sent to a user who is deleted/denied when "Admin Approval" is checked for "User Moderation" and the user\'s role is "Pending".', 'theme-my-login' ); ?>
				<?php _e( 'If any field is left empty, the default will be used instead.', 'theme-my-login' ); ?>
			</p>

			<p><label for="theme_my_login_user_denial_mail_from_name"><?php _e( 'From Name', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_denial][mail_from_name]" type="text" id="theme_my_login_user_denial_mail_from_name" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_denial', 'mail_from_name' ) ); ?>" class="extended-text" /></p>

			<p><label for="theme_my_login_user_denial_mail_from"><?php _e( 'From E-mail', 'theme-my-login' ); ?></label><br />
			<input name="theme_my_login[email][user_denial][mail_from]" type="text" id="theme_my_login_user_denial_mail_from" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_denial', 'mail_from' ) ); ?>" class="extended-text" /></p>

            <p><label for="theme_my_login_user_denial_mail_content_type"><?php _e( 'E-mail Format', 'theme-my-login' ); ?></label><br />
            <select name="theme_my_login[email][user_denial][mail_content_type]" id="theme_my_login_user_denial_mail_content_type">
            <option value="plain"<?php if ( 'plain' == $theme_my_login->options->get_option( array( 'email', 'user_denial', 'mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'Plain Text', 'theme-my-login' ); ?></option>
            <option value="html"<?php if ( 'html' == $theme_my_login->options->get_option( array( 'email', 'user_denial', 'mail_content_type' ) ) ) echo ' selected="selected"'; ?>><?php _e( 'HTML', 'theme-my-login' ); ?></option>
            </select></p>

			<p><label for="theme_my_login_user_denial_title"><?php _e('Subject', 'theme-my-login'); ?></label><br />
			<input name="theme_my_login[email][user_denial][title]" type="text" id="theme_my_login_user_denial_title" value="<?php echo $theme_my_login->options->get_option( array( 'email', 'user_denial', 'title' ) ); ?>" class="full-text" /></p>

			<p><label for="theme_my_login_user_denial_message"><?php _e('Message', 'theme-my-login'); ?></label><br />
			<textarea name="theme_my_login[email][user_denial][message]" id="theme_my_login_user_denial_message" class="large-text" rows="10"><?php echo $theme_my_login->options->get_option( array( 'email', 'user_denial', 'message' ) ); ?></textarea></p>

			<p class="description"><?php _e( 'Available Variables', 'theme-my-login' ); ?>: %blogname%, %siteurl%, %user_login%, %user_email%</p>
		</td>
	</tr>
</table><?php
	}

	/**
	 * Adds "E-mails" tab to Theme My Login menu
	 *
	 * Callback for "tml_admin_menu" hook in method Theme_My_Login_Admin::display_settings_page()
	 *
	 * @see Theme_My_Login_Admin::display_settings_page(), Theme_My_Login_Admin::add_menu_page, Theme_My_Login_Admin::add_submenu_page()
	 * @uses Theme_My_Login_Admin::add_menu_page, Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.0
	 * @access public
	 *
	 * @param object $admin Reference to global $theme_my_login_admin object
	 */
	function admin_menu( &$admin ) {
		global $theme_my_login;

		$admin->add_menu_page( __( 'E-mail', 'theme-my-login' ), 'tml-options-email' );
		$admin->add_submenu_page( 'tml-options-email', __( 'New User', 'theme-my-login' ), 'tml-options-email-new-user', array( &$this, 'display_new_user_settings' ) );
		$admin->add_submenu_page( 'tml-options-email', __( 'Retrieve Password', 'theme-my-login' ), 'tml-options-email-retrieve-pass', array( &$this, 'display_retrieve_pass_settings' ) );
		$admin->add_submenu_page( 'tml-options-email', __( 'Reset Password', 'theme-my-login' ), 'tml-options-email-reset-pass', array( &$this, 'display_reset_pass_settings' ) );
		if ( $theme_my_login->is_module_active( 'user-moderation/user-moderation.php' ) ) {
			$admin->add_submenu_page( 'tml-options-email', __( 'User Activation', 'theme-my-login' ), 'tml-options-email-user-activation', array( &$this, 'display_user_activation_settings' ) );
			$admin->add_submenu_page( 'tml-options-email', __( 'User Approval', 'theme-my-login' ), 'tml-options-email-user-approval', array( &$this, 'display_user_approval_settings' ) );
			$admin->add_submenu_page( 'tml-options-email', __( 'User Denial', 'theme-my-login' ), 'tml-options-email-user-denial', array( &$this, 'display_user_denial_settings' ) );
		}	
	}

	/**
	 * Activates this module
	 *
	 * Callback for "tml_activate_custom-email/custom-email.php" hook in method Theme_My_Login_Admin::activate_module()
	 *
	 * @see Theme_My_Login_Admin::activate_module()
	 * @since 6.0
	 * @access public
	 *
	 * @param object $theme_my_login Reference to global $theme_my_login object
	 */
	function activate( &$theme_my_login ) {
		$options = Theme_My_Login_Custom_Email::init_options();
		$theme_my_login->options->set_option( 'email', $options['email'] );
	}

	/**
	 * Loads the module
	 *
	 * @since 6.0
	 * @access public
	 */
	function load() {
		add_action( 'tml_activate_custom-email/custom-email.php', array( &$this, 'activate' ) );
		add_action( 'tml_admin_menu', array( &$this, 'admin_menu' ) );
		add_filter( 'tml_save_settings', array( &$this, 'save_settings' ) );
	}
}

/**
 * Holds the reference to Theme_My_Login_Custom_Email_Admin object
 * @global object $theme_my_login_custom_email_admin
 * @since 6.0
 */
$theme_my_login_custom_email_admin = new Theme_My_Login_Custom_Email_Admin();

endif; // Class exists

?>
