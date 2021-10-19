<?php

/*
Name:    Dev4Press\v37\Core\Plugins\AJAX
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

namespace Dev4Press\v37\Core\Plugins;

abstract class AJAX {
	private $_no_cache = true;

	public function __construct() {
	}

	abstract public static function instance();

	public function error( string $message = '', int $code = 400, array $args = array() ) {
		$result = array(
			'status'  => 'error',
			'message' => empty( $message ) ? __( "Invalid Request" ) : $message
		);

		if ( ! empty( $args ) ) {
			$result += $args;
		}

		$this->respond( $result, $code );
	}

	public function respond( array $response, int $code = 200 ) {
		status_header( $code );

		if ( $this->_no_cache ) {
			nocache_headers();
		}

		header( 'Content-Type: application/json' );

		die( json_encode( $response ) );
	}
}
