<?php
/**
 * Name:    Dev4Press\v56\Core\Shared\Enqueue
 * Version: v5.6
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2026 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Dev4Press\v56\Core\Shared;

use Dev4Press\v56\Library;
use Dev4Press\v56\WordPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Enqueue {
	private static $_current_instance = null;

	private string $_enqueue_prefix = 'd4plib-v56-';
	private string $_url;
	private bool $_rtl = false;
	private bool $_debug = false;

	private array $_actual = array(
		'js'  => array(),
		'css' => array(),
	);

	private array $_deps = array(
		'js'  => array(),
		'css' => array(),
	);

	private array $_libraries = array(
		'js'  => array(),
		'css' => array(),
	);

	/** @deprecated 5.6.0 To be removed in 5.7.0. */
	private array $_locales = array();

	protected function __construct() {
		$this->_url = Library::i()->url();

		$this->_libraries['js']  = Resources::instance()->shared_js();
		$this->_libraries['css'] = Resources::instance()->shared_css();

		add_action( 'init', array( $this, 'start' ), 15 );
	}

	/** @return Enqueue */
	public static function init() : static {
		if ( is_null( self::$_current_instance ) ) {
			self::$_current_instance = new Enqueue();
		}

		return self::$_current_instance;
	}

	/** @return Enqueue */
	public static function i() : static {
		return self::init();
	}

	public function prefix() : string {
		return $this->_enqueue_prefix;
	}

	/** @deprecated 5.6.0 To be removed in 5.7.0. */
	public function locale() {
		return apply_filters( 'plugin_locale', determine_locale(), 'd4plib' );
	}

	/** @deprecated 5.6.0 To be removed in 5.7.0. */
	public function locale_js_code( $script ) : bool|string {
		$locale = $this->locale();

		if ( ! empty( $locale ) && isset( $this->_libraries['js'][ $script ]['locales'] ) ) {
			$code = strtolower( substr( $locale, 0, 2 ) );

			if ( in_array( $code, $this->_libraries['js'][ $script ]['locales'] ) ) {
				return $code;
			}
		}

		return false;
	}

	/** @deprecated 5.6.0 To be removed in 5.7.0. */
	public function registered_locale( $script ) {
		return $this->_locales[ $script ] ?? false;
	}

	public function start() : void {
		$this->_rtl   = is_rtl();
		$this->_debug = WordPress::i()->is_script_debug();

		/** HOOK: `dev4press_v56_shared_enqueue_start` */
		do_action( Library::i()->hook( 'shared_enqueue_start' ) );

		/** @deprecated 5.5.0 To be removed in 5.7.0. */
		do_action( 'd4plib_shared_enqueue_prepare' );

		$this->register_styles();
		$this->register_scripts();
	}

	public function add_css( $name, $args = array() ) : void {
		$this->_libraries['css'][ $name ] = $args;
	}

	public function add_js( $name, $args = array() ) : void {
		$this->_libraries['js'][ $name ] = $args;
	}

	public function get_actual( $type, $name ) {
		return $this->_actual[ $type ][ $name ] ?? '';
	}

	public function get_locale( $name ) {
		return $this->_locales[ $name ] ?? '';
	}

	public function is_rtl() : bool {
		return $this->_rtl;
	}

	public function is_debug() : bool {
		return $this->_debug;
	}

	public function register_styles() : void {
		foreach ( $this->_libraries['css'] as $name => $args ) {
			$code = $args['lib'] ? $this->_enqueue_prefix . $name : $name;
			$req  = $args['req'] ?? array();
			$ver  = $args['ver'] ?? Library::i()->version();

			if ( ! empty( $args['int'] ) ) {
				foreach ( $args['int'] as $lib ) {
					if ( isset( $this->_actual['css'][ $lib ] ) ) {
						$req[] = $this->_actual['css'][ $lib ];
					}
				}
			}

			wp_register_style( $code, $this->url( $args ), $req, $ver );

			$this->_actual['css'][ $name ] = $code;
			$this->_deps['css'][ $name ]   = $req;
		}
	}

	public function register_scripts() : void {
		foreach ( $this->_libraries['js'] as $name => $args ) {
			$code   = $args['lib'] ? $this->_enqueue_prefix . $name : $name;
			$req    = $args['req'] ?? array();
			$footer = $args['footer'] ?? true;

			if ( ! empty( $args['int'] ) ) {
				foreach ( $args['int'] as $lib ) {
					if ( isset( $this->_actual['js'][ $lib ] ) ) {
						$req[] = $this->_actual['js'][ $lib ];
					}
				}
			}

			wp_register_script( $code, $this->url( $args ), $req, $args['ver'], $footer );

			$this->_actual['js'][ $name ] = $code;
			$this->_deps['js'][ $name ]   = $req;
		}
	}

	public function enqueue( $type, $name ) {
		$handle = $this->get_actual( $type, $name );

		if ( ! empty( $handle ) ) {
			if ( $type == 'css' ) {
				wp_enqueue_style( $handle );
			} else {
				wp_enqueue_script( $handle );
			}
		}

		return $handle;
	}

	private function url( $obj ) : string {
		$min = $obj['min'] ?? false;
		$src = $min && $obj['ext'] === 'js' ? 'src/scripts/' : 'resources/dist/';
		$url = trailingslashit( $this->_url . $src . ( $obj['path'] ?? '' ) );

		if ( ! empty( $obj['url'] ) ) {
			$url = trailingslashit( $obj['url'] );
		}

		$url .= $obj['file'];

		if ( $min && ! $this->_debug ) {
			$url .= '.min';
		}

		$url .= '.' . $obj['ext'];

		return $url;
	}
}
