=== Plugin Name ===
Contributors: dgupta
Donate link: http://indian-manpower.com/
Tags: views, wp-views, view, plugin, plugins
Requires at least: 1.0
Tested up to: 1.0
Stable tag: 1.0

All the information you need about this plugin can be found here: wp-create-view Readme.
== Description ==

The Views plugin provides a flexible method for wordpress site designers to control how content of posts and comments are presented.
This tool is essentially a smart query builder that, given enough information, can build the proper query, execute it, and display the results.

You need views if:
You like posts/comments details to display the way you like.
You want a way to display most recent posts of some particular type.
You want to create post/comments Links on your conditions.

Example: "Through the filter feature one can display only those posts which are posted by 'xyz' author, or posted before/after a specific date such type of conditions are easily manageable." It gives the user an option to create a view for display posts and comments. This plugin also creates views for comments/posts/pages for display their links into the widget for sidebars.


== Installation ==

You can simply download this plugin and extract into your plugin folder. Now activate the plugin and create your view by filling up a simple form.
Now plugin will generate a query and would show the preview for that query, after saving the view details.
Now go to the view manage link and activate that view.
If you create a view for the posts, you should use the following function on all those pages where your posts are displayed at the client side. Place this function on the top of the page just before <?php if (have_posts()) : ?>.

<?php show_view_result('post'); ?>.

Similarly if you create a view for comments, then use <?php show_view_result('comment');?> on all those pages where your comments are displayed at the client side. Place this function on the top of the comment pages just before <?php if ( have_comments() ) : ?>.

Note: Please do confirm that the view created by you is activated.

You can create multiple views for comments/posts but only one view will be activated at a time.
Such an awesome piece of work is done by just one of the programmer of http://Indian-manpower.com

== Frequently Asked Questions ==

= Can we change contents  of any specific post using your WP-Create-View plugin? =
No, Our plugin simply fatches the data "as it is" from posts, this date appeares on pages according to the view conditions that u set from admin area.
Though our next version will allow you to change the look of data using specific CSS.

== Screenshots ==
http://indian-manpower.com

== Changelog ==
= 1.0 =