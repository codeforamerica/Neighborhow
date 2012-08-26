=== User Photo ===
Contributors: westonruter, ryanhellyer
Tags: users, photos, images
Requires at least: 3.0.5
Stable tag: 0.9.5.2

Allows a user to associate a photo with their account and for this photo to be displayed in their posts and comments.

== Description ==

***Make sure you upgrade to version 0.9.5.2!***

Allows a user to associate a profile photo with their account through their "Your Profile" page. Admins may 
add a user profile photo by accessing the "Edit User" page. Uploaded images are resized to fit the dimensions specified 
on the options page; a thumbnail image correspondingly is also generated. 
User photos may be displayed within a post or a comment to 
help identify the author. New template tags introduced are: 

*   <code>userphoto_the_author_photo()</code>
*   <code>userphoto_the_author_thumbnail()</code>
*   <code>userphoto_comment_author_photo()</code>
*   <code>userphoto_comment_author_thumbnail()</code>

*Important: all of these "template tags" must appear inside of PHP script blocks (see examples below).*
The first two should be placed in the posts loop near <code>the_author()</code>, and the second two in the comments
loop near <code>comment_author()</code> (or their respective equivalents). Furthermore, <code>userphoto_the_author_photo()</code>
and <code>userphoto_the_author_thumbnail()</code> may be called anywhere (i.e. sidebar) if <code>$authordata</code> is set.

The output of these template tags may be modified by passing four parameters: `$before`, `$after`, `$attributes`, and `$default_src`,
as in: <code>userphoto_the_author_photo($before, $after, $attributes, $default_src)</code>.
If the user photo exists (or <code>$default_src</code> is supplied), then the text provided in the <code>$before</code> and <code>$after</code> parameters is respectively
prefixed and suffixed to the generated <code>img</code> tag (a common pattern in WordPress). If attributes are provided in the <code>$attributes</code>
parameter, then they are returned as attributes of the generated <code>img</code> element. For example: <code>userphoto_the_author_photo('', '', array(style => 'border:0'))</code>
Just added in 0.8.1 release are these two new template tags:

*   <code>userphoto($user, $before = '', $after = '', $attributes = array(), $default_src = '')</code>
*   <code>userphoto_thumbnail($user, $before = '', $after = '', $attributes = array(), $default_src = '')</code>

By using these, it is uneccessary to set the global <code>$authordata</code> to display a photo. Just pass <code>$authordata</code>, <code>$curauth</code> or
whatever variable you have which contains the user object, or (as of version 0.9), pass in a user ID or a user login name.

Here's an example that shows a few ways of inserting a user's photo into the post loop:
	//this will display the user's avatar if they don't have a user photo,

	<?php while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
			<div class="meta">
				<?php the_time('F jS, Y') ?>
				by <?php the_author() ?>
				
				<!-- displays the user's photo and then thumbnail -->
				<?php userphoto_the_author_photo() ?>
				<?php userphoto_the_author_thumbnail() ?>
				
				<!-- the following two do the same since $authordata populated -->
				<?php userphoto($authordata) ?>
				<?php userphoto_thumbnail($authordata) ?>
				
				<!-- and this is how to customize the output -->
				<?php userphoto_the_author_photo(
					'<b>Photo of me: ',
					'</b>',
					array('class' => 'photo'),
					get_template_directory_uri() . '/nophoto.jpg'
				) ?>
			</div>
			<?php the_content('Read the rest of this entry &raquo;'); ?>
		</div>
	<?php endwhile; ?>

If you want to display the user's photo in the sidebar, just get the user ID or object and pass it into <code>userphoto()</code> or <code>userphoto_thumbnail()</code> like this:

	<?php userphoto($posts[0]->post_author); ?>

If you want to display a user's photo their author page, you may do this:

	<?php userphoto($wp_query->get_queried_object()) ?>

In version 0.9 the boolean function `userphoto_exists($user)` has been introduced which returns true if the user has a photo and false if they do not.
Argument `$user` may be user object, ID, or login name. This function can be used along with avatars:

	<?php
	if(userphoto_exists($user))
		userphoto($user);
	else
		echo get_avatar($user->ID, 96);
	?>

Or if the new "Serve Avatar as Fallback" option is turned on, then the avatar will be served by any of the regular calls to display the user photo:

	<?php
	//this will display the user's avatar if they don't have a user photo,
	//  and if "Serve Avatar as Fallback" is turned on
	userphoto($user);
	?>

Additionally, all of the regular function calls to display the user photo may be done away with alltogether if the new "Override Avatar with User Photo"
option is enabled:

	<?php
	//both will display the user photo if it exists
	//  and if "Override Avatar with User Photo" is turned on
	echo get_avatar($user_id);
	echo get_avatar($user->user_email);
	?>

Both options "Serve Avatar as Fallback" and "Override Avatar with User Photo" require that the 'Avatar Display' setting under Discussion be set to "Show". 

Uploaded images may be moderated by administrators via the "Edit User" page.

Localizations included for Spanish, German, Dutch, Polish, Russian, French, Hungarian, Brazilian Portuguese, Italian, and Catalan.

If you value this plugin, *please donate* to ensure that it may continue to be maintained and improved.

== Changelog ==

= 2012-05-08: 0.9.5.2 =

* Security issue (credit Ryan Hellyer).

= 2011-02-17: 0.9.5 =

* Fixing major security issue (credit ADVtools SARL).

= 2009-7-28 =

* Fixed a small bug relating to line 453 changing "home" to "siteurl". Thanks Piotr!

= 2009-02-13 =

* Added Catalan localization. Thanks Robert!

* 2009-02-28 =

* Added Italian localization. Thanks Federico!

= 2009-02-17 =

* Added Brazilian Portuguese localization. Thanks gui!

= 2009-01-07 =

* Added Hungarian localization. Thanks Csaba!

= 2008-12-11 =

* Added French localization. Thanks Jean-Pierre!

= 2008-11-14: 0.9.4 =

* Now displaying error message if <code>wp_upload_dir()</code> fails when trying to display a user photo.

= 2008-11-14: 0.9.3 =

* Forcing the uploaded filename to lower-case

= 2008-11-03: 0.9.2 =

* Updated error message to include results for <code>wp_upload_dir()</code>

= 2008-09-22: 0.9.1 =

* Updated error messages to be more helpful (includes the paths in question). This will help debug some of the issues that have been raised on the forums lately.

= 2008-09-22: 0.9 =

* First argument to `userphoto()` and `userphoto_thumbnail()` may now just be a user ID or user login name in addition to a user object.
* New "Serve Avatar as Fallback" option; this is disabled by default.
* New boolean function `userphoto_exists($user)` which returns true if the user has a photo and false if they do not. Argument `$user` may be user object, ID, or login name.
* New option "Override Avatar with User Photo"; disabled by default.
* Adding `class="photo"` by default if no class attribute is supplied
* Fixed issue where thumbnail (and associated usermeta) wasn't being deleted along with the full-size photo (thanks Oliver).
* Now using `wp_upload_dir()` to get the basedir for where the userphoto directory will be located. 

= 2008-08-01: 0.8.2 =

* Verified that works in WP 2.6; added note explaining what the error message regarding what "image resizing not available" means... namely that the GD module is not installed.

= 2008-05-29: 0.8.1 =

* Added localization for Russian (thanks Kyr!)

= 2008-05-17: 0.8.1 =

* Finally updated the plugin for WP 2.5. Note that it still worked for 2.5, it's just the admin interfaces needed to be updated. Also added <code>userphoto()</code> and <code>userphoto_thumbnail()</code> template tags.

= 2008-04-23: 0.8.0.5 =

* Added localization for Polish (thanks Maciej!)

= 2008-04-04: 0.8.0.4 =

* Fixed issue where incorrect path was being generated for default photo.

= 2008-04-04: 0.8.0.3 =

* Using `wp_mail` instead of `mail` (Thanks again, Kyle.)

= 2008-03-28: 0.8.0.2b =

* Ensured that "unapproved" photos are not displayed. (Thanks Kyle.)

= 2008-02-24: 0.8.0.2 =

* Made minor improvement to security.

= 2008-02-13: 0.8.0.1 =

* Removed <code>print_r()</code> from being called when using <code>$default_src</code> (thanks David!)

= 2008-02-04: 0.8 =

* Allow before and after text to be outputted when there is a user photo.
* Allow attributes to be passed into template tags, including a default SRC value to be used when there is no user photo.
* Added Dutch localization translated by Joep Stender (thanks!)

= 2008-01-07: 0.7.4b =

* Added German localization translated by Robert Harm (thanks!)

= 2008-01-06: 0.7.4 =

* Added support for localization and added Spanish localization translated by Pakus (thanks!)

= 2008-01-02: 0.7.3 =

* Fixed issue where the post author photo was inadvertently used for non-registered comment author photos.

= 2007-12-28: 0.7.2 =

* Improved error message raised when unable to create 'userphoto' directory under /wp-content/uploads/. It now asks about whether write-permissions are set for the directory.
* Improved the plugin activation handler.
* All uploaded images are now explicitly set to chmod 666.

= 2007-12-22: 0.7.1 =

* All functions (and template tags) now are prefixed with "userphoto_"

= 2007-12-18: 0.7.0.1 =

* Now using `siteurl` option instead of `home` option
* Fixed the inclusion of the stylesheet for the options page

= Todo =
1. When changing the authorization level, all previous users' photos should be automatically approved if they meet the minimum user level
1. Include a get_userphoto() and get_userphoto_thumbnail() ?
1. Add a management page to allow admins to quickly approve/reject user photos.
1. Add option so that when a photo is rejected, the user is notified.
1. Restrict image types acceptable?
1. Add an option to indicate a default photo to be used when none supplied.

== Screenshots ==
1. Admin section in User Profile page

