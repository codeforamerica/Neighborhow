<?php

if ( !class_exists( 'Theme_My_Login_Themed_Profiles_Admin' ) ) :
/**
 * Theme My Login Themed Profiles module admin class
 *
 * @since 6.2
 */
class Theme_My_Login_Themed_Profiles_Admin extends Theme_My_Login_Module {
	/**
	 * Adds "Themed Profiles" tab to Theme My Login menu
	 *
	 * Callback for "tml_admin_menu" hook in method Theme_My_Login_Admin::display_settings_page()
	 *
	 * @see Theme_My_Login_Admin::display_settings_page(), Theme_My_Login_Admin::add_menu_page, Theme_My_Login_Admin::add_submenu_page()
	 * @uses Theme_My_Login_Admin::add_menu_page, Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.2
	 * @access public
	 *
	 * @param object $admin Reference to global $theme_my_login_admin object
	 */
	function admin_menu( &$admin ) {
		$admin->add_menu_page( __( 'Themed Profiles', 'theme-my-login' ), 'tml-options-themed-profiles', array( &$this, 'display_settings' ) );
	}

	/**
	 * Outputs themed profiles settings
	 *
	 * Callback for "$hookname" hook in method Theme_My_Login_Admin::add_submenu_page()
	 *
	 * @see Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.2
	 * @access public
	 */
	function display_settings() {
		global $theme_my_login, $wp_roles;

		$options = $theme_my_login->options->get_option( array( 'themed_profiles' ), array() );
		?>
<table class="form-table">
    <tr valign="top">
        <th scope="row"><?php _e( 'Themed Profiles', 'theme-my-login' ); ?></label></th>
        <td>
    <?php foreach ( $wp_roles->get_names() as $role => $label ) : if ( 'pending' == $role ) continue; ?>
            <input name="theme_my_login[themed_profiles][<?php echo $role; ?>][theme_profile]" type="checkbox" id="theme_my_login_themed_profiles_<?php echo $role; ?>_theme_profile" value="1"<?php checked( 1, $options[$role]['theme_profile'] ); ?> />
            <label for="theme_my_login_themed_profiles_<?php echo $role; ?>_theme_profile"><?php echo $label; ?></label><br />
    <?php endforeach; ?>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row"><?php _e( 'Restrict Admin Access', 'theme-my-login' ); ?></label></th>
        <td>
    <?php foreach ( $wp_roles->get_names() as $role => $label ) : if ( 'pending' == $role ) continue; ?>
            <input name="theme_my_login[themed_profiles][<?php echo $role; ?>][restrict_admin]" type="checkbox" id="theme_my_login_themed_profiles_<?php echo $role; ?>_restrict_admin" value="1"<?php checked( 1, $options[$role]['restrict_admin'] ); ?><?php if ( 'administrator' == $role ) echo ' disabled="disabled"'; ?> />
            <label for="theme_my_login_themed_profiles_<?php echo $role; ?>_restrict_admin"><?php echo $label; ?></label><br />
    <?php endforeach; ?>
        </td>
    </tr>
</table><?php
	}

	function display_permalink_settings() {
		global $theme_my_login;
		?>
	<tr valign="top">
		<th scope="row"><label for="theme_my_login_permalinks_profile"><?php _e( 'Profile', 'theme-my-login' ); ?></label></th>
		<td>
			<input name="theme_my_login[permalinks][profile]" type="text" id="theme_my_login_permalinks_profile" value="<?php echo $theme_my_login->options->get_option( array( 'permalinks', 'profile' ) ); ?>" class="regular-text" />
		</td>
	</tr><?php
	}

	/**
	 * Sanitizes settings
	 *
	 * Callback for "tml_save_settings" hook in method Theme_My_Login_Admin::save_settings()
	 *
	 * @see Theme_My_Login_Admin::save_settings()
	 * @since 6.2
	 * @access public
	 *
	 * @param string|array $settings Settings passed in from filter
	 * @return string|array Sanitized settings
	 */
	function save_settings( $settings ) {
		global $wp_roles;

		if ( did_action( 'tml_activate_themed-profiles/themed-profiles.php' ) )
			return $settings;

		foreach( $wp_roles->get_names() as $role => $label ) {
			if ( 'pending' == $role )
				continue;

			$settings['themed_profiles'][$role] = array(
				'theme_profile' => (int) isset( $_POST['theme_my_login']['themed_profiles'][$role]['theme_profile'] ),
				'restrict_admin' => (int) isset( $_POST['theme_my_login']['themed_profiles'][$role]['restrict_admin'] )
			);
		}
		return $settings;
	}

	/**
	 * Activates this module
	 *
	 * Callback for "tml_activate_themed-profiles/themed-profiles.php" hook in method Theme_My_Login_Admin::activate_module()
	 *
	 * @see Theme_My_Login_Admin::activate_module()
	 * @since 6.2
	 * @access public
	 *
	 * @param object $theme_my_login Reference to global $theme_my_login object
	 */
	function activate( &$theme_my_login ) {
		$options = Theme_My_Login_Themed_Profiles::init_options();
		$theme_my_login->options->set_option( 'themed_profiles', $options['themed_profiles'] );
	}

	/**
	 * Loads the module
	 *
	 * @since 6.2
	 * @access public
	 */
	function load() {
		add_action( 'tml_activate_themed-profiles/themed-profiles.php', array( &$this, 'activate' ) );
		add_action( 'tml_admin_menu', array( &$this, 'admin_menu' ) );
		add_filter( 'tml_save_settings', array( &$this, 'save_settings' ) );

		add_action( 'tml_settings_permalinks', array( &$this, 'display_permalink_settings' ) );
	}
}

/**
 * Holds the reference to Theme_My_Login_Themed_Profiles_Admin object
 * @global object $theme_my_login_themed_profiles_admin
 * @since 6.2
 */
$theme_my_login_themed_profiles_admin = new Theme_My_Login_Themed_Profiles_Admin();

endif; // Class exists

?>
