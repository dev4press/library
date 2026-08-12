<?php
/**
 * Name:    Dev4Press\v56\Core\Quick\WP
 * Version: v5.6
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2026 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Dev4Press\v56\Core\Quick;

use Dev4Press\v56\Core\Helpers\Error;
use JetBrains\PhpStorm\NoReturn;
use WP_Error;
use WP_Query;
use WP_Term;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR {
	/**
	 * Check whether a plugin is installed.
	 *
	 * @param string $plugin Plugin basename, for example `woocommerce/woocommerce.php`.
	 *
	 * @return bool
	 */
	public static function is_plugin_installed( string $plugin ) : bool {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$installed_plugins = get_plugins();

		return in_array( $plugin, array_keys( $installed_plugins ) );
	}

	/**
	 * Check whether a plugin is active.
	 *
	 * @param string $plugin Plugin basename.
	 *
	 * @return bool
	 */
	public static function is_plugin_active( string $plugin ) : bool {
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || self::is_plugin_active_for_network( $plugin );
	}

	/**
	 * Check whether a plugin is network-active.
	 *
	 * @param string $plugin Plugin basename.
	 *
	 * @return bool
	 */
	public static function is_plugin_active_for_network( string $plugin ) : bool {
		if ( ! is_multisite() ) {
			return false;
		}

		$plugins = get_site_option( 'active_sitewide_plugins' );
		if ( isset( $plugins[ $plugin ] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check whether the site is running ClassicPress.
	 *
	 * @return bool
	 */
	public static function is_classicpress() : bool {
		return function_exists( 'classicpress_version' ) &&
		       function_exists( 'classicpress_version_short' );
	}

	/**
	 * Check whether a value is a WordPress or plugin error object.
	 *
	 * @param mixed $thing Value to check.
	 *
	 * @return bool
	 */
	public static function is_wp_error( mixed $thing ) : bool {
		return ( $thing instanceof WP_Error ) || ( $thing instanceof Error );
	}

	/**
	 * Check whether the current page is the login page.
	 *
	 * @return bool
	 */
	public static function is_login_page() : bool {
		return isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'wp-login.php';
	}

	/**
	 * Check whether the current page is the signup page.
	 *
	 * @return bool
	 */
	public static function is_signup_page() : bool {
		return isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'wp-signup.php';
	}

	/**
	 * Check whether the current page is the activation page.
	 *
	 * @return bool
	 */
	public static function is_activate_page() : bool {
		return isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'wp-activate.php';
	}

	/**
	 * Check whether the current page is a login-related page, optionally matching a specific action.
	 *
	 * @param string $action Optional login action to match.
	 *
	 * @return bool
	 */
	public static function is_login_page_action( string $action = '' ) : bool {
		$login_page = isset( $GLOBALS['pagenow'] ) && in_array(
				$GLOBALS['pagenow'],
				array(
					'wp-login.php',
					'wp-register.php',
				)
			);

		if ( $login_page ) {
			if ( $action != '' ) {
				$real_action = isset( $_GET['action'] ) ? Sanitize::text( $_GET['action'] ) : 'login'; // phpcs:ignore WordPress.Security.NonceVerification

				return $real_action == $action;
			}

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check whether the current query is the posts page.
	 *
	 * @return bool
	 */
	public static function is_posts_page() : bool {
		global $wp_query;

		return $wp_query->is_posts_page;
	}

	/**
	 * Check whether the current view is any taxonomy archive.
	 *
	 * @return bool
	 */
	public static function is_any_tax() : bool {
		return is_tag() ||
		       is_tax() ||
		       is_category();
	}

	/**
	 * Check whether bbPress is available and active.
	 *
	 * @return bool
	 */
	public static function is_bbpress() : bool {
		if ( class_exists( 'bbPress' ) && function_exists( 'is_bbpress' ) ) {
			return is_bbpress();
		} else {
			return false;
		}
	}

	/**
	 * Check whether a URL is a valid oEmbed link.
	 *
	 * @param string $url URL to check.
	 *
	 * @return bool
	 */
	public static function is_oembed_link( string $url ) : bool {
		require_once ABSPATH . WPINC . '/class-oembed.php';

		$oembed = _wp_oembed_get_object();
		$result = $oembed->get_html( $url );

		return ! ( $result === false );
	}

	/**
	 * Determine whether the current visitor is allowed.
	 *
	 * @param bool       $super_admin Allowed state for super admins.
	 * @param bool|array $user_roles  Allowed roles configuration.
	 * @param bool       $visitor     Allowed state for visitors.
	 *
	 * @return bool
	 */
	public static function is_user_allowed( bool $super_admin, bool|array $user_roles, bool $visitor ) : bool {
		if ( is_super_admin() ) {
			return $super_admin;
		} else if ( is_user_logged_in() ) {
			$allowed = $user_roles;

			if ( $allowed === true || is_null( $allowed ) ) {
				return true;
			} else if ( is_array( $allowed ) && empty( $allowed ) ) {
				return false;
			} else if ( is_array( $allowed ) && ! empty( $allowed ) ) {
				global $current_user;

				if ( is_array( $current_user->roles ) ) {
					$matched = array_intersect( $current_user->roles, $allowed );

					return ! empty( $matched );
				}
			}
		} else {
			return $visitor;
		}

		return false;
	}

	/**
	 * Check whether permalinks are enabled.
	 *
	 * @return bool
	 */
	public static function is_permalinks_enabled() : bool {
		return ! empty( get_option( 'permalink_structure' ) );
	}

	/**
	 * Check whether the current user has administrator role.
	 *
	 * @return bool
	 */
	public static function is_current_user_admin() : bool {
		return self::is_current_user_roles( 'administrator' );
	}

	/**
	 * Check whether the current user has any of the provided roles.
	 *
	 * @param string|string[] $roles Roles to match.
	 *
	 * @return bool
	 */
	public static function is_current_user_roles( string|array $roles = array() ) : bool {
		$current = self::current_user_roles();
		$roles   = (array) $roles;

		if ( ! empty( $roles ) ) {
			$match = array_intersect( $roles, $current );

			return ! empty( $match );
		} else {
			return false;
		}
	}

	/**
	 * Check whether a specific user has any of the provided roles.
	 *
	 * @param int          $user_id User ID.
	 * @param string|array $roles   Roles to match.
	 *
	 * @return bool
	 */
	public static function is_user_roles( int $user_id, string|array $roles = array() ) : bool {
		$current = self::get_user_roles( $user_id );
		$roles   = (array) $roles;

		if ( ! empty( $roles ) ) {
			$match = array_intersect( $roles, $current );

			return ! empty( $match );
		} else {
			return false;
		}
	}

	/**
	 * Get roles for a specific user.
	 *
	 * @param int $user_id User ID.
	 *
	 * @return array
	 */
	public static function get_user_roles( int $user_id ) : array {
		$user = get_user_by( 'id', $user_id );

		if ( $user instanceof WP_User ) {
			return $user->roles;
		}

		return array();
	}

	/**
	 * Get roles for the current user.
	 *
	 * @return array
	 */
	public static function current_user_roles() : array {
		if ( is_user_logged_in() ) {
			global $current_user;

			return $current_user->roles;
		} else {
			return array();
		}
	}

	/**
	 * Register one or more actions.
	 *
	 * @param string|string[] $tags            Action hook name(s).
	 * @param callable        $function_to_add Callback to register.
	 * @param int             $priority       Hook priority.
	 * @param int             $accepted_args   Number of accepted arguments.
	 *
	 * @return void
	 */
	public static function add_action( string|array $tags, callable $function_to_add, int $priority = 10, int $accepted_args = 1 ) : void {
		$tags = (array) $tags;

		foreach ( $tags as $tag ) {
			add_action( $tag, $function_to_add, $priority, $accepted_args );
		}
	}

	/**
	 * Register one or more filters.
	 *
	 * @param string|string[] $tags            Filter hook name(s).
	 * @param callable        $function_to_add Callback to register.
	 * @param int             $priority       Hook priority.
	 * @param int             $accepted_args   Number of accepted arguments.
	 *
	 * @return void
	 */
	public static function add_filter( string|array $tags, callable $function_to_add, int $priority = 10, int $accepted_args = 1 ) : void {
		$tags = (array) $tags;

		foreach ( $tags as $tag ) {
			add_filter( $tag, $function_to_add, $priority, $accepted_args );
		}
	}

	/**
	 * Flush object cache and optionally reset query cache.
	 *
	 * @param bool $cache   Whether to flush cache.
	 * @param bool $queries Whether to clear stored query data.
	 *
	 * @return void
	 */
	public static function cache_flush( bool $cache = true, bool $queries = true ) : void {
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

	/**
	 * Redirect to the current request URI.
	 *
	 * @return void
	 */
	#[NoReturn]
	public static function redirect_self() : void {
		$url = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_url( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/'; // phpcs:ignore WordPress.Security.EscapeOutput,WordPress.Security.NonceVerification,WordPress.Security.ValidatedSanitizedInput,WordPress.WP.DeprecatedFunctions

		wp_redirect( $url );
		exit;
	}

	/**
	 * Redirect to the HTTP referer.
	 *
	 * @return void
	 */
	#[NoReturn]
	public static function redirect_referer() : void {
		wp_redirect( wp_get_referer() );
		exit;
	}

	/**
	 * Get the slug for a post.
	 *
	 * @param int|object|null $post Post object, ID, or null for the current global post.
	 *
	 * @return string|false
	 */
	public static function get_the_slug( int|object|null $post = null ) {
		$post = get_post( $post );

		return ! empty( $post ) ? $post->post_name : false;
	}

	/**
	 * Get a trimmed excerpt from a post object.
	 *
	 * @param object $post       Post object.
	 * @param int    $word_limit Maximum number of words.
	 * @param string $append     Text appended when the excerpt is trimmed.
	 *
	 * @return string
	 */
	public static function get_post_excerpt( object $post, int $word_limit = 50, string $append = '...' ) : string {
		$content = $post->post_excerpt == '' ? $post->post_content : $post->post_excerpt;

		$content = strip_shortcodes( $content );
		$content = str_replace( array( "\r", "\n", '  ' ), ' ', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = wp_strip_all_tags( $content );

		$words = explode( ' ', $content, $word_limit + 1 );

		if ( count( $words ) > $word_limit ) {
			array_pop( $words );
			$content = implode( ' ', $words );
			$content .= $append;
		}

		return $content;
	}

	/**
	 * Get rendered post content.
	 *
	 * @param object $post Post object.
	 *
	 * @return string
	 */
	public static function get_post_content( object $post ) : string {
		$content = $post->post_content;

		if ( post_password_required( $post ) ) {
			$content = get_the_password_form( $post );
		}

		$content = apply_filters( 'the_content', $content );

		return str_replace( ']]>', ']]&gt;', $content );
	}

	/**
	 * Get the featured image URL for a post.
	 *
	 * @param int|object $post_id Post ID or object accepted by WordPress functions.
	 * @param string     $size    Image size.
	 *
	 * @return string
	 */
	public static function get_thumbnail_url( int|object $post_id, string $size = 'full' ) : string {
		if ( has_post_thumbnail( $post_id ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );

			return $image[0];
		} else {
			return '';
		}
	}

	/**
	 * Remove the site URL prefix from a URL.
	 *
	 * @param string $url URL to normalize.
	 *
	 * @return string
	 */
	public static function remove_site_url( string $url ) : string {
		$site_url = untrailingslashit( site_url() );

		if ( str_starts_with( $url, $site_url ) ) {
			$url = str_replace( $site_url, '', $url );
		}

		return $url;
	}

	/**
	 * Get a post ID by slug.
	 *
	 * @param string       $slug      Post slug.
	 * @param string|array $post_type Post type name or list of post types.
	 *
	 * @return int
	 */
	public static function get_post_id_by_slug( string $slug, string|array $post_type = 'page' ) : int {
		$query = new WP_Query(
			array(
				'name'                   => $slug,
				'post_type'              => $post_type,
				'numberposts'            => 1,
				'fields'                 => 'ids',
				'update_post_meta_cache' => false,
				'no_found_rows'          => true,
			) );
		$posts = $query->get_posts();

		return empty( $posts ) ? 0 : array_shift( $posts );
	}

	/**
	 * Switch to the default WordPress theme.
	 *
	 * @return void
	 */
	public static function switch_to_default_theme() : void {
		switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	}

	/**
	 * Get a list of post types keyed by post type name.
	 *
	 * @param array $args Optional query arguments.
	 *
	 * @return array
	 */
	public static function list_post_types( array $args = array() ) : array {
		$list       = array();
		$post_types = get_post_types( $args, 'objects' );

		foreach ( $post_types as $cpt => $obj ) {
			$list[ $cpt ] = $obj->labels->name;
		}

		return $list;
	}

	/**
	 * Get a list of taxonomies keyed by taxonomy name.
	 *
	 * @param array $args Optional query arguments.
	 *
	 * @return array
	 */
	public static function list_taxonomies( array $args = array() ) : array {
		$list       = array();
		$taxonomies = get_taxonomies( $args, 'objects' );

		foreach ( $taxonomies as $tax => $obj ) {
			$list[ $tax ] = $obj->labels->name;
		}

		return $list;
	}

	/**
	 * Get a list of available user roles.
	 *
	 * @return array
	 */
	public static function list_user_roles() : array {
		$roles = array();

		foreach ( wp_roles()->roles as $role => $details ) {
			$roles[ $role ] = translate_user_role( $details['name'] );
		}

		return $roles;
	}

	/**
	 * Return a shortened HTML-safe excerpt.
	 *
	 * @param string $text  Input text.
	 * @param int    $limit Maximum length.
	 * @param string|null $more Optional suffix.
	 *
	 * @return string
	 */
	public static function html_excerpt( string $text, int $limit, ?string $more = null ) : string {
		return wp_html_excerpt( strip_shortcodes( $text ), $limit, $more );
	}

	/**
	 * Verify an AJAX nonce and optionally stop execution on failure.
	 *
	 * @param string     $action Action name.
	 * @param string|int $nonce  Nonce value.
	 * @param bool       $die    Whether to stop execution when verification fails.
	 *
	 * @return int|false
	 */
	public static function check_ajax_referer( string $action, string|int $nonce, bool $die = true ) {
		$result = wp_verify_nonce( $nonce, $action );

		if ( $die && false === $result ) {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				wp_die( - 1 );
			} else {
				die( '-1' );
			}
		}

		do_action( 'check_ajax_referer', $action, $result );

		return $result;
	}

	/**
	 * Check whether a post type supports archives.
	 *
	 * @param string $post_type Post type name.
	 *
	 * @return bool
	 */
	public static function post_type_has_archive( string $post_type ) : bool {
		if ( post_type_exists( $post_type ) ) {
			$cpt = get_post_type_object( $post_type );

			return $cpt->has_archive !== false;
		} else {
			return false;
		}
	}

	/**
	 * Output JSON and terminate execution.
	 *
	 * @param mixed     $data     Data to encode as JSON.
	 * @param int|null  $response Optional HTTP status code.
	 *
	 * @return void
	 */
	#[NoReturn]
	public static function json_die( mixed $data, ?int $response = null ) : void {
		if ( ! headers_sent() ) {
			header( 'Content-Type: application/json; charset=utf-8' );

			if ( null !== $response ) {
				status_header( $response );
			}

			nocache_headers();
		}

		die( wp_json_encode( $data ) );
	}

	/**
	 * Check whether a single event is scheduled for a hook.
	 *
	 * @param string $hook Action hook name.
	 * @param array  $args Event arguments.
	 *
	 * @return bool
	 */
	public static function is_scheduled_single( string $hook, array $args = array() ) : bool {
		$next_event = wp_get_scheduled_event( $hook, $args );

		if ( ! $next_event ) {
			return false;
		}

		return $next_event->schedule === false;
	}

	/**
	 * Get the next scheduled timestamp for a hook.
	 *
	 * @param string     $hook Action hook name.
	 * @param array|null $args Optional event arguments.
	 *
	 * @return int|false
	 */
	public static function next_scheduled( string $hook, ?array $args = null ) {
		if ( ! is_null( $args ) ) {
			return wp_next_scheduled( $hook, $args );
		} else {
			$crons = _get_cron_array();

			if ( empty( $crons ) ) {
				return false;
			}

			$t = - 1;
			foreach ( $crons as $timestamp => $cron ) {
				if ( isset( $cron[ $hook ] ) ) {
					if ( $t == - 1 || $timestamp < $t ) {
						$t = $timestamp;
					}
				}
			}

			return $t == - 1 ? false : $t;
		}
	}

	/**
	 * Delete a scheduled cron event by timestamp, hook and hash.
	 *
	 * @param int          $timestamp Event timestamp.
	 * @param string        $hook      Action hook name.
	 * @param array|object|string $hash Event hash or original arguments used to generate the hash.
	 *
	 * @return void
	 */
	public static function delete_cron_job( int $timestamp, string $hook, array|object|string $hash ) : void {
		$crons = _get_cron_array();

		if ( ! empty( $crons ) ) {
			$save = false;

			if ( is_array( $hash ) || is_object( $hash ) ) {
				$hash = md5( serialize( $hash ) );
			}

			if ( isset( $crons[ $timestamp ][ $hook ][ $hash ] ) ) {
				unset( $crons[ $timestamp ][ $hook ][ $hash ] );
				$save = true;

				if ( empty( $crons[ $timestamp ][ $hook ] ) ) {
					unset( $crons[ $timestamp ][ $hook ] );

					if ( empty( $crons[ $timestamp ] ) ) {
						unset( $crons[ $timestamp ] );
					}
				}
			}

			if ( $save ) {
				_set_cron_array( $crons );
			}
		}
	}

	/**
	 * Remove all cron events for a hook.
	 *
	 * @param string $hook Action hook name.
	 *
	 * @return void
	 */
	public static function remove_cron( string $hook ) : void {
		$crons = _get_cron_array();

		if ( ! empty( $crons ) ) {
			$save = false;

			foreach ( $crons as $timestamp => $cron ) {
				if ( isset( $cron[ $hook ] ) ) {
					unset( $crons[ $timestamp ][ $hook ] );
					$save = true;

					if ( empty( $crons[ $timestamp ] ) ) {
						unset( $crons[ $timestamp ] );
					}
				}
			}

			if ( $save ) {
				_set_cron_array( $crons );
			}
		}
	}

	/**
	 * Get a term by ID or slug.
	 *
	 * @param int|string|WP_Term $term    Term ID, slug, or term object.
	 * @param string             $taxonomy Taxonomy name.
	 * @param string             $output   Output format.
	 * @param string             $filter   Filter context.
	 *
	 * @return WP_Term|array|false
	 */
	public static function get_term( int|string|WP_Term $term, string $taxonomy = '', string $output = OBJECT, string $filter = 'raw' ) {
		if ( $term instanceof WP_Term || is_numeric( $term ) ) {
			return get_term( $term, $taxonomy, $output, $filter );
		} else if ( is_string( $term ) ) {
			return get_term_by( 'slug', $term, $taxonomy, $output, $filter );
		}

		return false;
	}

	/**
	 * Check whether a Gravatar exists for an email address.
	 *
	 * @param string $email Email address.
	 *
	 * @return bool
	 */
	public static function has_gravatar( string $email ) : bool {
		$hash = md5( strtolower( trim( $email ) ) );
		$url  = 'https://www.gravatar.com/avatar/' . $hash . '?d=404';

		$response = wp_remote_head( $url, array(
			'timeout'     => 3,
			'redirection' => 0,
		) );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$code = wp_remote_retrieve_response_code( $response );

		return $code === 200;
	}

	/**
	 * Get a user's display name.
	 *
	 * @param int $user_id User ID, or 0 for current user.
	 *
	 * @return string
	 */
	public static function get_user_display_name( int $user_id = 0 ) : string {
		if ( $user_id == 0 ) {
			$user_id = get_current_user_id();
		}

		if ( $user_id > 0 ) {
			$author_name = get_the_author_meta( 'display_name', $user_id );

			if ( empty( $author_name ) ) {
				$author_name = get_the_author_meta( 'user_login', $user_id );
			}

			return $author_name;
		}

		return '';
	}

	/**
	 * Get the number of blogs on the network.
	 *
	 * @return int
	 */
	public static function get_blogs_count() : int {
		if ( is_multisite() ) {
			return absint( get_sites( array(
				'count'  => true,
				'number' => 0,
			) ) );
		}

		return 1;
	}

	/**
	 * Get attachment ID from an uploaded file URL.
	 *
	 * @param string $url Attachment URL.
	 *
	 * @return int
     *
	 * Function by Micah Wood
	 * https://wpscholar.com/blog/get-attachment-id-from-wp-image-url/
	 */
	public static function get_attachment_id_from_url( string $url ) : int {
		$attachment_id = 0;

		$dir = wp_upload_dir();

		if ( str_contains( $url, $dir['baseurl'] . '/' ) ) {
			$file       = basename( $url );
			$query_args = array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'value'   => $file,
						'compare' => 'LIKE',
						'key'     => '_wp_attachment_metadata',
					),
				),
			);

			$query = new WP_Query( $query_args );

			if ( $query->have_posts() ) {
				foreach ( $query->posts as $post_id ) {
					$meta = wp_get_attachment_metadata( $post_id );

					$original_file       = basename( $meta['file'] );
					$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );

					if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
						$attachment_id = $post_id;
						break;
					}
				}
			}
		}

		return $attachment_id;
	}

	/**
	 * Get the allowed HTML tags list used by KSES.
	 *
	 * @deprecated 5.5.0 use `KSES::allowed_html_expanded()` instead. To be removed in 5.7.0.
	 *
	 * @return array
	 */
	public static function kses_expanded_list_of_tags() : array {
		return KSES::allowed_html_expanded();
	}

	/**
	 * Flush rewrite rules.
	 *
	 * @return void
	 *
	 * @deprecated 5.6.0 Use flush_rewrite_rules() instead.
	 */
	public static function flush_rewrite_rules() : void {
		_deprecated_function( __FUNCTION__, '5.6.0', 'flush_rewrite_rules' );

		flush_rewrite_rules();
	}
}