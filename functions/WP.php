<?php

/*
Name:    Dev4Press\v35\Functions\WP
Version: v3.5
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2021 Milan Petrovic (email: support@dev4press.com)

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

namespace Dev4Press\v35\Functions\WP;

use wpdb;

if ( ! function_exists( __NAMESPACE__ . '\add_action' ) ) {
	function add_action( $tags, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		$tags = (array) $tags;

		foreach ( $tags as $tag ) {
			\add_action( $tag, $function_to_add, $priority, $accepted_args );
		}
	}
}

if ( ! function_exists( __NAMESPACE__ . '\add_filter' ) ) {
	function add_filter( $tags, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		$tags = (array) $tags;

		foreach ( $tags as $tag ) {
			\add_filter( $tag, $function_to_add, $priority, $accepted_args );
		}
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_user_roles' ) ) {
	function get_user_roles() : array {
		global $wp_roles;

		$roles = array();

		foreach ( $wp_roles->role_names as $role => $title ) {
			$roles[ $role ] = $title;
		}

		return $roles;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\cache_flush' ) ) {
	/**
	 * @param bool  $cache
	 * @param bool  $queries
	 *
	 * @global wpdb $wpdb
	 */
	function cache_flush( $cache = true, $queries = true ) {
		if ( $cache ) {
			wp_cache_flush();
		}

		if ( $queries ) {
			global $wpdb;

			if ( is_array( $wpdb->queries ) && ! empty( $wpdb->queries ) ) {
				unset( $wpdb->queries );
				$wpdb->queries = array();
			}
		}
	}
}

if ( ! function_exists( __NAMESPACE__ . '\cache_posts_by_ids' ) ) {
	/**
	 * @param int[] $posts
	 *
	 * @global wpdb $wpdb
	 */
	function cache_posts_by_ids( $posts ) {
		global $wpdb;

		$posts = _get_non_cached_ids( $posts, 'posts' );
		$posts = array_filter( $posts );

		if ( ! empty( $posts ) ) {
			$sql = 'SELECT * FROM ' . $wpdb->posts . ' WHERE ID IN (' . join( ',', (array) $posts ) . ')';
			$raw = $wpdb->get_results( $sql );

			foreach ( $raw as $_post ) {
				$_post = sanitize_post( $_post, 'raw' );
				wp_cache_add( $_post->ID, $_post, 'posts' );
			}
		}
	}
}

if ( ! function_exists( __NAMESPACE__ . '\flush_rewrite_rules' ) ) {
	function flush_rewrite_rules() {
		global $wp_rewrite;

		$wp_rewrite->flush_rules();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\redirect_self' ) ) {
	function redirect_self() {
		wp_redirect( $_SERVER['REQUEST_URI'] );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\redirect_referer' ) ) {
	function redirect_referer() {
		wp_redirect( wp_get_referer() );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_the_slug' ) ) {
	function get_the_slug( $post = null ) {
		$post = get_post( $post );

		return ! empty( $post ) ? $post->post_name : false;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\switch_to_default_theme' ) ) {
	function switch_to_default_theme() {
		switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_plugin_active' ) ) {
	function is_plugin_active( $plugin ) : bool {
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || is_plugin_active_for_network( $plugin );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_plugin_active_for_network' ) ) {
	function is_plugin_active_for_network( $plugin ) : bool {
		if ( ! is_multisite() ) {
			return false;
		}

		$plugins = get_site_option( 'active_sitewide_plugins' );
		if ( isset( $plugins[ $plugin ] ) ) {
			return true;
		}

		return false;
	}
}
