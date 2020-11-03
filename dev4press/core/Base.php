<?php

/*
Name:    Dev4Press\Core\Base
Version: v3.3
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2020 Milan Petrovic (email: support@dev4press.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace Dev4Press\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Base {
	function __construct( $args = array() ) {
		if ( is_array( $args ) && ! empty( $args ) ) {
			$this->from_array( $args );
		}
	}

	function __clone() {
		foreach ( $this as $key => $val ) {
			if ( is_object( $val ) || ( is_array( $val ) ) ) {
				$this->{$key} = unserialize( serialize( $val ) );
			}
		}
	}

	public function to_array() {
		return (array) $this;
	}

	public function from_array( $args ) {
		foreach ( $args as $key => $value ) {
			$this->$key = $value;
		}
	}
}
