<?php
/**
 * Name:    Dev4Press\v56\Library
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

namespace Dev4Press\v56;

use Composer\CaBundle\CaBundle;
use Dev4Press\v56\Core\DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Library {
	private string $_version = '5.6.2';
	private string $_code = 'v56';
	private string $_build = '5620';
	private string $_php_version;
	private int $_php_code;
	private string $_library_url;
	private string $_library_path;
	private string $_cacert_path;
	private string $_base_path = 'vendor/dev4press/library';
	private DateTime $_datetime;

	private function __construct() {
		$this->_datetime     = DateTime::i();
		$this->_php_version  = (string) phpversion();
		$this->_php_code     = absint( substr( str_replace( '.', '', $this->_php_version ), 0, 2 ) );
		$this->_library_url  = str_replace( '/' . $this->_base_path . '/dev4press/', '/' . $this->_base_path . '/', plugins_url( '/', __FILE__ ) );
		$this->_library_path = wp_normalize_path( trailingslashit( dirname( __FILE__, 2 ) ) );
		$this->_cacert_path  = CaBundle::getSystemCaRootBundlePath();
	}

	/**
	 * Get the singleton instance.
	 *
	 * @return self
	 * @deprecated 5.5.0 Use self::i() instead. To be removed in 5.7.0.
	 *
	 */
	public static function instance() : self {
		return self::i();
	}

	/**
	 * Get the singleton instance.
	 *
	 * @return self
	 */
	public static function i() : self {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Get the cached DateTime helper instance.
	 *
	 * @return DateTime
	 */
	public function datetime() : DateTime {
		return $this->_datetime;
	}

	/**
	 * Build a namespaced hook name.
	 *
	 * @param string $name Hook suffix.
	 *
	 * @return string
	 */
	public function hook( string $name ) : string {
		return 'dev4press_' . $this->_code . '_' . $name;
	}

	/**
	 * Get the WordPress blog charset.
	 *
	 * @return string|false
	 */
	public function charset() : string|false {
		return get_option( 'blog_charset' );
	}

	/**
	 * Get the library version.
	 *
	 * @return string
	 */
	public function version() : string {
		return $this->_version;
	}

	/**
	 * Get the library build number.
	 *
	 * @return string
	 */
	public function build() : string {
		return $this->_build;
	}

	/**
	 * Get the current PHP version string.
	 *
	 * @return string
	 */
	public function php_version() : string {
		return $this->_php_version;
	}

	/**
	 * Get the current PHP version code.
	 *
	 * @return int
	 */
	public function php_code() : int {
		return $this->_php_code;
	}

	/**
	 * Get the normalized library path.
	 *
	 * @return string
	 */
	public function path() : string {
		return $this->_library_path;
	}

	/**
	 * Get the base library path relative to plugins.
	 *
	 * @return string
	 */
	public function base_path() : string {
		return $this->_base_path;
	}

	/**
	 * Get the library URL.
	 *
	 * @return string
	 */
	public function url() : string {
		return $this->_library_url;
	}

	/**
	 * Get the system CA certificate bundle path.
	 *
	 * @return string
	 */
	public function cacert_path() : string {
		return $this->_cacert_path;
	}
}