<?php

if ( !class_exists( 'Theme_My_Login_Custom_User_Links_Admin' ) ) :
/**
 * Theme My Login Custom User Links module admin class
 *
 * @since 6.0
 */
class Theme_My_Login_Custom_User_Links_Admin extends Theme_My_Login_Module {
	/**
	 * AJAX handler for adding/updating a link
	 *
	 * Callback for "wp_ajax_add-user-link" hook in file "wp-admin/admin-ajax.php"
	 *
	 * @since 6.0
	 * @access public
	 */
	function add_user_link_ajax() {
		global $theme_my_login;

		if ( !current_user_can( 'manage_options' ) )
			die( '-1' );

		check_ajax_referer( 'add-user-link' );

		// Create a reference to current links
		$links =& $theme_my_login->options->get_option( 'user_links' );

		$c = 0;
		if ( isset( $_POST['new_user_link'] ) ) {
			// Add a new link
			foreach ( $_POST['new_user_link'] as $role => $link ) {
				// Make sure input isn't empty
				if ( is_array( $link ) && !empty( $link ) ) {
					// Clean the input
					$clean_title = wp_kses( $link['title'], null );
					$clean_url = wp_kses( $link['url'], null );

					// Make sure input isn't empty after cleaning
					if ( empty( $clean_title ) || empty( $clean_url ) )
						die( '1' );

					// Add new link
					$links[$role][] = array( 'title' => $clean_title, 'url' => $clean_url );
					// Save links
					$theme_my_login->options->set_option( 'user_links', $links );
					$theme_my_login->options->save();

					$link_row = array_merge( array( 'id' => max( array_keys( $links[$role] ) ) ), end( $links[$role] ) );

					$x = new WP_Ajax_Response( array(
						'what' => $role . '-link',
						'id' => $link_row['id'],
						'data' => $this->get_link_row( $link_row, $role, $c ),
						'position' => 1,
						'supplemental' => array( 'user_role' => $role )
					) );
				}
			}
		} else {
			// Update a link
			foreach ( $_POST['user_links'] as $role => $link ) {
				// Set the link ID
				$id = key( $link );

				// Clean the input
				$clean_title = wp_kses( $link[$id]['title'], null );
				$clean_url = wp_kses( $link[$id]['url'], null );

				// Make sure the requested link ID exists
				if ( !isset( $links[$role][$id] ) )
					die( '0' );

				// Update the link if it has changed
				if ( $links[$role][$id]['title'] != $clean_title || $links[$role][$id]['url'] != $clean_url ) {
					$links[$role][$id] = array( 'title' => $clean_title, 'url' => $clean_url );
					$theme_my_login->options->set_option( 'user_links', $links );
					$theme_my_login->options->save();
				}

				$link_row = array_merge( array( 'id' => $id ), $links[$role][$id] );

				$x = new WP_Ajax_Response( array(
					'what' => $role . '-link',
					'id' => $id,
					'old_id' => $id,
					'data' => $this->get_link_row( $link_row, $role, $c ),
					'position' => 0,
					'supplemental' => array( 'user_role' => $role )
				) );
			}
		}
		$x->send();
	}

	/**
	 * AJAX handler for deleting a link
	 *
	 * Callback for "wp_ajax_delete-user-link" hook in file "wp-admin/admin-ajax.php"
	 *
	 * @since 6.0
	 * @access public
	 */
	function delete_user_link_ajax() {
		global $theme_my_login, $id;

		$user_role = isset( $_POST['user_role'] ) ? $_POST['user_role'] : '';
		if ( empty( $user_role ) )
			die( '0' );

		check_ajax_referer( "delete-user-link_$id" );

		$links =& $theme_my_login->options->get_option( 'user_links' );
		if ( isset( $links[$user_role][$id] ) ) {
			// Delete link
			unset( $links[$user_role][$id] );
			// Save links
			$theme_my_login->options->set_option( 'user_links', $links );
			$theme_my_login->options->save();
			die( '1' );
		}
		die( '0' );
	}

	/**
	 * Adds "User Links" tab to Theme My Login menu
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
		global $wp_roles;
		// Add menu tab
		$admin->add_menu_page( __( 'User Links', 'theme-my-login' ), 'tml-options-user-links' );
		// Iterate through each user role
		foreach ( $wp_roles->get_names() as $role => $label ) {
			// We don't want the 'pending' role created by the "User Moderation" module
			if ( 'pending' == $role )
				continue;
			// Add submenu tab for the role
			$admin->add_submenu_page( 'tml-options-user-links', translate_user_role( $label ), 'tml-options-user-links-' . $role, array( &$this, 'display_settings' ), array( $role ) );
		}
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
		// Bail-out if doing AJAX because it has it's own saving routine
		if ( defined('DOING_AJAX') && DOING_AJAX )
			return $settings;
		// Handle updating/deleting of links
		if ( isset( $_POST['user_links'] ) && is_array( $_POST['user_links'] ) && !empty( $_POST['user_links'] ) ) {
			foreach ( $_POST['user_links'] as $role => $links ) {
				foreach ( $links as $key => $link ) {
					$clean_title = wp_kses( $link['title'], null );
					$clean_url = wp_kses( $link['url'], null );
					$links[$key] = array( 'title' => $clean_title, 'url' => $clean_url );
					if ( ( empty( $clean_title ) && empty( $clean_url ) ) || ( isset( $_POST['delete_user_link'][$role][$key] ) ) )
						unset( $links[$key] );
				}
				$settings['user_links'][$role] = array_values( $links );
			}
		}
		// Handle new links
		if ( isset( $_POST['new_user_link'] ) && is_array( $_POST['new_user_link'] ) && !empty( $_POST['new_user_link'] ) ) {
			foreach ( $_POST['new_user_link'] as $role => $link ) {
				$clean_title = wp_kses( $link['title'], null );
				$clean_url = wp_kses( $link['url'], null );
				if ( !empty( $clean_title ) && !empty( $clean_url ) )
					$settings['user_links'][$role][] = array( 'title' => $clean_title, 'url' => $clean_url );
			}
		}
		// Reset link keys
		foreach ( $settings['user_links'] as $role => $links ) {
			$settings['user_links'][$role] = array_values( $links );
		}
		return $settings;
	}

	/**
	 * Loads admin styles and scripts
	 *
	 * Callback for "load-settings_page_theme-my-login" hook in file "wp-admin/admin.php"
	 *
	 * @since 6.0
	 * @access public
	 */
	function load_settings_page() {
		wp_enqueue_style( 'tml-custom-user-links-admin', plugins_url( 'theme-my-login/modules/custom-user-links/admin/css/custom-user-links-admin.css' ) );
		wp_enqueue_script( 'tml-custom-user-links-admin', plugins_url( 'theme-my-login/modules/custom-user-links/admin/js/custom-user-links-admin.js' ), array( 'wp-lists', 'jquery-ui-sortable' ) );
	}

	/**
	 * Outputs user links admin menu for specified role
	 *
	 * Callback for "$hookname" hook in method Theme_My_Login_Admin::add_submenu_page()
	 *
	 * @see Theme_My_Login_Admin::add_submenu_page()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $role Name of user role
	 */
	function display_settings( $role ) {
		global $theme_my_login;

		$links =& $theme_my_login->options->get_option( array( 'user_links', $role ) );
		if ( empty($links) )
			$links = array();
		?>
	<div id="ajax-response-<?php echo $role; ?>" class="ajax-response"></div>

	<table id="<?php echo $role; ?>-link-table"<?php if ( empty( $links ) ) echo ' style="display: none;"'; ?> class="sortable">
		<thead>
		<tr>
			<th class="left"><?php _e( 'Title', 'theme-my-login' ); ?></th>
			<th><?php _e( 'URL', 'theme-my-login' ); ?></th>
			<th></th>
		</tr>
		</thead>
		<tbody id="<?php echo $role; ?>-link-list" class="list:user-link">
		<?php if ( empty( $links ) ) {
			echo '<tr><td></td></tr>';
		} else {
			$count = 0;
			foreach ( $links as $key => $link ) {
				$link['id'] = $key;
				echo $this->get_link_row( $link, $role, $count );
			}
		} ?>
		</tbody>
	</table>

	<p><strong><?php _e( 'Add New link:' , 'theme-my-login' ) ?></strong></p>

	<table id="new-<?php echo $role; ?>-link">
	<thead>
	<tr>
		<th class="left"><label for="new_user_link[<?php echo $role; ?>][title]"><?php _e( 'Title', 'theme-my-login' ) ?></label></th>
		<th><label for="new_user_link[<?php echo $role; ?>][url]"><?php _e( 'URL', 'theme-my-login' ) ?></label></th>
		<th></th>
	</tr>
	</thead>

	<tbody>
	<tr>
		<td class="left"><input id="new_user_link[<?php echo $role; ?>][title]" name="new_user_link[<?php echo $role; ?>][title]" type="text" tabindex="8" size="20" /></td>
		<td class="center"><input id="new_user_link[<?php echo $role; ?>][url]" name="new_user_link[<?php echo $role; ?>][url]" type="text" tabindex="8" size="20" /></td>
		<td class="submit">
			<input type="submit" id="add_new_user_link_<?php echo $role; ?>" name="add_new_user_link[<?php echo $role; ?>]" class="add:<?php echo $role; ?>-link-list:new-<?php echo $role; ?>-link" tabindex="9" value="<?php esc_attr_e( 'Add link', 'theme-my-login' ) ?>" />
			<?php wp_nonce_field( 'add-user-link', '_ajax_nonce', false ); ?>
		</td>
	</tr>
	</tbody>
	</table>
<?php
	}

	/**
	 * Outputs a link row to the table
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param array $link Link data
	 * @param string $role Name of user role
	 * @param int $count Reference to counter variable
	 * @return sring Link row
	 */
	function get_link_row( $link, $role, &$count ) {
		$r = '';
		++ $count;
		if ( $count % 2 )
			$style = 'alternate';
		else
			$style = '';

		$link = (object) $link;

		$delete_nonce = wp_create_nonce( 'delete-user-link_' . $link->id );
		$update_nonce = wp_create_nonce( 'add-user-link' );

		$r .= "\n\t<tr id='$role-link-$link->id' class='$style'>";
		$r .= "\n\t\t<td class='left'><label class='screen-reader-text' for='user_links[$role][$link->id][title]'>" . __( 'Title', 'theme-my-login' ) . "</label><input name='user_links[$role][$link->id][title]' id='user_links[$role][$link->id][title]' tabindex='6' type='text' size='20' value='$link->title' />";
		$r .= wp_nonce_field( 'change-user-link', '_ajax_nonce', false, false );
		$r .= "</td>";

		$r .= "\n\t\t<td class='center'><label class='screen-reader-text' for='user_links[$role][$link->id][url]'>" . __( 'URL', 'theme-my-login' ) . "</label><input name='user_links[$role][$link->id][url]' id='user_links[$role][$link->id][url]' tabindex='6' type='text' size='20' value='$link->url' /></td>";

		$r .= "\n\t\t<td class='submit'><input name='delete_user_link[$role][$link->id]' type='submit' class='delete:$role-link-list:$role-link-$link->id::_ajax_nonce=$delete_nonce deletelink' tabindex='6' value='". esc_attr__( 'Delete' ) ."' />";
		$r .= "\n\t\t<input name='updatelink' type='submit' class='add:$role-link-list:$role-link-$link->id::_ajax_nonce=$update_nonce updatelink' tabindex='6' value='". esc_attr__( 'Update' ) ."' /></td>\n\t</tr>";
		return $r;
	}

	/**
	 * Activates this module
	 *
	 * Callback for "tml_activate_custom-user-links/custom-user-links.php" hook in method Theme_My_Login_Admin::activate_module()
	 *
	 * @see Theme_My_Login_Admin::activate_module()
	 * @since 6.0
	 * @access public
	 *
	 * @param object $theme_my_login Reference to global $theme_my_login object
	 */
	function activate( &$theme_my_login ) {
		$options = Theme_My_Login_Custom_User_Links::init_options();
		$theme_my_login->options->set_option( 'user_links', $options['user_links'] );
	}

	/**
	 * Loads the module
	 *
	 * @since 6.0
	 * @access public
	 */
	function load() {
		add_action( 'tml_activate_custom-user-links/custom-user-links.php', array( &$this, 'activate' ) );
		add_action( 'tml_admin_menu', array( &$this, 'admin_menu' ) );
		add_filter( 'tml_save_settings', array( &$this, 'save_settings' ) );

		add_action( 'load-settings_page_theme-my-login', array( &$this, 'load_settings_page' ) );

		add_action( 'wp_ajax_add-user-link', array( &$this, 'add_user_link_ajax' ) );
		add_action( 'wp_ajax_delete-user-link', array( &$this, 'delete_user_link_ajax' ) );
	}
}

/**
 * Holds the reference to Theme_My_Login_Custom_User_Links_Admin object
 * @global object $theme_my_login_custom_user_links_admin
 * @since 6.0
 */
$theme_my_login_custom_user_links_admin = new Theme_My_Login_Custom_User_Links_Admin();

endif; // Class exists

?>
