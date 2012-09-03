=== Formidable Plus ===
Contributors: topquarky
Tags: formidable, forms, table, new field-type
Requires at least: 2.5
Tested up to: 3.3.1
Stable tag: 1.1.7.1

*Please Note* version 1.1.7 includes a *CRITICAL* fix to make Formidable Plus compatible with Formidable Pro 1.06.03.  If you've upgraded Formidable Pro, please upgrade Formidable Plus *immediately*

This plugin adds a new field type to the Formidable Pro plugin.  It allows you to add a table to your form.

== Description ==

I'm a big fan of the [Stephanie Wells'](http://strategy11.com) [Formidable Pro](http://formidablepro.com) plugin.  On a recent project, the client needed to have a *table* field type for people entering financials vs. timeline.  This add-on to Formidable Pro is the result of that work.  

It integrates into Formidable Pro via the latter's wealth of filters and actions.  You can have any number of rows and any number of columns.  If you create a table without any rows (only columns), then the person filling out the form has the opportunity to add more rows as needed.  

Administrators can re-order, add & delete rows or columns and any existing data gets updated to retain integrity.  

Using special column/row naming nomenclature, administrators can turn the input field from the default `text` to either `textarea`, `select`, `radio` or `checkbox`.  See the FAQ for more information 

== Installation ==

1. Formidable Plus is available for purchase from [topquark.com](http://topquark.com)
1. Purchase/Download Formidable Plus from [topquark.com/extend/plugins/formidable-plus](http://topquark.com/extend/plugins/formidable-plus)
1. Install the ZIP file to your server and activate the plugin

== Frequently Asked Questions ==

= What version of Formidable Pro do I need? =

I've tested it in Formidable Pro 1.02.01 and 1.06.03.  Should work everywhere between as well and may work in earlier versions, though table fields will not display properly in Formidable before 1.05.04.  If something else doesn't seem to be working with your version, contact me at [topquark.com](http://topquark.com)

= I tried to run an export and my table fields just show Array,Array =

Unfortunately at this point exporting and importing does not work for table fields.  It is at the top of my feature list to get into a future release of Formidable Plus.  

= Can I create a table where the person filling out the form can add rows dynamically =

Yes.  To do this, simply create a table field and don't add any rows.  (Add and name as many columns as you'd like).  When that table gets rendered in the form, there will be options to add new rows

= How do I make the input field into a textarea for multiline input? =

If you give your column (or row) name a prefix of `textarea:` (i.e. `textarea:My Column Name`), then all cells in that column (or row) will be rendered as `<textarea>`, allowing multiiline input.

= How about Radio Buttons, Checkboxes and Dropdowns? =

As of Formidable Plus 1.1.0, you now have several options for input type within your table.  To use this feature, you will follow the example from the textarea above and prefix your column or row name with a field type.  Here are the available types:

* `textarea:{name}` - for multiline input (e.g. `textarea:My Row Name`)
* `checkbox:{name}` - for a checkbox with a checked value of `on` (e.g. `checkbox:My Row Name`)
* `checkbox:{name}:{value}` - for a checkbox with a checked value of `value` (e.g. `checkbox:My Row Name:Yes`)
* `checkbox:{name}:{value1|value2|value3|...}` - for a set of multiple checkboxes, each with a the corresponding checked value.  You can put as many options as you'd like, separating each by the `|` character (e.g. `checkbox:Fruits You Like:Apples|Oranges|Bananas`)
* `select:{name}:{value1|value2|value3|...}` - for a dropdown box. You can add as many options as you'd like to a dropdown box, separating each by the `|` character (e.g. `select:Choose a Fruit:Apples|Oranges|Bananas`)
* `radio:{name}:{value1|value2|value3|...}` - for a group of radio buttons within the table cell (e.g. `radio:My Favourite Car:Honda|Ford|Lincoln|Mazda`)
* `radioline:{name} - this will create a group of radio buttons across the entire row (or column), one button per cell that allows the user to choose an entire column (or row) (e.g. `radioline:Choose A Column`) 

= Can I dynamically fill the options for field types that allow them (select, radio, checkbox) = 
This is an advanced question and requires you to write an additional plugin and call a filter, but the short answer is yes, you can.  

If you're comfortable writing plugins, you'll want to `add_filter('frmplus_field_options','my_frmplus_field_options',10,5)` and then write something like this:

`function my_frmplus_field_options($options,$field,$name,$row_num,$col_num){
	// My table field is field 462 and the name of the row I want to affect is 'Fruits You Like'
	// I could also check the row_num or col_num to figure out if I want to update the options
	if ($field['id'] == '462' and $name == 'Fruits You Like'){
		static $fruit_options; // use a static because this function gets called ones for every cell in the row
		if (!isset($fruit_options)){
			$fruit_options = array('Bananas','Oranges','Apples','Peaches','Pears','Plums'); // or fill dynamically somehow
		}
		$options = $fruit_options;
	}
	return $options;
}`

= I inserted a table field into a custom display and all that shows up is "Array,Array" = 

This issue is resolved with Formidable Pro 1.05.04 and Formidable Plus 1.0.4.  Please upgrade.  

= Can I enable arrow-key navigation through a table? =

It's possible to enable users to use the arrow keys to navigate through a table.  This can be useful for large tables.  To do this, you need to add the class `use-arrow-keys` to your table.  To do that in PHP, hook into the filter frm_table_classes like so:

`add_filter('frm_table_classes','my_table_classes',10,2);
function my_table_classes($classes,$field_id){
	$my_field_id = 12; // the field_id of your table field
	if ($field_id == $my_field_id){
		$classes[] = 'use-arrow-keys';
	}
	return $classes;
}`

= I have a big table and the user loses the column/row headings when navigating around it =

It's possible to enable a tooltip which pops up the row/column name when the user focusses on a table cell.  This can be useful for large tables.  To do this, you need to add the class `use-tooltips` to your table.  To do that in PHP, hook into the filter frm_table_classes like so:

`add_filter('frm_table_classes','my_table_classes',10,2);
function my_table_classes($classes,$field_id){
	$my_field_id = 12; // the field_id of your table field
	if ($field_id == $my_field_id){
		$classes[] = 'use-tooltips';
	}
	return $classes;
}`

= People have made entries and I want to reorder/rename some of my rows/columns = 
 
No problem.  When you reorder, add or delete rows or columns, Formidable Plus will update all of your data to the appropriate new values.  No data will be lost.

== Screenshots ==

1. The admin view of a simple menu planning table
2. What the menu planner looks like to the end-user
3. The same form, but with all rows removed, and a new column to allow the end user to specify which meal.  They can add/delete rows
4. Examples with different field types within the table


== Changelog ==

= 1.1.7.1 = 
* Fix: further addressed the compatibility problem fixed in 1.1.7 for a case that popped up (thanks bebetsy.com)

= 1.1.7 = 
* *Critical Update* - fixed a compatibility problem with the latest version of Formidable Pro that caused data loss when editting entries with table fields

= 1.1.6.3rfc = 
* Fix: simple checkboxes now save the correct value (thanks Jason Hill for bringing the problem to my attention)

= 1.1.6.2rfc = 
* Fix: mutlibyte characters in textareas now properly encoded
* Added: on a dynamic table, when a row is added or deleted, the event 'add_row' or 'delete_row' is triggered on the table.  See `js/frm_plus.js` for details
* Deprecated: the post_add_row and post_delete_row methods, in favour of the event mechanism just added
* Added: a filter on the "Add Row" (frmplus-text-add-row) and "Delete Row" (frmplus-text-delete-row) text, to allow you to change those labels.  
* Added: an ajax indicator when you click Add Row

= 1.1.6.1rfc = 
* Fix: bug where td and tr elements weren't getting col-n and row-n classes.  (thanks JoeErsinghaus)
* Fix: unnecessary session() code removed from Entries Controller. (thanks glyphicwebdesign.com)

= 1.1.6 = 
* Critical fix: a bug that could have resulted in lost data when reordering columns on a table field a multipage form.  

= 1.1.5 = 
* Fix: a bug that prevented adding columns to a table field. A PHP error was being thrown trying to use a string as an array.
* Change: the main javascript file, frm_plus.js, is now plain javascript, as opposed to js.php.  This is because some installations of WordPress do not allow loading of .php files as scripts. They return a 404.  

= 1.1.4 = 
* Fixed: a table field on an earlier page in a multi-page form now gets saved properly (requires Formidable Pro 1.6.x - yet to be released).  If you're needing to use this on a multi-page form on Formidable 1.5.x, please contact support@topquark.com to obtain a formidable patch.  
* Added: a column-{n} class to each <td> element in the table.  Should allow better CSS control over individual columns

= 1.1.3 = 
* Emailed form results now properly display table field types.  (Note: looks like crap if you choose to send Plain Text)

= 1.1.2 = 
* Fixed a warning thrown on non-administrator profile page

= 1.1.1 = 
* Updated Top Quark credentials to work on multisite

= 1.1.0 = 
* Added new field types, allowing table to have checkboxes, radio buttons & dropdowns

= 1.0.4 =
* Changed the settings page to access the proper 'formidable-plus' settings page, instead of the generic 'plugin' settings page
* Used new hook in Formidable Pro 1.05.04 for Custom Displays.  The `[frm_table_display]` shortcode from Plus version 1.0.3 is now deprecated

= 1.0.3 =
* Added a shortcode for custom displays - `[frm_table_display id=N]` (where N is the field ID).  Works around the "Array,Array" problem when inserting a table field into a custom display

= 1.0.2 =
* Added TopQuark.com authentication

= 1.0.1 =
* Initial check-in

== Upgrade Notice ==

= 1.0.4 =
You will have to re-enter your TopQuark credentials on the Settings > Formidable Plus page.

= 1.0.2 =
You will only be able to upgrade to this version after purchasing the plugin from [TopQuark.com](http://topquark.com/extend/plugins/formidable-plus)

= 1.0.1 =
No upgrade notice

