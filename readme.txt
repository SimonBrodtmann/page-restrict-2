=== Page Restrict 2 ===
Contributors: Simon Brodtmann
Tags: pages, page, restrict, restriction, logged in
Requires at least: 4.0
Tested up to: 4.8.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0
Stable tag: trunk

Restrict certain pages or posts to logged in users.

== Description ==

First of all: Thanks to the creators of Page Restrct (Matt Martz & Andy Stratton).
Since they are not active on their plugin anymore, I took it an rewrite almost everything.
When switching from the original version, your options and page restrictions will be lost (for now).

Restrict certain pages or posts to logged in users

This plugin will allow you to restrict all, none, or certain pages/posts to logged in users only.  

In some cases where you are using WordPress as a CMS and only want logged in users to have access to the content or where you want users to register for purposes unknown so that they can see the content, then this plugin is what you are looking for.

== Screenshots ==

1. Edit page/post
2. Admin Page
3. Message when access is denied

== Usage ==

1. Visit Settings>Page Restrict in the admin area of your blog.
1. Select your restriction method (all, none, selected).
1. If you chose selected, edit pages/posts and set the restriction option as you need it
1. Enjoy.

To completely hide a post from a custom page (content-post.php) use the following function: pr2_is_post_restricted().
Combine it with is_user_logged_in() to control the rendering of the post.

== Changelog ==

= 1.0 =
* Initial Public Release