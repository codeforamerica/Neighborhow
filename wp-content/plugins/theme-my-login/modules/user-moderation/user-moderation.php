<?php
/*
Plugin Name: User Moderation
Description: Enabling this module will initialize user moderation. You will then have to configure the settings via the "Moderation" tab.
*/

if ( !class_exists( 'Theme_My_Login_User_Moderation' ) ) :
/**
 * Theme My Login Custom User Links module class
 *
 * Adds the ability to define custom links to display to a user when logged in based upon their "user role".
 *
 * @since 6.0
 */
class Theme_My_Login_User_Moderation extends Theme_My_Login_Module {
	/**
	 * Applies user moderation upon registration
	 *
	 * @since 6.0
	 * @access public
	 */
	function register_post() {
		global $theme_my_login, $theme_my_login_custom_email;

		// Remove default new user notification
		if ( has_action( 'tml_new_user_registered', 'wp_new_user_notification' ) )
			remove_action( 'tml_new_user_registered', 'wp_new_user_notification', 10, 2 );

		// Remove Custom Email new user notification
		if ( $theme_my_login->is_module_active( 'custom-email/custom-email.php' ) ) {
			if ( has_action( 'tml_new_user_registered', array( &$theme_my_login_custom_email, 'new_user_notification' ) ) )
				remove_action( 'tml_new_user_registered', array( &$theme_my_login_custom_email, 'new_user_notification' ), 10, 2 );
		}

		// Moderate user upon registration
		add_action( 'tml_new_user_registered', array( &$this, 'moderate_user' ), 100, 2 );
	}

	/**
	 * Applies moderation to a newly registered user
	 *
	 * Callback for "register_post" hook in method Theme_My_Login::register_new_user()
	 *
	 * @see Theme_My_Login::register_new_user()
	 * @since 6.0
	 * @access public
	 *
	 * @param int $user_id The user's ID
	 * @param string $user_pass The user's password
	 */
	function moderate_user( $user_id, $user_pass ) {
		global $theme_my_login, $wpdb;

		// Set user role to "pending"
		$user = new WP_User( $user_id );
		$user->set_role( 'pending' );

		// Temporarily save plaintext pass
		if ( isset( $_POST['user_pass'] ) )
			update_user_meta( $user_id, 'user_pass', $_POST['user_pass'] );

		// Send appropriate e-mail depending on moderation type
		if ( 'email' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) ) { // User activation
			// Generate an activation key
			$key = wp_generate_password( 20, false );
			// Set the activation key for the user
			$wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'user_login' => $user->user_login ) );
			// Send activation e-mail
			$this->new_user_activation_notification( $user_id, $key );
		} elseif ( 'admin' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) ) { // Admin approval
			// Send approval e-mail
			$this->new_user_approval_admin_notification( $user_id );
		}
	}

	/**
	 * Handles "activate" action for login page
	 *
	 * Callback for "tml_request_activate" hook in method Theme_My_Login::the_request();
	 *
	 * @see Theme_My_Login::the_request();
	 * @since 6.0
	 * @access public
	 */
	function user_activation() {
		global $theme_my_login;

		// Attempt to activate the user
		$errors = $this->activate_new_user( $_GET['key'], $_GET['login'] );
		// Make sure there are no errors
		if ( !is_wp_error( $errors ) ) {
			$redirect_to = Theme_My_Login::get_current_url( 'activation=complete' );
			if ( !empty( $theme_my_login->request_instance ) )
				$redirect_to = add_query_arg( 'instance', $theme_my_login->request_instance, $redirect_to );
			wp_redirect( $redirect_to );
			exit();
		}
		// If we make it here, the user failed activation, so it must be an invalid key
		$redirect_to = Theme_My_Login::get_current_url( 'activation=invalidkey' );
		if ( !empty( $theme_my_login->request_instance ) )
			$redirect_to = add_query_arg( 'instance', $theme_my_login->request_instance, $redirect_to );
		wp_redirect( $redirect_to );
		exit();
	}

	/**
	 * Handles "send_activation" action for login page
	 *
	 * Callback for "tml_request_send_activation" hook in method Theme_My_Login::the_request();
	 *
	 * @see Theme_My_Login::the_request();
	 * @since 6.0
	 * @access public
	 */
	function send_activation() {
		global $theme_my_login, $wpdb;

		$login = isset( $_GET['login'] ) ? trim( $_GET['login'] ) : '';

		if ( !$user_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->users WHERE user_login = %s", $login ) ) ) {
			$redirect_to = Theme_My_Login::get_current_url( 'sendactivation=failed' );
			if ( !empty( $theme_my_login->request_instance ) )
				$redirect_to = add_query_arg( 'instance', $theme_my_login->request_instance, $redirect_to );
			wp_redirect( $redirect_to );
			exit();
		}

		$user = new WP_User( $user_id );

		if ( in_array( 'pending', (array) $user->roles ) ) {
			// Send activation e-mail
			$this->new_user_activation_notification( $user->ID );
			// Now redirect them
			$redirect_to = Theme_My_Login::get_current_url( 'sendactivation=sent' );
			wp_redirect( $redirect_to );
			exit();
		}
	}

	/**
	 * Blocks "pending" users from loggin in
	 *
	 * Callback for "authenticate" hook in function wp_authenticate()
	 *
	 * @see wp_authenticate()
	 * @since 6.0
	 * @access public
	 *
	 * @param WP_User $user WP_User object
	 * @param string $username Username posted
	 * @param string $password Password posted
	 * @return WP_User|WP_Error WP_User if the user can login, WP_Error otherwise
	 */
	function authenticate( $user, $username, $password ) {
		global $theme_my_login, $wpdb;

		$cap_key = $wpdb->prefix . 'capabilities';

		if ( $userdata = get_user_by( 'login', $username ) ) {
			if ( array_key_exists( 'pending', (array) $userdata->$cap_key ) ) {
				if ( 'email' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) ) {
					return new WP_Error( 'pending', sprintf(
						__( '<strong>ERROR</strong>: You have not yet confirmed your e-mail address. <a href="%s">Resend activation</a>?', 'theme-my-login' ),
						$theme_my_login->get_login_page_link( 'action=sendactivation&login=' . $username ) ) );
				} else {
					return new WP_Error( 'pending', __( '<strong>ERROR</strong>: Your registration has not yet been approved.', 'theme-my-login' ) );
				}
			}
		}
		return $user;
	}

	/**
	 * Blocks "pending" users from resetting their password
	 *
	 * Callback for "allow_password_reset" in method Theme_My_Login::retrieve_password()
	 *
	 * @see Theme_My_Login::retrieve_password()
	 * @since 6.0
	 * @access public
	 *
	 * @param bool $allow Default setting
	 * @param int $user_id User ID
	 * @return bool Whether to allow password reset or not
	 */
	function allow_password_reset( $allow, $user_id ) {
		$user = new WP_User( $user_id );
		if ( in_array( 'pending', (array) $user->roles ) )
			$allow = false;
		return $allow;
	}

	/**
	 * Changes the registration redirection based upon moderaton type
	 *
	 * Callback for "register_redirect" hook in method Theme_My_Login::the_request()
	 *
	 * @see Theme_My_Login::the_request()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $redirect_to Default redirect
	 * @return string URL to redirect to
	 */
	function register_redirect( $redirect_to ) {
		global $theme_my_login;

		// TML page link
		$redirect_to = $theme_my_login->get_login_page_link();

		if ( !empty( $theme_my_login->request_instance ) )
			$redirect_to = $theme_my_login->get_current_url( 'instance=' . $theme_my_login->request_instance );

		if ( 'email' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) )
			$redirect_to = add_query_arg( 'pending', 'activation', $redirect_to );
		elseif ( 'admin' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) )
			$redirect_to = add_query_arg( 'pending', 'approval', $redirect_to );

		return $redirect_to;
	}

	/**
	 * Handles activating a new user by user email confirmation
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $key Hash to validate sending confirmation email
	 * @param string $login User's username for logging in
	 * @return bool|WP_Error True if successful, WP_Error otherwise
	 */
	function activate_new_user( $key, $login ) {
		global $wpdb;

		$key = preg_replace('/[^a-z0-9]/i', '', $key);

		if ( empty( $key ) || !is_string( $key ) )
			return new WP_Error( 'invalid_key', __( 'Invalid key', 'theme-my-login' ) );

		if ( empty( $login ) || !is_string( $login ) )
			return new WP_Error( 'invalid_key', __( 'Invalid key', 'theme-my-login' ) );

		// Validate activation key
		$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login ) );
		if ( empty( $user ) )
			return new WP_Error( 'invalid_key', __( 'Invalid key', 'theme-my-login' ) );

		do_action( 'tml_user_activation_post', $user->user_login, $user->user_email );

		// Allow plugins to short-circuit process and send errors
		$errors = new WP_Error();
		$errors = apply_filters( 'tml_user_activation_errors', $errors, $user->user_login, $user->user_email );

		// Return errors if there are any
		if ( $errors->get_error_code() )
			return $errors;

		// Clear the activation key
		$wpdb->update( $wpdb->users, array( 'user_activation_key' => '' ), array( 'user_login' => $login ) );

		// Set user role
		$user_object = new WP_User( $user->ID );
		$user_object->set_role( get_option( 'default_role' ) );
		unset( $user_object );

		// Check for plaintext pass
		if ( !$user_pass = get_user_meta( $user->ID, 'user_pass', true ) ) {
			$user_pass = wp_generate_password();
			wp_set_password( $user_pass, $user->ID );
		}

		// Delete plaintext pass
		delete_user_meta( $user->ID, 'user_pass' );

		do_action( 'tml_new_user_activated', $user->ID, $user_pass );

		return true;
	}

	/**
	 * Calls the "tml_new_user_registered" hook
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param int $user_id The user's ID
	 * @param string $user_pass The user's password
	 */
	function new_user_activated( $user_id, $user_pass ) {
		do_action( 'tml_new_user_registered', $user_id, $user_pass );
	}

	/**
	 * Notifies a pending user to activate their account
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param int $user_id The user's ID
	 * @param string $key The unique activation key
	 */
	function new_user_activation_notification( $user_id, $key = '' ) {
		global $wpdb, $current_site;

		$user = new WP_User( $user_id );

		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );

		if ( empty( $key ) ) {
			$key = $wpdb->get_var( $wpdb->prepare( "SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login ) );
			if ( empty( $key ) ) {
				$key = wp_generate_password( 20, false );
				$wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'user_login' => $user_login ) );
			}
		}

		if ( is_multisite() ) {
			$blogname = $current_site->site_name;
		} else {
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}			

		$activation_url = add_query_arg( array( 'action' => 'activate', 'key' => $key, 'login' => rawurlencode( $user_login ) ), wp_login_url() );

		$title = sprintf( __( '[%s] Activate Your Account', 'theme-my-login' ), $blogname );
		$message  = sprintf( __( 'Thanks for registering at %s! To complete the activation of your account please click the following link: ', 'theme-my-login' ), $blogname ) . "\r\n\r\n";
		$message .=  $activation_url . "\r\n";

		$title = apply_filters( 'user_activation_notification_title', $title, $user_id );
		$message = apply_filters( 'user_activation_notification_message', $message, $activation_url, $user_id );

		return wp_mail( $user_email, $title, $message );
	}

	/**
	 * Notifies the administrator of a pending user needing approval
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param int $user_id The user's ID
	 */
	function new_user_approval_admin_notification( $user_id ) {
		global $current_site;

		$user = new WP_User( $user_id );

		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );

		if ( is_multisite() ) {
			$blogname = $current_site->site_name;
		} else {
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		$title = sprintf( __( '[%s] New User Awaiting Approval', 'theme-my-login' ), $blogname );

		$message  = sprintf( __( 'New user requires approval on your blog %s:', 'theme-my-login' ), $blogname ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s', 'theme-my-login' ), $user_login ) . "\r\n";
		$message .= sprintf( __( 'E-mail: %s', 'theme-my-login' ), $user_email ) . "\r\n\r\n";
		$message .= __( 'To approve or deny this user:', 'theme-my-login' ) . "\r\n";
		$message .= admin_url( 'users.php?role=pending' );

		$title = apply_filters( 'user_approval_admin_notification_title', $title, $user_id );
		$message = apply_filters( 'user_approval_admin_notification_message', $message, $user_id );

		$to = apply_filters( 'user_approval_admin_notifcation_mail_to', get_option( 'admin_email' ) );

		@wp_mail( $to, $title, $message );
	}

	/**
	 * Handles display of various action/status messages
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param object $theme_my_login Reference to global $theme_my_login object
	 */
	function action_messages( &$theme_my_login ) {
		if ( isset( $_GET['pending'] ) && 'activation' == $_GET['pending'] ) {
			$theme_my_login->errors->add( 'pending_activation', __( 'Your registration was successful but you must now confirm your email address before you can log in. Please check your email and click on the link provided.', 'theme-my-login' ), 'message' );
		} elseif ( isset( $_GET['pending'] ) && 'approval' == $_GET['pending'] ) {
			$theme_my_login->errors->add( 'pending_approval', __( 'Your registration was successful but you must now be approved by an administrator before you can log in. You will be notified by e-mail once your account has been reviewed.', 'theme-my-login' ), 'message' );
		} elseif ( isset( $_GET['activation'] ) && 'complete' == $_GET['activation'] ) {
			if ( $theme_my_login->is_module_active( 'custom-passwords/custom-passwords.php' ) )
				$theme_my_login->errors->add( 'activation_complete', __( 'Your account has been activated. You may now log in.', 'theme-my-login' ), 'message' );
			else
				$theme_my_login->errors->add( 'activation_complete', __( 'Your account has been activated. Please check your e-mail for your password.', 'theme-my-login' ), 'message' );
		} elseif ( isset( $_GET['activation'] ) && 'invalidkey' == $_GET['activation'] ) {
			$theme_my_login->errors->add( 'invalid_key', __('<strong>ERROR</strong>: Sorry, that key does not appear to be valid.', 'theme-my-login' ) );
		} elseif ( isset( $_GET['sendactivation'] ) ) {
			if ( 'failed' == $_GET['sendactivation'] )
				$theme_my_login->errors->add( 'sendactivation_failed', __('<strong>ERROR</strong>: Sorry, the activation e-mail could not be sent.', 'theme-my-login' ) );
			elseif ( 'sent' == $_GET['sendactivation'] )
				$theme_my_login->errors->add( 'sendactivation_sent', __('The activation e-mail has been sent to the e-mail address with which you registered. Please check your email and click on the link provided.', 'theme-my-login' ), 'message' );
		}
	}

	/**
	 * Deactivates this module
	 *
	 * Callback for "tml_deactivate_user-moderation/user-moderation.php" hook in method Theme_My_Login_Admin::deactivate_modules()
	 *
	 * @see Theme_My_Login_Admin::deactivate_modules()
	 * @since 6.0
	 * @access public
	 *
	 * @param object $theme_my_login Reference to global $theme_my_login object
	 */
	function deactivate( &$theme_my_login ) {
		remove_role( 'pending' );
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
	 * @param array $options Options passd in from filter
	 * @return array Original $options array with module options appended
	 */
	function init_options( $options = array() ) {
		$options = (array) $options;

		$options['moderation'] = array(
			'type' => 'none'
			);

		$email = array(
			'user_activation' => array(
				'mail_from' => '',
				'mail_from_name' => '',
				'mail_content_type' => '',
				'title' => '',
				'message' => ''
				),
			'user_approval' => array(
				'mail_from' => '',
				'mail_from_name' => '',
				'mail_content_type' => '',
				'title' => '',
				'message' => '',
				'admin_mail_to' => '',
				'admin_mail_from' => '',
				'admin_mail_from_name' => '',
				'admin_mail_content_type' => '',
				'admin_title' => '',
				'admin_message' => '',
				'admin_disable' => 0
				),
			'user_denial' => array(
				'mail_from' => '',
				'mail_from_name' => '',
				'mail_content_type' => '',
				'title' => '',
				'message' => ''
				)
			);
		if ( isset( $options['email'] ) )
			$options['email'] = array_merge( $options['email'], $email );
		else
			$options['email'] = $email;

		return $options;
	}

	/**
	 * Applies module actions and filters
	 *
	 * Callback for "tml_modules_loaded" in file "theme-my-login.php"
	 *
	 * @since 6.0
	 * @access public
	 */
	function modules_loaded() {
		global $theme_my_login;

		// Moderation is enabled
		if ( in_array( $theme_my_login->options->get_option( array( 'moderation', 'type' ) ), array( 'admin', 'email' ) ) ) {
			// Remove all other registration filters
			add_action( 'register_post', array( &$this, 'register_post' ) );

			// Redirect with proper message after registration
			add_filter( 'register_redirect', array( &$this, 'register_redirect' ), 100 );

			// Block pending users from logging in
			add_action( 'authenticate', array( &$this, 'authenticate' ), 100, 3 );
			// Block pending users from password reset
			add_filter( 'allow_password_reset', array( &$this, 'allow_password_reset' ), 10, 2 );

			// Call "tml_new_user_registered" hook on successful activation
			add_action( 'tml_new_user_activated', array( &$this, 'new_user_activated' ), 10, 2 );

			// Add activation action
			if ( 'email' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) ) {
				add_action( 'tml_request_activate', array( &$this, 'user_activation' ) );
				add_action( 'tml_request_sendactivation', array( &$this, 'send_activation' ) );
			}
		}
	}

	/**
	 * Loads the module
	 *
	 * @since 6.0
	 * @access public
	 */
	function load() {
		if ( is_multisite() )
			return;

		add_action( 'tml_deactivate_user-moderation/user-moderation.php', array( &$this, 'deactivate' ) );
		add_filter( 'tml_init_options', array( &$this, 'init_options' ) );
		add_action( 'tml_modules_loaded', array( &$this, 'modules_loaded' ) );
		add_action( 'tml_request', array( &$this, 'action_messages' ) );

		add_role( 'pending', 'Pending', array() );
	}

}

/**
 * Holds the reference to Theme_My_Login_User_Moderation object
 * @global object $theme_my_login_user_moderation
 * @since 6.0
 */
$theme_my_login_user_moderation = new Theme_My_Login_User_Moderation();

if ( is_admin() )
	include_once( TML_ABSPATH. '/modules/user-moderation/admin/user-moderation-admin.php' );

endif; // Class exists

?>
