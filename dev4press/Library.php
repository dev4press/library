<?php
/**
 * Name:    Dev4Press\v53\Library
 * Version: v5.3
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2025 Milan Petrovic (email: support@dev4press.com)
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

namespace Dev4Press\v53;

use Composer\CaBundle\CaBundle;
use Dev4Press\v53\Core\DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Library {
	private string $_version = '5.3';
	private string $_build = '5300';
	private string $_php_version;
	private int $_php_code;
	private string $_library_url;
	private string $_library_path;
	private string $_cacert_path;
	private string $_base_path = 'vendor/dev4press/library';
	private DateTime $_datetime;

	public function __construct() {
		$this->_datetime     = new DateTime();
		$this->_php_version  = (string) phpversion();
		$this->_php_code     = absint( substr( str_replace( '.', '', $this->_php_version ), 0, 2 ) );
		$this->_library_url  = str_replace( '/' . $this->_base_path . '/dev4press/', '/' . $this->_base_path . '/', plugins_url( '/', __FILE__ ) );
		$this->_library_path = wp_normalize_path( trailingslashit( dirname( __FILE__, 2 ) ) );
		$this->_cacert_path  = CaBundle::getSystemCaRootBundlePath();
	}

	public static function instance() : Library {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Library();
		}

		return $instance;
	}

	public function datetime() : DateTime {
		return $this->_datetime;
	}

	public function charset() {
		return get_option( 'blog_charset' );
	}

	public function version() : string {
		return $this->_version;
	}

	public function build() : string {
		return $this->_build;
	}

	public function php_version() : string {
		return $this->_php_version;
	}

	public function php_code() : int {
		return $this->_php_code;
	}

	public function path() : string {
		return $this->_library_path;
	}

	public function base_path() : string {
		return $this->_base_path;
	}

	public function url() : string {
		return $this->_library_url;
	}

	public function cacert_path() : string {
		return $this->_cacert_path;
	}
}
