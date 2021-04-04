<?php

/*
Name:    Dev4Press\v35\Functions\Transient
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

namespace Dev4Press\v35\Functions\Transient;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( __NAMESPACE__ . '\sql_query' ) ) {
	function sql_query( $query, $key, $method, $output = OBJECT, $x = 0, $y = 0, $ttl = 86400 ) {
		$var = get_transient( $key );

		if ( $var === false ) {
			global $wpdb;

			switch ( $method ) {
				case 'var':
					$var = $wpdb->get_var( $query, $x, $y );
					break;
				case 'row':
					$var = $wpdb->get_row( $query, $output, $y );
					break;
				case 'results':
					$var = $wpdb->get_results( $query, $output );
					break;
			}

			set_transient( $key, $var, $ttl );
		}

		return $var;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sql_query_site' ) ) {
	function sql_query_site( $query, $key, $method, $output = OBJECT, $x = 0, $y = 0, $ttl = 86400 ) {
		$var = get_site_transient( $key );

		if ( $var === false ) {
			global $wpdb;

			switch ( $method ) {
				case 'var':
					$var = $wpdb->get_var( $query, $x, $y );
					break;
				case 'row':
					$var = $wpdb->get_row( $query, $output, $y );
					break;
				case 'results':
					$var = $wpdb->get_results( $query, $output );
					break;
			}

			set_site_transient( $key, $var, $ttl );
		}

		return $var;
	}
}
