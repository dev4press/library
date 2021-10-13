<?php

/*
Name:    Dev4Press\v37\Request
Version: v3.7
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

namespace Dev4Press\v37;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Server {
	public function __construct() {

	}

	public static function instance() : Server {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Server();
		}

		return $instance;
	}

	public function is_request_post() : bool {
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}

	public function is_request_get() : bool {
		return $_SERVER['REQUEST_METHOD'] === 'GET';
	}

	public function get_regex_error( $error_code ) : string {
		if ( is_bool( $error_code ) ) {
			return 'OK';
		}

		$errors = array_flip( get_defined_constants( true )['pcre'] );

		if ( isset( $errors[ $error_code ] ) ) {
			return $errors[ $error_code ];
		}

		return 'UNKNOWN_ERROR';
	}
}