<?php

/*
Name:    Dev4Press\v38\Core\Quick\BP
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

namespace Dev4Press\v38\Core\Quick;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BP {
	public static function is_active() : bool {
		if ( WPR::is_plugin_active( 'buddypress/bp-loader.php' ) ) {
			$version = BP::major_version_code();

			return $version > 59;
		} else {
			return false;
		}
	}

	public static function major_version_code() : int {
		if ( function_exists( 'bp_get_version' ) ) {
			$version = bp_get_version();

			return intval( substr( str_replace( '.', '', $version ), 0, 2 ) );
		}

		return 0;
	}

	public static function major_version_number( $ret = 'number' ) {
		if ( function_exists( 'bp_get_version' ) ) {
			$version = bp_get_version();

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