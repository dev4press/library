<?php

/*
Name:    Dev4Press\v36\Functions
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

namespace Dev4Press\v36\Functions;

use DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_date' ) ) {
	function sanitize_date( $value, $format = 'Y-m-d', $return_on_error = '' ) : string {
		$dt = DateTime::createFromFormat( '!' . $format, $value );

		if ( $dt === false ) {
			return $return_on_error;
		}

		return $dt->format( $format );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_time' ) ) {
	function sanitize_time( $value, $format = 'H:i:s', $return_on_error = '' ) : string {
		return sanitize_date( $value, $format, $return_on_error );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_month' ) ) {
	function sanitize_month( $value, $format = 'Y-m', $return_on_error = '' ) : string {
		return sanitize_date( $value, $format, $return_on_error );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_file_path' ) ) {
	function sanitize_file_path( $filename ) : string {
		$filename_raw = $filename;

		$special_chars = apply_filters( __NAMESPACE__ . '\sanitize_file_path_chars', array(
			"?",
			"[",
			"]",
			"/",
			"\\",
			"=",
			"<",
			">",
			":",
			";",
			",",
			"'",
			"\"",
			"&",
			"$",
			"#",
			"*",
			"(",
			")",
			"|",
			"~",
			"`",
			"!",
			"{",
			"}",
			"%",
			"+",
			chr( 0 )
		), $filename_raw );

		$filename = preg_replace( "#\x{00a0}#siu", ' ', $filename );
		$filename = str_replace( $special_chars, '', $filename );
		$filename = str_replace( array( '%20', '+' ), '-', $filename );
		$filename = preg_replace( '/[\r\n\t -]+/', '-', $filename );
		$filename = trim( $filename, '.-_' );

		return apply_filters( __NAMESPACE__ . '\sanitize_file_path', $filename, $filename_raw );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_key_expanded' ) ) {
	function sanitize_key_expanded( $key ) : string {
		$key = strtolower( $key );

		return preg_replace( '/[^a-z0-9._\-]/', '', $key );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_extended' ) ) {
	function sanitize_extended( $text, $tags = null, $protocols = array(), $strip_shortcodes = false ) : string {
		$tags = is_null( $tags ) ? wp_kses_allowed_html( 'post' ) : $tags;
		$text = stripslashes( $text );

		if ( $strip_shortcodes ) {
			$text = strip_shortcodes( $text );
		}

		return wp_kses( trim( $text ), $tags, $protocols );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_basic' ) ) {
	function sanitize_basic( $text, $strip_shortcodes = true ) : string {
		$text = stripslashes( $text );

		if ( $strip_shortcodes ) {
			$text = strip_shortcodes( $text );
		}

		return trim( wp_kses( $text, array() ) );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_html' ) ) {
	function sanitize_html( $text, $tags = null, $protocols = array() ) : string {
		$tags = is_null( $tags ) ? wp_kses_allowed_html( 'post' ) : $tags;

		return wp_kses( trim( stripslashes( $text ) ), $tags, $protocols );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_slug' ) ) {
	function sanitize_slug( $text ) : string {
		return trim( sanitize_title_with_dashes( stripslashes( $text ) ), "-_ \t\n\r\0\x0B" );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_html_classes' ) ) {
	function sanitize_html_classes( $classes ) : string {
		$list = explode( ' ', trim( stripslashes( $classes ) ) );
		$list = array_map( 'sanitize_html_class', $list );

		return trim( join( ' ', $list ) );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_basic_array' ) ) {
	function sanitize_basic_array( $input, $strip_shortcodes = true ) : array {
		$output = array();

		foreach ( $input as $key => $value ) {
			$output[ $key ] = sanitize_basic( $value, $strip_shortcodes );
		}

		return $output;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_ids_list' ) ) {
	function sanitize_ids_list( $ids, $map = 'absint' ) : array {
		$ids = (array) $ids;

		$ids = array_map( $map, $ids );
		$ids = array_unique( $ids );

		return array_filter( $ids );
	}
}
