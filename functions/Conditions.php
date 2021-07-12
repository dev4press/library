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

if ( ! function_exists( __NAMESPACE__ . '\is_odd' ) ) {
	function is_odd( $number ) : bool {
		return $number % 2 == 0;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_divisible' ) ) {
	function is_divisible( $number, $by_number ) : bool {
		return $number % $by_number == 0;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_associative_array' ) ) {
	function is_associative_array( $array ) : bool {
		return is_array( $array ) && ( 0 !== count( array_diff_key( $array, array_keys( array_keys( $array ) ) ) ) || count( $array ) == 0 );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_valid_md5' ) ) {
	function is_valid_md5( $hash = '' ) : bool {
		return strlen( $hash ) == 32 && ctype_xdigit( $hash );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_valid_datetime' ) ) {
	function is_valid_datetime( $date, $format = 'Y-m-d H:i:s' ) : bool {
		$d = DateTime::createFromFormat( $format, $date );

		return $d && $d->format( $format ) == $date;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_request_post' ) ) {
	function is_request_post() : bool {
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_regex_valid' ) ) {
	function is_regex_valid( $regex ) {
		if ( preg_match( '/' . $regex . '/i', 'dev4press' ) !== false ) {
			return true;
		}

		return preg_last_error();
	}
}
