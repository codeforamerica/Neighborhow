<?php
/**
 * Holds generic plugin options class
 *
 * @package Plugin Options
 */

if ( !class_exists( 'WDBJ_Plugin_Options' ) ) :
/**
 * Plugin options class
 *
 * This class holds properties and methods common to plugin options.
 *
 * @since 0.1
 */
class WDBJ_Plugin_Options {
	/**
	 * Holds options
	 *
	 * @since 0.1
	 * @access public
	 * @var array
	 */
	var $options = array();

	/**
	 * Holds options key
	 *
	 * @since 0.1
	 * @access public
	 * @var string
	 */
	var $db_key = '';

	/**
	 * Loads options from DB
	 *
	 * @since 0.1
	 * @access public
	 */
	function load( $options = '' ) {

		if ( !empty( $options ) )
			$this->options = (array) $options;

		$options = get_option( $this->db_key );

		if ( is_array( $options ) ) {
			$this->options = array_merge( $this->options, $options );
		} else {
			update_option( $this->db_key, $this->options );
		}
	}

	/**
	 * Saves options to DB
	 *
	 * @since 0.1
	 * @access public
	 */
	function save() {
		$options = get_option( $this->db_key );
		if ( $options !== $this->options )
			update_option( $this->db_key, $this->options );
	}

	/**
	 * Retrieves an option
	 *
	 * @since 0.1
	 * @access public
	 *
	 * @param string|array $option Name of option to retrieve or an array of hierarchy for multidimensional options
	 * @param mixed $default Default value to return if $option is not set
	 * @return mixed Value of requested option or $default if option is not set
	 */
	function get_option( $option, $default = false ) {
		$options = $this->options;
		$value = false;
		if ( is_array( $option ) ) {
			foreach ( $option as $_option ) {
				if ( !isset( $options[$_option] ) ) {
					$value = $default;
					break;
				}
				$options = $value = $options[$_option];
			}
		} else {
			$value = isset( $options[$option] ) ? $options[$option] : $default;
		}
		return apply_filters( $this->db_key . '_get_option', $value, $option, $default );
	}

	/**
	 * Sets an option
	 *
	 * @since 0.1
	 * @access public
	 *
	 * @param string $option Name of option to set or an array of hierarchy for multidimensional options
	 * @param mixed $value Value of new option
	 */
	function set_option( $option, $value = '' ) {
		if ( is_array( $option ) ) {
			$options = $this->options;
			$last = array_pop( $option );
			foreach ( $option as $_option ) {
				if ( !isset( $options[$_option] ) )
					$options[$_option] = array();
				$options = $options[$_option];
			}
			$options[$last] = $value;
			$this->options = array_merge( $this->options, $options );
		} else {
			$this->options[$option] = apply_filters( $this->db_key . '_set_option', $value, $option );
		}
	}

	/**
	 * Deletes an option
	 *
	 * @since 0.1
	 * @access public
	 *
	 * @param string $option Name of option to delete
	 * @param bool $save True will save to DB
	 */
	function delete_option( $option, $save = false ) {
		if ( isset( $this->options[$option] ) )
			unset( $this->options[$option] );
	}

	/**
	 * PHP4 style constructor
	 *
	 * @since 0.1
	 * @access public
	 */
	function WDBJ_Plugin_Options( $db_key, $options = '' ) {
		$this->db_key = sanitize_user( $db_key );
		if ( !empty( $options ) )
			$this->load( $options );
	}
}

endif; // Class exists