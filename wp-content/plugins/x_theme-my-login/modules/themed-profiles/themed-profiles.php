<?php
/*
Plugin Name: Themed Profiles
Description: Enabling this module will initialize and enable themed profiles. You will then have to configure the settings via the "Themed Profiles" tab.
*/

if ( !class_exists( 'Theme_My_Login_Themed_Profiles' ) ) :
/**
 * Theme My Login Themed Profiles module class
 *
 * Allows users to edit profile on the front-end.
 *
 * @since 6.0
 */
class Theme_My_Login_Themed_Profiles extends Theme_My_Login_Module {
	/**
	 * Redirects "profile.php" to themed profile page
	 *
	 * Callback for "init" hook
	 *
	 * @since 6.0
	 * @access public
	 */
	function init() {
		global $theme_my_login, $current_user, $pagenow;

        if ( is_user_logged_in() && is_admin() ) {
        	$redirect_to = $theme_my_login->get_login_page_link( array( 'action' => 'profile' ) );
			$user_role = reset( $current_user->roles );
			if ( is_multisite() && empty( $user_role ) ) {
				$user_role = 'subscriber';
			}
			if ( 'profile.php' == $pagenow && ! isset( $_REQUEST['page'] ) ) {
                if ( $theme_my_login->options->get_option( array( 'themed_profiles', $user_role, 'theme_profile' ) ) ) {
                	if ( !empty( $_GET ) )
                		$redirect_to = add_query_arg( (array) $_GET, $redirect_to );
					wp_redirect( $redirect_to );
                    exit;
                }
            } else {
            	if ( $theme_my_login->options->get_option( array( 'themed_profiles', $user_role, 'restrict_admin' ) ) ) {
            		if ( ! defined( 'DOING_AJAX' ) ) {
		            	wp_redirect( $redirect_to );
		            	exit;
		            }
                }
            }
        }
	}

	/**
	 * Hides admin bar is admin is restricted
	 *
	 * Callback for "show_admin_bar" hook
	 *
	 * @since 6.2
	 * @access public
	 */
	function show_admin_bar( $show_admin_bar ) {
		global $theme_my_login, $current_user;

		$user_role = reset( $current_user->roles );
		if ( is_multisite() && empty( $user_role ) ) {
			$user_role = 'subscriber';
		}

		if ( $theme_my_login->options->get_option( array( 'themed_profiles', $user_role, 'restrict_admin' ) ) )
			return false;

		return $show_admin_bar;
	}

	/**
	 * Redirects login page to profile if user is logged in
	 *
	 * Callback for "template_redirect" hook
	 *
	 * @since 6.0
	 * @access public
	 */
	function template_redirect() {
		global $theme_my_login;

		if ( $theme_my_login->is_login_page() ) {
			switch ( $theme_my_login->request_action ) {
				case 'profile' :
					// Redirect to login page if not logged in
					if ( !is_user_logged_in() ) {
						$redirect_to = $theme_my_login->get_login_page_link( array( 'reauth' => 1 ) );
						wp_redirect( $redirect_to );
						exit();
					}
					break;
				case 'logout' :
					// Allow logout action
					break;
				case 'register' :
					// Allow register action if multisite
					if ( is_multisite() )
						break;
				default :
					// Redirect to profile for any other action if logged in
					if ( is_user_logged_in() ) {
						$redirect_to = $theme_my_login->get_login_page_link( array( 'action' => 'profile' ) );
						wp_redirect( $redirect_to );
						exit();
					}
			}

			// Remove instance if instance requested
			if ( $theme_my_login->request_instance ) {
				$redirect_to = remove_query_arg( array( 'instance' ) );
				wp_redirect( $redirect_to );
				exit();
			}
		}
	}

	/**
	 * Handles profile action
	 *
	 * Callback for "tml_request_profile" in method Theme_My_Login::the_request()
	 *
	 * @see Theme_My_Login::the_request()
	 * @since 6.0
	 * @access public
	 */
	function profile_action() {
		global $theme_my_login;

		require_once( ABSPATH . 'wp-admin/includes/user.php' );
		require_once( ABSPATH . 'wp-admin/includes/misc.php' );

		define( 'IS_PROFILE_PAGE', true );

		register_admin_color_schemes();

		wp_enqueue_style( 'password-strength', plugins_url( 'theme-my-login/modules/themed-profiles/themed-profiles.css' ) );

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';

		wp_enqueue_script( 'user-profile', admin_url( "js/user-profile$suffix.js" ), array( 'jquery' ), '', true );
		wp_enqueue_script( 'password-strength-meter', admin_url( "js/password-strength-meter$suffix.js" ), array( 'jquery' ), '', true );
		wp_localize_script( 'password-strength-meter', 'pwsL10n', array(
			'empty' => __( 'Strength indicator', 'theme-my-login' ),
			'short' => __( 'Very weak', 'theme-my-login' ),
			'bad' => __( 'Weak', 'theme-my-login' ),
			/* translators: password strength */
			'good' => _x( 'Medium', 'password strength', 'theme-my-login' ),
			'strong' => __( 'Strong', 'theme-my-login' ),
			'l10n_print_after' => 'try{convertEntities(pwsL10n);}catch(e){};'
		) );

		$current_user = wp_get_current_user();

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			check_admin_referer( 'update-user_' . $current_user->ID );

			if ( !current_user_can( 'edit_user', $current_user->ID ) )
				wp_die( __( 'You do not have permission to edit this user.', 'theme-my-login' ) );

			do_action( 'personal_options_update', $current_user->ID );

			$errors = edit_user( $current_user->ID );

			if ( !is_wp_error( $errors ) ) {
				$redirect = add_query_arg( array( 'updated' => 'true' ) );
				wp_redirect( $redirect );
				exit();
			}

			$theme_my_login->errors = $errors;
		}

		if ( isset( $_GET['updated'] ) && 'true' == $_GET['updated'] )
			$theme_my_login->errors->add( 'profile_updated', __( 'Profile updated.', 'theme-my-login' ), 'message' );
	}

	/**
	 * Outputs profile form HTML
	 *
	 * Callback for "tml_template_profile" hook in method Theme_My_login_Template::display()
	 *
	 * @see Theme_My_Login_Template::display()
	 * @since 6.0
	 * @access public
	 *
	 * @param object $template Reference to $theme_my_login_template object
	 */
	function get_profile_form( &$template ) {
		global $current_user, $profileuser, $_wp_admin_css_colors, $wp_version;

		$current_user = wp_get_current_user();
		$profileuser = get_user_to_edit( $current_user->ID );

		$role = reset( $profileuser->roles );

		$_template = array();
		// Allow template override via shortcode or template tag args
		if ( !empty( $template->options['profile_template'] ) )
			$_template[] = $template->options['profile_template'];
		// Role template
		if ( !empty( $template->options["profile_template_$role"] ) )
			$_template[] = $template->options["profile_template_$role"];
		$_template[] = "profile-form-$role.php";
		// Default template
		$_template[] = 'profile-form.php';
		// Load template
		$template->get_template( $_template, '', true, compact( 'current_user', 'profileuser', '_wp_admin_css_colors', 'wp_version' ) );
	}

	/**
	 * Changes links from "profile.php" to themed profile page
	 *
	 * Callback for "site_url" hook
	 *
	 * @see site_url()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $url The generated link
	 * @param string $path The specified path
	 * @param string $orig_scheme The original connection scheme
	 * @return string The filtered link
	 */
	function site_url( $url, $path, $orig_scheme = '' ) {
		global $theme_my_login, $current_user, $pagenow;

		if ( 'profile.php' != $pagenow && strpos( $url, 'profile.php' ) !== false ) {
			$user_role = reset( $current_user->roles );
			if ( is_multisite() && empty( $user_role ) ) {
				$user_role = 'subscriber';
			}

			if ( $user_role && !$theme_my_login->options->get_option( array( 'themed_profiles', $user_role, 'theme_profile' ) ) )
				return $url;
					
			$parsed_url = parse_url( $url );
			$url = $theme_my_login->get_login_page_link( array( 'action' => 'profile' ) );
			if ( isset( $parsed_url['query'] ) ) {
				wp_parse_str( $parsed_url['query'], $r );
				foreach ( $r as $k => $v ) {
					if ( strpos( $v, ' ' ) !== false )
						$r[$k] = rawurlencode( $v );
				}
				$url = add_query_arg( $r, $url );
			}
		}
		return $url;
	}

	/**
	 * Changes the page title for themed profile page
	 *
	 * Callback for "tml_title" hook in method Theme_My_Login_Template::get_page_title()
	 *
	 * @see Theme_My_Login_Template::get_page_title()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $title The current title
	 * @param string $action The requested action
	 * @return string The filtered title
	 */
	function tml_title( $title, $action ) {
		global $theme_my_login;
		if ( 'profile' == $action && is_user_logged_in() && '' == $theme_my_login->request_instance )
			$title = __( 'Your Profile', 'theme-my-login' );
		return $title;
	}

	/**
	 * Initializes options for this module
	 *
	 * Callback for "tml_init_options" hook in method Theme_My_Login::init_options()
	 *
	 * @see Theme_My_Login::init_options()
	 * @since 6.2
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

		$options['themed_profiles'] = array();
		foreach ( $wp_roles->get_names() as $role => $label ) {
			if ( 'pending' == $role )
				continue;
			$options['themed_profiles'][$role] = array(
				'theme_profile' => 1,
				'restrict_admin' => 0
			);
		}
		return $options;
	}

	/**
	 * Adds filters to site_url() and admin_url()
	 *
	 * Callback for "tml_modules_loaded" in file "theme-my-login.php"
	 *
	 * @since 6.0
	 * @access public
	 */
	function modules_loaded() {
		add_filter( 'site_url', array( &$this, 'site_url' ), 10, 3 );
		add_filter( 'admin_url', array( &$this, 'site_url' ), 10, 2 );
	}

	/**
	 * Loads the module
	 *
	 * @since 6.0
	 * @access public
	 */
	function load() {
		// Load
		add_action( 'tml_modules_loaded', array( &$this, 'modules_loaded' ) );
		add_filter( 'tml_init_options', array( &$this, 'init_options' ) );
		add_filter( 'tml_title', array( &$this, 'tml_title' ), 10, 2 );

		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'template_redirect', array( &$this, 'template_redirect' ) );
		add_filter( 'show_admin_bar', array( &$this, 'show_admin_bar' ) );

		add_action( 'tml_request_profile', array( &$this, 'profile_action' ) );
		add_action( 'tml_display_profile', array( &$this, 'get_profile_form' ) );
	}
}

/**
 * Holds the reference to Theme_My_Login_Themed_Profiles object
 * @global object $theme_my_login_themed_profiles
 * @since 6.0
 */
$theme_my_login_themed_profiles = new Theme_My_Login_Themed_Profiles();

if ( is_admin() )
	include_once( TML_ABSPATH . '/modules/themed-profiles/admin/themed-profiles-admin.php' );

endif; // Class exists

?>
