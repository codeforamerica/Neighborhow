=== Revision Control ===
Contributors: dd32
Tags: revisions, post, admin
Requires at least: 3.2
Stable tag: 2.1

Revision Control allows finer control over the Post Revision system included with WordPress

== Description ==

Revision Control is a plugin for WordPress which gives the user more control over the Revision functionality.

The plugin allows the user to set a site-global setting (Settings -> Revisions) for pages/posts to enable/disable/limit the number of revisions which are saved for the page/post. The user may change this setting on a per-page/post basis from the Revisions Meta box.

The plugin also allows the deletion of specific revisions via the Revisions post metabox.

== Ugrade Notice ==

= 2.1 =
 Belorussian Translation, 3.2.x styling, Compare/Delete toggle fix, properly delete Taxonomy relationships.

== Changelog ==

= 2.1 =
 * Belorussian Translation from MarcisG
 * Bugfix: Delete Taxonomy relations for Revisions upon revision deletion
 * Bigfix: Compare/Delete toggling
 * Correct styling for WordPress 3.2+
 * 

= 2.0.1 =
 * Small IE bug fix, Table formatting was a bit wacky.
 * RECALLED update, Had brought up some issues in other browsers

= 2.0 =
 * Rewrite from scratch(99%) utilising 2.9 only functionality
 * Better support for custom post types, Next release will finialise it in line with WordPress 3.0 development
 * Storing of Taxonomy changes in revisions (eg. You can see that TagX was added, while Category Z was removed.)
 * Translations:
 * German Translation from Tux
 * Hebrew Translation from Elad Salomons - http://elad.blogli.co.il
 * Russian Translation from Lecactus
 * Italian Translation from Stefano Aglietti
 * Estonian Translation from Lembit
 * Japanese Translation from Tai

= 2.0-pre =
 * Swedish Translation from Linus
 * Estonian Translation from "Lembit Kivisik" <lembit@designux.com>

= 1.9.x =
  * Belorussian translation from Marcis

= 1.9.7 =
 * 1.9.2, 1.9.3, 1.9.5 all seem screwy, Something was wrong with my SVN client, it was commiting from a previous revision and not my current revision... 
 * 2.8.1 compatibility

= 1.9.1 =
 * Small bugfixes

= 1.9 =
 * Spanish Translation from Alejandro 
 * Turkish Translation from Semih
 * Latvian Translation from Rolands
 * Fix 'Disabled' per-object checkbox
 * Introduce DD32's common data class, Adds Update version changelog functionality
 * WP 2.7 compatibility, This is mainly a maintanence release until version 2.0 is fully finalised.

= 1.8 =
 * German Translation from Tux
 * Czech Translation from Pavel
 * Dutch Translation from Steven
 * Russian Translation from Кактус
 * French Translation from David
 * Bug fix: Limit revisions dropdown sticks to 2 revisions on admin panel.
 * No features added.

= 1.7 =
 * Fix a bug with Firefox stealing the focus on the Revision limit drop-down, Thanks Viper007Bond
 * Added HeBrew translation, Thanks Elad!
 * No features added.

= 1.6 =
 * oops, Forgot something from 1.5: If you set the page/posts's option to the *same* as the default, Then the per-page option is now forgotten.

= 1.5 =
 * Skipped 1.4
 * Sticking option values should finally be fixed.
 * Thanks to Translators, Apologies to Translators for releasing 1.5 with changes before getting updated lang files

= 1.1, 1.2, 1.3 =
 * Italian & Japanese Translations
 * Allows Deletion of a Single revision via the Revisions post box
 * The global setting has been divded into Posts and Pages, The setting should now stick past a page load
 * The per-post setting should now correctly work again (After a bug introduced in 1.2)

= 1.0 =
 * Initial Release

== Screenshots ==

1. The Revisions Meta box
2. Revision Controls global settings