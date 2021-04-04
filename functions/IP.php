<?php

/*
Name:    Dev4Press\v35\Functions\IP
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

namespace Dev4Press\v35\Functions\IP;

use Dev4Press\v35\Core\Helpers\IP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function is_v6( $ip ) : bool {
	if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
		if ( filter_var( $ip, FILTER_FLAG_IPV6 ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\in_range' ) ) {
	function in_range( $ip, $range ) : bool {
		return is_v6( $ip ) ? IP::is_ipv6_in_range( $ip, $range ) : IP::is_ipv4_in_range( $ip, $range );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_cloudflare' ) ) {
	function is_cloudflare( $ip = null ) : bool {
		return IP::is_cloudflare_ip( $ip );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_private' ) ) {
	function is_private( $ip = null ) : bool {
		return IP::is_private_ip( $ip );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\visitor' ) ) {
	function visitor( $no_local_or_protected = false ) {
		return IP::get_visitor_ip( $no_local_or_protected );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\server' ) ) {
	function server() : string {
		return IP::get_server_ip();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\validate' ) ) {
	function validate( $ip, $no_local_or_protected = false ) {
		return IP::validate_ip( $ip, $no_local_or_protected );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\clean' ) ) {
	function clean( $ip ) {
		return IP::cleanup_ip( $ip );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\whois' ) ) {
	function whois( $ip = '' ) : string {
		return IP::get_ip_whois( $ip );
	}
}
