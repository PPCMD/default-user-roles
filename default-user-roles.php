<?php
/**
 * Plugin Name:       Default User Roles
 * Description:       Sets default roles for users with none, when imported from another WordPress installation's database table.
 * Version:           0.8
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            PPCMD
 * Author URI:        https://profiles.wordpress.org/ppcmd
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       default-user-roles
 */

/*
Default User Roles is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Default User Roles is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Default User Roles. If not, see {URI to Plugin License}.
*/

register_activation_hook( __FILE__, 'dur_activate' );
// register_deactivation_hook( __FILE__, 'dur_deactivate' );
register_uninstall_hook(__FILE__, 'dur_uninstall');

function dur_activate() {
	global $wpdb;

	// set default role if it doesn't exist
	if ( ! get_option( 'dur_role' ) && get_option( 'default_role' ) ) {
		update_option( 'dur_role', get_option('default_role') );
	} else {
		update_option( 'dur_role', 'subscriber' );
	}

	// set default prefix if not exist
	$prefixes = array();
	$prefixes[] = $wpdb->prefix;
	if ( ! get_option( 'dur_tableprefixes' ) ) {
		update_option( 'dur_tableprefixes', $prefixes );
	}
}

function dur_uninstall() {
	global $wpdb;
	$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE {$wpdb->prefix}options.option_name = 'dur_role'");
	$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE {$wpdb->prefix}options.option_name = 'dur_tableprefixes'");
}

/* Create Settings page for the plugin */
function dur_settings() {
	include_once( dirname( __FILE__ ) . '/default-user-roles-settings.php' );
}

/* Create Administration Page */
function add_dur_settings() {
	add_users_page( __( 'Default User Roles' ), __( 'Default Roles' ), 'administrator', 'default-user-roles', 'dur_settings' );
}
add_action( 'admin_menu', 'add_dur_settings' );

function dur_set_role_to_user( $login ) {
  $user = get_user_by( 'login', $login );

	if ( ! current_user_can( 'read' ) ) {
		$usernew = new WP_User( $user->ID );
		$usernew->set_role( dur_find_role( $user->ID ) );
	}
}
add_action( 'wp_login', 'dur_set_role_to_user' ); 

function dur_set_all_roles() {
	foreach ( dur_find_users() as $user_id ) {
		$user = new WP_User( $user_id );
		if ( ! user_can( $user_id, 'read' ) ) {
			$user->set_role( dur_find_role( $user_id ) );
		}
	}
}
add_action( 'load-users.php', 'dur_set_all_roles' );

function dur_find_all_roles() {
	global $wpdb;

	$option = $wpdb->prefix . 'user_roles';
	return get_option( $option );
}

/* Find all users */
function dur_find_users() {
	global $wpdb;

	$results = $wpdb->get_col( "SELECT ID FROM $wpdb->users" );
	return $results;
}

function dur_find_role( $user_id = false ) {
	global $wpdb, $current_user;

	$current_user = wp_get_current_user();
	if ( ! $user_id ) {
		$user_id = $current_user->ID;
	}

	$prefixes = get_option( 'dur_tableprefixes' );
	if ( $prefixes && is_array( $prefixes ) ) {
		foreach ( $prefixes as $prefix ) {
			$role = get_user_meta( $user_id, $prefix . 'capabilities', true );
			if ( $role != '' && is_array( $role ) ) {
				foreach ( $role as $key => $value ) {
					return $key;
				}
			}
		}
	}

	// if no one was found, return default role
	$default = get_option( 'dur_role' );
	return $default;
}
