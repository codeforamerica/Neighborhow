<?php

// REGISTRATION VALIDATION
function tml_registration_errors( $errors ) {
// First Name
	if ( empty( $_POST['first_name'] ) ) {
		$errors->add( 'empty_first_name', '<strong>ERROR</strong>: Please type your first name.' );	
	}
	if ( !empty( $_POST['first_name'] ) ) {	
		$value_first_name = $_POST['first_name'];
		$value_first_name = stripslashes($value_first_name);
		
		if (strlen($value_first_name) > '16') {
			$errors->add( 'maxlength_first_name', '<strong>ERROR</strong>: Please enter a first name with 16 or fewer characters.' );
		}		
		elseif (!preg_match("/^[a-zA-Z '-]+$/", $value_first_name)) {
			$errors->add( 'invalid_first_name', '<strong>ERROR</strong>: Invalid characters entered. Please enter a first name using only letters, space, hyphen, and apostrophe.' );
		}
	}

// Last Name
	if ( empty( $_POST['last_name'] ) ) {
		$errors->add( 'empty_last_name', '<strong>ERROR</strong>: Please type your last name.' );	
	}
	if ( !empty( $_POST['last_name'] ) ) {	
		$value_last_name = $_POST['last_name'];
		$value_last_name = stripslashes($value_last_name);

		if (strlen($value_last_name) > '30') {
			$errors->add( 'maxlength_last_name', '<strong>ERROR</strong>: Please enter a last name with 30 or fewer characters.' );
		}		
		elseif (!preg_match("/^[a-zA-Z '-]+$/", $value_last_name)) {
			$errors->add( 'invalid_last_name', '<strong>ERROR</strong>: Invalid characters entered. Please enter a last name using only letters, space, hyphen, and apostrophe.' );
		}
	}
	
// Username - attempting to make user create username
// that is of sane min/max length
	if ( !empty( $_POST['user_login'] ) ) {
		$value_user_login = $_POST['user_login'];
		$value_user_login = stripslashes($value_user_login);		
		
		if (strlen($value_user_login) < '6') {
			$errors->add( 'minlength_user_login', '<strong>ERROR</strong>: Please enter a username with 6 or more characters.' );	
		}

		elseif (strlen($value_user_login) > '16') {
			$errors->add( 'maxlength_user_login', '<strong>ERROR</strong>: Please enter a username with 16 or fewer characters.' );	
		}	
	}

// Password - attempting to limit the number of
// special characters user can use for pwd
	if ( !empty( $_POST['pass1'] ) AND !isset($_POST['password_mismatch']) ) {	
		$value_user_pass = $_POST['pass1'];	
		$value_user_pass = stripslashes($value_user_pass);

		if (!preg_match("/^[a-zA-Z0-9_!%&-]+$/", $value_user_pass)) {
			$errors->add( 'invalid_password', '<strong>ERROR</strong>: Invalid characters entered. Your password can use upper and lower case letters, numbers, and hyphen, underscore, exclamation point, percentage, and ampersand ( - _ ! % and & ).' );
		}
	}	 	
	return $errors;
}
add_filter( 'registration_errors', 'tml_registration_errors' );


// INSERT THE NEW REGISTRATION FIELDS
function tml_user_register( $user_id ) {
	if ( !empty( $_POST['first_name'] ) ) {
		$un_first_name = $_POST['first_name'];
		$first_name = wp_strip_all_tags($un_first_name);
		update_user_meta($user_id, 'first_name', $first_name);
	}
	
	if ( !empty( $_POST['last_name'] ) ) {
		$un_last_name = $_POST['first_name'];
		$last_name = wp_strip_all_tags($un_last_name);
		update_user_meta($user_id, 'last_name', $last_name);
	}
}
add_action( 'user_register', 'tml_user_register' );

?>