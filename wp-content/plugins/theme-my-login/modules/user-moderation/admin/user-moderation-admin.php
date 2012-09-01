<?php

if ( !class_exists( 'Theme_My_Login_User_Moderation_Admin' ) ) :
/**
 * Theme My Login User Moderation module admin class
 *
 * @since 6.0
 */
class Theme_My_Login_User_Moderation_Admin extends Theme_My_Login_Module {
	/**
	 * Attaches actions/filters explicitly to users.php
	 *
	 * Callback for "load-users.php" hook
	 *
	 * @since 6.0
	 * @access public
	 */
	function load_users_page() {
		add_filter( 'user_row_actions', array( &$this, 'user_row_actions' ), 10, 2 );
		add_action( 'admin_notices', array( &$this, 'admin_notices' ) );
		add_action( 'delete_user', array( &$this, 'deny_user' ) );

		// Is there an action?
		if ( isset( $_GET['action'] ) ) {

			// Is it a sanctioned action?
			if ( in_array( $_GET['action'], array( 'approve', 'resendactivation' ) ) ) {

				// Is there a user ID?
				$user = isset( $_GET['user'] ) ? $_GET['user'] : '';

				// No user ID?
				if ( !$user || !current_user_can( 'edit_user', $user ) )
					wp_die( __( 'You can&#8217;t edit that user.', 'theme-my-login' ) );

				// Where did we come from?
				$redirect_to = isset( $_REQUEST['wp_http_referer'] ) ? remove_query_arg( array( 'wp_http_referer', 'updated', 'delete_count' ), stripslashes( $_REQUEST['wp_http_referer'] ) ) : 'users.php';

				// Are we approving?
				if ( 'approve' == $_GET['action'] ) {
					check_admin_referer( 'approve-user' );

					if ( !Theme_My_Login_User_Moderation_Admin::approve_user( $user ) )
						wp_die( __( 'You can&#8217;t edit that user.', 'theme-my-login' ) );

					$redirect_to = add_query_arg( 'update', 'approve', $redirect_to );
				}
				// Are we resending an activation e-mail?
				elseif ( 'resendactivation' == $_GET['action'] ) {
					check_admin_referer( 'resend-activation' );

					if ( !Theme_My_Login_User_Moderation::new_user_activation_notification( $user ) )
						wp_die( __( 'The e-mail could not be sent.', 'theme-my-login' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function...', 'theme-my-login' ) );

					$redirect_to = add_query_arg( 'update', 'sendactivation', $redirect_to );
				}
				wp_redirect( $redirect_to );
				exit;
			}
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
		if ( isset( $_GET['update'] ) && in_array( $_GET['update'], array( 'approve', 'sendactivation' ) ) ) {
			echo '<div id="message" class="updated fade"><p>';
			if ( 'approve' == $_GET['update'] )
				_e( 'User approved.', 'theme-my-login' );
			elseif ( 'sendactivation' == $_GET['update'] )
				_e( 'Activation sent.', 'theme-my-login' );
			echo '</p></div>';
		}
	}

	/**
	 * Adds "Approve" link for each pending user on users.php
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
		global $theme_my_login;

		$current_user = wp_get_current_user();
		if ( $current_user->ID != $user_object->ID ) {
			if ( in_array( 'pending', (array) $user_object->roles ) ) {
				$_actions = array();
				// If moderation type is e-mail activation, add "Resend Activation" link
				if ( 'email' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) ) {
					$_actions['resend-activation'] = '<a href="' . add_query_arg( 'wp_http_referer',
						urlencode( esc_url( stripslashes( $_SERVER['REQUEST_URI'] ) ) ),
						wp_nonce_url( "users.php?action=resendactivation&amp;user=$user_object->ID", 'resend-activation' ) ) . '">' . __( 'Resend Activation', 'theme-my-login' ) . '</a>';
				} elseif ( 'admin' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) ) {
					// Add "Approve" link
					$_actions['approve-user'] = '<a href="' . add_query_arg( 'wp_http_referer',
						urlencode( esc_url( stripslashes( $_SERVER['REQUEST_URI'] ) ) ),
						wp_nonce_url( "users.php?action=approve&amp;user=$user_object->ID", 'approve-user' ) ) . '">' . __( 'Approve', 'theme-my-login' ) . '</a>';
				}
				$actions = array_merge( $_actions, $actions );
			}
		}
		return $actions;
	}

	/**
	 * Handles activating a new user by admin approval
	 *
	 * @param string $user_id User's ID
	 * @return bool Returns false if not a valid user
	 */
	function approve_user( $user_id ) {
		global $wpdb, $current_site;

		$user_id = (int) $user_id;

		// Get user by ID
		$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE ID = %d", $user_id ) );
		if ( empty( $user ) )
			return false;

		do_action( 'approve_user', $user->ID );

		// Clear the activation key if there is one
		$wpdb->update( $wpdb->users, array( 'user_activation_key' => '' ), array( 'ID' => $user->ID ) );

		$approval_role = apply_filters( 'tml_approval_role', get_option( 'default_role' ), $user->ID );

		// Set user role
		$user_object = new WP_User( $user->ID );
		$user_object->set_role( $approval_role );
		unset( $user_object );

		// Check for plaintext pass
		if ( !$user_pass = get_user_meta( $user->ID, 'user_pass', true ) ) {
			$user_pass = wp_generate_password();
			wp_set_password( $user_pass, $user->ID );
		}

		// Delete plaintext pass
		delete_user_meta( $user->ID, 'user_pass' );

		if ( is_multisite() ) {
			$blogname = $current_site->site_name;
		} else {
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		$message  = sprintf( __( 'You have been approved access to %s', 'theme-my-login' ), $blogname ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s', 'theme-my-login' ), $user->user_login ) . "\r\n";
		$message .= sprintf( __( 'Password: %s', 'theme-my-login' ), $user_pass ) . "\r\n\r\n";
		$message .= site_url( 'wp-login.php', 'login' ) . "\r\n";	

		$title = sprintf( __( '[%s] Registration Approved', 'theme-my-login' ), $blogname );

		$title = apply_filters( 'user_approval_notification_title', $title, $user->ID );
		$message = apply_filters( 'user_approval_notification_message', $message, $user_pass, $user->ID );

		if ( $message && !wp_mail( $user->user_email, $title, $message ) )
			  die( '<p>' . __( 'The e-mail could not be sent.', 'theme-my-login' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function...', 'theme-my-login' ) . '</p>' );

		return true;
	}

	/**
	 * Called upon deletion of a user in the "Pending" role
	 *
	 * @param string $user_id User's ID
	 */
	function deny_user( $user_id ) {
		global $current_site;

		$user_id = (int) $user_id;

		$user = new WP_User( $user_id );
		if ( !in_array( 'pending', (array) $user->roles ) )
			return;

		do_action( 'deny_user', $user->ID );

		if ( is_multisite() ) {
			$blogname = $current_site->site_name;
		} else {
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		$message = sprintf( __( 'You have been denied access to %s', 'theme-my-login' ), $blogname );
		$title = sprintf( __( '[%s] Registration Denied', 'theme-my-login' ), $blogname );

		$title = apply_filters( 'user_denial_notification_title', $title, $user_id );
		$message = apply_filters( 'user_denial_notification_message', $message, $user_id );

		if ( $message && !wp_mail( $user->user_email, $title, $message ) )
			  die( '<p>' . __( 'The e-mail could not be sent.', 'theme-my-login' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function...', 'theme-my-login' ) . '</p>' );
	}

	/**
	 * Adds "Moderation" tab to Theme My Login menu
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
		$admin->add_menu_page( __( 'Moderation', 'theme-my-login' ), 'tml-options-moderation', array( &$this, 'display_settings' ) );
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
		global $theme_my_login; ?>
<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e( 'User Moderation', 'theme-my-login' ); ?></th>
		<td>
			<input name="theme_my_login[moderation][type]" type="radio" id="theme_my_login_moderation_type_none" value="none" <?php if ( 'none' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) ) echo 'checked="checked"'; ?> />
			<label for="theme_my_login_moderation_type_none"><?php _e( 'None', 'theme-my-login' ); ?></label>
			<p class="description"><?php _e( 'Check this option to require no moderation.', 'theme-my-login' ); ?></p>
			<input name="theme_my_login[moderation][type]" type="radio" id="theme_my_login_moderation_type_email" value="email" <?php if ( 'email' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) ) echo 'checked="checked"'; ?> />
			<label for="theme_my_login_moderation_type_email"><?php _e( 'E-mail Confirmation', 'theme-my-login' ); ?></label>
			<p class="description"><?php _e( 'Check this option to require new users to confirm their e-mail address before they may log in.', 'theme-my-login' ); ?></p>
			<input name="theme_my_login[moderation][type]" type="radio" id="theme_my_login_moderation_type_admin" value="admin" <?php if ( 'admin' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) ) echo 'checked="checked"'; ?> />
			<label for="theme_my_login_moderation_type_admin"><?php _e( 'Admin Approval', 'theme-my-login' ); ?></label>
			<p class="description"><?php _e( 'Check this option to require new users to be approved by an administrator before they may log in.', 'theme-my-login' ); ?></p>
		</td>
	</tr>
</table><?php
	}

	function admin_init() {
		global $theme_my_login, $theme_my_login_admin;

		// Disable moderation if using multisite
		if ( is_multisite() ) {
			if ( $theme_my_login->is_module_active( 'user-moderation/user-moderation.php' ) ) {
				// Deactivate the module
				$theme_my_login_admin->deactivate_modules( 'user-moderation/user-moderation.php' );

				// Set an error so the administrator will know
				$module_errors = $theme_my_login->options->get_option( 'module_errors', array() );
				$module_errors['user-moderation/user-moderation.php'] = __( 'User Moderation is not currently compatible with multisite.', 'theme-my-login' );
				$theme_my_login->options->set_option( 'module_errors', $module_errors );
			}
		}
	}

	/**
	 * Activates this module
	 *
	 * Callback for "tml_activate_user-moderation/user-moderation.php" hook in method Theme_My_Login_Admin::activate_module()
	 *
	 * @see Theme_My_Login_Admin::activate_module()
	 * @since 6.0
	 * @access public
	 *
	 * @param object $theme_my_login Reference to global $theme_my_login object
	 */
	function activate( &$theme_my_login ) {
		$options = Theme_My_Login_User_Moderation::init_options();
		$theme_my_login->options->set_option( 'moderation', $options['moderation'] );

		$email = array_merge( (array) $theme_my_login->get_option( 'email' ), $options['email'] );
		$theme_my_login->options->set_option( 'email', $email );
	}

	/**
	 * Loads the module
	 *
	 * @since 6.0
	 * @access public
	 */
	function load() {
		add_action( 'tml_activate_user-moderaiton/user-moderation.php', array( &$this, 'activate' ) );
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		if ( is_multisite() )
			return;
		add_action( 'tml_admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'load-users.php', array( &$this, 'load_users_page' ) );
	}

}

/**
 * Holds the reference to Theme_My_Login_User_Moderation_Admin object
 * @global object $theme_my_login_user_moderation_admin
 * @since 6.0
 */
$theme_my_login_user_moderation_admin = new Theme_My_Login_User_Moderation_Admin();

endif; // Class exists

?>
