<?php

/*
Name:    Dev4Press\v36\Core\Shared\Enqueue
Version: v3.6
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

namespace Dev4Press\v36\Core\Shared;

use Dev4Press\v36\Library;
use Dev4Press\v36\WordPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Enqueue {
	static private $_current_instance = null;

	private $_enqueue_prefix = 'd4plib3-';
	private $_url;
	private $_rtl;
	private $_debug;

	private $_locales = array();
	private $_actual = array(
		'js'  => array(),
		'css' => array()
	);

	private $_libraries = array(
		'js'  => array(),
		'css' => array()
	);

	public function __construct() {
		$this->_url = Library::instance()->url();

		$this->_libraries['js']  = Resources::instance()->shared_js();
		$this->_libraries['css'] = Resources::instance()->shared_css();

		add_action( 'init', array( $this, 'start' ), 15 );
	}

	/** @return Enqueue */
	public static function init() {
		if ( is_null( self::$_current_instance ) ) {
			self::$_current_instance = new Enqueue();
		}

		return self::$_current_instance;
	}

	/** @return Enqueue */
	public static function i() {
		return self::init();
	}

	public function prefix() : string {
		return $this->_enqueue_prefix;
	}

	public function locale() {
		return apply_filters( 'plugin_locale', get_user_locale() );
	}

	public function locale_js_code( $script ) {
		$locale = $this->locale();

		if ( ! empty( $locale ) && isset( $this->_libraries['js'][ $script ]['locales'] ) ) {
			$code = strtolower( substr( $locale, 0, 2 ) );

			if ( in_array( $code, $this->_libraries['js'][ $script ]['locales'] ) ) {
				return $code;
			}
		}

		return false;
	}

	public function registered_locale( $script ) {
		return $this->_locales[ $script ] ?? false;
	}

	public function start() {
		$this->_rtl   = is_rtl();
		$this->_debug = WordPress::instance()->is_script_debug();

		do_action( 'd4plib_shared_enqueue_prepare' );

		$this->register_styles();
		$this->register_scripts();
	}

	public function add_css( $name, $args = array() ) {
		$this->_libraries['css'][ $name ] = $args;
	}

	public function add_js( $name, $args = array() ) {
		$this->_libraries['js'][ $name ] = $args;
	}

	public function is_rtl() {
		return $this->_rtl;
	}

	public function register_styles() {
		foreach ( $this->_libraries['css'] as $name => $args ) {
			$code = $args['lib'] ? $this->_enqueue_prefix . $name : $name;
			$req  = $args['req'] ?? array();

			if ( isset( $args['int'] ) && ! empty( $args['int'] ) ) {
				foreach ( $args['int'] as $lib ) {
					$req[] = $this->_actual['css'][ $lib ];
				}
			}

			wp_register_style( $code, $this->url( $args ), $req, $args['ver'] );

			$this->_actual['css'][ $name ] = $code;
		}
	}

	public function register_scripts() {
		foreach ( $this->_libraries['js'] as $name => $args ) {
			$code   = $args['lib'] ? $this->_enqueue_prefix . $name : $name;
			$req    = $args['req'] ?? array();
			$footer = $args['footer'] ?? true;

			if ( isset( $args['int'] ) && ! empty( $args['int'] ) ) {
				foreach ( $args['int'] as $lib ) {
					$req[] = $this->_actual['js'][ $lib ];
				}
			}

			wp_register_script( $code, $this->url( $args ), $req, $args['ver'], $footer );

			$this->_actual['js'][ $name ] = $code;

			if ( isset( $args['locales'] ) ) {
				$_locale = $this->locale_js_code( $name );

				if ( $_locale !== false ) {
					$this->_locales[ $name ] = $_locale;
					$loc_code                = $code . '-' . $_locale;

					wp_register_script( $loc_code, $this->url( $args, $_locale ), array( $code ), $args['ver'], $footer );

					$this->_actual['js'][ $name ] = $loc_code;
				}
			}
		}
	}

	private function url( $obj, $locale = null ) : string {
		$url = $obj['lib'] ? trailingslashit( $this->_url . 'resources/libraries/' . $obj['path'] ) : trailingslashit( $obj['url'] );

		if ( is_null( $locale ) ) {
			$min = $obj['min'];
			$url .= $obj['file'];
		} else {
			$min = $obj['min_locale'];
			$url .= 'l10n/' . $locale;
		}

		if ( $min && ! $this->_debug ) {
			$url .= '.min';
		}

		$url .= '.' . $obj['ext'];

		return $url;
	}
}
