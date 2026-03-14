<?php
/**
 * Name:    Dev4Press\v56\Core\Quick\Misc
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

use Dev4Press\v56\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Misc {
	/**
	 * Retrieves a human-readable error message for a given PCRE (Perl Compatible Regular Expressions) error code.
	 *
	 * @param mixed $error_code The error code to map to a descriptive message.
	 *
	 * @return string A descriptive error message corresponding to the provided error code, or 'UNKNOWN_ERROR'
	 *                if the code is not found in the predefined PCRE constants.
	 */
	public static function get_regex_error( $error_code ) : string {
		if ( is_bool( $error_code ) ) {
			return 'OK';
		}

		$errors = array_flip( get_defined_constants( true )['pcre'] );

		if ( isset( $errors[ $error_code ] ) ) {
			return $errors[ $error_code ];
		}

		return 'UNKNOWN_ERROR';
	}

	/**
	 * Converts a PHP INI configuration value (e.g., post_max_size, upload_max_filesize)
	 * into its corresponding size in bytes.
	 *
	 * @param string $name The name of the INI configuration option to retrieve and convert.
	 *
	 * @return float|int The size in bytes, or 0 if the INI value is not set or invalid.
	 */
	public static function php_ini_size_value( string $name ) : float|int {
		$ini = ini_get( $name );

		if ( $ini === false ) {
			return 0;
		}

		$ini  = trim( $ini );
		$last = strtoupper( $ini[ strlen( $ini ) - 1 ] );
		$ini  = absint( substr( $ini, 0, strlen( $ini ) - 1 ) );

		switch ( $last ) {
			case 'G':
				$ini = $ini * GB_IN_BYTES;
				break;
			case 'M':
				$ini = $ini * MB_IN_BYTES;
				break;
			case 'K':
				$ini = $ini * KB_IN_BYTES;
				break;
		}

		return $ini;
	}

	/**
	 * Converts a hexadecimal color code to its equivalent RGBA (Red, Green, Blue, Alpha) representation as a comma-separated string.
	 *
	 * @param string $color   The hexadecimal color code (e.g., "#RRGGBB" or "#RGB").
	 * @param string $default The default RGBA value to return if the input color is invalid or empty. Defaults to "0,0,0".
	 *
	 * @return string The RGBA representation of the color as a comma-separated string (e.g., "255,255,255"), or the default value if the input is invalid.
	 */
	public static function hex_to_rgba( string $color, string $default = '0,0,0' ) : string {
		if ( empty( $color ) ) {
			return $default;
		}

		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} else if ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		$rgb = array_map( 'hexdec', $hex );

		return implode( ',', $rgb );
	}

	/**
	 * Generates an HTML image tag for a country flag based on the provided country code and parameters.
	 *
	 * @param string $country_code The ISO country code or identifier (e.g., 'US', 'GB', 'cidr').
	 * @param string $location     An optional location name for the flag tooltip (used in the HTML "title" and "alt" attributes).
	 * @param string $status       Specifies the status of the flag; possible values are 'active' (default) or 'private'.
	 * @param string $not_found    Determines the behavior when the flag cannot be located; possible values are 'image' (default) or an empty string ('').
	 *
	 * @return string The HTML image tag representing the flag, or an empty string if the flag image is not found and $not_found is set to ''.
	 */
	public static function flag_from_country_code( string $country_code, string $location = '', string $status = 'active', string $not_found = 'image' ) : string {
		if ( $country_code == 'cidr' ) {
			$_base = Library::i()->url() . 'resources/gfx/flag_icon_cidr.png';

			return '<img src="' . $_base . '" class="cidr" title="' . esc_html__( 'CIDR IP Range', 'd4plib' ) . '" alt="' . esc_html__( 'CIDR IP Range', 'd4plib' ) . '" />';
		}

		$_base = Library::i()->url() . 'resources/vendor/flags/img/flag_placeholder.png';

		if ( $status == 'active' ) {
			if ( $country_code != '' ) {
				return '<img src="' . $_base . '" class="flag flag-' . strtolower( $country_code ) . '" title="' . $location . '" alt="' . $location . '" />';
			}
		} else if ( $status == 'private' ) {
			return '<img src="' . $_base . '" class="flag" title="' . esc_html__( 'Localhost or Private IP', 'd4plib' ) . '" alt="' . esc_html__( 'Localhost or Private IP', 'd4plib' ) . '" />';
		}

		if ( $not_found == 'image' ) {
			return '<img src="' . $_base . '" class="flag" title="' . esc_html__( 'IP can\'t be located.', 'd4plib' ) . '" alt="' . esc_html__( 'IP can\'t be located.', 'd4plib' ) . '" />';
		} else {
			return '';
		}
	}
}
