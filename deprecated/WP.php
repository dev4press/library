<?php

/*
Name:    Dev4Press\v38\Functions\WP
Version: v3.8
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2022 Milan Petrovic (email: support@dev4press.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>
*/

namespace Dev4Press\v38\Functions\WP;

use Dev4Press\v38\Core\Quick\WPR;
use wpdb;

if ( ! function_exists( __NAMESPACE__ . '\is_plugin_active' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_plugin_active( $plugin ) : bool {
		return WPR::is_plugin_active( $plugin );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_plugin_active_for_network' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_plugin_active_for_network( $plugin ) : bool {
		return WPR::is_plugin_active_for_network( $plugin );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_classicpress' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_classicpress() : bool {
		return WPR::is_classicpress();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_wp_error' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_wp_error( $thing ) : bool {
		return WPR::is_wp_error( $thing );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_login_page' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_login_page() : bool {
		return WPR::is_login_page();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_signup_page' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_signup_page() : bool {
		return WPR::is_signup_page();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_activate_page' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_activate_page() : bool {
		return WPR::is_activate_page();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_login_page_action' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_login_page_action( $action = '' ) : bool {
		return WPR::is_login_page_action( $action );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_posts_page' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_posts_page() : bool {
		return WPR::is_posts_page();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_any_tax' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_any_tax() : bool {
		return WPR::is_any_tax();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_oembed_link' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_oembed_link( $url ) : bool {
		return WPR::is_oembed_link( $url );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_user_allowed' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_user_allowed( $super_admin, $user_roles, $visitor ) : bool {
		return WPR::is_user_allowed( $super_admin, $user_roles, $visitor );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_permalinks_enabled' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_permalinks_enabled() : bool {
		return WPR::is_permalinks_enabled();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_current_user_admin' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_current_user_admin() : bool {
		return WPR::is_current_user_admin();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_current_user_roles' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_current_user_roles( $roles = array() ) : bool {
		return WPR::is_current_user_roles( $roles );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\current_user_roles' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function current_user_roles() : array {
		return WPR::current_user_roles();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\add_action' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function add_action( $tags, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		WPR::add_action( $tags, $function_to_add, $priority, $accepted_args );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\add_filter' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function add_filter( $tags, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		WPR::add_filter( $tags, $function_to_add, $priority, $accepted_args );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\all_user_roles' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function all_user_roles() : array {
		return WPR::all_user_roles();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\cache_flush' ) ) {
	/**
	 * @param bool  $cache
	 * @param bool  $queries
	 *
	 * @global wpdb $wpdb
	 */
	/** @deprecated since 3.7 to be removed in 3.9 */
	function cache_flush( bool $cache = true, bool $queries = true ) {
		WPR::cache_flush( $cache, $queries );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\cache_posts_by_ids' ) ) {
	/**
	 * @param int[] $posts
	 *
	 * @global wpdb $wpdb
	 */
	/** @deprecated since 3.7 to be removed in 3.9 */
	function cache_posts_by_ids( array $posts ) {
		WPR::cache_posts_by_ids( $posts );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\flush_rewrite_rules' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function flush_rewrite_rules() {
		WPR::flush_rewrite_rules();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\redirect_self' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function redirect_self() {
		WPR::redirect_self();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\redirect_referer' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function redirect_referer() {
		WPR::redirect_referer();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_the_slug' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_the_slug( $post = null ) {
		return WPR::get_the_slug( $post );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_post_excerpt' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_post_excerpt( $post, $word_limit = 50 ) : string {
		return WPR::get_post_excerpt( $post, $word_limit );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_post_content' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_post_content( $post ) {
		return WPR::get_post_content( $post );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_thumbnail_url' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_thumbnail_url( $post_id, $size = 'full' ) : string {
		return WPR::get_thumbnail_url( $post_id, $size );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_attachment_id_from_url' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_attachment_id_from_url( $url ) : int {
		return WPR::get_attachment_id_from_url( $url );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\switch_to_default_theme' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function switch_to_default_theme() {
		WPR::switch_to_default_theme();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\kses_expanded_list_of_tags' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function kses_expanded_list_of_tags() : array {
		return WPR::kses_expanded_list_of_tags();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\list_post_types' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function list_post_types( $args = array() ) : array {
		return WPR::list_post_types( $args );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\list_taxonomies' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function list_taxonomies( $args = array() ) : array {
		return WPR::list_taxonomies( $args );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\list_user_roles' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function list_user_roles() : array {
		return WPR::list_user_roles();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_gmt_offset' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_gmt_offset() {
		return WPR::get_gmt_offset();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\html_excerpt' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function html_excerpt( $text, $limit, $more = null ) : string {
		return WPR::html_excerpt( $text, $limit, $more );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\check_ajax_referer' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function check_ajax_referer( $action, $nonce, $die = true ) {
		return WPR::check_ajax_referer( $action, $nonce, $die );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\post_type_has_archive' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function post_type_has_archive( $post_type ) : bool {
		return WPR::post_type_has_archive( $post_type );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\json_die' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function json_die( $data, $response = null ) {
		return WPR::json_die( $data, $response );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\next_scheduled' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function next_scheduled( $hook, $args = null ) {
		WPR::next_scheduled( $hook, $args );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\delete_cron_job' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function delete_cron_job( $timestamp, $hook, $hash ) {
		WPR::delete_cron_job( $timestamp, $hook, $hash );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\remove_cron' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function remove_cron( $hook ) {
		return WPR::remove_cron( $hook );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_term' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_term( $term, $taxonomy = '', $output = OBJECT, $filter = 'raw' ) {
		return WPR::get_term( $term, $taxonomy, $output, $filter );
	}
}
