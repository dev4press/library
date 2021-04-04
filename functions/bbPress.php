<?php

/*
Name:    Dev4Press\v35\Functions\bbPress
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

namespace Dev4Press\v35\Functions\bbPress;

use function Dev4Press\v35\Functions\WP\is_plugin_active;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( __NAMESPACE__ . '\is_active' ) ) {
	function is_active() : bool {
		if ( is_plugin_active( 'bbpress/bbpress.php' ) ) {
			$version = major_version_code();

			return $version > 25;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( __NAMESPACE__ . '\major_version_code' ) ) {
	function major_version_code() : int {
		if ( function_exists( 'bbp_get_version' ) ) {
			$version = bbp_get_version();

			return intval( substr( str_replace( '.', '', $version ), 0, 2 ) );
		}

		return 0;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\major_version_number' ) ) {
	function major_version_number( $ret = 'number' ) {
		if ( function_exists( 'bbp_get_version' ) ) {
			$version = bbp_get_version();

			if ( isset( $version ) ) {
				if ( $ret == 'number' ) {
					return substr( str_replace( '.', '', $version ), 0, 2 );
				} else {
					return $version;
				}
			}
		}

		return 0;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_user_roles' ) ) {
	function get_user_roles() : array {
		$roles = array();

		$dynamic_roles = bbp_get_dynamic_roles();

		foreach ( $dynamic_roles as $role => $obj ) {
			$roles[ $role ] = $obj['name'];
		}

		return $roles;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_moderator_roles' ) ) {
	function get_moderator_roles() : array {
		$roles = array();

		$dynamic_roles = bbp_get_dynamic_roles();

		foreach ( $dynamic_roles as $role => $obj ) {
			if ( isset( $obj['capabilities']['moderate'] ) && $obj['capabilities']['moderate'] ) {
				$roles[ $role ] = $obj['name'];
			}
		}

		return $roles;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_forums_list' ) ) {
	function get_forums_list( $args = array() ) : array {
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
				'title'  => $forum->post_title
			);
		}

		return $forums;
	}
}
