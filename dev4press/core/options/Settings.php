<?php

/*
Name:    Dev4Press\Core\Options\Settings
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

namespace Dev4Press\Core\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Settings {
	protected $settings;

	public function __construct() {
		$this->init();
	}

	/** @return \Dev4Press\Core\Options\Settings */
	public static function instance() {
		static $instance = array();

		$class = get_called_class();

		if ( ! isset( $instance[ $class ] ) ) {
			$instance[ $class ] = new $class();
		}

		return $instance[ $class ];
	}

	public function all() {
		return $this->settings;
	}

	public function get( $panel, $group = '' ) {
		if ( $group == '' ) {
			return $this->settings[ $panel ];
		} else {
			return $this->settings[ $panel ][ $group ];
		}
	}

	public function settings( $panel ) {
		$list = array();

		if ( in_array( $panel, array( 'index', 'full' ) ) ) {
			foreach ( $this->settings as $panel ) {
				foreach ( $panel as $obj ) {
					$list = array_merge( $list, $this->settings_from_panel( $obj ) );
				}
			}
		} else {
			foreach ( $this->settings[ $panel ] as $obj ) {
				$list = array_merge( $list, $this->settings_from_panel( $obj ) );
			}
		}

		return $list;
	}

	public function settings_from_panel( $obj ) {
		$list = array();

		if ( isset( $obj['settings'] ) ) {
			$obj['sections'] = array( 'label' => '', 'name' => '', 'class' => '', 'settings' => $obj['settings'] );
			unset( $obj['settings'] );
		}

		foreach ( $obj['sections'] as $s ) {
			foreach ( $s['settings'] as $o ) {
				if ( ! empty( $o->type ) ) {
					$list[] = $o;
				}
			}
		}

		return $list;
	}

	abstract protected function init();

	abstract protected function value( $name, $group = 'settings', $default = null );
}
