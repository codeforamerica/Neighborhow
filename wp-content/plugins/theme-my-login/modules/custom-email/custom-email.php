<?php
/*
Plugin Name: Custom E-mail
Description: Enabling this module will initialize custom e-mails. You will then have to configure the settings via the "E-mail" tab.
*/

if ( !class_exists( 'Theme_My_Login_Custom_Email' ) ) :
/**
 * Theme My Login Custom E-mail module class
 *
 * Customize e-mails sent from the login/registration system.
 *
 * @since 6.0
 */
class Theme_My_Login_Custom_Email extends Theme_My_Login_Module {
	/**
	 * Holds reference to module specific options in $theme_my_login object options
	 *
	 * @since 6.0
	 * @access public
	 * @var array
	 */
	var $options;

	/**
	 * Mail from
	 *
	 * @since 6.0
	 * @access public
	 * @var string
	 */
	var $mail_from;

	/**
	 * Mail from name
	 *
	 * @since 6.0
	 * @access public
	 * @var string
	 */
	var $mail_from_name;

	/**
	 * Mail content type
	 *
	 * @since 6.0
	 * @access public
	 * @var string
	 */
	var $mail_content_type;

	/**
	 * Sets variables to be used with mail header filters
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $mail_from E-mail address to send the mail from
	 * @param string $mail_from_name Name to send the mail from
	 * @param string $mail_content_type Content type for the message
	 */
	function set_mail_headers( $mail_from = '', $mail_from_name = '', $mail_content_type = 'text' ) {
		$this->mail_from = $mail_from;
		$this->mail_from_name = $mail_from_name;
		$this->mail_content_type = $mail_content_type;
	}

	/**
	 * Applies all password retrieval mail filters
	 *
	 * Callback for "retrieve_password" hook in method Theme_My_Login::retrieve_password()
	 *
	 * @see Theme_My_Login::retrieve_password()
	 * @since 6.0
	 * @access public
	 */
	function apply_retrieve_pass_filters() {
		global $theme_my_login;
		$options =& $theme_my_login->options->get_option( array( 'email', 'retrieve_pass' ) );
		$this->set_mail_headers( $options['mail_from'], $options['mail_from_name'], $options['mail_content_type'] );
		add_filter( 'retrieve_password_title', array( &$this, 'retrieve_pass_title_filter' ), 10, 2 );
		add_filter( 'retrieve_password_message', array( &$this, 'retrieve_pass_message_filter' ), 10, 3 );
	}

	/**
	 * Applies all password reset mail filters
	 *
	 * Callback for "password_reset" hook in Theme_My_Login::reset_password()
	 *
	 * @see Theme_My_Login::reset_password()
	 * @since 6.2
	 * @access public
	 */
	function apply_password_reset_filters() {
		global $theme_my_login;
		$options =& $theme_my_login->options->get_option( array( 'email', 'reset_pass' ) );
		$this->set_mail_headers( $options['admin_mail_from'], $options['admin_mail_from_name'], $options['admin_mail_content_type'] );
		add_filter( 'password_change_notification_mail_to', array( &$this, 'password_change_notification_mail_to_filter' ) );
		add_filter( 'password_change_notification_title', array( &$this, 'password_change_notification_title_filter' ), 10, 2 );
		add_filter( 'password_change_notification_message', array( &$this, 'password_change_notification_message_filter' ), 10, 2 );
		add_filter( 'send_password_change_notification', array( &$this, 'send_password_change_notification_filter' ) );
	}

	/**
	 * Applies all new user mail filters
	 *
	 * Callback for "register_post" hook in method Theme_My_Login::register_new_user()
	 *
	 * @see Theme_My_Login::register_new_user()
	 * @since 6.0
	 * @access public
	 */
	function apply_new_user_filters() {
		add_filter( 'new_user_notification_title', array( &$this, 'new_user_notification_title_filter' ), 10, 2 );
		add_filter( 'new_user_notification_message', array( &$this, 'new_user_notification_message_filter' ), 10, 3 );
		add_filter( 'send_new_user_notification', array( &$this, 'send_new_user_notification_filter' ) );
		add_filter( 'new_user_admin_notification_mail_to', array( &$this, 'new_user_admin_notification_mail_to_filter' ) );
		add_filter( 'new_user_admin_notification_title', array( &$this, 'new_user_admin_notification_title_filter' ), 10, 2 );
		add_filter( 'new_user_admin_notification_message', array( &$this, 'new_user_admin_notification_message_filter' ), 10, 2 );
		add_filter( 'send_new_user_admin_notification', array( &$this, 'send_new_user_admin_notification_filter' ) );
	}

	/**
	 * Changes the mail from address
	 *
	 * Callback for "wp_mail_from" hook in wp_mail()
	 *
	 * @see wp_mail()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $from_email Default from email
	 * @return string New from email
	 */
	function mail_from_filter( $from_email ) {
		return empty( $this->mail_from ) ? $from_email : $this->mail_from;
	}

	/**
	 * Changes the mail from name
	 *
	 * Callback for "wp_mail_from_name" hook in wp_mail()
	 *
	 * @see wp_mail()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $from_name Default from name
	 * @return string New from name
	 */
	function mail_from_name_filter( $from_name ) {
		return empty( $this->mail_from_name ) ? $from_name : $this->mail_from_name;
	}

	/**
	 * Changes the mail content type
	 *
	 * Callback for "wp_mail_content_type" hook in wp_mail()
	 *
	 * @see wp_mail()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $content_type Default content type
	 * @return string New content type
	 */
	function mail_content_type_filter( $content_type ) {
		return empty( $this->mail_content_type ) ? $content_type : 'text/' . $this->mail_content_type;
	}

	/**
	 * Changes the retrieve password e-mail subject
	 *
	 * Callback for "retrieve_pass_title" hook in Theme_My_Login::retrieve_password()
	 *
	 * @see Theme_My_Login::retrieve_password()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $title Default subject
	 * @param int $user_id User ID
	 * @return string New subject
	 */
	function retrieve_pass_title_filter( $title, $user_id ) {
		global $theme_my_login;
		$_title = $theme_my_login->options->get_option( array( 'email', 'retrieve_pass', 'title' ) );
		return empty( $_title ) ? $title : Theme_My_Login_Custom_Email::replace_vars( $_title, $user_id );
	}

	/**
	 * Changes the retrieve password e-mail message
	 *
	 * Callback for "retrieve_password_message" hook in Theme_My_Login::retrieve_password()
	 *
	 * @see Theme_My_Login::retrieve_password()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $message Default message
	 * @param string $key The user's reset key
	 * @param int $user_id User ID
	 * @return string New message
	 */
	function retrieve_pass_message_filter( $message, $key, $user_id ) {
		global $theme_my_login;
		$user = get_userdata($user_id);
		$replacements = array(
			'%loginurl%' => site_url( 'wp-login.php', 'login' ),
			'%reseturl%' => site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' )
			);
		$_message = $theme_my_login->options->get_option( array( 'email', 'retrieve_pass', 'message' ) );
		return empty( $_message ) ? $message : Theme_My_Login_Custom_Email::replace_vars( $_message, $user_id, $replacements );
	}

	/**
	 * Changes who the password change notification e-mail is sent to
	 *
	 * Callback for "password_change_notification_mail_to" hook in Theme_My_Login_Custom_Email::password_change_notification()
	 *
	 * @see Theme_My_Login_Custom_Email::password_change_notification()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $to Default admin e-mail address
	 * @return string New e-mail address(es)
	 */
	function password_change_notification_mail_to_filter( $to ) {
		global $theme_my_login;
		$_to = $theme_my_login->options->get_option( array( 'email', 'reset_pass', 'admin_mail_to' ) );
		return empty( $_to ) ? $to : $_to;
	}

	/**
	 * Changes the password change notification e-mail subject
	 *
	 * Callback for "password_change_notification_title" hook in Theme_My_Login_Custom_Email::password_change_notification()
	 *
	 * @see Theme_My_Login_Custom_Email::password_change_notification()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $title Default subject
	 * @param int $user_id User ID
	 * @return string New subject
	 */
	function password_change_notification_title_filter( $title, $user_id ) {
		global $theme_my_login;
		$_title = $theme_my_login->options->get_option( array( 'email', 'reset_pass', 'admin_title' ) );
		return empty( $_title ) ? $title : Theme_My_Login_Custom_Email::replace_vars( $_title, $user_id );
	}

	/**
	 * Changes the password change notification e-mail message
	 *
	 * Callback for "password_change_notification_message" hook in Theme_My_Login_Custom_Email::password_change_notification()
	 *
	 * @see Theme_My_Login_Custom_Email::password_change_notification()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $title Default message
	 * @param int $user_id User ID
	 * @return string New message
	 */
	function password_change_notification_message_filter( $message, $user_id ) {
		global $theme_my_login;
		$_message = $theme_my_login->options->get_option( array( 'email', 'reset_pass', 'admin_message' ) );
		return empty( $_message ) ? $message : Theme_My_Login_Custom_Email::replace_vars( $_message, $user_id );
	}

	/**
	 * Determines whether or not to send the password change notification e-mail
	 *
	 * Callback for "send_password_change_notification" hook in Theme_My_Login_Custom_Email::password_change_notification()
	 *
	 * @see Theme_My_Login_Custom_Email::password_change_notification()
	 * @since 6.0
	 * @access public
	 *
	 * @param bool $enable Default setting
	 * @return bool New setting
	 */
	function send_password_change_notification_filter( $enable ) {
		global $theme_my_login;
		$options =& $theme_my_login->options->get_option( array( 'email', 'reset_pass' ) );
		$this->set_mail_headers( $options['admin_mail_from'], $options['admin_mail_from_name'], $options['admin_mail_content_type'] );
		if ( $options['admin_disable'] )
			return false;
		return $enable;
	}

	/**
	 * Changes the new user e-mail subject
	 *
	 * Callback for "new_user_notification_title" hook in Theme_My_Login_Custom_Email::new_user_notification()
	 *
	 * @see Theme_My_Login_Custom_Email::new_user_notification()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $title Default title
	 * @param int $user_id User ID
	 * @return string New title
	 */
	function new_user_notification_title_filter( $title, $user_id ) {
		global $theme_my_login;
		$_title = $theme_my_login->options->get_option( array( 'email', 'new_user', 'title' ) );
		return empty( $_title ) ? $title : Theme_My_Login_Custom_Email::replace_vars( $_title, $user_id );
	}

	/**
	 * Changes the new user e-mail message
	 *
	 * Callback for "new_user_notification_message" hook in Theme_My_Login_Custom_Email::new_user_notification()
	 *
	 * @see Theme_My_Login_Custom_Email::new_user_notification()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $title Default message
	 * @param string $new_pass The user's password
	 * @param int $user_id User ID
	 * @return string New message
	 */
	function new_user_notification_message_filter( $message, $new_pass, $user_id ) {
		global $theme_my_login;
		$replacements = array(
			'%loginurl%' => site_url( 'wp-login.php', 'login' ),
			'%user_pass%' => $new_pass
			);
		$_message = $theme_my_login->options->get_option( array( 'email', 'new_user', 'message' ) );
		return empty( $_message ) ? $message : Theme_My_Login_Custom_Email::replace_vars( $_message, $user_id, $replacements );
	}

	/**
	 * Determines whether or not to send the new user e-mail
	 *
	 * Callback for "send_new_user_notification" hook in Theme_My_Login_Custom_Email::new_user_notification()
	 *
	 * @see Theme_My_Login_Custom_Email::new_user_notification()
	 * @since 6.0
	 * @access public
	 *
	 * @param bool $enable Default setting
	 * @return bool New setting
	 */
	function send_new_user_notification_filter( $enable ) {
		global $theme_my_login;
		$options =& $theme_my_login->options->get_option( array( 'email', 'new_user' ) );
		$this->set_mail_headers( $options['mail_from'], $options['mail_from_name'], $options['mail_content_type'] );
		return $enable;
	}

	/**
	 * Changes who the new user admin notification e-mail is sent to
	 *
	 * Callback for "new_user_admin_notification_mail_to" hook in Theme_My_Login_Custom_Email::new_user_notification()
	 *
	 * @see Theme_My_Login_Custom_Email::new_user_notification()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $to Default admin e-mail address
	 * @return string New e-mail address(es)
	 */
	function new_user_admin_notification_mail_to_filter( $to ) {
		global $theme_my_login;
		$_to = $theme_my_login->options->get_option( array( 'email', 'new_user', 'admin_mail_to' ) );
		return empty( $_to ) ? $to : $_to;
	}

	/**
	 * Changes the new user admin notification e-mail subject
	 *
	 * Callback for "new_user_admin_notification_title" hook in Theme_My_Login_Custom_Email::new_user_notification()
	 *
	 * @see Theme_My_Login_Custom_Email::new_user_notification()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $title Default subject
	 * @param int $user_id User ID
	 * @return string New subject
	 */
	function new_user_admin_notification_title_filter( $title, $user_id ) {
		global $theme_my_login;
		$_title = $theme_my_login->options->get_option( array( 'email', 'new_user', 'admin_title' ) );
		return empty( $_title ) ? $title : Theme_My_Login_Custom_Email::replace_vars( $_title, $user_id );
	}

	/**
	 * Changes the new user admin notification e-mail message
	 *
	 * Callback for "new_user_admin_notification_message" hook in Theme_My_Login_Custom_Email::new_user_notification()
	 *
	 * @see Theme_My_Login_Custom_Email::new_user_notification()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $title Default message
	 * @param int $user_id User ID
	 * @return string New message
	 */
	function new_user_admin_notification_message_filter( $message, $user_id ) {
		global $theme_my_login;
		$_message = $theme_my_login->options->get_option( array( 'email', 'new_user', 'admin_message' ) );
		return empty( $_message ) ? $message : Theme_My_Login_Custom_Email::replace_vars( $_message, $user_id );
	}

	/**
	 * Determines whether or not to send the new user admin notification e-mail
	 *
	 * Callback for "send_new_user_admin_notification" hook in Theme_My_Login_Custom_Email::new_user_notification()
	 *
	 * @see Theme_My_Login_Custom_Email::new_user_notification()
	 * @since 6.0
	 * @access public
	 *
	 * @param bool $enable Default setting
	 * @return bool New setting
	 */
	function send_new_user_admin_notification_filter( $enable ) {
		global $theme_my_login;
		$options =& $theme_my_login->options->get_option( array( 'email', 'new_user' ) );
		$this->set_mail_headers( $options['admin_mail_from'], $options['admin_mail_from_name'], $options['admin_mail_content_type'] );
		if ( $options['admin_disable'] )
			return false;
		return $enable;
	}

	/**
	 * Applies user moderation mail filters according to moderation type
	 *
	 * @since 6.1
	 * @access public
	 */
	function apply_user_moderation_notification_filters() {
		global $theme_my_login;

		if ( !$theme_my_login->is_module_active( 'user-moderation/user-moderation.php' ) )
			return;

		if ( 'email' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) ) {
			$options =& $theme_my_login->options->get_option( array( 'email', 'user_activation' ) );
			$this->set_mail_headers( $options['mail_from'], $options['mail_from_name'], $options['mail_content_type'] );
			add_filter( 'user_activation_notification_title', array( &$this, 'user_activation_notification_title_filter' ), 10, 2 );
			add_filter( 'user_activation_notification_message', array( &$this, 'user_activation_notification_message_filter' ), 10, 3 );
		} elseif ( 'admin' == $theme_my_login->options->get_option( array( 'moderation', 'type' ) ) ) {
			$options =& $theme_my_login->options->get_option( array( 'email', 'user_approval' ) );
			$this->set_mail_headers( $options['admin_mail_from'], $options['admin_mail_from_name'], $options['admin_mail_content_type'] );
			add_filter( 'user_approval_admin_notifcation_mail_to', array( &$this, 'user_approval_admin_notifcation_mail_to_filter' ) );
			add_filter( 'user_approval_admin_notification_title', array( &$this, 'user_approval_admin_notification_title_filter' ), 10, 2 );
			add_filter( 'user_approval_admin_notification_message', array( &$this, 'user_approval_admin_notification_message_filter' ), 10, 2 );
		}
	}

	/**
	 * Applies all user approval mail filters
	 *
	 * Callback for "approve_user" hook in method Theme_My_Login_User_Moderation::approve_user()
	 *
	 * @see Theme_My_Login_User_Moderation::approve_user()
	 * @since 6.1
	 * @access public
	 */
	function apply_user_approval_notification_filters() {
		global $theme_my_login;
		$options =& $theme_my_login->options->get_option( array( 'email', 'user_approval' ) );
		$this->set_mail_headers( $options['mail_from'], $options['mail_from_name'], $options['mail_content_type'] );
		add_filter( 'user_approval_notification_title', array( &$this, 'user_approval_notification_title_filter' ), 10, 2 );
		add_filter( 'user_approval_notification_message', array( &$this, 'user_approval_notification_message_filter' ), 10, 3 );
	}

	/**
	 * Applies all user denial mail filters
	 *
	 * Callback for "deny_user" hook in method Theme_My_Login_User_Moderation_Admin::deny_user()
	 *
	 * @see Theme_My_Login_User_Moderation_Admin::deny_user()
	 * @since 6.1
	 * @access public
	 */
	function apply_user_denial_notification_filters() {
		global $theme_my_login;
		$options =& $theme_my_login->options->get_option( array( 'email', 'user_denial' ) );
		$this->set_mail_headers( $options['mail_from'], $options['mail_from_name'], $options['mail_content_type'] );
		add_filter( 'user_denial_notification_title', array( &$this, 'user_denial_notification_title_filter' ), 10, 2 );
		add_filter( 'user_denial_notification_message', array( &$this, 'user_denial_notification_message_filter' ), 10, 2 );
	}

	/**
	 * Changes the user activation e-mail subject
	 *
	 * Callback for "user_activation_notification_title" hook in Theme_My_Login_User_Moderation::new_user_activation_notification()
	 *
	 * @see Theme_My_Login_User_Moderation::new_user_activation_notification()
	 * @since 6.1
	 * @access public
	 *
	 * @param string $title The default subject
	 * @param int $user_id The user's ID
	 * @return string The filtered subject
	 */
	function user_activation_notification_title_filter( $title, $user_id ) {
		global $theme_my_login;
		$_title = $theme_my_login->options->get_option( array( 'email', 'user_activation', 'title' ) );
		return empty( $_title ) ? $title : Theme_My_Login_Custom_Email::replace_vars( $_title, $user_id );
	}

	/**
	 * Changes the user activation e-mail message
	 *
	 * Callback for "user_activation_notification_message" hook in Theme_My_Login_User_Moderation::new_user_activation_notification()
	 *
	 * @see Theme_My_Login_User_Moderation::new_user_activation_notification()
	 * @since 6.1
	 * @access public
	 *
	 * @param string $title The default message
	 * @param int $user_id The user's ID
	 * @param string $activation_url The activation URL
	 * @return string The filtered message
	 */
	function user_activation_notification_message_filter( $message, $activation_url, $user_id ) {
		global $theme_my_login;
		$_message = $theme_my_login->options->get_option( array( 'email', 'user_activation', 'message' ) );
		return empty( $_message ) ? $message : Theme_My_Login_Custom_Email::replace_vars( $_message, $user_id, array( '%activateurl%' => $activation_url ) );
	}

	/**
	 * Changes the user approval e-mail subject
	 *
	 * Callback for "user_approval_notification_title" hook in Theme_My_Login_User_Moderation_Admin::approve_user()
	 *
	 * @see Theme_My_Login_User_Moderation_Admin::approve_user()
	 * @since 6.1
	 * @access public
	 *
	 * @param string $title The default subject
	 * @param int $user_id The user's ID
	 * @return string The filtered subject
	 */
	function user_approval_notification_title_filter( $title, $user_id ) {
		global $theme_my_login;
		$_title = $theme_my_login->options->get_option( array( 'email', 'user_approval', 'title' ) );
		return empty( $_title ) ? $title : Theme_My_Login_Custom_Email::replace_vars( $_title, $user_id );
	}

	/**
	 * Changes the user approval e-mail message
	 *
	 * Callback for "user_approval_notification_message" hook in Theme_My_Login_User_Moderation_Admin::approve_user()
	 *
	 * @see Theme_My_Login_User_Moderation_Admin::approve_user()
	 * @since 6.1
	 * @access public
	 *
	 * @param string $title The default message
	 * @param string $new_pass The user's new password
	 * @param int $user_id The user's ID
	 * @return string The filtered message
	 */
	function user_approval_notification_message_filter( $message, $new_pass, $user_id ) {
		global $theme_my_login;
		$_message = $theme_my_login->options->get_option( array( 'email', 'user_approval', 'message' ) );
		return empty( $_message ) ? $message : Theme_My_Login_Custom_Email::replace_vars( $_message, $user_id, array( '%loginurl%' => $theme_my_login->get_login_page_link(), '%user_pass%' => $new_pass ) );
	}

	/**
	 * Changes the user approval admin e-mail recipient
	 *
	 * Callback for "user_approval_admin_notification_mail_to" hook in Theme_My_Login_User_Moderation::new_user_approval_admin_notification()
	 *
	 * @see Theme_My_Login_User_Moderation::new_user_approval_admin_notification()
	 * @since 6.1
	 * @access public
	 *
	 * @param string $to The default recipient
	 * @return string The filtered recipient
	 */
	function user_approval_admin_notifcation_mail_to_filter( $to ) {
		global $theme_my_login;
		$_to = $theme_my_login->options->get_option( array( 'email', 'user_approval', 'admin_mail_to' ) );
		return empty( $_to ) ? $to : $_to;
	}

	/**
	 * Changes the user approval admin e-mail subject
	 *
	 * Callback for "user_approval_admin_notification_title" hook in Theme_My_Login_User_Moderation::new_user_approval_admin_notification()
	 *
	 * @see Theme_My_Login_User_Moderation::new_user_approval_admin_notification()
	 * @since 6.1
	 * @access public
	 *
	 * @param string $title The default subject
	 * @param int $user_id The user's ID
	 * @return string The filtered subject
	 */
	function user_approval_admin_notification_title_filter( $title, $user_id ) {
		global $theme_my_login;
		$_title = $theme_my_login->options->get_option( array( 'email', 'user_approval', 'admin_title' ) );
		return empty( $_title ) ? $title : Theme_My_Login_Custom_Email::replace_vars( $_title, $user_id );
	}

	/**
	 * Changes the user approval admin e-mail message
	 *
	 * Callback for "user_approval_admin_notification_message" hook in Theme_My_Login_User_Moderation::new_user_approval_admin_notification()
	 *
	 * @see Theme_My_Login_User_Moderation::new_user_approval_admin_notification()
	 * @since 6.1
	 * @access public
	 *
	 * @param string $message The default message
	 * @param int $user_id The user's ID
	 * @return string The filtered message
	 */
	function user_approval_admin_notification_message_filter( $message, $user_id ) {
		global $theme_my_login;
		$_message = $theme_my_login->options->get_option( array( 'email', 'user_approval', 'admin_message' ) );
		return empty( $_message ) ? $message : Theme_My_Login_Custom_Email::replace_vars( $_message, $user_id, array( '%pendingurl%' => admin_url( 'users.php?role=pending' ) ) );
	}

	/**
	 * Changes the user denial e-mail subject
	 *
	 * Callback for "user_denial_notification_title" hook in Theme_My_Login_User_Moderation_Admin::deny_user()
	 *
	 * @see Theme_My_Login_User_Moderation_Admin::deny_user()
	 * @since 6.1
	 * @access public
	 *
	 * @param string $title The default subject
	 * @param int $user_id The user's ID
	 * @return string The filtered subject
	 */
	function user_denial_notification_title_filter( $title, $user_id ) {
		global $theme_my_login;
		$_title = $theme_my_login->options->get_option( array( 'email', 'user_denial', 'title' ) );
		return empty( $_title ) ? $title : Theme_My_Login_Custom_Email::replace_vars( $_title, $user_id );
	}

	/**
	 * Changes the user denial e-mail message
	 *
	 * Callback for "user_denial_notification_message" hook in Theme_My_Login_User_Moderation_Admin::deny_user()
	 *
	 * @see Theme_My_Login_User_Moderation_Admin::deny_user()
	 * @since 6.1
	 * @access public
	 *
	 * @param string $message The default message
	 * @param int $user_id The user's ID
	 * @return string The filtered message
	 */
	function user_denial_notification_message_filter( $message, $user_id ) {
		global $theme_my_login;
		$_message = $theme_my_login->options->get_option( array( 'email', 'user_denial', 'message' ) );
		return empty( $_message ) ? $message : Theme_My_Login_Custom_Email::replace_vars( $_message, $user_id );
	}

	/**
	 * Notify the blog admin of a new user
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param int $user_id User ID
	 * @param string $plaintext_pass Optional. The user's plaintext password
	 */
	function new_user_notification( $user_id, $plaintext_pass = '' ) {
		global $current_site;

		$user = new WP_User( $user_id );

		do_action( 'tml_new_user_notification', $user_id, $plaintext_pass );

		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );

		if ( is_multisite() ) {
			$blogname = $current_site->site_name;
		} else {
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		if ( apply_filters( 'send_new_user_admin_notification', true ) ) {
			$message  = sprintf( __( 'New user registration on your site %s:', 'theme-my-login' ), $blogname ) . "\r\n\r\n";
			$message .= sprintf( __( 'Username: %s', 'theme-my-login' ), $user_login ) . "\r\n\r\n";
			$message .= sprintf( __( 'E-mail: %s', 'theme-my-login' ), $user_email ) . "\r\n";

			$title = sprintf( __( '[%s] New User Registration', 'theme-my-login' ), $blogname );

			$title = apply_filters( 'new_user_admin_notification_title', $title, $user_id );
			$message = apply_filters( 'new_user_admin_notification_message', $message, $user_id );

			$to = apply_filters( 'new_user_admin_notification_mail_to', get_option( 'admin_email' ) );

			@wp_mail( $to, $title, $message );		
		}

		if ( empty( $plaintext_pass ) )
			return;

		if ( apply_filters( 'send_new_user_notification', true ) ) {
			$message  = sprintf( __( 'Username: %s', 'theme-my-login' ), $user_login ) . "\r\n";
			$message .= sprintf( __( 'Password: %s', 'theme-my-login' ), $plaintext_pass ) . "\r\n";
			$message .= wp_login_url() . "\r\n";

			$title = sprintf( __( '[%s] Your username and password', 'theme-my-login' ), $blogname);

			$title = apply_filters( 'new_user_notification_title', $title, $user_id );
			$message = apply_filters( 'new_user_notification_message', $message, $plaintext_pass, $user_id );

			wp_mail( $user_email, $title, $message );
		}
	}

	/**
	 * Notify the blog admin of a user changing password
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param object $user User object
	 */
	function password_change_notification( &$user ) {
		global $current_site;

		$to = apply_filters( 'password_change_notification_mail_to', get_option( 'admin_email' ) );
		// send a copy of password change notification to the admin
		// but check to see if it's the admin whose password we're changing, and skip this
		if ( $user->user_email != $to && apply_filters( 'send_password_change_notification', true ) ) {
			if ( is_multisite() ) {
				$blogname = $current_site->site_name;
			} else {
				// The blogname option is escaped with esc_html on the way into the database in sanitize_option
				// we want to reverse this for the plain text arena of emails.
				$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			}

			$title = sprintf( __( '[%s] Password Lost/Changed' ), $blogname );
			$message = sprintf( __( 'Password Lost and Changed for user: %s' ), $user->user_login ) . "\r\n";

			$title = apply_filters( 'password_change_notification_title', $title, $user->ID );
			$message = apply_filters( 'password_change_notification_message', $message, $user->ID );

			wp_mail( $to, $title, $message );
		}
	}

	/**
	 * Replaces certain user and blog variables in $input string
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $input The input string
	 * @param int $user_id User ID to replace user specific variables
	 * @param array $replacements Misc variables => values replacements
	 * @return string The $input string with variables replaced
	 */
	function replace_vars( $input, $user_id = '', $replacements = array() ) {
		// Get user data
		if ( $user_id )
			$user = get_userdata( $user_id );

		// Get all matches ($matches[0] will be '%value%'; $matches[1] will be 'value')
		preg_match_all( '/%([^%]*)%/', $input, $matches );

		//Avoid deprecated 'siteurl' notice
		$replacements['%siteurl%'] = get_bloginfo( 'url' );
		// Allow %user_ip% variable
		$replacements['%user_ip%'] = $_SERVER['REMOTE_ADDR'];

		// Allow replacements to be filtered
		$replacements = apply_filters( 'tml_custom_email_variables', $replacements, $user_id );

		// Iterate through matches
		foreach ( $matches[0] as $key => $match ) {
			if ( !isset( $replacements[$match] ) ) {	
				if ( isset( $user ) && isset( $user->{$matches[1][$key]} ) ) // Replacement from WP_User object
					$replacements[$match] = ( '%user_pass%' == $match ) ? '' : $user->{$matches[1][$key]};
				else
					$replacements[$match] = get_bloginfo( $matches[1][$key] ); // Replacement from get_bloginfo()
			}
		}

		return str_replace( array_keys( $replacements ), array_values( $replacements ), $input );
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
		// Make sure it's an array
		$options = (array) $options;
		// Assign our options
		$options['email'] = array(
			'new_user' => array(
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
			'retrieve_pass' => array(
				'mail_from' => '',
				'mail_from_name' => '',
				'mail_content_type' => '',
				'title' => '',
				'message' => ''
				),
			'reset_pass' => array(
				'admin_mail_to' => '',
				'admin_mail_from' => '',
				'admin_mail_from_name' => '',
				'admin_mail_content_type' => '',
				'admin_title' => '',
				'admin_message' => '',
				'admin_disable' => 0
				)
			);
		return $options;
	}

	/**
	 * Loads the module
	 *
	 * @since 6.0
	 * @access public
	 */
	function load() {
		add_filter( 'tml_init_options', array( &$this, 'init_options' ) );

		add_filter( 'wp_mail_from', array( &$this, 'mail_from_filter' ) );
		add_filter( 'wp_mail_from_name', array( &$this, 'mail_from_name_filter') );
		add_filter( 'wp_mail_content_type', array( &$this, 'mail_content_type_filter') );

		add_action( 'retrieve_password', array( &$this, 'apply_retrieve_pass_filters' ) );
		add_action( 'password_reset', array( &$this, 'apply_password_reset_filters' ) );
		add_action( 'tml_new_user_notification', array( &$this, 'apply_new_user_filters' ) );

		remove_action( 'tml_new_user_registered', 'wp_new_user_notification', 10, 2 );
		add_action( 'tml_new_user_registered', array( &$this, 'new_user_notification' ), 10, 2 );

		remove_action( 'tml_user_password_changed', 'wp_password_change_notification' );
		add_action( 'tml_user_password_changed', array( &$this, 'password_change_notification' ) );

		add_action( 'register_post', array( &$this, 'apply_user_moderation_notification_filters' ) );
		add_action( 'tml_request_sendactivation', array( &$this, 'apply_user_moderation_notification_filters' ) );
		add_action( 'approve_user', array( &$this, 'apply_user_approval_notification_filters' ) );
		add_action( 'deny_user', array( &$this, 'apply_user_denial_notification_filters' ) );
	}
}

/**
 * Holds the reference to Theme_My_Login_Custom_Email object
 * @global object $theme_my_login_custom_email
 * @since 6.0
 */
$theme_my_login_custom_email = new Theme_My_Login_Custom_Email();

if ( is_admin() )
	include_once( TML_ABSPATH . '/modules/custom-email/admin/custom-email-admin.php' );

endif; // Class exists

?>
