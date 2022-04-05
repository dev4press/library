<?php

/*
Name:    Dev4Press\v38\Functions\bbPress
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

namespace Dev4Press\v38\Functions\bbPress;

use Dev4Press\v38\Core\Quick\BBP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( __NAMESPACE__ . '\is_active' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_active() : bool {
		return BBP::is_active();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_user_roles' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_user_roles() : array {
		return BBP::get_user_roles();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_moderator_roles' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_moderator_roles() : array {
		return BBP::get_moderator_roles();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\can_user_moderate' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function can_user_moderate() {
		return BBP::can_user_moderate();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_forums_list' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_forums_list( $args = array() ) : array {
		return BBP::get_forums_list( $args );
	}
}
