=== Better Postviews ===
Contributors: vedstudio, rcoll
Tags: post, view, views, analytics
Requires at least: 3.0.1
Tested up to: 3.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An efficient plugin which tracks all single-post views. Easily use the data anywhere in your theme or plugin.

== Description ==

This is an efficient plugin which tracks all single-post views. It saves the data as post meta data which can easily be updated if needed. The view count can be seen on the grid on the post screen. The plugin also provides an easy way to get the top post data and use in other plugins or theme files (see below). WordPress high-volume tested.

== Installation ==

Automatic Installation: 

1. Go to Admin - Plugins - Add New and search for "Better Postviews"
2. Click on Install
3. Click on Activate

Manual Installation:

1. Download better-postviews.zip
2. Unzip and upload the "better-postviews" folder to your "/wp-content/plugins/" directory
3. Activate the plugin through the "Plugins" menu in WordPress

Configuration:

This plugin will work out-of-the-box - no configuration is necessary and there is no admin interface. However, it might be necessary to modify a few options (in the code) for a better experience.

1. To disable tracking but retain the output, change "true" to "false" on line 13 of better-postviews.php
2. To disable the post column view but retain tracking, change "true" to "false" on line 14 of better-postviews.php
3. To enable a debug output in comments at the bottom of your sites source code, change "false" to "true" on line 15 of better-postviews.php
4. To increase or decrease the transient lifetime of the top-one, top-three, and top-five data, change "300" on line 16 to a value of your choice
5. To use the top-one, top-three, or top-five data elsewhere, simply call better_postviews::get_top_one(), better_postviews::get_top_three(), or better_postviews::get_top_five() and it will return an array of post IDs with element 0 being greatest - prepped and ready for your custom WP_Query!
6. Will add get_top_five_count or something similar in the future.

== Frequently Asked Questions ==

None yet.

== Screenshots ==

1. A view of the post grid in the administration area which shows view counts for each post.

== Changelog ==
= 1.5 =
* Added several filters to give developers more control

= 1.0.1 =
* Fixing readme.txt markup

= 1.0 =
* Initial submission of plugin files

== Upgrade Notice ==

= 1.0.1 =
* Proper readme.txt markup in this version

= 1.0 =
* Download the first version of the plugin