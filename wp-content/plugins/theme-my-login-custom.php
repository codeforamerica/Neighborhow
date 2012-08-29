<?php

// REGISTRATION ERRORS
function tml_registration_errors( $errors ) {
// First Name
	if ( empty( $_POST['first_name'] ) ) {
		$errors->add( 'empty_first_name', '<strong>ERROR</strong>: Please type your first name.' );	
	}
	if ( !empty( $_POST['first_name'] ) ) {	
		$value_first_name = trim($_POST['first_name']);
		$value_first_name = sanitize_text_field($value_first_name);
		
		if (strlen($value_first_name) > '16') {
			$errors->add( 'maxlength_first_name', '<strong>ERROR</strong>: Please enter a first name with 16 or fewer characters.' );
		}		
		elseif (!preg_match("/^[a-zA-Z \\\'-]+$/", $value_first_name)) {
			$errors->add( 'invalid_first_name', '<strong>ERROR</strong>: Invalid characters in first name. Please enter a first name using only letters, space, hyphen, and apostrophe.' );
		}
	}

// Last Name
	if ( empty( $_POST['last_name'] ) ) {
		$errors->add( 'empty_last_name', '<strong>ERROR</strong>: Please type your last name.' );	
	}
	if ( !empty( $_POST['last_name'] ) ) {
		$value_last_name = trim($_POST['last_name']);			
		$value_last_name = sanitize_text_field($value_last_name);

		if (strlen($value_last_name) > '30') {
			$errors->add( 'maxlength_last_name', '<strong>ERROR</strong>: Please enter a last name with 30 or fewer characters.' );
		}		
		elseif (!preg_match("/^[a-zA-Z \\\'-]+$/", $value_last_name)) {
			$errors->add( 'invalid_last_name', '<strong>ERROR</strong>: Invalid characters in last name. Please enter a last name using only letters, space, hyphen, and apostrophe.' );
		}
	}
// Username	- from WP - try for a sane min/max length
	// let WP handle validation
	if ( !empty( $_POST['user_login'] ) ) {
		$value_user_login = trim($_POST['user_login']);		
		
		if (strlen($value_user_login) < '6') {
			$errors->add( 'minlength_user_login', '<strong>ERROR</strong>: Please enter a username with 6 or more characters.' );	
		}

		elseif (strlen($value_user_login) > '16') {
			$errors->add( 'maxlength_user_login', '<strong>ERROR</strong>: Please enter a username with 16 or fewer characters.' );	
		}	
	}
		 	
	return $errors;
}
add_filter( 'registration_errors', 'tml_registration_errors' );


// INSERT THE NEW REGISTRATION FIELDS
function tml_user_register( $user_id ) {
	$default_city = 'Philadelphia, PA';

	if ( !empty( $_POST['first_name'] ) ) {
		$un_first_name = trim($_POST['first_name']);	
		$first_name = sanitize_text_field($un_first_name);
		update_user_meta($user_id, 'first_name', $first_name);
	}
	
	if ( !empty( $_POST['last_name'] ) ) {
		$un_last_name = trim($_POST['first_name']);
		$last_name = sanitize_text_field($un_last_name);
		update_user_meta($user_id, 'last_name', $last_name);
	}
	
	if ( !empty( $_POST['nh_cities'] ) ) {
		$un_nh_cities = trim($_POST['nh_cities']);
		$nh_cities = sanitize_text_field($un_nh_cities);			
	}
	elseif ( empty( $_POST['nh_cities'] ) ) {
		$nh_cities = $default_city;
	}
	update_user_meta($user_id, 'nh_cities', $nh_cities);
	
}
add_action( 'user_register', 'tml_user_register' );



/*------- SUBMIT CHANGES TO ADMIN PROFILE -----*/
function nh_save_extra_profile_fields( &$errors, $update, &$user ) {
	if($update) {

// FIRST NAME - required + of right format		
		if(empty($_POST['first_name'])) {
			$errors->add('empty_first_name', '<strong>ERROR</strong>: First name is required. Please type your first name.', array('form-field' => 'first_name'));
		}
		elseif (!empty($_POST['first_name'])) {
			$value_first_name = trim($_POST['first_name']);
			$value_first_name = sanitize_text_field($value_first_name);			
			if (strlen($value_first_name) > '16') {
				$errors->add('maxlength_first_name', '<strong>ERROR</strong>: Please enter a first name with 16 or fewer characters.', array('form-field' => 'first_name'));
			}
			if (!preg_match("/^[a-zA-Z \\\'-]+$/", $value_first_name)) {
				$errors->add('invalid_first_name', '<strong>ERROR</strong>: Invalid characters in first name. Please enter a first name using only letters, spaces, hyphens, or apostrophes.', array('form-field' => 'first_name'));
			}			
			else // do the update
			{
				update_user_meta($user->ID, 'first_name', $value_first_name);
			}
		}	
		
// LAST NAME - required + of right format		
		if(empty($_POST['last_name'])) {
			$errors->add('empty_last_name', '<strong>ERROR</strong>: Last name is required. Please type your last name.', array('form-field' => 'last_name'));
		}
		elseif (!empty($_POST['last_name'])) {
			$value_last_name = trim($_POST['last_name']);
			$value_last_name = sanitize_text_field($value_last_name);			
			if (strlen($value_last_name) > '30') {
				$errors->add('maxlength_last_name', '<strong>ERROR</strong>: Please enter a last name with 30 or fewer characters.', array('form-field' => 'last_name'));
			}
			if (!preg_match("/^[a-zA-Z \\\'-]+$/", $value_last_name)) {
				$errors->add('invalid_last_name', '<strong>ERROR</strong>: Invalid characters in last name. Please enter a last name using only letters, spaces, hyphens, or apostrophes.', array('form-field' => 'last_name'));
			}			
			else // do the update
			{
				update_user_meta($user->ID, 'last_name', $value_last_name);
			}
		}	
		
// DESCRIPTION - let WP handle char validation		
		if (!empty($_POST['description'])) {
			$value_description = trim($_POST['description']);

			if (strlen($value_description) > '200') {
				$errors->add('maxlength_description', '<strong>ERROR</strong>: Please enter a description with 200 or fewer characters.', array('form-field' => 'description'));
			}			
						
			else // do the update
			{
				update_user_meta($user->ID, 'description', $value_description);
			}
		}
	
// USER CITY		

// if = choose a city change to philly

		$value_nh_cities = trim($_POST['nh_cities']);
		$value_nh_cities = sanitize_text_field($value_nh_cities);	
		
		update_user_meta($user->ID, 'nh_cities', $value_nh_cities);

	}
}
add_action('user_profile_update_errors', 'nh_save_extra_profile_fields', 10, 3);


/*---------ADD CITY / STATE TO USER PROFILE-------------*/
/*add_action( 'show_user_profile', 'nh_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'nh_show_extra_profile_fields' );

function nh_show_extra_profile_fields( $user ) { ?>
	<!--table class="form-table">
		<div class="form-item form-item-admin">
			<label class="nh-form-label label-admin" for="user_city">City</label>
			<input class="regular-text input-admin" type="text" name="user_city" id="user_city" value="<?php echo esc_attr( get_the_author_meta( 'user_city', $user->ID ) ); ?>" required />
			<br/><span class="help-block description help-block-admin <?php //foreach ($nh_error_keys as $key) { if ($key == "empty_user_city" OR $key == "invalid_user_city") { echo 'nh-error'; }} ?>">City name is required and is publicly visible.</span>
		</div>		
	</table-->
	
	<table class="form-table">
		<div class="form-item form-item-admin">
			<label class="nh-form-label label-admin" for="nh_cities">Your City</label>
<?php 
// convert from slug to nicename to show selected

nh_custom_taxonomy_dropdown('nh_cities');
?>
			<br/><span class="help-block description help-block-admin">Select your city. If your city is not in the list, that's ok, you can still sign up!</span>			
		</div>
	</table>	
<?php }
*/



?>