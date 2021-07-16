<?php

/*
Name:    Dev4Press\v36\Functions\WP
Version: v3.6
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

namespace Dev4Press\v36\Functions\WP;

use Dev4Press\v36\Core\Helpers\Error;
use WP_Error;
use WP_Query;
use WP_Term;
use wpdb;
use function get_term_by;

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

if ( ! function_exists( __NAMESPACE__ . '\is_classicpress' ) ) {
	function is_classicpress() : bool {
		return function_exists( 'classicpress_version' ) &&
		       function_exists( 'classicpress_version_short' );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_wp_error' ) ) {
	function is_wp_error( $thing ) : bool {
		return ( $thing instanceof WP_Error ) || ( $thing instanceof Error );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_login_page' ) ) {
	function is_login_page() : bool {
		return isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'wp-login.php';
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_signup_page' ) ) {
	function is_signup_page() : bool {
		return isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'wp-signup.php';
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_activate_page' ) ) {
	function is_activate_page() : bool {
		return isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'wp-activate.php';
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_login_page_action' ) ) {
	function is_login_page_action( $action = '' ) : bool {
		$login_page = isset( $GLOBALS['pagenow'] ) && in_array( $GLOBALS['pagenow'], array(
				'wp-login.php',
				'wp-register.php'
			) );

		if ( $login_page ) {
			if ( $action != '' ) {
				$real_action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : 'login';

				return $real_action == $action;
			}

			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_posts_page' ) ) {
	function is_posts_page() : bool {
		global $wp_query;

		return $wp_query->is_posts_page;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_any_tax' ) ) {
	function is_any_tax() : bool {
		return is_tag() ||
		       is_tax() ||
		       is_category();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_oembed_link' ) ) {
	function is_oembed_link( $url ) : bool {
		require_once( ABSPATH . WPINC . '/class-oembed.php' );

		$oembed = _wp_oembed_get_object();
		$result = $oembed->get_html( $url );

		return ! ( $result === false );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_user_allowed' ) ) {
	function is_user_allowed( $super_admin, $user_roles, $visitor ) : bool {
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
}

if ( ! function_exists( __NAMESPACE__ . '\is_permalinks_enabled' ) ) {
	function is_permalinks_enabled() : bool {
		return ! empty( get_option( 'permalink_structure' ) );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_current_user_admin' ) ) {
	function is_current_user_admin() : bool {
		return is_current_user_roles( 'administrator' );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_current_user_roles' ) ) {
	function is_current_user_roles( $roles = array() ) : bool {
		$current = current_user_roles();
		$roles   = (array) $roles;

		if ( is_array( $current ) && ! empty( $roles ) ) {
			$match = array_intersect( $roles, $current );

			return ! empty( $match );
		} else {
			return false;
		}
	}
}

if ( ! function_exists( __NAMESPACE__ . '\current_user_roles' ) ) {
	function current_user_roles() : array {
		if ( is_user_logged_in() ) {
			global $current_user;

			return (array) $current_user->roles;
		} else {
			return array();
		}
	}
}

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

if ( ! function_exists( __NAMESPACE__ . '\all_user_roles' ) ) {
	function all_user_roles() : array {
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
	function cache_flush( bool $cache = true, bool $queries = true ) {
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
	function cache_posts_by_ids( array $posts ) {
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

if ( ! function_exists( __NAMESPACE__ . '\get_post_excerpt' ) ) {
	function get_post_excerpt( $post, $word_limit = 50 ) : string {
		$content = $post->post_excerpt == '' ? $post->post_content : $post->post_excerpt;

		$content = strip_shortcodes( $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = strip_tags( $content );

		$words = explode( ' ', $content, $word_limit + 1 );

		if ( count( $words ) > $word_limit ) {
			array_pop( $words );
			$content = implode( ' ', $words );
			$content .= '...';
		}

		return $content;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_post_content' ) ) {
	function get_post_content( $post ) {
		$content = $post->post_content;

		if ( post_password_required( $post ) ) {
			$content = get_the_password_form( $post );
		}

		$content = apply_filters( 'the_content', $content );

		return str_replace( ']]>', ']]&gt;', $content );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_thumbnail_url' ) ) {
	function get_thumbnail_url( $post_id, $size = 'full' ) : string {
		if ( has_post_thumbnail( $post_id ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );

			return $image[0];
		} else {
			return '';
		}
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_attachment_id_from_url' ) ) {
	/*
	 * Function by Micah Wood
	 * https://wpscholar.com/blog/get-attachment-id-from-wp-image-url/
	 */
	function get_attachment_id_from_url( $url ) : int {
		$attachment_id = 0;
		$dir           = wp_upload_dir();

		if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) {
			$file       = basename( $url );
			$query_args = array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'value'   => $file,
						'compare' => 'LIKE',
						'key'     => '_wp_attachment_metadata'
					)
				)
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
}

if ( ! function_exists( __NAMESPACE__ . '\switch_to_default_theme' ) ) {
	function switch_to_default_theme() {
		switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\kses_expanded_list_of_tags' ) ) {
	function kses_expanded_list_of_tags() : array {
		return array(
			'a'          => array(
				'class'    => true,
				'href'     => true,
				'title'    => true,
				'rel'      => true,
				'style'    => true,
				'download' => true,
				'target'   => true
			),
			'abbr'       => array(),
			'blockquote' => array(
				'class' => true,
				'style' => true,
				'cite'  => true
			),
			'div'        => array(
				'class' => true,
				'style' => true
			),
			'span'       => array(
				'class' => true,
				'style' => true
			),
			'code'       => array(
				'class' => true,
				'style' => true
			),
			'p'          => array(
				'class' => true,
				'style' => true
			),
			'pre'        => array(
				'class' => true,
				'style' => true
			),
			'em'         => array(
				'class' => true,
				'style' => true
			),
			'i'          => array(
				'class' => true,
				'style' => true
			),
			'b'          => array(
				'class' => true,
				'style' => true
			),
			'strong'     => array(
				'class' => true,
				'style' => true
			),
			'del'        => array(
				'datetime' => true,
				'class'    => true,
				'style'    => true
			),
			'h1'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'h2'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'h3'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'h4'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'h5'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'h6'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'ul'         => array(
				'class' => true,
				'style' => true
			),
			'ol'         => array(
				'class' => true,
				'style' => true,
				'start' => true
			),
			'li'         => array(
				'class' => true,
				'style' => true
			),
			'img'        => array(
				'class'  => true,
				'style'  => true,
				'src'    => true,
				'border' => true,
				'alt'    => true,
				'height' => true,
				'width'  => true
			),
			'table'      => array(
				'align'   => true,
				'bgcolor' => true,
				'border'  => true,
				'class'   => true,
				'style'   => true
			),
			'tbody'      => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			),
			'td'         => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			),
			'tfoot'      => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			),
			'th'         => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			),
			'thead'      => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			),
			'tr'         => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			)
		);
	}
}

if ( ! function_exists( __NAMESPACE__ . '\list_post_types' ) ) {
	function list_post_types( $args = array() ) : array {
		$list       = array();
		$post_types = get_post_types( $args, 'objects' );

		foreach ( $post_types as $cpt => $obj ) {
			$list[ $cpt ] = $obj->labels->name;
		}

		return $list;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\list_taxonomies' ) ) {
	function list_taxonomies( $args = array() ) : array {
		$list       = array();
		$taxonomies = get_taxonomies( $args, 'objects' );

		foreach ( $taxonomies as $tax => $obj ) {
			$list[ $tax ] = $obj->labels->name;
		}

		return $list;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\list_user_roles' ) ) {
	function list_user_roles() : array {
		$roles = array();

		foreach ( wp_roles()->roles as $role => $details ) {
			$roles[ $role ] = translate_user_role( $details['name'] );
		}

		return $roles;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_gmt_offset' ) ) {
	function get_gmt_offset() {
		$offset = get_option( 'gmt_offset' );

		if ( empty( $offset ) ) {
			$offset = wp_timezone_override_offset();
		}

		return $offset === false ? 0 : $offset;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\html_excerpt' ) ) {
	function html_excerpt( $text, $limit, $more = null ) : string {
		return wp_html_excerpt( strip_shortcodes( $text ), $limit, $more );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\check_ajax_referer' ) ) {
	function check_ajax_referer( $action, $nonce, $die = true ) {
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
}

if ( ! function_exists( __NAMESPACE__ . '\post_type_has_archive' ) ) {
	function post_type_has_archive( $post_type ) : bool {
		if ( post_type_exists( $post_type ) ) {
			$cpt = get_post_type_object( $post_type );

			return $cpt->has_archive !== false;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( __NAMESPACE__ . '\json_die' ) ) {
	function json_die( $data, $response = null ) {
		if ( ! headers_sent() ) {
			header( 'Content-Type: application/json; charset=utf-8' );

			if ( null !== $response ) {
				status_header( $response );
			}

			nocache_headers();
		}

		die( wp_json_encode( $data ) );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\next_scheduled' ) ) {
	function next_scheduled( $hook, $args = null ) {
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
}

if ( ! function_exists( __NAMESPACE__ . '\delete_cron_job' ) ) {
	function delete_cron_job( $timestamp, $hook, $hash ) {
		$crons = _get_cron_array();

		if ( ! empty( $crons ) ) {
			$save = false;

			if ( is_array( $hash ) || is_object( $hash ) ) {
				$hash = md5( serialize( $hash ) );
			}

			if ( isset( $crons[ $timestamp ] ) && isset( $crons[ $timestamp ][ $hook ] ) && isset( $crons[ $timestamp ][ $hook ][ $hash ] ) ) {
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
}

if ( ! function_exists( __NAMESPACE__ . '\remove_cron' ) ) {
	function remove_cron( $hook ) {
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
}

if ( ! function_exists( __NAMESPACE__ . '\get_term' ) ) {
	function get_term( $term, $taxonomy = '', $output = OBJECT, $filter = 'raw' ) {
		if ( $term instanceof WP_Term || is_numeric( $term ) ) {
			return \get_term( $term, $taxonomy, $output, $filter );
		} else if ( is_string( $term ) ) {
			return get_term_by( 'slug', $term, $taxonomy, $output, $filter );
		}

		return false;
	}
}
