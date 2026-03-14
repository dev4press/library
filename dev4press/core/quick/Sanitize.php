<?php
/**
 * Name:    Dev4Press\v56\Core\Quick\Sanitize
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

use DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sanitize {
	/**
	 * Sanitize a date-like value using a specific format.
	 *
	 * @param string $value           The value to sanitize.
	 * @param string $format          Date format used for parsing and output.
	 * @param string $return_on_error Value returned when parsing fails.
	 *
	 * @return string
	 */
	public static function date( string $value, string $format = 'Y-m-d', string $return_on_error = '' ) : string {
		$dt = DateTime::createFromFormat( '!' . $format, $value );

		if ( $dt === false ) {
			return $return_on_error;
		}

		return $dt->format( $format );
	}

	/**
	 * Sanitize a time-like value using a specific format.
	 *
	 * @param string $value The value to sanitize.
	 * @param string $format Time format used for parsing and output.
	 * @param string $return_on_error Value returned when parsing fails.
	 *
	 * @return string
	 */
	public static function time( string $value, string $format = 'H:i:s', string $return_on_error = '' ) : string {
		return self::date( $value, $format, $return_on_error );
	}

	/**
	 * Sanitize a month-like value using a specific format.
	 *
	 * @param string $value The value to sanitize.
	 * @param string $format Month format used for parsing and output.
	 * @param string $return_on_error Value returned when parsing fails.
	 *
	 * @return string
	 */
	public static function month( string $value, string $format = 'Y-m', string $return_on_error = '' ) : string {
		return self::date( $value, $format, $return_on_error );
	}

	/**
	 * Sanitize a value as absolute integer.
	 *
	 * @param mixed $value The value to sanitize.
	 *
	 * @return int
	 */
	public static function absint( mixed $value ) : int {
		return absint( $value );
	}

	/**
	 * Sanitize a value as email address.
	 *
	 * @param string $email The email to sanitize.
	 *
	 * @return string
	 */
	public static function email( string $email ) : string {
		return sanitize_email( $email );
	}

	/**
	 * Sanitize a value as URL.
	 *
	 * @param string $url The URL to sanitize.
	 *
	 * @return string
	 */
	public static function url( string $url ) : string {
		return sanitize_url( $url ); // phpcs:ignore WordPress.WP.DeprecatedFunctions
	}

	/**
	 * Sanitize a value as a key string.
	 *
	 * @param mixed $text The value to sanitize.
	 *
	 * @return string
	 */
	public static function key( mixed $text ) : string {
		$text = stripslashes( (string) $text );

		return sanitize_key( $text );
	}

	/**
	 * Sanitize a value as a slug.
	 *
	 * @param mixed $text The value to sanitize.
	 *
	 * @return string
	 */
	public static function slug( mixed $text ) : string {
		if ( is_null( $text ) ) {
			return '';
		}

		$text = stripslashes( (string) $text );

		return trim( sanitize_title_with_dashes( $text ), "-_ \t\n\r\0\x0B" );
	}

	/**
	 * Sanitize a value as a slug that can contain slashes.
	 *
	 * @param mixed $text The value to sanitize.
	 *
	 * @return string
	 */
	public static function slag_with_slashes( mixed $text ) : string {
		if ( is_null( $text ) ) {
			return '';
		}

		$text = stripslashes( (string) $text );
		$text = strtolower( $text );

		return preg_replace( '/[^a-z0-9.\/_\-]/', '', $text );
	}

	/**
	 * Sanitize a value as plain text.
	 *
	 * @param mixed $text The value to sanitize.
	 * @param bool $strip_shortcodes Whether shortcodes should be removed first.
	 *
	 * @return string
	 */
	public static function text( mixed $text, bool $strip_shortcodes = false ) : string {
		if ( is_null( $text ) ) {
			return '';
		}

		$text = stripslashes( (string) $text );

		if ( $strip_shortcodes ) {
			$text = strip_shortcodes( $text );
		}

		return trim( wp_kses( $text, array() ) );
	}

	/**
	 * Sanitize a value as HTML.
	 *
	 * @param mixed $text The value to sanitize.
	 * @param array|string|null $tags Allowed HTML tags. Defaults to 'post'.
	 * @param array $protocols Allowed protocols.
	 * @param bool $strip_shortcodes Whether shortcodes should be removed first.
	 *
	 * @return string
	 */
	public static function html( mixed $text, mixed $tags = null, array $protocols = array(), bool $strip_shortcodes = false ) : string {
		if ( is_null( $text ) ) {
			return '';
		}

		$tags = is_null( $tags ) ? 'post' : $tags;
		$text = stripslashes( (string) $text );

		if ( $strip_shortcodes ) {
			$text = strip_shortcodes( $text );
		}

		return wp_kses( trim( $text ), $tags, $protocols );
	}

	/**
	 * Deep-sanitize array values using one of the supported sanitizers.
	 *
	 * @param array $input Input array to sanitize.
	 * @param string $method Sanitizing method: html, text, key, or slug.
	 * @param bool $strip_shortcodes Whether shortcodes should be removed first.
	 *
	 * @return array
	 */
	public static function deep( array $input, string $method, bool $strip_shortcodes = false ) : array {
		switch ( $method ) {
			default:
			case 'html':
				$input = map_deep( $input, '\Dev4Press\v56\Core\Quick\Sanitize::html' );
				break;
			case 'text':
				$input = map_deep( $input, '\Dev4Press\v56\Core\Quick\Sanitize::text' );
				break;
			case 'key':
				$input = map_deep( $input, '\Dev4Press\v56\Core\Quick\Sanitize::key' );
				break;
			case 'slug':
				$input = map_deep( $input, '\Dev4Press\v56\Core\Quick\Sanitize::slug' );
				break;
		}

		if ( $strip_shortcodes ) {
			$input = map_deep( $input, 'strip_shortcodes' );
		}

		return $input;
	}

	/**
	 * Sanitize a list of CSS classes.
	 *
	 * @param array|string $classes Classes as array or space-separated string.
	 *
	 * @return string
	 */
	public static function html_classes( array|string $classes ) : string {
		$list = is_array( $classes ) ? $classes : explode( ' ', trim( stripslashes( $classes ) ) );
		$list = array_map( 'sanitize_html_class', $list );

		return trim( join( ' ', $list ) );
	}

	/**
	 * Sanitize a list of IDs.
	 *
	 * @param array|scalar|null $ids IDs to sanitize.
	 * @param callable|string $map Mapping callback used for each value.
	 *
	 * @return array
	 */
	public static function ids_list( mixed $ids, string $map = 'absint' ) : array {
		if ( empty( $ids ) ) {
			return array();
		}

		$ids = (array) $ids;

		$ids = array_map( $map, $ids );
		$ids = array_unique( $ids );

		return array_filter( $ids );
	}

	/**
	 * Sanitize a file path or filename.
	 *
	 * @param string $filename The filename/path to sanitize.
	 *
	 * @return string
	 */
	public static function file_path( string $filename ) : string {
		$filename_raw = $filename;

		$special_chars = apply_filters(
			__NAMESPACE__ . '\sanitize\file_path_chars',
			array(
				'?',
				'[',
				']',
				'/',
				'\\',
				'=',
				'<',
				'>',
				':',
				';',
				',',
				"'",
				'"',
				'&',
				'$',
				'#',
				'*',
				'(',
				')',
				'|',
				'~',
				'`',
				'!',
				'{',
				'}',
				'%',
				'+',
				chr( 0 ),
			),
			$filename_raw
		);

		$filename = preg_replace( "#\x{00a0}#siu", ' ', $filename );
		$filename = str_replace( $special_chars, '', $filename );
		$filename = str_replace( array( '%20', '+' ), '-', $filename );
		$filename = preg_replace( '/[\r\n\t -]+/', '-', $filename );
		$filename = trim( $filename, '.-_' );

		return apply_filters( __NAMESPACE__ . '\sanitize\file_path', $filename, $filename_raw );
	}

	/**
	 * Read a switch-style array from POST data.
	 *
	 * @param string      $key     Main POST key.
	 * @param bool|string $sub_key Optional sub-key to read from.
	 * @param string      $value   Expected value for enabled entries.
	 *
	 * @return array
	 */
	public static function _get_switch_array( string $key, bool|string $sub_key = false, string $value = 'on' ) : array {
		$source = self::deep( $_POST[ $key ] ?? array(), 'key' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
		$source = $sub_key !== false ? ( $source[ $sub_key ] ?? array() ) : $source;
		$result = array();

		foreach ( $source as $name => $val ) {
			if ( $value === $val ) {
				$result[] = sanitize_key( $name );
			}
		}

		return $result;
	}

	/**
	 * Get a slug value from query parameters.
	 *
	 * @param string $name Query parameter name.
	 * @param string $default Default value if parameter is missing.
	 *
	 * @return string
	 */
	public static function _get_slug( string $name, string $default = '' ) : string {
		return ! empty( $_GET[ $name ] ) ? self::slug( $_GET[ $name ] ) : $default; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
	}

	/**
	 * Get a text value from query parameters.
	 *
	 * @param string $name Query parameter name.
	 * @param string $default Default value if parameter is missing.
	 *
	 * @return string
	 */
	public static function _get_text( string $name, string $default = '' ) : string {
		return ! empty( $_GET[ $name ] ) ? self::text( $_GET[ $name ] ) : $default; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
	}

	/**
	 * Get an absolute integer value from query parameters.
	 *
	 * @param string $name Query parameter name.
	 * @param int $default Default value if parameter is missing.
	 *
	 * @return int
	 */
	public static function _get_absint( string $name, int $default = 0 ) : int {
		return ! empty( $_GET[ $name ] ) ? absint( $_GET[ $name ] ) : $default; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
	}

	/**
	 * Get a list of IDs from query parameters.
	 *
	 * @param string $name Query parameter name.
	 * @param array $default Default value if parameter is missing.
	 *
	 * @return array
	 */
	public static function _get_ids( string $name, array $default = array() ) : array {
		$ids = isset( $_GET[ $name ] ) ? (array) $_GET[ $name ] : $default; // phpcs:ignore WordPress.Security.NonceVerification,WordPress.Security.ValidatedSanitizedInput

		return self::ids_list( $ids );
	}
}