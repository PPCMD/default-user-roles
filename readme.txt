=== Plugin Name ===
Plugin Name: Default User Roles
Contributors: ppcmd
Tags: users, roles, user, custom user database table, custom users table, custom usermeta table
Requires at least: 4.4
Tested up to: 5.5
Stable tag: 0.8
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sets default roles for users with none, when imported from another WordPress installation's database table.

== Description ==

This plugin helps in assigning roles to imported users when the default is null. This plugin performs the task automatically 
without any user involvement, as soon as a user with no role logs on to the site.

    The features of this plugin is useful when usage of custom `users` and `usermeta` [database tables is done](https://wordpress.org/support/article/editing-wp-config-php/#custom-user-and-usermeta-tables). The plugin sets 
the role of any user to their original role on the site where they registered. If it is not found, the default role configured in 
this plugin is set.

== Frequently Asked Questions ==

= What Does This Plugin Do? =

If you have defined `CUSTOM_USER_TABLE` and/or `CUSTOM_USER_META_TABLE` in your site's `wp-config.php` file, this 
plugin saves you the hassle of manually updating a new user's roles, as otherwise, the user registered on another site will 
face issues logging in to the current site.

== Screenshots ==

1. The plugin's page at Users < WordPress.

== Changelog ==

= 0.8 =
The initial release.

