<?php
/**
 * Name:    Dev4Press\v56\Core\Quick\BBP
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BBP {
	/**
	 * Checks if a bbPress plugin is active and meets the minimum required version.
	 *
	 * @param string $min_version Minimum version of the bbPress plugin required (default is '2.6').
	 *
	 * @return bool True if the plugin is active and meets the minimum version, false otherwise.
	 */
	public static function is_active( string $min_version = '2.6' ) : bool {
		if ( WPR::is_plugin_active( 'bbpress/bbpress.php' ) && function_exists( 'bbp_get_version' ) ) {
			return version_compare( bbp_get_version(), $min_version, '>=' );
		} else {
			return false;
		}
	}

	/**
	 * Returns an array of post types associated with the bbPress.
	 *
	 * @return array An array of post types, or an empty array if bbPress is not active.
	 */
	public static function get_post_types() : array {
		if ( self::is_active() ) {
			return array(
				bbp_get_forum_post_type(),
				bbp_get_topic_post_type(),
				bbp_get_reply_post_type(),
			);
		}

		return array();
	}

	/**
	 * Returns an array of user roles dynamically defined in bbPress.
	 *
	 * @return array An array containing keys representing user roles.
	 */
	public static function list_user_roles() : array {
		$dynamic_roles = bbp_get_dynamic_roles();

		return array_keys( $dynamic_roles );
	}

	/**
	 * Returns an array of user roles dynamically defined in bbPress.
	 *
	 * @param bool $translate Whether to translate role labels.
	 *
	 * @return array<string, string> An associative array of role slugs and labels.
	 */
	public static function get_user_roles( bool $translate = true ) : array {
		$roles = array();

		$dynamic_roles = bbp_get_dynamic_roles();

		foreach ( $dynamic_roles as $role => $obj ) {
			$roles[ $role ] = $translate ? bbp_translate_user_role( $obj['name'] ) : $obj['name'];
		}

		return $roles;
	}

	/**
	 * Returns only bbPress roles that have moderator capabilities.
	 *
	 * @param bool $translate Whether to translate role labels.
	 *
	 * @return array<string, string> An associative array of moderator role slugs and labels.
	 */
	public static function get_moderator_roles( bool $translate = true ) : array {
		$roles = array();

		$dynamic_roles = bbp_get_dynamic_roles();

		foreach ( $dynamic_roles as $role => $obj ) {
			if ( isset( $obj['capabilities']['moderate'] ) && $obj['capabilities']['moderate'] ) {
				$roles[ $role ] = $translate ? bbp_translate_user_role( $obj['name'] ) : $obj['name'];
			}
		}

		return $roles;
	}

	/**
	 * Checks whether the currently logged-in user has a bbPress moderator role.
	 *
	 * @return bool True if the current user is a moderator, false otherwise.
	 */
	public static function has_moderator_role() : bool {
		$roles = array_keys( self::get_moderator_roles() );

		if ( is_user_logged_in() ) {
			if ( is_super_admin() ) {
				return true;
			} else {
				global $current_user;

				if ( is_array( $current_user->roles ) ) {
					$matched = array_intersect( $current_user->roles, $roles );

					return ! empty( $matched );
				}
			}
		}

		return false;
	}

	/**
	 * Checks whether the current user can moderate bbPress content.
	 *
	 * @return bool True if the current user can moderate, false otherwise.
	 */
	public static function can_moderate() : bool {
		return current_user_can( 'moderate' );
	}

	/**
	 * Retrieves a list of bbPress forums.
	 *
	 * @param array $args Optional query arguments passed to get_posts().
	 *
	 * @return array<int, object> An array of forum objects indexed by forum ID.
	 */
	public static function get_forums_list( array $args = array() ) : array {
		$defaults = array(
			'post_type'   => bbp_get_forum_post_type(),
			'numberposts' => - 1,
		);

		$args = wp_parse_args( $args, $defaults );

		$_forums = get_posts( $args );

		$forums = array();

		foreach ( $_forums as $forum ) {
			$forums[ $forum->ID ] = (object) array(
				'id'     => $forum->ID,
				'url'    => get_permalink( $forum->ID ),
				'parent' => $forum->post_parent,
				'title'  => $forum->post_title,
			);
		}

		return $forums;
	}

	/**
	 * Checks whether a given user has the bbPress moderator role.
	 *
	 * @param int $user_id User ID to check.
	 *
	 * @return bool True if the user is a moderator, false otherwise.
	 */
	public static function is_user_moderator( int $user_id ) : bool {
		return WPR::is_user_roles( $user_id, bbp_get_moderator_role() );
	}

	/**
	 * Checks whether a given user has the bbPress keymaster role.
	 *
	 * @param int $user_id User ID to check.
	 *
	 * @return bool True if the user is a keymaster, false otherwise.
	 */
	public static function is_user_keymaster( int $user_id ) : bool {
		return WPR::is_user_roles( $user_id, bbp_get_keymaster_role() );
	}

	/**
	 * Checks whether the current user has the bbPress moderator role.
	 *
	 * @return bool True if the current user is a moderator, false otherwise.
	 */
	public static function is_current_user_moderator() : bool {
		return WPR::is_current_user_roles( bbp_get_moderator_role() );
	}

	/**
	 * Checks whether the current user has the bbPress keymaster role.
	 *
	 * @return bool True if the current user is a keymaster, false otherwise.
	 */
	public static function is_current_user_keymaster() : bool {
		return WPR::is_current_user_roles( bbp_get_keymaster_role() );
	}

	/**
	 * Checks whether bbPress pretty URLs are enabled.
	 *
	 * @return bool True if pretty URLs are enabled, false otherwise.
	 */
	public static function can_use_pretty_urls() : bool {
		if ( function_exists( 'bbp_use_pretty_urls' ) ) {
			return bbp_use_pretty_urls();
		}

		global $wp_rewrite;

		return $wp_rewrite->using_permalinks();
	}
}
