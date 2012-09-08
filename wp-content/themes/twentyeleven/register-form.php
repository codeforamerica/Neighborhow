<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

$nh_errors = $theme_my_login->errors;
$value = getL2Keys($nh_errors);
?>
<div id="content">
	<div id="page-register">
		<h3 class="page-title">Sign Up for Neighborhow</h3>

		<h5>Connect with</h5>
		<?php do_action( 'wordpress_social_login' ); ?>
			
		<h5>Or create a Neighborhow account</h5>
		<div class="login" id="theme-my-login<?php $template->the_instance(); ?>">
<?php $template->the_action_template_message( '' ); ?>
<?php $template->the_errors(); ?>
    
			<form class="nh-register form-horizontal" name="registerform" id="registerform<?php $template->the_instance(); ?>" action="<?php $template->the_action_url( 'register' ); ?>" method="post">				
				
			<div class="form-item">
				<label for="user_login<?php $template->the_instance(); ?>"><?php _e( 'Username', 'theme-my-login' ) ?></label>

				<input type="text" name="user_login" id="user_login<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_login' ); ?>" size="20" tabindex="5" required />

				<div class="help-block <?php foreach ($value as $key) { if ($key == "empty_username" OR $key == "minlength_user_login" OR $key == "maxlength_user_login" OR $key == "invalid_username" OR $key == "username_exists") { echo 'nh-error'; }} ?>"><span class="txt-help">Your username is a unique ID on Neighborhow. It should be between 6 and 16 characters and can include letters, numbers, and dash. Choose carefully &#8212; usernames cannot be changed later.</span>
				</div>
			</div>
			
			<div class="form-item">
				<label for="first_name<?php $template->the_instance(); ?>"><?php _e( 'First name', 'theme-my-login' ) ?></label>

				<input type="text" name="first_name" id="first_name<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'first_name' ); ?>" size="20" tabindex="10" required />
				<div class="help-block <?php foreach ($value as $key) { if ($key == "empty_first_name" OR $key == "maxlength_first_name" OR $key == "invalid_first_name") { echo 'nh-error'; }} ?>"><span class="txt-help">Enter your first name (max length is 16 characters). Your first name will be publicly visible.</span>
				</div>
			</div>

			<div class="form-item">
				<label for="last_name<?php $template->the_instance(); ?>"><?php _e( 'Last name', 'theme-my-login' ) ?></label>

				<input type="text" name="last_name" id="last_name<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'last_name' ); ?>" size="20" tabindex="15" required  />
				<div class="help-block <?php foreach ($value as $key) { if ($key == "empty_last_name" OR $key == "maxlength_last_name" OR $key == "invalid_last_name") { echo 'nh-error'; }} ?>"><span class="txt-help">Enter your last name (max length is 16 characters). Your last name will be publicly visible.</span>
				</div>
			</div>				
		
			<div class="form-item">
				<label for="user_email<?php $template->the_instance(); ?>"><?php _e( 'Email Address', 'theme-my-login' ) ?></label>

				<input type="email" name="user_email" id="user_email<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_email' ); ?>" size="20" tabindex="20" required />

				<div class="help-block <?php foreach ($value as $key) { if ($key == "empty_email" OR $key == "invalid_email" OR $key == "email_exists") { echo 'nh-error'; }} ?>"><span class="txt-help">Enter your email address.</span>
				</div>
			</div>	
<?php
// PASSWORD fields Theme My Login - the custom
// password file in modules/custom passwords folder
?>
<?php
do_action( 'register_form' ); // Wordpress hook
do_action_ref_array( 'tml_register_form', array( &$template ) ); //TML hook
?>

			<div class="form-item">
				<label for="nh_cities<?php $template->the_instance(); ?>"><?php _e( 'Your City', 'theme-my-login' ) ?></label>
<?php
$taxonomy = 'nh_cities';
$terms = get_terms($taxonomy);
$default_city = 'Philadelphia PA';
$posted_city = esc_attr($_POST['nh_cities']);
if ($terms) {
?>
<select tabindex="40" name="nh_cities" class="input" id="nh_cities<?php $template->the_instance(); ?>" value="<?php $template->the_posted_value('nh_cities');?>">
<?php
	foreach ($terms as $term) {	
?>	
	
<option<?php 
	if (!empty($posted_city) AND $posted_city == $term->name) { 
		echo ' selected="yes"'; 
	} 
	elseif (empty($posted_city) AND $term->name == $default_city) {
		echo ' selected="yes"'; 
	} 
?> value="<?php echo $term->name;?>"><?php echo $term->name;?></option>
<?php
	}
?>
<option value="newcity">My city&#39;s not here!</option></select>
<?php
}
?>	
or <label for="user_city<?php $template->the_instance(); ?>"><?php _e( 'City Name', 'theme-my-login' ) ?></label>

<input type="text" name="user_city" id="user_city<?php $template->the_instance(); ?>" class="input" value="<?php $template->the_posted_value( 'user_city' ); ?>" size="20" tabindex="45" required />		
			<div class="help-block"><span class="txt-help">Select your city. If your city isn&#39;t on the list, sign up by entering your city on the right. The more people who sign up from your city, the sooner your city will get on the list!</span>			
				</div>
			</div>

			<p id="reg_passmail<?php $template->the_instance(); ?>"><?php echo apply_filters( 'tml_register_passmail_template_message', __( 'A password will be e-mailed to you.', 'theme-my-login' ) ); ?></p>

			<p id="nh-submit" class="submit reg-with-pwd">
	            <input class="nh-btn-orange" type="submit" name="wp-submit" id="wp-submit<?php $template->the_instance(); ?>" value="<?php _e( 'Sign Up', 'theme-my-login' ); ?>" tabindex="45" />
				<input type="hidden" name="redirect_to" value="<?php $template->the_redirect_url( 'register' ); ?>" />
				<input type="hidden" name="instance" value="<?php $template->the_instance(); ?>" />
	        </p>
	    	</form>
	
<style>
.ui-autocomplete-category {
	font-weight: bold;
	padding: .2em .4em;
	margin: .8em 0 .2em;
	line-height: 1.5;
}
/* Component containers
----------------------------------*/
.ui-widget { font-family: Trebuchet MS,Verdana,Arial,sans-serif; font-size: 1em; }
.ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button { font-family: Trebuchet MS,Verdana,Arial,sans-serif; font-size: 1em; }
.ui-widget-header { border: 1px solid #ffffff; background: #464646 url(images/464646_40x100_textures_01_flat_100.png) 50% 50% repeat-x; color: #ffffff; font-weight: bold; }
.ui-widget-header a { color: #ffffff; }
.ui-widget-content { border: 1px solid #ffffff; background: #ffffff url(images/ffffff_40x100_textures_01_flat_75.png) 50% 50% repeat-x; color: #222222; }
.ui-widget-content a { color: #222222; }

/* Interaction states
----------------------------------*/
.ui-state-default, .ui-widget-content .ui-state-default { border: 1px solid #666666; background: #555555 url(images/555555_40x100_textures_03_highlight_soft_75.png) 50% 50% repeat-x; font-weight: normal; color: #ffffff; outline: none; }
.ui-state-default a { color: #ffffff; text-decoration: none; outline: none; }
.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus { border: 1px solid #666666; background: #444444 url(images/444444_40x100_textures_03_highlight_soft_60.png) 50% 50% repeat-x; font-weight: normal; color: #ffffff; outline: none; }
.ui-state-hover a { color: #ffffff; text-decoration: none; outline: none; }
.ui-state-active, .ui-widget-content .ui-state-active { border: 1px solid #666666; background: #ffffff url(images/ffffff_40x100_textures_01_flat_65.png) 50% 50% repeat-x; font-weight: normal; color: #F6921E; outline: none; }
.ui-state-active a { color: #F6921E; outline: none; text-decoration: none; }
/* Interaction Cues
----------------------------------*/
.ui-state-highlight, .ui-widget-content .ui-state-highlight {border: 1px solid #fcefa1; background: #fbf9ee url(images/fbf9ee_40x100_textures_02_glass_55.png) 50% 50% repeat-x; color: #363636; }
.ui-state-error, .ui-widget-content .ui-state-error {border: 1px solid #cd0a0a; background: #fef1ec url(images/fef1ec_40x100_textures_05_inset_soft_95.png) 50% bottom repeat-x; color: #cd0a0a; }
.ui-state-error-text, .ui-widget-content .ui-state-error-text { color: #cd0a0a; }
.ui-state-disabled, .ui-widget-content .ui-state-disabled { opacity: .35; filter:Alpha(Opacity=35); background-image: none; }
.ui-priority-primary, .ui-widget-content .ui-priority-primary { font-weight: bold; }
.ui-priority-secondary, .ui-widget-content .ui-priority-secondary { opacity: .7; filter:Alpha(Opacity=70); font-weight: normal; }

/* Icons
----------------------------------*/

/* states and images */
#demo-frame-wrapper .ui-icon, .ui-icon { width: 16px; height: 16px; background-image: url(images/222222_256x240_icons_icons.png); }
.ui-widget-content .ui-icon {background-image: url(images/222222_256x240_icons_icons.png); }
.ui-widget-header .ui-icon {background-image: url(images/222222_256x240_icons_icons.png); }
.ui-state-default .ui-icon { background-image: url(images/888888_256x240_icons_icons.png); }
.ui-state-hover .ui-icon, .ui-state-focus .ui-icon {background-image: url(images/454545_256x240_icons_icons.png); }
.ui-state-active .ui-icon {background-image: url(images/454545_256x240_icons_icons.png); }
.ui-state-highlight .ui-icon {background-image: url(images/2e83ff_256x240_icons_icons.png); }
.ui-state-error .ui-icon, .ui-state-error-text .ui-icon {background-image: url(images/cd0a0a_256x240_icons_icons.png); }


</style>

<div class="demo">

<div class="ui-widget">
	<label for="tags">Tag programming languages: </label>
	<input id="tags" size="50" />
</div>

</div><!-- End demo -->	
	
	
	

		</div><!-- / login-->
	</div><!--/ page-register-->
</div><!--/ content-->
<?php //get_sidebar('login-register'); ?>