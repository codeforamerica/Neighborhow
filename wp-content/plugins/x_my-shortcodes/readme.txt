=== My Shortcodes ===
Contributors: Desertsnowman
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=PA8U68HRBXTEU&lc=ZA&item_name=my-shortcodes&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted

Tags: shortcode, My Shortcodes, shortcode builder, custom shortcode, custom code, widgets, custom widget
Requires at least: 3.3
Tested up to: 3.4.1
Stable tag: 1.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to add custom code as a shortcode to be used within your pages or posts.

== Description ==

Build custom shortcode elements or download and install shortcodes made by other My Shortcodes users.

Highly flexible shortcode builder environment. dedicated areas for template view, javascript input, custom PHP library, external/CDN css and javascript sources.
This enables you to render the page or posts with the requires scripts and styles to be placed where it belongs. not all in the shortcode replace area.

[Documentation](http://myshortcodes.cramer.co.za/documentation/)

== Installation ==

1. Upload `my-shortcodes` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to the My Shortcodes menu item and start creating your own shortcodes.

== Screenshots ==

1. List of shortcodes that have been created with information like shortcode to use.
2. A shortcode with attributes with info panel showing what attributes you can use and examples for each.
3. Template editing environment
3. Page rendering process and where shortcode scripts are loaded.

== Frequently Asked Questions ==

Well, Its new and there are no questions yet.

== Upgrade Notice ==

nothing special, just overwrite the old one.

== Changelog ==

= 1.9 =
* html is properly escaped before going into the code editor so using <textarea> tags wont break it.

= 1.8 =
* Fixed a bug that prevented CSS and JS from rendering if a page with a shortcode is placed as the home page.

= 1.7 =
* Fixed a bug that messed up utf-8 characters.

= 1.6 =
* Description text for inserting a shortcode without any attributes. changed to sound less like an error.
* Corrected an error that prevented running shortcodes on the home page

= 1.5 =
* Corrected a bug that prevented PHP from rendering on some pages.
* Fixed a bug that allowed prevented the footer scripts from running.

= 1.4 =
* Added correction to the attribute access in CSS and Javascript.

= 1.3 =
* Improved the shortcode detection to load all scripts.
* Added another screenshot that explains the rendering process.
* Added a tab in admin that links to the explain screen.
* Added PHP execution in javascript and CSS tabs. (Yup, dynamic CSS and javascript!).
* Added an con to the admin menu item.

= 1.2 =
* Added an insert shortcode button to the post editor.

= 1.1 =
* Added attribute codes to javascript panel so you can use attributes in output scripts as well.
* Fixed a bug that reverted the external javascript library to be placed in footer on edit.

= 1.0 =
* Initial Release