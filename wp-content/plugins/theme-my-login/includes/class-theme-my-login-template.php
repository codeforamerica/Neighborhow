<?php
/**
 * Holds the Theme My Login template class
 *
 * @package Theme My Login
 * @subpackage Template
 */

if ( !class_exists( 'Theme_My_Login_Template' ) ) :
/*
 * Theme My Login template class
 *
 * This class contains properties and methods common to displaying output.
 *
 * @since 6.0
 */
class Theme_My_Login_Template {
	/**
	 * Holds this instance
	 *
	 * @since 6.0
	 * @access public
	 * @var int
	 */
	var $instance;

	/**
	 * Holds this instance action
	 *
	 * @since 6.0
	 * @access public
	 * @var string
	 */
	var $action;

	/**
	 * Set if current instance is active
	 *
	 * @since 6.0
	 * @access public
	 * @var bool
	 */
	var $is_active = false;

	/**
	 * Holds instance specific template options
	 *
	 * @since 6.0
	 * @access public
	 * @var array
	 */
	var $options = array();

	/**
	 * Holds instance specific template errors
	 *
	 * @since 6.0
	 * @access public
	 * @var object
	 */
	var $errors;

	/**
	 * Displays output according to current action
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @return string HTML output
	 */
	function display( $action = '' ) {
		if ( empty( $action ) )
			$action = $this->action;

		ob_start();
		echo $this->options['before_widget'];
		if ( $this->options['show_title'] )
			echo $this->options['before_title'] . $this->get_title( $action ) . $this->options['after_title'] . "\n";
		// Is there a specified template?
		if ( has_action( 'tml_display_' . $action ) ) {
			do_action_ref_array( 'tml_display_' . $action, array( &$this ) );
		} else {
			$template = array();
			if ( is_user_logged_in() ) {
				if ( !empty( $this->options['user_template'] ) )
					$template[] = $this->options['user_template'];
				$template[] = 'user-panel.php';
			} else {
				switch ( $action ) {
					case 'lostpassword':
					case 'retrievepassword':
						if ( !empty( $this->options['lostpassword_template'] ) )
							$template[] = $this->options['lostpassword_template'];
						$template[] = 'lostpassword-form.php';
						break;
					case 'resetpass':
					case 'rp':
						if ( !empty( $this->options['resetpass_template'] ) )
							$template[] = $this->options['resetpass_template'];
						$template[] = 'resetpass-form.php';
						break;
					case 'register':
						if ( !empty( $this->options['register_template'] ) )
							$template[] = $this->options['register_template'];
						$template[] = 'register-form.php';
						break;
					case 'login':
					default :
						if ( !empty( $this->options['login_template'] ) )
							$template[] = $this->options['login_template'];
						$template[] = 'login-form.php';
				}
			}
			$this->get_template( $template );
		}
		echo $this->options['after_widget'] . "\n";
		$output = ob_get_contents();
		ob_end_clean();
		return apply_filters_ref_array( 'tml_display', array( $output, $action, &$this ) );
	}

	/**
	 * Returns action title
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $action The action to retrieve. Defaults to current action.
	 * @return string Title of $action
	 */
	function get_title( $action = '' ) {
		if ( empty( $action ) )
			$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'login';

		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();
			$title = sprintf( __( 'Welcome, %s', 'theme-my-login' ), $user->display_name );
		} else {
			switch ( $action ) {
				case 'register':
					$title = __( 'Register', 'theme-my-login' );
					break;
				case 'lostpassword':
				case 'retrievepassword':
				case 'resetpass':
				case 'rp':
					$title = __( 'Lost Password', 'theme-my-login' );
					break;
				case 'login':
				default:
					$title = __( 'Log In', 'theme-my-login' );
			}
		}
		return apply_filters( 'tml_title', $title, $action );
	}

	/**
	 * Outputs action title
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $action The action to retieve. Defaults to current action.
	 */
	function the_title( $action = '' ) {
		echo $this->get_title( $action );
	}

	/**
	 * Returns plugin errors
	 *
	 * @since 6.0
	 * @access public
	 */
	function get_errors() {
		global $theme_my_login, $error;

		$wp_error =& $theme_my_login->errors;

		if ( empty( $wp_error ) )
			$wp_error = new WP_Error();

		// Incase a plugin uses $error rather than the $errors object
		if ( !empty( $error ) ) {
			$wp_error->add('error', $error);
			unset($error);
		}

		$output = '';
		if ( $this->is_active ) {
			if ( $wp_error->get_error_code() ) {
				$errors = '';
				$messages = '';
				foreach ( $wp_error->get_error_codes() as $code ) {
					$severity = $wp_error->get_error_data( $code );
					foreach ( $wp_error->get_error_messages( $code ) as $error ) {
						if ( 'message' == $severity )
							$messages .= '    ' . $error . "<br />\n";
						else
							$errors .= '    ' . $error . "<br />\n";
					}
				}
				if ( !empty( $errors ) )
					$output .= '<p class="error">' . apply_filters( 'login_errors', $errors ) . "</p>\n";
				if ( !empty( $messages ) )
					$output .= '<p class="message">' . apply_filters( 'login_messages', $messages ) . "</p>\n";
			}
		}
		return $output;
	}

	/**
	 * Prints plugin errors
	 *
	 * @since 6.0
	 * @access public
	 */
	function the_errors() {
		echo $this->get_errors();
	}

	/**
	 * Returns requested action URL
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $action Action to retrieve
	 * @param int|string $instance Optionally add an instance to the URL
	 * @return string The requested action URL
	 */
	function get_action_url( $action = 'login', $instance = '' ) {
		global $theme_my_login;

		if ( empty( $instance ) )
			$instance = $this->instance;

		if ( ( isset( $this->options[$action . '_widget'] ) && !$this->options[$action . '_widget'] ) || $theme_my_login->is_login_page() ) {
			$url = $theme_my_login->get_login_page_link( 'action=' . $action );
		} else {
			if ( empty( $instance ) )
				$url = Theme_My_Login::get_current_url( array( 'action' => $action ) );
			else
				$url = Theme_My_Login::get_current_url( array( 'action' => $action, 'instance' => $instance ) );
		}

		// Respect FORCE_SSL_LOGIN
		if ( 'login' == $action && force_ssl_login() )
			$url = preg_replace( '|^http://|', 'https://', $url );

		return apply_filters( 'tml_action_url', $url, $action, $instance );
	}

	/**
	 * Outputs requested action URL
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $action Action to retrieve
	 * @param int|string $instance Optionally add an instance to the URL
	 */
	function the_action_url( $action = 'login', $instance = '' ) {
		echo esc_url( $this->get_action_url( $action, $instance ) );
	}

	/**
	 * Returns the action links
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param array $args Optionally specify which actions to include/exclude. By default, all are included.
	 */
	function get_action_links( $args = '' ) {
		$args = wp_parse_args( $args, array( 'login' => true, 'register' => true, 'lostpassword' => true ) );
		$action_links = array();
		if ( $args['login'] && $this->options['show_log_link'] )
			$action_links[] = array( 'title' => $this->get_title( 'login' ), 'url' => $this->get_action_url( 'login' ) );
		if ( $args['register'] && $this->options['show_reg_link'] && get_option( 'users_can_register' ) )
			$action_links[] = array( 'title' => $this->get_title( 'register' ), 'url' => $this->get_action_url( 'register' ) );
		if ( $args['lostpassword'] && $this->options['show_pass_link'] )
			$action_links[] = array( 'title' => $this->get_title( 'lostpassword' ), 'url' => $this->get_action_url( 'lostpassword' ) );
		return apply_filters( 'tml_action_links', $action_links, $args );
	}

	/**
	 * Outputs the action links
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param array $args Optionally specify which actions to include/exclude. By default, all are included.
	 */
	function the_action_links( $args = '' ) {
		if ( $action_links = $this->get_action_links( $args ) ) {
			echo '<ul class="tml-action-links">' . "\n";
			foreach ( (array) $action_links as $link ) {
				echo '<li><a href="' . esc_url( $link['url'] ) . '" rel="nofollow">' . esc_html( $link['title'] ) . '</a></li>' . "\n";
			}
			echo '</ul>' . "\n";
		}
	}

	/**
	 * Returns logged-in user links
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @return array Logged-in user links
	 */
	function get_user_links() {
		$user_links = array(
			array( 'title' => __( 'Dashboard', 'theme-my-login' ), 'url' => admin_url() ),
			array( 'title' => __( 'Profile', 'theme-my-login' ), 'url' => admin_url( 'profile.php' ) )
			);
		return apply_filters( 'tml_user_links', $user_links );
	}

	/**
	 * Outputs logged-in user links
	 *
	 * @since 6.0
	 * @access public
	 */
	function the_user_links() {
		echo '<ul class="tml-user-links">';
		if ( $user_links = $this->get_user_links() ) {
			foreach ( (array) $user_links as $link ) {
				echo '<li><a href="' . esc_url( $link['url'] ) . '">' . esc_html( $link['title'] ) . '</a></li>' . "\n";
			}
		}
		echo '<li><a href="' . wp_logout_url() . '">' . __( 'Log out', 'theme-my-login' ) . '</a></li>' . "\n";
		echo '</ul>';
	}

	/**
	 * Displays user avatar
	 *
	 * @since 6.0
	 * @access public
	 */
	function the_user_avatar( $size = '' ) {
		global $current_user;
		if ( empty( $size ) )
			$size = $this->options['gravatar_size'];
		echo get_avatar( $current_user->ID, $size );
	}

	/**
	 * Returns template message for requested action
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $action Action to retrieve
	 * @return string The requested template message
	 */
	function get_action_template_message( $action = '' ) {
		switch ( $action ) {
			case 'register':
				$message = __( 'Register For This Site', 'theme-my-login' );
				break;
			case 'lostpassword':
				$message = __( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'theme-my-login' );
				break;
			case 'resetpass':
				$message = __( 'Enter your new password below.', 'theme-my-login' );
				break;
			default:
				$message = '';
		}
		$message = apply_filters( 'login_message', $message );

		return apply_filters( 'tml_action_template_message', $message, $action );
	}

	/**
	 * Outputs template message for requested action
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $action Action to retrieve
	 * @param string $before_message Text/HTML to add before the message
	 * @param string $after_message Text/HTML to add after the message
	 */
	function the_action_template_message( $action = 'login', $before_message = '<p class="message">', $after_message = '</p>' ) {
		if ( $message = $this->get_action_template_message( $action ) )
			echo $before_message . $message . $after_message;
	}

	/**
	 * Locates specified template
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string|array $template_names The template(s) to locate
	 * @param string $template_path Directory of default template
	 * @param bool $load If true, the template will be included if found
	 * @param array $args Array of extra variables to make available to template
	 * @return string|bool Template path if found, false if not
	 */
	function get_template( $template_names, $template_path = '', $load = true, $args = array() ) {
		global $theme_my_login;

		// Shothand reference to this
		$template =& $this;

		// Easy access to current user
		$current_user = wp_get_current_user();

		if ( empty( $template_path ) )
			$template_path = TML_ABSPATH . '/templates';

		extract( apply_filters_ref_array( 'tml_template_args', array( $args, &$this ) ) );

		if ( !is_array( $template_names ) )
			$template_names = array( $template_names );

		if ( !$found_template = locate_template( $template_names ) ) {
			foreach ( $template_names as $template_name ) {
				if ( file_exists( rtrim( $template_path, '/' ) . '/' . $template_name ) ) {
					$found_template = rtrim( $template_path, '/' ) . '/' . $template_name;
					break;
				}
			}
		}

		$found_template = apply_filters_ref_array( 'tml_template', array( $found_template, $template_names, $template_path, &$this ) );

		if ( $load && $found_template ) {
			include( $found_template );
		}

		return $found_template;
	}

	/**
	 * Returns the proper redirect URL according to action
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $action The action
	 * @return string The redirect URL
	 */
	function get_redirect_url( $action = '' ) {
		if ( empty( $action ) )
			$action = $this->action;

		$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';

		switch ( $action ) {
			case 'lostpassword' :
			case 'retrievepassword' :
				$url = apply_filters( 'lostpassword_redirect', !empty( $redirect_to ) ? $redirect_to : Theme_My_Login::get_current_url( 'checkemail=confirm' ) );
				break;
			case 'register' :
				$url = apply_filters( 'registration_redirect', !empty( $redirect_to ) ? $redirect_to : Theme_My_Login::get_current_url( 'checkemail=registered' ) );
				break;
			case 'login' :
			default :
				$url = apply_filters( 'login_redirect', !empty( $redirect_to ) ? $redirect_to : admin_url(), $redirect_to, null );
		}
		return apply_filters( 'tml_redirect_url', $url, $action );
	}

	/**
	 * Outputs redirect URL
	 *
	 * @since 6.0
	 * @access public
	 */
	function the_redirect_url() {
		echo esc_attr( $this->get_redirect_url() );
	}

	/**
	 * Outputs current template instance ID
	 *
	 * @since 6.0
	 * @access public
	 */
	function the_instance() {
		echo esc_attr( $this->instance );
	}

	/**
	 * Returns requested $value
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $value The value to retrieve
	 * @return string|bool The value if it exists, false if not
	 */
	function get_posted_value( $value ) {
		if ( $this->is_active && isset( $_REQUEST[$value] ) )
			return stripslashes( $_REQUEST[$value] );
		return false;
	}

	/**
	 * Outputs requested value
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $value The value to retrieve
	 */
	function the_posted_value( $value ) {
		echo esc_attr( $this->get_posted_value( $value ) );
	}

	/**
	 * Merges default template options with instance template options
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param array $options Instance options
	 */
	function load_options( $options = array() ) {
		$this->options = wp_parse_args( $options, array(
			'instance' => '',
			'default_action' => '',
			'login_template' => '',
			'register_template' => '',
			'lostpassword_template' => '',
			'resetpass_template' => '',
			'user_template' => '',
			'show_title' => true,
			'show_log_link' => true,
			'show_reg_link' => true,
			'show_pass_link' => true,
			'register_widget' => false,
			'lostpassword_widget' => false,
			'logged_in_widget' => true,
			'show_gravatar' => true,
			'gravatar_size' => 50,
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => ''
		) );
	}

	/**
	 * PHP4 style constructor
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param array $options Instance options
	 */
	function Theme_My_Login_Template( $options = '' ) {
		$this->__construct( $options );
	}

	/**
	 * PHP5 style constructor
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param array $options Instance options
	 */
	function __construct( $options = '' ) {
		global $theme_my_login;

		$this->load_options( $options );
		
		$this->action = isset( $this->options['default_action'] ) ? $this->options['default_action'] : '';
		$this->instance = $this->options['instance'];
		if ( $theme_my_login->request_instance == $this->instance ) {
			$this->is_active = true;
			$this->action = $theme_my_login->request_action;
		}
	}
}

endif; // Class exists

?>
