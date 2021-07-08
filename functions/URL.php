<?php

/*
Name:    Dev4Press\v35\Functions
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

namespace Dev4Press\v35\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( __NAMESPACE__ . '\domain_name' ) ) {
	function domain_name( $url ) {
		return parse_url( $url, PHP_URL_HOST );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\current_request_path' ) ) {
	function current_request_path() {
		$uri = $_SERVER['REQUEST_URI'];

		return parse_url( $uri, PHP_URL_PATH );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\current_url_request' ) ) {
	function current_url_request() : string {
		$pathinfo = $_SERVER['PATH_INFO'] ?? '';
		list( $pathinfo ) = explode( '?', $pathinfo );
		$pathinfo = str_replace( '%', '%25', $pathinfo );

		$request         = explode( '?', $_SERVER['REQUEST_URI'] );
		$req_uri         = $request[0];
		$req_query       = $request[1] ?? false;
		$home_path       = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
		$home_path_regex = sprintf( '|^%s|i', preg_quote( $home_path, '|' ) );

		$req_uri = str_replace( $pathinfo, '', $req_uri );
		$req_uri = ltrim( $req_uri, '/' );
		$req_uri = preg_replace( $home_path_regex, '', $req_uri );
		$req_uri = ltrim( $req_uri, '/' );

		$url_request = $req_uri;

		if ( $req_query !== false ) {
			$url_request .= '?' . $req_query;
		}

		return $url_request;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\current_url' ) ) {
	function current_url( $use_wp = true ) : string {
		if ( $use_wp ) {
			return home_url( current_url_request() );
		} else {
			$s        = empty( $_SERVER['HTTPS'] ) ? '' : ( $_SERVER['HTTPS'] == 'on' ? 's' : '' );
			$protocol = strleft( strtolower( $_SERVER['SERVER_PROTOCOL'] ), '/' ) . $s;
			$port     = $_SERVER['SERVER_PORT'] == '80' || $_SERVER['SERVER_PORT'] == '443' ? '' : ':' . $_SERVER['SERVER_PORT'];

			return $protocol . '://' . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
		}
	}
}
