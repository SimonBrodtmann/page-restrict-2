=== Page Restrict 2 ===
Contributors: Simon Brodtmann
Tags: pages, page, restrict, restriction, logged in
Requires at least: 2.6
Tested up to: 4.8.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0
Stable tag: trunk

Restrict certain pages or posts to logged in users.

== Description ==

Restrict certain pages or posts to logged in users

This plugin will allow you to restrict all, none, or certain pages/posts to logged in users only.  

In some cases where you are using WordPress as a CMS and only want logged in users to have access to the content or where you want users to register for purposes unknown so that they can see the content, then this plugin is what you are looking for.

Simple admin interface to select all, none, or some of your pages/posts.  This now works for posts!

== Installation ==

1. Upload the `pagerestrict` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Login Form
2. Admin Page

== Usage ==

1. Visit Settings>Page Restrict in the admin area of your blog.
1. Select your restriction method (all, none, selected).
1. If you chose selected, select the pages you wish to restrict.
1. Enjoy.

To completely hide a post from a custom page (content-post.php) use the following function: pr_is_post_restricted().
Combine it with is_user_logged_in() to control the rendering of the post.


== Changelog ==

= 1.0 =
* Initial Public Release
