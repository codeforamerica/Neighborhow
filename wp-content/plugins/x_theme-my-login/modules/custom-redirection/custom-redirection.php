<?php
/*
Plugin Name: Custom Redirection
Description: Enabling this module will initialize custom redirection. You will then have to configure the settings via the "Redirection" tab.
*/

if ( !class_exists( 'Theme_My_Login_Custom_Redirection' ) ) :
/**
 * Theme My Login Custom Redirection module class
 *
 * Adds the ability to redirect users when logging in/out based upon their "user role".
 *
 * @since 6.0
 */
class Theme_My_Login_Custom_Redirection extends Theme_My_Login_Module {
	/**
	 * Adds "_wp_original_referer" field to login form
	 *
	 * Callback for "tml_login_form" hook in file "login-form.php", included by method Theme_My_Login_Template::display()
	 *
	 * @see Theme_My_Login_Template::display()
	 * @since 6.0
	 * @access public
	 *
	 * @param object $template Reference to $theme_my_login_template object
	 */
	function login_form( &$template ) {
		$jump_back_to = empty( $template->instance ) ? 'previous' : 'current';
		wp_original_referer_field( true, $jump_back_to );
		echo "\n";
	}

	/**
	 * Handles login redirection
	 *
	 * Callback for "login_redirect" hook in method Theme_My_Login::the_request()
	 *
	 * @see Theme_My_Login::the_request()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $redirect_to Default redirect
	 * @param string $request Requested redirect
	 * @param WP_User|WP_Error WP_User if user logged in, WP_Error otherwise
	 * @return string New redirect
	 */
	function login_redirect( $redirect_to, $request, $user ) {
		global $theme_my_login;

		// Determine the correct referer
		if ( !$http_referer = wp_get_original_referer() )
			$http_referer = wp_get_referer();

		// Remove some arguments that may be present and shouldn't be
		$http_referer = remove_query_arg( array( 'instance', 'action', 'checkemail', 'error', 'loggedout', 'registered', 'redirect_to', 'updated', 'key', '_wpnonce', 'reauth' ), $http_referer );

		// Make sure $user object exists and is a WP_User instance
		if ( !is_wp_error( $user ) && is_a( $user, 'WP_User' ) ) {
			if ( is_multisite() && empty( $user->roles ) ) {
				$user->roles = array( 'subscriber' );
			}
			$redirection = array( 'login_type' => 'default' );
			foreach ( (array) $user->roles as $role ) {
				if ( $theme_my_login->options->get_option( array( 'redirection', $role ) ) ) {
					$redirection = $theme_my_login->options->get_option( array( 'redirection', $role ) );
					break;
				}
			}
			if ( 'referer' == $redirection['login_type'] ) {
				// Send 'em back to the referer
				$redirect_to = $http_referer;
			} elseif ( 'custom' == $redirection['login_type'] ) {
				// Send 'em to the specified URL
				$redirect_to = $redirection['login_url'];
				// Allow a few user specific variables
				$replace = array( '%user_id%' => $user->ID, '%user_login%' => $user->user_login );
				$redirect_to = str_replace( array_keys( $replace ), array_values( $replace ), $redirect_to );
			}
		}

		// If a redirect is requested, it takes precedence
		if ( !empty( $request ) && admin_url() != $request && admin_url( 'profile.php' ) != $request )
			$redirect_to = $request;

		// Make sure $redirect_to isn't empty
		if ( empty( $redirect_to ) )
			$redirect_to = get_option( 'home' );

		return $redirect_to;
	}

	/**
	 * Handles logout redirection
	 *
	 * Callback for "logout_redirect" hook in method Theme_My_Login::the_request()
	 *
	 * @see Theme_My_Login::the_request()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $redirect_to Default redirect
	 * @param string $request Requested redirect
	 * @param WP_User|WP_Error WP_User if user logged in, WP_Error otherwise
	 * @return string New redirect
	 */
	function logout_redirect( $redirect_to, $request, $user ) {
		global $theme_my_login;

		// Determine the correct referer
		if ( !$http_referer = wp_get_original_referer() )
			$http_referer = wp_get_referer();

		// Remove some arguments that may be present and shouldn't be
		$http_referer = remove_query_arg( array( 'instance', 'action', 'checkemail', 'error', 'loggedout', 'registered', 'redirect_to', 'updated', 'key', '_wpnonce' ), $http_referer );

		// Make sure $user object exists and is a WP_User instance
		if ( !is_wp_error( $user ) && is_a( $user, 'WP_User' ) ) {
			if ( is_multisite() && empty( $user->roles ) ) {
				$user->roles = array( 'subscriber' );
			}
			$redirection = array();
			foreach ( (array) $user->roles as $role ) {
				if ( $theme_my_login->options->get_option( array( 'redirection', $role ) ) ) {
					$redirection = $theme_my_login->options->get_option( array( 'redirection', $role ) );
					break;
				}
			}
			if ( 'referer' == $redirection['logout_type'] ) {
				// Send 'em back to the referer
				$redirect_to = $http_referer;
			} elseif ( 'custom' == $redirection['logout_type'] ) {
				// Send 'em to the specified URL
				$redirect_to = $redirection['logout_url'];
				// Allow a few user specific variables
				$replace = array( '%user_id%' => $user->ID, '%user_login%' => $user->user_login );
				$redirect_to = str_replace( array_keys( $replace ), array_values( $replace ), $redirect_to );
			}
		}

		// Make sure $redirect_to isn't empty or pointing to an admin URL (causing an endless loop)
		if ( empty( $redirect_to ) || strpos( $redirect_to, 'wp-admin' ) !== false )
			$redirect_to = $theme_my_login->get_login_page_link( 'loggedout=true' );

		return $redirect_to;
	}

	/**
	 * Adds "Redirection" tab to Theme My Login menu
	 *
	 * Callback for "tml_admin_menu" hook in method Theme_My_Login_Admin::display_settings_page()
	 *
	 * @see Theme_My_Login_Admin::display_settings_page(), Theme_My_Login_Admin::add_menu_page, Theme_My_Login_Admin::add_submenu_page()
	 * @uses Theme_My_Login_Admin::add_menu_page, Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.0
	 * @access public
	 *
	 * @param object Reference to global $theme_my_login_admin object
	 */
	function admin_menu( &$admin ) {
		global $wp_roles;
		// Add menu tab
		$admin->add_menu_page( __( 'Redirection', 'theme-my-login' ), 'tml-options-redirection' );
		// Iterate through each user role
		foreach ( $wp_roles->get_names() as $role => $label ) {
			// We don't want the "pending" role created by the "User Moderation" module
			if ( 'pending' == $role )
				continue;
			// Add submenu tab for the role
			$admin->add_submenu_page( 'tml-options-redirection', translate_user_role( $label ), 'tml-options-redirection-' . $role, array( &$this, 'display_redirection_settings' ), array( $role ) );
		}
	}

	/**
	 * Outputs redirection admin menu for specified role
	 *
	 * Callback for "$hookname" hook in method Theme_My_Login_Admin::add_submenu_page()
	 *
	 * @see Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $role Name of user role
	 */
	function display_redirection_settings( $role ) {
		global $theme_my_login;

		$redirection =& $theme_my_login->options->get_option( array( 'redirection', $role ) );
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e( 'Log in', 'theme-my-login' ); ?></th>
		<td>
			<input name="theme_my_login[redirection][<?php echo $role; ?>][login_type]" type="radio" id="theme_my_login_redirection_<?php echo $role; ?>_login_type_default" value="default"<?php checked( 'default', $redirection['login_type'] ); ?> /> <label for="theme_my_login_redirection_<?php echo $role; ?>_login_type_default"><?php _e( 'Default', 'theme-my-login' ); ?></label>
			<p class="description"><?php _e( 'Check this option to send the user to their WordPress Dashboard/Profile.', 'theme-my-login' ); ?></p>
			<input name="theme_my_login[redirection][<?php echo $role; ?>][login_type]" type="radio" id="theme_my_login_redirection_<?php echo $role; ?>_login_type_referer" value="referer"<?php checked( 'referer', $redirection['login_type'] ); ?> /> <label for="theme_my_login_redirection_<?php echo $role; ?>_login_type_referer"><?php _e( 'Referer', 'theme-my-login' ); ?></label>
			<p class="description"><?php _e( 'Check this option to send the user back to the page they were visiting before logging in.', 'theme-my-login' ); ?></p>
			<input name="theme_my_login[redirection][<?php echo $role; ?>][login_type]" type="radio" id="theme_my_login_redirection_<?php echo $role; ?>_login_type_custom" value="custom"<?php checked( 'custom', $redirection['login_type'] ); ?> />
			<input name="theme_my_login[redirection][<?php echo $role; ?>][login_url]" type="text" id="theme_my_login_redirection_<?php echo $role; ?>_login_url" value="<?php echo $redirection['login_url']; ?>" class="regular-text" />
			<p class="description"><?php _e( 'Check this option to send the user to a custom location, specified by the textbox above.', 'theme-my-login' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e( 'Log out', 'theme-my-login' ); ?></th>
		<td>
			<input name="theme_my_login[redirection][<?php echo $role; ?>][logout_type]" type="radio" id="theme_my_login_redirection_<?php echo $role; ?>_logout_type_default" value="default"<?php checked( 'default', $redirection['logout_type'] ); ?> /> <label for="theme_my_login_redirection_<?php echo $role; ?>_logout_type_default"><?php _e( 'Default', 'theme-my-login' ); ?></label><br />
			<p class="description"><?php _e( 'Check this option to send the user to the log in page, displaying a message that they have successfully logged out.', 'theme-my-login' ); ?></p>
			<input name="theme_my_login[redirection][<?php echo $role; ?>][logout_type]" type="radio" id="theme_my_login_redirection_<?php echo $role; ?>_logout_type_referer" value="referer"<?php checked( 'referer', $redirection['logout_type'] ); ?> /> <label for="theme_my_login_redirection_<?php echo $role; ?>_logout_type_referer"><?php _e( 'Referer', 'theme-my-login' ); ?></label><br />
			<p class="description"><?php _e( 'Check this option to send the user back to the page they were visiting before logging out. (Note: If the previous page being visited was an admin page, this can have unexpected results.)', 'theme-my-login' ); ?></p>
			<input name="theme_my_login[redirection][<?php echo $role; ?>][logout_type]" type="radio" id="theme_my_login_redirection_<?php echo $role; ?>_logout_type_custom" value="custom"<?php checked( 'custom', $redirection['logout_type'] ); ?> />
			<input name="theme_my_login[redirection][<?php echo $role; ?>][logout_url]" type="text" id="theme_my_login_redirection_<?php echo $role; ?>_logout_url" value="<?php echo $redirection['logout_url']; ?>" class="regular-text" />
			<p class="description"><?php _e( 'Check this option to send the user to a custom location, specified by the textbox above.', 'theme-my-login' ); ?></p>
		</td>
	</tr>
</table>
<?php
	}

	/**
	 * Initializes options for this module
	 *
	 * Callback for "tml_init_options" hook in method Theme_My_Login::init_options()
	 *
	 * @see Theme_My_Login::init_options()
	 * @since 6.0
	 * @access public
	 *
	 * @param array $options Options passed in from filter
	 * @return array Original $options array with module options appended
	 */
	function init_options( $options = array() ) {
		global $wp_roles;

		if ( empty( $wp_roles ) )
			$wp_roles =& new WP_Roles();

		$options = (array) $options;

		$options['redirection'] = array();
		foreach ( $wp_roles->get_names() as $role => $label ) {
			if ( 'pending' == $role )
				continue;
			$options['redirection'][$role] = array( 'login_type' => 'default', 'login_url' => '', 'logout_type' => 'default', 'logout_url' => '' );
		}
		return $options;
	}

	/**
	 * Activates this module
	 *
	 * Callback for "tml_activate_custom-redirection/custom-redirection.php" hook in method Theme_My_Login_Admin::activate_module()
	 *
	 * @see Theme_My_Login_Admin::activate_module()
	 * @since 6.0
	 * @access public
	 *
	 * @param object $theme_my_login Reference to global $theme_my_login object
	 */
	function activate( &$theme_my_login ) {
		$options = $this->init_options();
		$theme_my_login->options->set_option( 'redirection', $options['redirection'] );
	}

	/**
	 * Loads the module
	 *
	 * @since 6.0
	 * @access public
	 */
	function load() {
		add_action( 'tml_activate_custom-redirection/custom-redirection.php', array( &$this, 'activate' ) );
		add_filter( 'tml_init_options', array( &$this, 'init_options' ) );
		add_action( 'tml_admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'tml_login_form', array( &$this, 'login_form' ) );
		add_filter( 'login_redirect', array( &$this, 'login_redirect' ), 10, 3 );
		add_filter( 'logout_redirect', array( &$this, 'logout_redirect' ), 10, 3 );
	}
}

/**
 * Holds the reference to Theme_My_Login_Custom_Redirection object
 * @global object $theme_my_login_custom_redirection
 * @since 6.0
 */
$theme_my_login_custom_redirection = new Theme_My_Login_Custom_Redirection();

endif; // Class exists

?>
