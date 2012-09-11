<?php
// TODO
// - fix TML hack below
// - check format of city names

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
		if (strlen($value_last_name) > '16') {
			$errors->add( 'maxlength_last_name', '<strong>ERROR</strong>: Please enter a last name with 16 or fewer characters.' );
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
		elseif (!preg_match("/^[a-zA-Z0-9-]+$/", $value_user_login)) {
			$errors->add( 'invalid_user_login', '<strong>ERROR</strong>: Invalid characters in username. Please enter a username using only letters and numbers.' );
		}			
	}
	
// User City
	if ( empty( $_POST['user_city'] ) ) {
		$errors->add( 'empty_user_city', '<strong>ERROR</strong>: Please enter the name of your city.' );	
	}
	if ( !empty( $_POST['user_city'] ) ) {
		$value_user_city = trim($_POST['user_city']);			
		$value_user_city = sanitize_text_field($value_user_city);
		if (!preg_match("/^[a-zA-Z \\\'-]+$/", $value_user_city)) {
			$errors->add( 'invalid_user_city', '<strong>ERROR</strong>: Invalid characters in city name. Please enter a city name using only letters, space, hyphen, and apostrophe.' );
		}
	}

// User Organization - validate how?
	if ( !empty( $_POST['user_org'] ) ) {
		$value_user_org = trim($_POST['user_org']);			
		$value_user_org = sanitize_text_field($value_user_org);
	}	
		 	
	return $errors;
}
add_filter( 'registration_errors', 'tml_registration_errors' );


// INSERT VALUES
function tml_user_register( $user_id ) {
// NAMES
	if ( !empty( $_POST['first_name'] ) ) {
		$un_first_name = trim($_POST['first_name']);	
		$first_name = sanitize_text_field($un_first_name);
		update_user_meta($user_id, 'first_name', $first_name);
	}
	
	if ( !empty( $_POST['last_name'] ) ) {
		$un_last_name = trim($_POST['last_name']);
		$last_name = sanitize_text_field($un_last_name);
		update_user_meta($user_id, 'last_name', $last_name);
	}
	
	if ( !empty( $_POST['first_name'] ) AND !empty( $_POST['last_name'] )) {	
		wp_update_user(array(
			'ID' => $user_id,
			'display_name' => $first_name.' '.$last_name
		));		
	}

// USER CITY
	if ( !empty( $_POST['user_city'] ) ) {
		$un_user_city = trim($_POST['user_city']);
		$nh_user_city = sanitize_text_field($un_user_city);			
	}
	update_user_meta($user_id, 'user_city', $nh_user_city);

// USER ORGANIZATION
	if ( !empty( $_POST['user_org'] ) ) {
		$un_user_org = trim($_POST['user_org']);
		$nh_user_org = sanitize_text_field($un_user_org);			
	}
	update_user_meta($user_id, 'user_org', $nh_user_org);	

}
add_action( 'user_register', 'tml_user_register' );


/*------- UPDATE THESE FIELDS IN ADMIN PROFILE-----*/
// also adding errors for Description to limit length
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
			else {
				update_user_meta($user->ID, 'first_name', $value_first_name);
				$last = get_user_meta($user->ID,'last_name',true);
				wp_update_user(array(
					'ID' => $user->ID,
					'display_name' => $value_first_name.' '.$last
				));
			}
		}	
		
// LAST NAME - required + of right format		
		if(empty($_POST['last_name'])) {
			$errors->add('empty_last_name', '<strong>ERROR</strong>: Last name is required. Please type your last name.', array('form-field' => 'last_name'));
		}
		elseif (!empty($_POST['last_name'])) {
			$value_last_name = trim($_POST['last_name']);
			$value_last_name = sanitize_text_field($value_last_name);			
			if (strlen($value_last_name) > '16') {
				$errors->add('maxlength_last_name', '<strong>ERROR</strong>: Please enter a last name with 16 or fewer characters.', array('form-field' => 'last_name'));
			}
			if (!preg_match("/^[a-zA-Z \\\'-]+$/", $value_last_name)) {
				$errors->add('invalid_last_name', '<strong>ERROR</strong>: Invalid characters in last name. Please enter a last name using only letters, spaces, hyphens, or apostrophes.', array('form-field' => 'last_name'));
			}			
			else {
				update_user_meta($user->ID, 'last_name', $value_last_name);
				$first = get_user_meta($user->ID,'first_name',true);
				wp_update_user(array(
					'ID' => $user->ID,
					'display_name' => $first.' '.$value_last_name
				));
			}
		}	
		
// DESCRIPTION - let WP handle validation		
		if (!empty($_POST['description'])) {
			$value_description = trim($_POST['description']);

			if (strlen($value_description) > '200') {
				$errors->add('maxlength_description', '<strong>ERROR</strong>: Please enter a description with 200 or fewer characters.', array('form-field' => 'description'));
			}					
			else {
				update_user_meta($user->ID, 'description', $value_description);
			}
		}

// USER CITY
		if ( !empty( $_POST['user_city'] ) ) {
				$un_user_city = trim($_POST['user_city']);
				$nh_user_city = sanitize_text_field($un_user_city);		
		}
		update_user_meta($user->ID, 'user_city', $nh_user_city);
		
// USER ORGANIZATION
		if ( !empty( $_POST['user_org'] ) ) {
				$un_user_org = trim($_POST['user_org']);
				$nh_user_org = sanitize_text_field($un_user_org);		
		}
		update_user_meta($user->ID, 'user_org', $nh_user_org);		

	}
}
add_action('user_profile_update_errors', 'nh_save_extra_profile_fields', 10, 3);


/*---------ADD EXTRA FIELDS TO ADMIN PROFILE-------------*/
add_action( 'show_user_profile', 'nh_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'nh_show_extra_profile_fields' );

function nh_show_extra_profile_fields( $user ) { 
// below USER CITY + USER ORGANIZATION ARE 
// hackS around Theme My Login (TML)
// if the reg/profile form is just in front end 
// as TML wants, then it doesn't show in admin - 
// we want both so have to put this here
// also this form doesnt recognize TML $profileuser
// so using tmp WP vars

// USER CITY
?>
<div class="form-item form-item-admin">
<?php
$taxonomy = 'nh_cities';
$terms = get_terms($taxonomy);
$posted_city = esc_attr($_POST['user_city']);
$tmp_id = $user->ID;
$cities = get_user_meta($tmp_id,'user_city');
$user_current_city =  $cities[0];
?>
	<label for="user_city"><?php _e( 'City', 'theme-my-login' ) ?></label>

	<input type="text" name="user_city" id="user_city" class="input" value="<?php echo esc_attr( $user_current_city ) ?>" size="20" tabindex="45" required />
	<div class="help-block help-block-city"><span class="txt-help admin-description"><p>Neighborhow is about helping you find and share local knowledge about your own city. The more people who sign up from your city, the sooner your city will get its own Neighborhow page! Be sure your city name is in the format "Philadelphia PA" and "San Francisco CA".</p></span>
	</div>	
</div>

<?php
// USER ORGANIZATION
?>
<div class="form-item form-item-admin">
<?php
$posted_user_org = esc_attr($_POST['user_org']);
$tmp_id = $user->ID;
$user_org = get_user_meta($tmp_id,'user_org');
$user_current_user_org =  $user_org[0];
?>
	<label for="user_org"><?php _e( 'Organization', 'theme-my-login' ) ?></label>

	<input type="text" name="user_org" id="user_org" class="input" value="<?php echo esc_attr( $user_current_user_org ) ?>" size="20" tabindex="45" />

	<div class="help-block help-block-city"><span class="txt-help admin-description"><p>If you work with a city or an organization, please enter the name here. This information will be publicly visible.</p></span>
	</div>	
</div>
<?php }



// STOP HERE
?>