<?php
if (!defined('TOPQUARK_SETTINGS')){
	define('TOPQUARK_SETTINGS','DONE_DID_EM');	
	add_action('register_topquark_plugin','register_topquark_plugin',10,2);
	function register_topquark_plugin($plugin,$mothership = 'http://topquark.com'){
		$TopQuarkPlugin = new TopQuarkPlugin($plugin,$mothership);
	}
	class TopQuarkPlugin{
		var $name;
		var $slug;
		var $option_name;

		// Pass an array($PluginName,$PluginSlug,$OptionsSlug);
		// As long as $PluginSlug == sanitize_title($PluginName) and $OptionSlug == str_replace('-','_',sanitize_title($PluginName)).'_plugin_options'
		// then you can just pass in $PluginName
		function TopQuarkPlugin($plugin,$mothership){
			if (!is_array($plugin)){
				$this->name = $plugin;
			}
			else{
				list($this->name,$this->slug,$this->option_name) = $plugin;
			}
			if (!$this->slug){
				$this->slug = sanitize_title($this->name);
			}
			$this->underscore_slug = str_replace('-','_',$this->slug);
			if (!$this->option_name){
				$this->option_name = $this->underscore_slug.'_plugin_options';
			}
			$this->mothership = $mothership;

			add_action('admin_menu',array(&$this,'maybe_create_settings_page'),99);
			if (is_admin() and ((isset($_GET['page']) and $_GET['page'] == $this->slug) or (isset($_REQUEST['option_page']) and $_REQUEST['option_page'] == $this->option_name))){
				add_action('admin_init',array(&$this,'admin_init'),3);
				add_filter($this->underscore_slug.'_valid_options',array(&$this,'valid_options'));
				add_action('update_option_'.$this->option_name,array(&$this,'update_option'),10,2);
				add_action('add_option_'.$this->option_name,array(&$this,'update_option'),10,2);
			}
			add_filter('puc_request_info_query_args-'.$this->slug,array(&$this,'request_info_query_args'));
		}

		function admin_init(){		
			add_settings_section($this->underscore_slug.'_topquark', 'Top Quark Credentials', array(&$this,'topquark_settings_section'), $this->slug);
			if ($this->option('topquark_save_as_global') or !$this->site_option('email') or !$this->option('topquark_use_global')){
				add_settings_field('topquark_email', 'Email:', array(&$this,'topquark_settings_fields'), $this->slug, $this->underscore_slug.'_topquark','topquark_email');
				add_settings_field('topquark_password', 'Password:', array(&$this,'topquark_settings_fields'), $this->slug, $this->underscore_slug.'_topquark','topquark_password');
			}
			if (!$this->option('topquark_save_as_global') and $this->site_option('email')){
				// There are global credentials saved on this site
				add_settings_field('topquark_use_global', 'Use Global:', array(&$this,'topquark_settings_global'), $this->slug, $this->underscore_slug.'_topquark','topquark_use_global');
			}
			else{
				add_settings_field('topquark_save_as_global', 'Save as Global:', array(&$this,'topquark_settings_global'), $this->slug, $this->underscore_slug.'_topquark','topquark_save_as_global');
			}
		}
		
		function valid_options($valid){
			$valid['topquark_email'] = '';
			$valid['topquark_password'] = '';
			$valid['topquark_save_as_global'] = '';
			$valid['topquark_use_global'] = '';
			return $valid;
		}

		function update_option($oldvalue,$newvalue){
			if ($newvalue['topquark_save_as_global']){
				// They have specified that they want to save these credentials as global Top Quark 
				// credentials.  Here's where that happens
				$global_credentials = array();
				$global_credentials['topquark_email'] = $newvalue['topquark_email'];
				$global_credentials['topquark_password'] = $newvalue['topquark_password'];
				$global_credentials['topquark_master'] = admin_url('options-general.php?page='.$this->slug);
				if (get_site_option('topquark_global_credentials') === false){
					add_site_option('topquark_global_credentials',$global_credentials);
				}
				else{
					update_site_option('topquark_global_credentials',$global_credentials);
				}
			}
			elseif (is_array($oldvalue) and $oldvalue['topquark_save_as_global']){
				// remove old credentials
				delete_site_option('topquark_global_credentials');				
			}
		}

		function topquark_settings_section(){
			echo '<p>To receive updates to this plugin, you must have purchased '.$this->name.' from TopQuark.com.  Please enter your Top Quark email/password below, or visit <a href="http://topquark.com/extend/plugins/'.$this->slug.'">TopQuark.com</a> to purchase.</p>';
			$options = $this->get_query_args();
			
			if ($options['topquark_email'] != ''){
				// Verify against the mothership
				$options['topquark_request'] = 'verify';
				$options['topquark_plugin'] = $this->slug;
				$url = $this->mothership.'/?'.http_build_query($options);
				$result = wp_remote_get($url);
				if (!is_array($result) or !is_array($result['response']) or $result['response']['code'] != 200){
					$note = "Unable to communicate with TopQuark.com to verify your settings.";
					echo sprintf('<div class="error fade"><p>%s</p></div>', $note);
				}
				elseif ($result['body'] != 'verified'){
					$note = "Your credentials could not be verified.<br/>";
					$note.= $result['body'];
					echo sprintf('<div class="error fade"><p>%s</p></div>', $note);
				}
				else{
					$note = "Awesome!  You're good to go!";
					echo sprintf('<div class="updated fade"><p>%s</p></div>', $note);
				}
			}
		}

		function topquark_settings_fields($what){
			$option = $this->option($what);
			$name = $id = $what;
			switch($what){
			case 'topquark_password':
				$type = 'password';
				break;
			default:
				$type = 'text';
				break;
			}
			echo "<input id=\"$id\" name=\"".$this->option_name."[$name]\" type=\"$type\" value=\"".esc_attr($option)."\" />";
		}

		function topquark_settings_global($what){
			$option = $this->option($what);
			$name = $id = $what;
			switch($what){
			case 'topquark_use_global':
				$message = sprintf('Use the global %sTop Quark credentials%s','<a href="'.$this->site_option('master').'">','</a>');
				break;
			case 'topquark_save_as_global':
				$message = 'Make these credentials available to all Top Quark Plugins on your site';
				break;
			}
			echo "<input id=\"$id\" name=\"".$this->option_name."[$name]\" type=\"checkbox\" value=\"1\" ".checked($option,1,false)." />";
			echo " <span class=\"description\">$message</span>";
		}

		function option($what){
			if (!isset($this->options)){
				$this->options = get_option($this->option_name);
			}
			if (strpos($what,$this->underscore_slug.'_') === false and isset($this->options[$this->underscore_slug.'_'.$what])){
				$what = $this->underscore_slug.'_'.$what;
			}
			return $this->options[$what];
		}

		function site_option($what){
			if (!isset($this->site_options)){
				$this->site_options = get_site_option('topquark_global_credentials');
			}
			if (strpos($what,'topquark_') === false and isset($this->site_options['topquark_'.$what])){
				$what = 'topquark_'.$what;
			}
			return $this->site_options[$what];
		}
		
		function get_query_args(){
			$options = array();
			if ($this->site_option('topquark_email') and $this->option('topquark_use_global')){
				$options['topquark_email'] = $this->site_option('topquark_email');
				$options['topquark_password'] = md5($this->site_option('topquark_password')); // Don't send password plain text
			}
			else{
				$options['topquark_email'] = $this->option('topquark_email');
				$options['topquark_password'] = md5($this->option('topquark_password')); // Don't send password plain text
			}
			return $options;
		}

		function request_info_query_args($queryArgs){
			$queryArgs+=$this->get_query_args();
			return $queryArgs;
		}

		function maybe_create_settings_page(){
			global $submenu;
			$found = false;
			if (current_user_can('administrator') and is_array($submenu['options-general.php'])){
				foreach ($submenu['options-general.php'] as $subpage){
					if ($subpage[0] == $this->name){
						$found = true;
						break;
					}
				}
				if (!$found){
					// There is no settings page defined for this plugin, so create a stub one
					$this->create_settings_page();
				}
			}
		}
		
		function create_settings_page(){
			add_options_page($this->name, $this->name, 'manage_options', $this->slug, array(&$this,'plugin_options_page'));
			add_action('admin_init',array(&$this,'register_setting'));
		}
		
		function plugin_options_page(){
			echo '
				<div>
				<h2>'.$this->name.'</h2>
				<form action="options.php" method="post">
			';
			settings_fields($this->option_name);
			do_settings_sections($this->slug);
			echo '
				<p class="submit">
				<input id="submit" class="button-primary" type="submit" value="Save Changes" name="submit">
				</p>
				</form></div>
			';
		}
		
		function register_setting(){
			register_setting( $this->option_name, $this->option_name, array(&$this,'plugin_options_validate'));
		}
		
		function plugin_options_validate($input){
			$valid = array();
			$input = wp_parse_args($input,apply_filters($this->underscore_slug.'_valid_options',$valid));
			return $input;
		}


	}
} 


?>