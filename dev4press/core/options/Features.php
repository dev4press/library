<?php

/*
Name:    Dev4Press\v39\Core\Options\Features
Version: v3.9
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2022 Milan Petrovic (email: support@dev4press.com)

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

namespace Dev4Press\v39\Core\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Features {
	protected $settings;
	protected $feature;

	public function __construct( $feature ) {
		$this->feature              = $feature;
		$this->settings[ $feature ] = array();

		$this->init();
	}

	abstract public static function instance( $feature );

	public function get() : array {
		return $this->settings[ $this->feature ];
	}

	public function settings() : array {
		$list = array();

		foreach ( $this->settings[ $this->feature ] as $data ) {
			foreach ( $data['sections'] as $section ) {
				foreach ( $section['settings'] as $o ) {
					if ( ! empty( $o->type ) ) {
						$list[] = $o;
					}
				}
			}
		}

		return $list;
	}

	abstract protected function init();

	abstract public function core();
}
