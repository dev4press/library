<?php

/*
Name:    Dev4Press\v39\Core\Features\Load
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

namespace Dev4Press\v39\Core\Features;

use Dev4Press\v39\Core\Scope;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Load {
	protected $_load;
	protected $_list;
	protected $_active = array();
	protected $_scopes = array( 'global', 'admin', 'front' );

	/** @return static */
	public static function instance() {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new static();
		}

		return $instance;
	}

	protected function allow_load( string $feature, bool $early = false, string $scope = '' ) : bool {
		if ( $this->is_always_on( $feature ) || $this->is_enabled( $feature ) ) {
			if ( $early === $this->is_early( $feature ) ) {
				$actual = $this->get_scope( $feature );

				if ( empty( $scope ) || $actual == 'global' || $actual == $scope ) {
					return true;
				}
			}
		}

		return false;
	}

	public function list() : array {
		return array_keys( $this->_list );
	}

	public function load_main( bool $early = false ) {
		$scope = Scope::instance()->is_frontend() ? 'front' : 'admin';

		foreach ( $this->list() as $feature ) {
			if ( $this->allow_load( $feature, $early, $scope ) ) {
				$this->_active[] = $feature;
				$this->_list[ $feature ]['main']::instance();
			}
		}
	}

	public function load_admin() {
		foreach ( $this->list() as $feature ) {
			$this->_list[ $feature ]['admin']::instance();
		}
	}

	public function attribute( string $attr, string $feature, $default = null ) {
		if ( $attr == 'get_scope' ) {
			$attr = 'scope';
		}

		$default = $default ?? ( in_array( $attr, array(
				'is_active',
				'is_enabled',
				'is_always_on',
				'is_early',
				'has_settings',
				'has_menu',
				'has_meta_tab'
			) ) ? false : '' );

		if ( $this->is_valid( $feature ) ) {
			if ( $attr == 'is_active' ) {
				return $this->is_active( $feature );
			} else if ( $attr == 'is_enabled' ) {
				return $this->is_enabled( $feature );
			}

			$value = $this->_list[ $feature ]['attributes'][ $attr ] ?? $default;

			if ( $attr == 'scope' && ! in_array( $value, $this->_scopes ) ) {
				$value = 'global';
			}

			return $value;
		}

		return $default;
	}

	public function is_valid( string $feature ) : bool {
		return isset( $this->_list[ $feature ] );
	}

	public function is_enabled( string $feature ) : bool {
		return isset( $this->_load[ $feature ] ) && $this->_load[ $feature ] === true;
	}

	public function is_active( string $feature ) : bool {
		return in_array( $feature, $this->_active );
	}

	public function is_always_on( string $feature ) : bool {
		return (bool) $this->attribute( 'is_always_on', $feature );
	}

	public function is_early( string $feature ) : bool {
		return (bool) $this->attribute( 'is_early', $feature );
	}

	public function has_settings( string $feature ) : bool {
		return (bool) $this->attribute( 'has_settings', $feature );
	}

	public function has_menu( string $feature ) : bool {
		return (bool) $this->attribute( 'has_menu', $feature );
	}

	public function has_meta_tab( string $feature ) : bool {
		return (bool) $this->attribute( 'has_meta_tab', $feature );
	}

	public function get_scope( string $feature ) : string {
		return $this->attribute( 'scope', $feature );
	}

	public function panels( array $panels ) : array {
		foreach ( $this->_list as $feature => $obj ) {
			$panels[ $feature ] = array(
				'title'     => $obj['label'],
				'icon'      => $obj['icon'],
				'info'      => $obj['description'],
				'settings'  => $obj['has_settings'],
				'active'    => $this->is_active( $feature ),
				'always_on' => $this->is_always_on( $feature )
			);
		}

		return $panels;
	}

	public function activation( string $feature, bool $status ) {
		if ( $this->is_valid( $feature ) ) {
			$this->s()->set( $feature, $status, 'load', true );
		}
	}

	abstract public function s();
}
