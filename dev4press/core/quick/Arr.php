<?php
/**
 * Name:    Dev4Press\v56\Core\Quick\Arr
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

class Arr {
	/**
	 * Check if the provided array is associative.
	 *
	 * An empty array is treated as associative.
	 *
	 * @param array $input Input value to inspect.
	 *
	 * @return bool True if the input is an associative array, false otherwise.
	 */
	public static function is_associative( array $input ) : bool {
		return ( 0 !== count( array_diff_key( $input, array_keys( array_keys( $input ) ) ) ) || count( $input ) == 0 );
	}

	/**
	 * Convert an array of attributes into an HTML attribute string.
	 *
	 * Boolean values are treated as attribute flags and emitted without a value.
	 * Non-boolean values are escaped using `esc_attr()`.
	 *
	 * @param array $input Array of HTML attributes.
	 *
	 * @return string Concatenated HTML attribute string.
	 */
	public static function to_html_attributes( array $input ) : string {
		$list = array();

		foreach ( $input as $item => $value ) {
			if ( is_bool( $value ) ) {
				$list[] = $item;
			} else {
				$list[] = $item . '="' . esc_attr( $value ) . '"';
			}
		}

		return join( ' ', $list );
	}

	/**
	 * Remove all occurrences of a value from an array.
	 *
	 * If the input is not a non-empty array, an empty array is returned.
	 *
	 * @param array $input         Array to filter.
	 * @param mixed $val           Value to remove.
	 * @param bool  $preserve_keys Whether to preserve the original array keys.
	 *
	 * @return array Filtered array with the matching value removed.
	 */
	public static function remove_by_value( array $input, mixed $val, bool $preserve_keys = true ) : array {
		if ( empty( $input ) ) {
			return array();
		}

		while ( in_array( $val, $input ) ) {
			unset( $input[ array_search( $val, $input ) ] );
		}

		return $preserve_keys ? $input : array_values( $input );
	}

	/**
	 * Get a list of supported CSS size units.
	 *
	 * @return array<string, string> Array of CSS unit identifiers mapped to themselves.
	 */
	public static function get_css_size_units() : array {
		return array(
			// Absolute Lengths
			"px"    => "px",
			"cm"    => "cm",
			"mm"    => "mm",
			"in"    => "in",
			"pt"    => "pt",
			"pc"    => "pc",
			"q"     => "q",

			// Font-Relative Lengths
			"em"    => "em",
			"rem"   => "rem",
			"ex"    => "ex",
			"ch"    => "ch",
			"cap"   => "cap",
			"ic"    => "ic",
			"lh"    => "lh",
			"rlh"   => "rlh",

			// Viewport-Relative Lengths (Standard)
			"vw"    => "vw",
			"vh"    => "vh",
			"vmin"  => "vmin",
			"vmax"  => "vmax",

			// Viewport-Relative Lengths (Logical/Dynamic)
			"svw"   => "svw",
			"svh"   => "svh",
			"lvw"   => "lvw",
			"lvh"   => "lvh",
			"dvw"   => "dvw",
			"dvh"   => "dvh",
			"vi"    => "vi",
			"vb"    => "vb",

			// Container Query Units
			"cqw"   => "cqw",
			"cqh"   => "cqh",
			"cqi"   => "cqi",
			"cqb"   => "cqb",
			"cqmin" => "cqmin",
			"cqmax" => "cqmax",

			// Percentages
			"%"     => "%",

			// Angles
			"deg"   => "deg",
			"grad"  => "grad",
			"rad"   => "rad",
			"turn"  => "turn",

			// Time
			"s"     => "s",
			"ms"    => "ms",

			// Frequency
			"hz"    => "hz",
			"khz"   => "khz",

			// Resolution
			"dpi"   => "dpi",
			"dpcm"  => "dpcm",
			"dppx"  => "dppx",
			"x"     => "x",
		);
	}

	/**
	 * Insert one array before a given key in another array.
	 *
	 * If the target key is missing, the new array is appended when enabled.
	 *
	 * @param array  $array             Original array.
	 * @param string $key               Key to insert before.
	 * @param array  $new               Array to insert.
	 * @param bool   $append_if_missing Whether to append the new array if the key is not found.
	 *
	 * @return array Resulting array with the new values inserted.
	 */
	public static function insert_before( array $array, string $key, array $new, bool $append_if_missing = true ) : array {
		$keys = array_keys( $array );
		$pos  = array_search( $key, $keys, true );

		if ( $pos === false ) {
			if ( ! $append_if_missing ) {
				return $array;
			}

			return array_merge( $array, $new );
		}

		return array_merge(
			array_slice( $array, 0, $pos, true ),
			$new,
			array_slice( $array, $pos, null, true )
		);
	}
}