<?php

/*
Name:    Dev4Press\Core\Plugins\Information
Version: v3.4
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

namespace Dev4Press\Core\Plugins;

use Dev4Press\API\Store;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Information {
	public $code = '';

	public $version = '';
	public $build = 0;
	public $updated = '';
	public $status = '';
	public $edition = '';
	public $released = '';

	public $is_bbpress_plugin = false;

	public $author_name = 'Milan Petrovic';
	public $author_url = 'https://www.dev4press.com/';

	public $php = '5.6';
	public $mysql = '5.5';

	public $cms = array(
		'wordpress'    => '5.0',
		'classicpress' => '1.0'
	);

	public $plugins = array(
		'bbpress'    => false,
		'buddypress' => false
	);

	public $install = false;
	public $update = false;
	public $previous = 0;

	public $translations = array();

	function __construct() {
	}

	public function to_array() {
		return (array) $this;
	}

	/** @return Information */
	public static function instance() {
		static $instance = array();

		$class = get_called_class();

		if ( ! isset( $instance[ $class ] ) ) {
			$instance[ $class ] = new $class();
		}

		return $instance[ $class ];
	}

	public function name() {
		return Store::instance()->name( $this->code );
	}

	public function description() {
		return Store::instance()->description( $this->code );
	}

	public function punchline() {
		return Store::instance()->punchline( $this->code );
	}

	public function color() {
		return Store::instance()->color( $this->code );
	}

	public function url() {
		return Store::instance()->url( $this->code );
	}

	public function system_requirements() {
		$list = array(
			'PHP'   => $this->php,
			'MySQL' => $this->mysql
		);

		if ( d4p_is_classicpress() ) {
			$list['ClassicPress'] = $this->cms['classicpress'];
		} else {
			$list['WordPress'] = $this->cms['wordpress'];
		}

		if ( isset( $this->plugins['bbpress'] ) && $this->plugins['bbpress'] !== false ) {
			$list['bbPress'] = $this->plugins['bbpress'];
		}

		if ( isset( $this->plugins['buddypress'] ) && $this->plugins['buddypress'] !== false ) {
			$list['BuddyPress'] = $this->plugins['buddypress'];
		}

		return $list;
	}

	public function requirement_version( $name ) {
		if ( in_array( $name, array( 'php', 'mysql' ) ) ) {
			return $this->$name;
		} else if ( isset( $this->cms[ $name ] ) ) {
			return $this->cms[ $name ];
		} else if ( isset( $this->plugins[ $name ] ) ) {
			return $this->plugins[ $name ];
		}

		return false;
	}

	public function version_full() {
		$version = $this->version;

		if ( $this->status != 'stable' ) {
			switch ( $this->status ) {
				case 'beta':
					$version .= ' ' . __( "Beta", "d4plib" );
					break;
				case 'alpha':
					$version .= ' ' . __( "Alpha", "d4plib" );
					break;
				case 'rc':
					$version .= ' ' . __( "RC", "d4plib" );
					break;
				case 'nightly':
					$version .= ' ' . __( "Nightly", "d4plib" );
					break;
			}
		}

		return $version;
	}
}
