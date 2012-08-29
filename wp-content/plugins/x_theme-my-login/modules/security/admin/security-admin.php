<?php

if ( !class_exists( 'Theme_My_Login_Security_Admin' ) ) :
/**
 * Theme My Login Security module admin class
 *
 * @since 6.0
 */
class Theme_My_Login_Security_Admin extends Theme_My_Login_Module {
	/**
	 * Attaches actions/filters explicitly to "users.php"
	 *
	 * Callback for "load-users.php" hook
	 *
	 * @since 6.0
	 * @access public
	 */
	function load_users_page() {
		global $theme_my_login_security;

		wp_enqueue_script( 'tml-security-admin', plugins_url( TML_DIRNAME . '/modules/security/admin/js/security-admin.js' ), array( 'jquery' ) );

		add_action( 'admin_notices', array( &$this, 'admin_notices' ) );
		add_filter( 'user_row_actions', array( &$this, 'user_row_actions' ), 10, 2 );

		if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'lock', 'unlock' ) ) ) {

			$redirect_to = isset( $_REQUEST['wp_http_referer'] ) ? remove_query_arg( array( 'wp_http_referer', 'updated', 'delete_count' ), stripslashes( $_REQUEST['wp_http_referer'] ) ) : 'users.php';
			$user = isset( $_GET['user'] ) ? $_GET['user'] : '';

			if ( !$user || !current_user_can( 'edit_user', $user ) )
				wp_die( __( 'You can&#8217;t edit that user.', 'theme-my-login' ) );

			if ( !$user = get_userdata( $user ) )
				wp_die( __( 'You can&#8217;t edit that user.', 'theme-my-login' ) );

			if ( 'lock' == $_GET['action'] ) {
				check_admin_referer( 'lock-user_' . $user->ID );

				$theme_my_login_security->lock_user( $user );

				$redirect_to = add_query_arg( 'update', 'lock', $redirect_to );
			} elseif ( 'unlock' == $_GET['action'] ) {
				check_admin_referer( 'unlock-user_' . $user->ID );

				$theme_my_login_security->unlock_user( $user );

				$redirect_to = add_query_arg( 'update', 'unlock', $redirect_to );
			}

			wp_redirect( $redirect_to );
			exit;
		}
	}

	/**
	 * Adds update messages to the admin screen
	 *
	 * Callback for "admin_notices" hook in file admin-header.php
	 *
	 * @since 6.0
	 * @access public
	 */
	function admin_notices() {
		if ( isset( $_GET['update'] ) ) {
			if ( 'lock' == $_GET['update'] )
				echo '<div id="message" class="updated fade"><p>' . __( 'User locked.', 'theme-my-login' ) . '</p></div>';
			elseif ( 'unlock' == $_GET['update'] )
				echo '<div id="message" class="updated fade"><p>' . __( 'User unlocked.', 'theme-my-login' ) . '</p></div>';
		}
	}

	/**
	 * Adds "Lock" and "Unlock" links for each pending user on users.php
	 *
	 * Callback for "user_row_actions" hook in {@internal unknown}
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param array $actions The user actions
	 * @param WP_User $user_object The current user object
	 * @return array The filtered user actions
	 */
	function user_row_actions( $actions, $user_object ) {
		$current_user = wp_get_current_user();

		$security_meta = isset( $user_object->theme_my_login_security ) ? (array) $user_object->theme_my_login_security : array();

		if ( $current_user->ID != $user_object->ID ) {
			if ( isset( $security_meta['is_locked'] ) && $security_meta['is_locked'] )
				$new_actions['unlock-user'] = '<a href="' . add_query_arg( 'wp_http_referer', urlencode( esc_url( stripslashes( $_SERVER['REQUEST_URI'] ) ) ), wp_nonce_url( "users.php?action=unlock&amp;user=$user_object->ID", "unlock-user_$user_object->ID" ) ) . '">' . __( 'Unlock', 'theme-my-login' ) . '</a>';
			else
				$new_actions['lock-user'] = '<a href="' . add_query_arg( 'wp_http_referer', urlencode( esc_url( stripslashes( $_SERVER['REQUEST_URI'] ) ) ), wp_nonce_url( "users.php?action=lock&amp;user=$user_object->ID", "lock-user_$user_object->ID" ) ) . '">' . __( 'Lock', 'theme-my-login' ) . '</a>';
			$actions = array_merge( $new_actions, $actions );
		}
		return $actions;
	}

	/**
	 * Adds "Security" tab to Theme My Login menu
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
		$admin->add_menu_page( __( 'Security', 'theme-my-login' ), 'tml-options-security', array( &$this, 'display_settings' ) );
	}

	/**
	 * Outputs user moderation settings
	 *
	 * Callback for "$hookname" hook in method Theme_My_Login_Admin::add_submenu_page()
	 *
	 * @see Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.0
	 * @access public
	 */
	function display_settings() {
		global $theme_my_login;

		$options =& $theme_my_login->options;
		?>
<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e( 'Private Site', 'theme-my-login' ); ?></th>
		<td>
			<input type="checkbox" name="theme_my_login[security][private_site]" id="theme_my_login_security_private_site" value="1"<?php checked( $options->get_option( array( 'security', 'private_site' ) ) ); ?> />
			<?php _e( 'Require users to be logged in to view site', 'theme-my-login' ); ?>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e( 'Login Attempts', 'theme-my-login' ); ?></th>
		<td>
			<?php
			// Units
			$units = array(
				'minute' => __( 'minute(s)', 'theme-my-login' ),
				'hour' => __( 'hour(s)', 'theme-my-login' ),
				'day' => __( 'day(s)', 'theme-my-login' )
				);
			// Threshold
			$threshold = '<input type="text" name="theme_my_login[security][failed_login][threshold]" id="theme_my_login_security_failed_login_threshold" value="' . $options->get_option( array( 'security', 'failed_login', 'threshold' ) ) . '" size="1" />';
			// Threshold duration
			$threshold_duration = '<input type="text" name="theme_my_login[security][failed_login][threshold_duration]" id="theme_my_login_security_failed_login_threshold_duration" value="' . $options->get_option( array( 'security', 'failed_login', 'threshold_duration' ) ) . '" size="1" />';
			// Threshold duration unit
			$threshold_duration_unit = '<select name="theme_my_login[security][failed_login][threshold_duration_unit]" id="theme_my_login_security_failed_login_threshold_duration_unit">';
			foreach ( $units as $unit => $label ) {
				$selected = ( $options->get_option( array( 'security', 'failed_login', 'threshold_duration_unit' ) ) == $unit ) ? ' selected="selected"' : '';
				$threshold_duration_unit .= '<option value="' . $unit . '"' . $selected . '>' . $label . '</option>';
			}
			$threshold_duration_unit .= '</select>';
			// Lockout duration
			$lockout_duration = '<input type="text" name="theme_my_login[security][failed_login][lockout_duration]" id="theme_my_login_security_failed_login_lockout_duration" value="' . $options->get_option( array( 'security', 'failed_login', 'lockout_duration' ) ) . '" size="1" />';
			// Lockout duration unit
			$lockout_duration_unit = '<select name="theme_my_login[security][failed_login][lockout_duration_unit]" id="theme_my_login_security_failed_login_lockout_duration_unit">';
			foreach ( $units as $unit => $label ) {
				$selected = ( $options->get_option( array( 'security', 'failed_login', 'lockout_duration_unit' ) ) == $unit ) ? ' selected="selected"' : '';
				$lockout_duration_unit .= '<option value="' . $unit . '"' . $selected . '>' . $label . '</option>';
			}
			$lockout_duration_unit .= '</select>';
			// Output them all
			printf( __( 'After %1$s failed login attempts within %2$s %3$s, lockout the account for %4$s %5$s.', 'theme-my-login' ), $threshold, $threshold_duration, $threshold_duration_unit, $lockout_duration, $lockout_duration_unit ); ?>
		</td>
	</tr>
</table>
<?php
	}

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
		$settings['security'] = array(
			'private_site' => isset( $_POST['theme_my_login']['security']['private_site'] ),
			'failed_login' => array(
				'threshold' => absint( $settings['security']['failed_login']['threshold'] ),
				'threshold_duration' => absint( $settings['security']['failed_login']['threshold_duration'] ),
				'threshold_duration_unit' => $settings['security']['failed_login']['threshold_duration_unit'],
				'lockout_duration' => absint( $settings['security']['failed_login']['lockout_duration'] ),
				'lockout_duration_unit' => $settings['security']['failed_login']['lockout_duration_unit']
			)
		);
		return $settings;
	}

	/**
	 * Activates this module
	 *
	 * Callback for "tml_activate_security/security.php" hook in method Theme_My_Login_Admin::activate_module()
	 *
	 * @see Theme_My_Login_Admin::activate_module()
	 * @since 6.0
	 * @access public
	 *
	 * @param object $theme_my_login Reference to global $theme_my_login object
	 */
	function activate( &$theme_my_login ) {
		$options = Theme_My_Login_Security::init_options();
		$theme_my_login->options->set_option( 'security', $options['security'] );
	}

	/**
	 * Loads the module
	 *
	 * @since 6.0
	 * @access public
	 */
	function load() {
		add_action( 'tml_activate_security/security.php', array( &$this, 'activate' ) );
		add_action( 'tml_admin_menu', array( &$this, 'admin_menu' ) );
		add_filter( 'tml_save_settings', array( &$this, 'save_settings' ) );
		add_action( 'load-users.php', array( &$this, 'load_users_page' ) );
	}
}

/**
 * Holds the reference to Theme_My_Login_Security_Admin object
 * @global object $theme_my_login_security_admin
 * @since 6.0
 */
$theme_my_login_security_admin = new Theme_My_Login_Security_Admin();

endif; // Class exists

?>
