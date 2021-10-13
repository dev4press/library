<?php

/*
Name:    Dev4Press\v37\Core\Cache\Core
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

namespace Dev4Press\v37\Core\Cache;

use function Dev4Press\v37\Functions\object_cache;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Core {
	public $store = 'd4plib';

	public function __construct() {
		$this->clear();
	}

	abstract public static function instance();

	private function _key( $group, $key ) : string {
		return $group . '::' . $key;
	}

	public function add( $group, $key, $data ) : bool {
		return object_cache()->add( $this->_key( $group, $key ), $data, $this->store );
	}

	public function set( $group, $key, $data ) : bool {
		return object_cache()->set( $this->_key( $group, $key ), $data, $this->store );
	}

	public function get( $group, $key, $default = false ) : bool {
		$obj = object_cache()->get( $this->_key( $group, $key ), $this->store );

		return $obj === false ? $default : $obj;
	}

	public function delete( $group, $key ) : bool {
		return object_cache()->delete( $this->_key( $group, $key ), $this->store );
	}

	public function in( $group, $key ) : bool {
		return object_cache()->in( $this->_key( $group, $key ), $this->store );
	}

	public function clear() {
		object_cache()->flush( $this->store );
	}

	public function storage() : array {
		return object_cache()->get_group( $this->store );
	}
}
