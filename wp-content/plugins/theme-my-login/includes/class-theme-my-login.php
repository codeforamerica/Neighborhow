<?php
/**
 * Holds the Theme My Login class
 *
 * @package Theme My Login
 */

if ( !class_exists( 'Theme_My_Login' ) ) :
/*
 * Theme My Login class
 *
 * This class contains properties and methods common to the front-end.
 *
 * @since 6.0
 */
class Theme_My_Login {
	/**
	 * Holds options object
	 *
	 * @since 6.1
	 * @access public
	 * @var object
	 */
	var $options;

	/**
	 * Holds errors object
	 *
	 * @since 6.0
	 * @access public
	 * @var object
	 */
	var $errors;

	/**
	 * Holds total instances of TML
	 *
	 * @since 6.0
	 * @access public
	 * @var int
	 */
	var $count = 0;

	/**
	 * Holds current instance being requested
	 *
	 * @since 6.0
	 * @access public
	 * @var int
	 */
	var $request_instance;

	/**
	 * Holds current action being requested
	 *
	 * @since 6.0
	 * @access public
	 * @var string
	 */
	var $request_action;

	/**
	 * PHP4 style constructor
	 *
	 * @since 6.0
	 * @access public
	 */
	function Theme_My_Login() {
		$this->__construct();
	}

	/**
	 * PHP5 constructor
	 *
	 * @since 6.0
	 * @access public
	 */
	function __construct() {
		$this->request_action = isset( $_REQUEST['action'] ) ? sanitize_user( $_REQUEST['action'], true ) : '';
		$this->request_instance = isset( $_REQUEST['instance'] ) ? sanitize_user( $_REQUEST['instance'], true ) : '';

		$this->init_options();

		// Load options again to allow modules to tap in
		add_action( 'tml_modules_loaded', array( &$this, 'init_options' ), 0 );

		add_filter( 'rewrite_rules_array', array( &$this, 'rewrite_rules_array' ) );

		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'widgets_init', array( &$this, 'widgets_init' ) );
		add_action( 'parse_request', array( &$this, 'parse_request' ) );

		add_action( 'wp', array( &$this, 'wp' ) );

		add_action( 'wp_head', array( &$this, 'login_head' ) );
		add_action( 'wp_print_footer_scripts', array( &$this, 'print_footer_scripts' ) );

		add_filter( 'the_title', array( &$this, 'the_title' ), 10, 2 );
		add_filter( 'single_post_title', array( &$this, 'single_post_title' ) );
		add_filter( 'wp_setup_nav_menu_item', array( &$this, 'wp_setup_nav_menu_item' ) );

		add_filter( 'site_url', array( &$this, 'site_url' ), 10, 3 );
		add_filter( 'page_link', array( &$this, 'page_link' ), 10, 2 );
		add_filter( 'tml_redirect_url', array( &$this, 'tml_redirect_url' ), 10, 2 );

		add_filter( 'wp_list_pages_excludes', array( &$this, 'wp_list_pages_excludes' ) );
		add_filter( 'wp_list_pages', array( &$this, 'wp_list_pages' ) );

		add_action( 'wp_authenticate', array( &$this, 'wp_authenticate' ) );

		add_action( 'tml_new_user_registered', 'wp_new_user_notification', 10, 2 );
		add_action( 'tml_user_password_changed', 'wp_password_change_notification' );

		add_shortcode( 'theme-my-login', array( &$this, 'shortcode' ) );
	}

	/**
	 * Initializes plugin options object
	 *
	 * @since 6.1
	 * @access public
	 */
	function init_options() {
		$this->options = new WDBJ_Plugin_Options( 'theme_my_login', apply_filters( 'tml_init_options', array(
			'page_id' => 0,
			'show_page' => 1,
			'enable_css' => 1,
			'email_login' => 1,
			'active_modules' => array(),
			'permalinks' => array(),
			'initial_nag' => 1
		) ) );
	}

	/**
	 * Handles permalink rewrite rules
	 *
	 * @since 6.2.2
	 * @access public
	 *
	 * @param array $rules Rewrite rules
	 * @return array Rewrite rules
	 */
	function rewrite_rules_array( $rules ) {
		if ( defined( 'WP_INSTALLING' ) )
			return $rules;

		$page =& get_page( $this->options->get_option( 'page_id' ) );

		$page_uri = get_page_uri( $page->ID );

		$tml_rules = array();
		foreach ( $this->options->get_option( 'permalinks', array() ) as $action => $slug ) {
			if ( !empty( $slug ) ) {
				$slug = str_replace( $page->post_name, $slug, $page_uri );
				$tml_rules["{$slug}/?$"] = "index.php?page_id={$page->ID}&action={$action}";
			}
		}
		return array_merge( $tml_rules, $rules );
	}

	/**
	 * Initializes the plugin
	 *
	 * @since 6.0
	 * @access public
	 */
	function init() {
		global $wp;

		load_plugin_textdomain( 'theme-my-login', '', TML_DIRNAME . '/language' );

		$this->errors = new WP_Error();

		if ( $this->options->get_option( 'enable_css' ) )
			wp_enqueue_style( 'theme-my-login', Theme_My_Login::get_stylesheet(), false, $this->options->get_option( 'version' ) );

		$wp->add_query_var( 'action' );
	}

	/**
	 * Registers the widget
	 *
	 * @since 6.0
	 * @access public
	 */
	function widgets_init() {
		if ( class_exists( 'Theme_My_Login_Widget' ) )
			register_widget( 'Theme_My_Login_Widget' );
	}

	/**
	 * Determine if specified page is the login page
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param int $page_id Optional. The page ID (Defaults to current page)
	 */
	function is_login_page( $page_id = '' ) {
		if ( empty( $page_id ) ) {
			global $wp_query;
			if ( $wp_query->is_page )
				$page_id = $wp_query->get_queried_object_id();
		}

		$is_login_page = ( $page_id == $this->options->get_option( 'page_id' ) );

		return apply_filters( 'tml_is_login_page', $is_login_page, $page_id );
	}

	/**
	 * Proccesses the request
	 *
	 * Callback for "parse_request" hook in WP::parse_request()
	 *
	 * @see WP::parse_request()
	 * @since 6.0
	 * @access public
	 */
	function parse_request( &$wp ) {
		$errors =& $this->errors;
		$action =& $this->request_action;
		if ( isset( $wp->query_vars['action'] ) )
			$action = $wp->query_vars['action'];
		$instance =& $this->request_instance;

		if ( is_admin() )
			return;

		do_action_ref_array( 'tml_request', array( &$this ) );

		// allow plugins to override the default actions, and to add extra actions if they want
		do_action( 'login_form_' . $action );

		if ( has_action( 'tml_request_' . $action ) ) {
			do_action_ref_array( 'tml_request_' . $action, array( &$this ) );
		} else {
			$http_post = ( 'POST' == $_SERVER['REQUEST_METHOD'] );
			switch ( $action ) {
				case 'postpass' :
					global $wp_hasher;

					if ( empty( $wp_hasher ) ) {
						require_once( ABSPATH . 'wp-includes/class-phpass.php' );
						// By default, use the portable hash from phpass
						$wp_hasher = new PasswordHash( 8, true );
					}

					// 10 days
					setcookie( 'wp-postpass_' . COOKIEHASH, $wp_hasher->HashPassword( stripslashes( $_POST['post_password'] ) ), time() + 864000, COOKIEPATH );

					wp_safe_redirect( wp_get_referer() );
					exit;

					break;
				case 'logout' :
					check_admin_referer( 'log-out' );

					$user = wp_get_current_user();

					wp_logout();

					$redirect_to = apply_filters( 'logout_redirect', site_url( 'wp-login.php?loggedout=true' ), isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '', $user );
					wp_safe_redirect( $redirect_to );
					exit();
					break;
				case 'lostpassword' :
				case 'retrievepassword' :
					if ( $http_post ) {
						$errors = $this->retrieve_password();
						if ( !is_wp_error( $errors ) ) {
							$redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : Theme_My_Login::get_current_url( 'checkemail=confirm' );
							if ( !empty( $instance ) )
								$redirect_to = add_query_arg( 'instance', $instance, $redirect_to );
							wp_safe_redirect( $redirect_to );
							exit();
						}
					}

					if ( isset( $_REQUEST['error'] ) && 'invalidkey' == $_REQUEST['error'] )
						$errors->add( 'invalidkey', __( 'Sorry, that key does not appear to be valid.', 'theme-my-login' ) );

					do_action( 'lost_password' );
					break;
				case 'resetpass' :
				case 'rp' :
					$user = $this->check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );

					if ( is_wp_error( $user ) ) {
						wp_redirect( Theme_My_Login::get_current_url( 'action=lostpassword&error=invalidkey' ) );
						exit;
					}

					$errors = '';

					if ( isset( $_POST['pass1'] ) && $_POST['pass1'] != $_POST['pass2'] ) {
						$errors = new WP_Error( 'password_reset_mismatch', __( 'The passwords do not match.', 'theme-my-login' ) );
					} elseif ( isset( $_POST['pass1'] ) && !empty( $_POST['pass1'] ) ) {
						$this->reset_password( $user, $_POST['pass1'] );

						$redirect_to = Theme_My_Login::get_current_url( 'resetpass=complete' );
						if ( isset( $_REQUEST['instance'] ) & !empty( $_REQUEST['instance'] ) )
							$redirect_to = add_query_arg( 'instance', $_REQUEST['instance'], $redirect_to );
						wp_safe_redirect( $redirect_to );
						exit();
					}

					wp_enqueue_script( 'utils' );
					wp_enqueue_script( 'user-profile' );
					break;
				case 'register' :
					if ( !get_option( 'users_can_register' ) ) {
						wp_redirect( Theme_My_Login::get_current_url( 'registration=disabled' ) );
						exit();
					}

					$user_login = '';
					$user_email = '';
					if ( $http_post ) {
						$user_login = $_POST['user_login'];
						$user_email = $_POST['user_email'];

						$errors = Theme_My_Login::register_new_user( $user_login, $user_email );
						if ( !is_wp_error( $errors ) ) {
							$redirect_to = !empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : Theme_My_Login::get_current_url( 'checkemail=registered' );
							if ( !empty( $instance ) )
								$redirect_to = add_query_arg( 'instance', $instance, $redirect_to );
							$redirect_to = apply_filters( 'register_redirect', $redirect_to );
							wp_safe_redirect( $redirect_to );
							exit();
						}
					}
					break;
				case 'login' :
				default:
					$secure_cookie = '';
					$interim_login = isset( $_REQUEST['interim-login'] );

					// If the user wants ssl but the session is not ssl, force a secure cookie.
					if ( !empty( $_POST['log'] ) && !force_ssl_admin() ) {
						$user_name = sanitize_user( $_POST['log'] );
						if ( $user = get_user_by( 'login', $user_name ) ) {
							if ( get_user_option( 'use_ssl', $user->ID ) ) {
								$secure_cookie = true;
								force_ssl_admin( true );
							}
						}
					}

					if ( !empty( $_REQUEST['redirect_to'] ) ) {
						$redirect_to = $_REQUEST['redirect_to'];
						// Redirect to https if user wants ssl
						if ( $secure_cookie && false !== strpos( $redirect_to, 'wp-admin' ) )
							$redirect_to = preg_replace( '|^http://|', 'https://', $redirect_to );
					} else {
						$redirect_to = admin_url();
					}

					$reauth = empty( $_REQUEST['reauth'] ) ? false : true;

					// If the user was redirected to a secure login form from a non-secure admin page, and secure login is required but secure admin is not, then don't use a secure
					// cookie and redirect back to the referring non-secure admin page.  This allows logins to always be POSTed over SSL while allowing the user to choose visiting
					// the admin via http or https.
					if ( !$secure_cookie && is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos( $redirect_to, 'https' ) ) && ( 0 === strpos( $redirect_to, 'http' ) ) )
						$secure_cookie = false;

					if ( $http_post && isset( $_POST['log'] ) ) {

						// Set a cookie now to see if they are supported by the browser.
						setcookie( TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN );
						if ( SITECOOKIEPATH != COOKIEPATH )
							setcookie( TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN );

						$user = wp_signon( '', $secure_cookie );

						$redirect_to = apply_filters( 'login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '', $user );

						if ( !is_wp_error( $user ) && !$reauth ) {
							if ( ( empty( $redirect_to ) || $redirect_to == 'wp-admin/' || $redirect_to == admin_url() ) ) {
								// If the user doesn't belong to a blog, send them to user admin. If the user can't edit posts, send them to their profile.
								if ( is_multisite() && !get_active_blog_for_user( $user->ID ) && !is_super_admin( $user->ID ) )
									$redirect_to = user_admin_url();
								elseif ( is_multisite() && !$user->has_cap( 'read' ) )
									$redirect_to = get_dashboard_url( $user->ID );
								elseif ( !$user->has_cap( 'edit_posts' ) )
									$redirect_to = admin_url( 'profile.php' );
							}
							wp_safe_redirect( $redirect_to );
							exit();
						}

						$errors = $user;
					}

					$this->redirect_to = $redirect_to;

					// Clear errors if loggedout is set.
					if ( !empty( $_GET['loggedout'] ) || $reauth )
						$errors = new WP_Error();

					// If cookies are disabled we can't log in even with a valid user+pass
					if ( isset( $_POST['testcookie'] ) && empty( $_COOKIE[TEST_COOKIE] ) )
						$errors->add( 'test_cookie', __( '<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href="http://www.google.com/cookies.html">enable cookies</a> to use WordPress.', 'theme-my-login' ) );

					// Some parts of this script use the main login form to display a message
					if		( isset( $_GET['loggedout'] ) && true == $_GET['loggedout'] )
						$errors->add( 'loggedout', __( 'You are now logged out.', 'theme-my-login' ), 'message' );
					elseif	( isset( $_GET['registration'] ) && 'disabled' == $_GET['registration'] )
						$errors->add( 'registerdisabled', __( 'User registration is currently not allowed.', 'theme-my-login' ) );
					elseif	( isset( $_GET['checkemail'] ) && 'confirm' == $_GET['checkemail'] )
						$errors->add( 'confirm', __( 'Check your e-mail for the confirmation link.', 'theme-my-login' ), 'message' );
					elseif ( isset( $_GET['resetpass'] ) && 'complete' == $_GET['resetpass'] )
						$errors->add( 'password_reset', __( 'Your password has been reset.', 'theme-my-login' ), 'message' );
					elseif	( isset( $_GET['checkemail'] ) && 'registered' == $_GET['checkemail'] )
						$errors->add( 'registered', __( 'Registration complete. Please check your e-mail.', 'theme-my-login' ), 'message' );
					elseif	( $interim_login )
						$errors->add( 'expired', __( 'Your session has expired. Please log-in again.', 'theme-my-login' ), 'message' );
					elseif ( strpos( $redirect_to, 'about.php?updated' ) )
						$errors->add('updated', __( '<strong>You have successfully updated WordPress!</strong> Please log back in to experience the awesomeness.' ), 'message' );
					elseif	( $reauth )
						$errors->add( 'reauth', __( 'Please log in to continue.', 'theme-my-login' ), 'message' );

					// Clear any stale cookies.
					if ( $reauth )
						wp_clear_auth_cookie();
					break;
			} // end switch
		} // endif has_filter()
	}

	/**
	 * Used to add/remove filters from login page
	 *
	 * @since 6.1.1
	 * @access public
	 */
	function wp() {
		global $wp_version;

		if ( $this->is_login_page() ) {
			do_action( 'login_init' );

			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
			remove_action( 'wp_head', 'rsd_link' );
			remove_action( 'wp_head', 'wlwmanifest_link' );
			remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
			remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
			remove_action( 'wp_head', 'rel_canonical' );

			// Don't index any of these forms
			if ( version_compare( $wp_version, '3.3', '<' ) ) {
				add_filter( 'pre_option_blog_public', '__return_zero' );
				add_action( 'login_head', 'noindex' );
			} else {
				add_action( 'login_head', 'wp_no_robots' );
			}

			if ( force_ssl_admin() && !is_ssl() ) {
				if ( 0 === strpos( $_SERVER['REQUEST_URI'], 'http' ) ) {
					wp_redirect( preg_replace( '|^http://|', 'https://', $_SERVER['REQUEST_URI'] ) );
					exit();
				} else {
					wp_redirect( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
					exit();
				}
			}
		}
	}

	/**
	 * Returns link for login page
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string|array $query Optional. Query arguments to add to link
	 * @return string Login page link with optional $query arguments appended
	 */
	function get_login_page_link( $query = '' ) {
		global $wp_rewrite;

		$q = wp_parse_args( $query );

		$page = get_page( $this->options->get_option( 'page_id' ) );

		$link = $wp_rewrite->get_page_permastruct();
		if ( !empty( $link ) ) {
			$link = str_replace( '%pagename%', get_page_uri( $page->ID ), $link );
			$action = isset( $q['action'] ) ? $q['action'] : 'login';
			if ( $slug = $this->options->get_option( array( 'permalinks', $action ) ) ) {
				$link = str_replace( $page->post_name, $slug, $link );
				unset( $q['action'] );
			}
			$link = home_url( $link );
			$link = user_trailingslashit( $link, 'page' );
		} else {
			$link = home_url( "?page_id={$page->ID}" );
		}

		if ( !empty( $q ) )
			$link = add_query_arg( $q, $link );

		return apply_filters( 'tml_page_link', $link, $query );
	}

	/**
	 * Changes login page link to custom permalink
	 *
	 * Callback for "page_link" filter in get_page_link()
	 *
	 * @see get_page_link()
	 * @since 6.2
	 * @access public
	 *
	 * @param string $link Page link
	 * @param int $id Page ID
	 * @return string Page link
	 */
	function page_link( $link, $id ) {
		if ( $this->is_login_page( $id ) )
			return $this->get_login_page_link();
		return $link;
	}

	/**
	 * Changes redirect URL to login page permalink for specific actions
	 *
	 * Callback for "tml_redirect_url" filter in Theme_My_Login_Template::get_redirect_url()
	 *
	 * @since 6.2
	 * @access public
	 *
	 * @param string $url Redirect URL
	 * @param string $action Requested action
	 * @return string Redirect URL
	 */
	function tml_redirect_url( $url, $action ) {
		global $wp_rewrite;

		if ( $wp_rewrite->using_permalinks() && $this->is_login_page() && $this->request_action == $action ) {
			if ( $slug = $this->options->get_option( 'permalinks', $action ) ) {
				switch ( $action ) {
					case 'lostpassword' :
					case 'retrievepassword' :
					case 'register' :
						$permalink = $this->get_login_page_link();

						$parsed_permalink = parse_url( $permalink );
						$parsed_url = parse_url( $url );

						$url = str_replace( $parsed_url['path'], $parsed_permalink['path'], $url );
						break;
				}
			}
		}
		return $url;
	}

	/**
	 * Changes the_title() to reflect the current action
	 *
	 * Callback for "the_title" hook in the_title()
	 *
	 * @see the_title()
	 * @since 6.0
	 * @acess public
	 *
	 * @param string $title The current post title
	 * @param int $post_id The current post ID
	 * @return string The modified post title
	 */
	function the_title( $title, $post_id = 0 ) {
		if ( is_admin() )
			return $title;

		if ( $this->is_login_page( $post_id ) ) {
			if ( !in_the_loop() ) {
				$title = is_user_logged_in() ? __( 'Log Out', 'theme-my-login' ) : __( 'Log In', 'theme-my-login' );
			} else {
				$action = empty( $this->request_instance ) ? $this->request_action : 'login';
				$title = Theme_My_Login_Template::get_title( $action );
			}
		}
		return $title;
	}

	/**
	 * Changes single_post_title() to reflect the current action
	 *
	 * Callback for "single_post_title" hook in single_post_title()
	 *
	 * @see single_post_title()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $title The current post title
	 * @return string The modified post title
	 */
	function single_post_title( $title ) {
		if ( $this->is_login_page() ) {
			$action = empty( $this->request_instance ) ? $this->request_action : 'login';
			$title = Theme_My_Login_Template::get_title( $action );
		}
		return $title;
	}

	/**
	 * Excludes TML page if set in the admin
	 *
	 * Callback for "wp_list_pages_excludes" hook in wp_list_pages()
	 *
	 * @see wp_list_pages()
	 * @since 6.0
	 * @access public
	 *
	 * @param array $exclude_array Array of excluded pages
	 * @return array Modified array of excluded pages
	 */
	function wp_list_pages_excludes( $exclude_array ) {
		$exclude_array = (array) $exclude_array;
		if ( !$this->options->get_option( 'show_page' ) )
			$exclude_array[] = $this->options->get_option( 'page_id' );
		return $exclude_array;
	}

	/**
	 * Changes login link to logout if user is logged in
	 *
	 * Callback for "wp_list_pages" hook in wp_list_pages()
	 *
	 * @see wp_list_pages()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $output The output
	 * @return string The filtered output
	 */
	function wp_list_pages( $output ) {
		if ( is_user_logged_in() )
			$output = str_replace( '"' . $this->get_login_page_link() . '"', '"' . wp_logout_url() . '"', $output );
		return $output;
	}

	/**
	 * Alters menu item title & link according to whether user is logged in or not
	 *
	 * Callback for "wp_setup_nav_menu_item" hook in wp_setup_nav_menu_item()
	 *
	 * @see wp_setup_nav_menu_item()
	 * @since 6.0
	 * @access public
	 *
	 * @param object $menu_item The menu item
	 * @return object The (possibly) modified menu item
	 */
	function wp_setup_nav_menu_item( $menu_item ) {
		if ( 'page' == $menu_item->object && $this->is_login_page( $menu_item->object_id ) ) {
			$menu_item->title = $this->the_title( $menu_item->title, $menu_item->object_id );
			$menu_item->url = is_user_logged_in() ? wp_logout_url() : $this->get_login_page_link();
		}
		return $menu_item;
	}

	/**
	 * Handler for "theme-my-login" shortcode
	 *
	 * Optional $atts contents:
	 *
	 * - instance - A unqiue instance ID for this instance.
	 * - default_action - The action to display. Defaults to "login".
	 * - login_template - The template used for the login form. Defaults to "login-form.php".
	 * - register_template - The template used for the register form. Defaults to "register-form.php".
	 * - lostpassword_template - The template used for the lost password form. Defaults to "lostpassword-form.php".
	 * - resetpass_template - The template used for the reset password form. Defaults to "resetpass-form.php".
	 * - user_template - The templated used for when a user is logged in. Defalts to "user-panel.php".
	 * - show_title - True to display the current title, false to hide. Defaults to true.
	 * - show_log_link - True to display the login link, false to hide. Defaults to true.
	 * - show_reg_link - True to display the register link, false to hide. Defaults to true.
	 * - show_pass_link - True to display the lost password link, false to hide. Defaults to true.
	 * - register_widget - True to allow registration in widget, false to send to register page. Defaults to false.
	 * - lostpassword_widget - True to allow password recovery in widget, false to send to lost password page. Defaults to false.
	 * - logged_in_widget - True to display the widget when logged in, false to hide. Defaults to true.
	 * - show_gravatar - True to display the user's gravatar, false to hide. Defaults to true.
	 * - gravatar_size - The size of the user's gravatar. Defaults to "50".
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string|array $atts Attributes passed from the shortcode
	 * @return string HTML output from Theme_My_Login_Template->display()
	 */
	function shortcode( $atts = '' ) {

		if ( $this->is_login_page() && in_the_loop() ) {
			$atts['instance'] = '';
			$atts['show_title'] = false;
		} else {
			if ( !isset( $atts['instance'] ) )
				$atts['instance'] = $this->get_new_instance();
		}

		$template =& new Theme_My_Login_Template( $atts );

		return $template->display();
	}

	/**
	 * Incremenets $this->count and returns it
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @return int New value of $this->count
	 */
	function get_new_instance() {
		$this->count++;
		return $this->count;
	}

	/**
	 * Returns current URL
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $query Optionally append query to the current URL
	 * @return string URL with optional path appended
	 */
	function get_current_url( $query = '' ) {
		$url = remove_query_arg( array( 'instance', 'action', 'checkemail', 'error', 'loggedout', 'registered', 'redirect_to', 'updated', 'key', '_wpnonce', 'reauth', 'login' ) );
		if ( !empty( $query ) ) {
			$r = wp_parse_args( $query );
			foreach ( $r as $k => $v ) {
				if ( strpos( $v, ' ' ) !== false )
					$r[$k] = rawurlencode( $v );
			}
			$url = add_query_arg( $r, $url );
		}
		return $url;
	}

	/**
	 * Rewrites URL's containing wp-login.php created by site_url()
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $url The URL
	 * @param string $path The path specified
	 * @param string $orig_scheme The current connection scheme (HTTP/HTTPS)
	 * @return string The modified URL
	 */
	function site_url( $url, $path, $orig_scheme ) {
		global $pagenow;
		if ( 'wp-login.php' != $pagenow && strpos( $url, 'wp-login.php' ) !== false && !isset( $_REQUEST['interim-login'] ) ) {
			$parsed_url = parse_url( $url );
			$url = $this->get_login_page_link();
			if ( 'https' == strtolower( $orig_scheme ) )
				$url = preg_replace( '|^http://|', 'https://', $url );
			if ( isset( $parsed_url['query'] ) ) {
				wp_parse_str( $parsed_url['query'], $r );
				foreach ( $r as $k => $v ) {
					if ( strpos($v, ' ') !== false )
						$r[$k] = rawurlencode( $v );
				}
				$url = add_query_arg( $r, $url );
			}
		}
		return $url;
	}

	/**
	 * Enqueues the specified sylesheet
	 *
	 * First looks in theme/template directories for the stylesheet, falling back to plugin directory
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $file Filename of stylesheet to load
	 * @return string Path to stylesheet
	 */
	function get_stylesheet( $file = 'theme-my-login.css' ) {
		if ( file_exists( get_stylesheet_directory() . '/' . $file ) )
			$stylesheet = get_stylesheet_directory_uri() . '/' . $file;
		elseif ( file_exists( get_template_directory() . '/' . $file ) )
			$stylesheet = get_template_directory_uri() . '/' . $file;
		else
			$stylesheet = plugins_url( '/theme-my-login/' . $file );
		return $stylesheet;
	}

	/**
	 * Prints javascript in the footer
	 *
	 * @since 6.0
	 * @access public
	 */
	function print_footer_scripts() {
		if ( !$this->is_login_page() )
			return;

		$action = empty( $this->request_action ) ? 'login' : $this->request_action;
		switch ( $action ) {
			case 'lostpassword' :
			case 'retrievepassword' :
			case 'register' :
			?>
<script type="text/javascript">
try{document.getElementById('user_login<?php echo $this->request_instance; ?>').focus();}catch(e){}
if(typeof wpOnload=='function')wpOnload()
</script>
<?php
				break;
			case 'login' :
				$user_login = '';
				if ( isset($_POST['log']) )
					$user_login = ( 'incorrect_password' == $this->errors->get_error_code() || 'empty_password' == $this->errors->get_error_code() ) ? esc_attr( stripslashes( $_POST['log'] ) ) : '';
			?>
<script type="text/javascript">
function wp_attempt_focus() {
setTimeout( function() {
try {
<?php if ( $user_login ) { ?>
d = document.getElementById('user_pass<?php echo $this->request_instance; ?>');
<?php } else { ?>
d = document.getElementById('user_login<?php echo $this->request_instance; ?>');
<?php } ?>
d.value = '';
d.focus();
} catch(e){}
}, 200 );
}
wp_attempt_focus();
if(typeof wpOnload=='function')wpOnload()
</script>
<?php
				break;
		}
	}

	/**
	 * Calls "login_head" hook on login page
	 *
	 * Callback for "wp_head" hook
	 *
	 * @since 6.0
	 * @access public
	 */
	function login_head() {
		if ( $this->is_login_page() ) {
			do_action( 'login_enqueue_scripts' );
			do_action( 'login_head' );
		}
	}

	/**
	 * Merges arrays recursively, replacing duplicate string keys
	 *
	 * @since 6.0
	 * @access public
	 */
	function array_merge_recursive() {
		$args = func_get_args();

		$result = array_shift( $args );

		foreach ( $args as $arg ) {
			foreach ( $arg as $key => $value ) {
				// Renumber numeric keys as array_merge() does.
				if ( is_numeric( $key ) ) {
					if ( !in_array( $value, $result ) )
						$result[] = $value;
				}
				// Recurse only when both values are arrays.
				elseif ( array_key_exists( $key, $result ) && is_array( $result[$key] ) && is_array( $value ) ) {
					$result[$key] = Theme_My_Login::array_merge_recursive( $result[$key], $value );
				}
				// Otherwise, use the latter value.
				else {
					$result[$key] = $value;
				}
			}
		}
		return $result;
	}

	/**
	 * Returns active and valid TML modules
	 *
	 * Returns all valid modules specified via $this->options['active_modules']
	 *
	 * @since 6.0
	 * @access public
	 */
	function get_active_and_valid_modules() {
		$modules = array();
		$active_modules = apply_filters( 'tml_active_modules', $this->options->get_option( 'active_modules' ) );
		foreach ( (array) $active_modules as $module ) {
			// check the $plugin filename
			// Validate plugin filename	
			if ( !validate_file( $module ) // $module must validate as file
				|| '.php' == substr( $module, -4 ) // $module must end with '.php'
				|| file_exists( TML_ABSPATH . '/modules/' . $module )	// $module must exist
				)
			$modules[] = TML_ABSPATH . '/modules/' . $module;
		}
		return $modules;
	}

	/**
	 * Determine if $module is an active TML module
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $module Filename of module to check
	 * @return bool True if $module is active, false if not
	 */
	function is_module_active( $module ) {
		$active_modules = apply_filters( 'tml_active_modules', $this->options->get_option( 'active_modules' ) );
		return in_array( $module, (array) $active_modules );
	}

	/**
	 * Handles e-mail address login
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $username Username or email
	 * @param string $password User's password
	 */
	function wp_authenticate( &$user_login ) {
		global $wpdb;
		if ( is_email( $user_login ) && $this->options->get_option( 'email_login' ) ) {
			if ( $found = $wpdb->get_var( $wpdb->prepare( "SELECT user_login FROM $wpdb->users WHERE user_email = %s", $user_login ) ) )
				$user_login = $found;
		}
		return;
	}

	/**
	 * Handles sending password retrieval email to user.
	 *
	 * @since 6.0
	 * @access public
	 * @uses $wpdb WordPress Database object
	 *
	 * @return bool|WP_Error True: when finish. WP_Error on error
	 */
	function retrieve_password() {
		global $wpdb, $current_site;

		$errors = new WP_Error();

		if ( empty( $_POST['user_login'] ) ) {
			$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Enter a username or e-mail address.', 'theme-my-login' ) );
		} else if ( strpos( $_POST['user_login'], '@' ) ) {
			$user_data = get_user_by_email( trim( $_POST['user_login'] ) );
			if ( empty( $user_data ) )
				$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: There is no user registered with that email address.', 'theme-my-login' ) );
		} else {
			$login = trim( $_POST['user_login'] );
			$user_data = get_user_by( 'login', $login );
		}

		do_action( 'lostpassword_post' );

		if ( $errors->get_error_code() )
			return $errors;

		if ( !$user_data ) {
			$errors->add( 'invalidcombo', __( '<strong>ERROR</strong>: Invalid username or e-mail.', 'theme-my-login' ) );
			return $errors;
		}

		// redefining user_login ensures we return the right case in the email
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;

		do_action( 'retreive_password', $user_login );  // Misspelled and deprecated
		do_action( 'retrieve_password', $user_login );

		$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

		if ( !$allow )
			return new WP_Error( 'no_password_reset', __( 'Password reset is not allowed for this user', 'theme-my-login' ) );
		else if ( is_wp_error( $allow ) )
			return $allow;

		$key = $wpdb->get_var( $wpdb->prepare( "SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login ) );
		if ( empty( $key ) ) {
			// Generate something random for a key...
			$key = wp_generate_password( 20, false );
			do_action( 'retrieve_password_key', $user_login, $key );
			// Now insert the new md5 key into the db
			$wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'user_login' => $user_login ) );
		}
		$message = __( 'Someone requested that the password be reset for the following account:', 'theme-my-login' ) . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s', 'theme-my-login' ), $user_login ) . "\r\n\r\n";
		$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'theme-my-login' ) . "\r\n\r\n";
		$message .= __( 'To reset your password, visit the following address:', 'theme-my-login' ) . "\r\n\r\n";
		$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

		if ( is_multisite() ) {
			$blogname = $current_site->site_name;
		} else {
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		$title = sprintf( __( '[%s] Password Reset', 'theme-my-login' ), $blogname );

		$title = apply_filters( 'retrieve_password_title', $title, $user_data->ID );
		$message = apply_filters( 'retrieve_password_message', $message, $key, $user_data->ID );

		if ( $message && !wp_mail( $user_email, $title, $message ) )
			wp_die( __( 'The e-mail could not be sent.', 'theme-my-login' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function...', 'theme-my-login' ) );

		return true;
	}

	/**
	 * Retrieves a user row based on password reset key and login
	 *
	 * @since 6.1.1
	 * @access public
	 * @uses $wpdb WordPress Database object
	 *
	 * @param string $key Hash to validate sending user's password
	 * @param string $login The user login
	 *
	 * @return object|WP_Error
	 */
	function check_password_reset_key( $key, $login ) {
		global $wpdb;

		$key = preg_replace( '/[^a-z0-9]/i', '', $key );

		if ( empty( $key ) || !is_string( $key ) )
			return new WP_Error( 'invalid_key', __( 'Invalid key', 'theme-my-login' ) );

		if ( empty( $login ) || !is_string( $login ) )
			return new WP_Error( 'invalid_key', __( 'Invalid key', 'theme-my-login' ) );

		$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login ) );

		if ( empty( $user ) )
			return new WP_Error( 'invalid_key', __( 'Invalid key', 'theme-my-login' ) );

		return $user;
	}

	/**
	 * Handles resetting the user's password.
	 *
	 * @since 6.0
	 * @access public
	 * @uses $wpdb WordPress Database object
	 *
	 * @param string $key Hash to validate sending user's password
	 */
	function reset_password( $user, $new_pass ) {
		do_action( 'password_reset', $user, $new_pass );

		wp_set_password( $new_pass, $user->ID );

		do_action_ref_array( 'tml_user_password_changed', array( &$user ) );
	}

	/**
	 * Handles registering a new user.
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param string $user_login User's username for logging in
	 * @param string $user_email User's email address to send password and add
	 * @return int|WP_Error Either user's ID or error on failure.
	 */
	function register_new_user( $user_login, $user_email ) {
		$errors = new WP_Error();

		$sanitized_user_login = sanitize_user( $user_login );
		$user_email = apply_filters( 'user_registration_email', $user_email );

		// Check the username
		if ( $sanitized_user_login == '' ) {
			$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.', 'theme-my-login' ) );
		} elseif ( !validate_username( $user_login ) ) {
			$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.', 'theme-my-login' ) );
			$sanitized_user_login = '';
		} elseif ( username_exists( $sanitized_user_login ) ) {
			$errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered, please choose another one.', 'theme-my-login' ) );
		}

		// Check the e-mail address
		if ( '' == $user_email ) {
			$errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your e-mail address.', 'theme-my-login' ) );
		} elseif ( !is_email( $user_email ) ) {
			$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'theme-my-login' ) );
			$user_email = '';
		} elseif ( email_exists( $user_email ) ) {
			$errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email is already registered, please choose another one.', 'theme-my-login' ) );
		}

		do_action( 'register_post', $sanitized_user_login, $user_email, $errors );

		$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

		if ( $errors->get_error_code() )
			return $errors;

		$user_pass = apply_filters( 'tml_user_registration_pass', wp_generate_password( 12, false ) );
		$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
		if ( !$user_id ) {
			$errors->add( 'registerfail', sprintf( __( '<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !', 'theme-my-login' ), get_option( 'admin_email' ) ) );
			return $errors;
		}

		update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.

		do_action( 'tml_new_user_registered', $user_id, $user_pass );

		return $user_id;
	}
}

endif; // Class exists

?>
