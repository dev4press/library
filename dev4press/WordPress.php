<?php
/**
 * Name:    Dev4Press\v56\WordPress
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

use Dev4Press\v56\Core\Quick\WPR;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @method bool is_admin()
 * @method bool is_cli()
 * @method bool is_ajax()
 * @method bool is_cron()
 * @method bool is_rest()
 * @method bool is_xmlrpc()
 * @method bool is_debug()
 * @method bool is_multisite()
 * @method bool is_script_debug()
 * @method bool is_async_upload()
 * @method bool is_wordpress()
 * @method bool is_classicpress()
 * @method bool is_stable()
 */
final class WordPress {
	private array $_versions;
	private array $_switches;
	private array $_cached;

	private function __construct() {
		global $wp_version;

		$this->_cached = array();

		$this->_versions = array(
			'wp' => $wp_version,
		);

		$this->_switches = array(
			'wordpress'    => true,
			'classicpress' => false,
			'rest'         => false,
			'context'      => false,
			'multisite'    => is_multisite(),
			'cli'          => defined( 'WP_CLI' ) && defined( 'WP_CLI_VERSION' ) && defined( 'WP_CLI_START_MICROTIME' ) && WP_CLI,
			'admin'        => defined( 'WP_ADMIN' ) && WP_ADMIN,
			'ajax'         => defined( 'DOING_AJAX' ) && DOING_AJAX,
			'cron'         => defined( 'DOING_CRON' ) && DOING_CRON,
			'xmlrpc'       => defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST,
			'debug'        => defined( 'WP_DEBUG' ) && WP_DEBUG,
			'script_debug' => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG,
			'async_upload' => defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && 'upload-attachment' === $_REQUEST['action'], // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
			'stable'       => ! str_contains( $wp_version, '-' ),
		);

		if ( WPR::is_classicpress() ) {
			$this->_switches['wordpress']    = false;
			$this->_switches['classicpress'] = true;
			$this->_versions['cp']           = function_exists( 'classicpress_version' ) ? classicpress_version() : '2.0';
		}

		$this->_versions['cms'] = $this->_versions['cp'] ?? $this->_versions['wp'];

		add_action( 'rest_api_init', array( $this, 'rest_api' ) );
	}

	/**
	 * Magic getter for the `is_*` checks exposed as dynamic methods.
	 *
	 * @param string $name Method name.
	 * @param array $arguments Method arguments.
	 *
	 * @return bool
	 */
	public function __call( string $name, array $arguments ) {
		if ( str_starts_with( $name, 'is_' ) ) {
			$switch = substr( $name, 3 );

			if ( isset( $this->_switches[ $switch ] ) ) {
				return $this->_switches[ $switch ] ?? false;
			}
		}

		return false;
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
	 * Get the CMS name.
	 *
	 * @return string
	 */
	public function cms() : string {
		return $this->is_classicpress() ? 'classicpress' : 'wordpress';
	}

	/**
	 * Get the CMS display title.
	 *
	 * @return string
	 */
	public function cms_title() : string {
		return $this->is_classicpress() ? 'ClassicPress' : 'WordPress';
	}

	/**
	 * Get the uploads directory path.
	 *
	 * @return string
	 */
	public function uploads_directory() : string {
		$uploads = wp_upload_dir();

		return $uploads['basedir'];
	}

	/**
	 * Get the major version for a stored version key.
	 *
	 * @param string $key Version key.
	 *
	 * @return string
	 */
	public function major_version( string $key = 'cms' ) : string {
		$version = $this->version( $key );

		return substr( $version, 0, 3 );
	}

	/**
	 * Get a stored version value.
	 *
	 * @param string $key Version key.
	 *
	 * @return string
	 */
	public function version( string $key = 'cms' ) : string {
		return $this->_versions[ $key ] ?? '0.0.0';
	}

	/**
	 * Check whether a stored version is equal to or higher than the provided version.
	 *
	 * @param string $version Version to compare against.
	 * @param string $key Version key.
	 *
	 * @return bool
	 */
	public function is_version_equal_or_higher( string $version = '', string $key = 'cms' ) : bool {
		return version_compare( $this->version( $key ), $version, '>=' );
	}

	/**
	 * Check whether a stored version is lower than the provided version.
	 *
	 * @param string $version Version to compare against.
	 * @param string $key Version key.
	 *
	 * @return bool
	 */
	public function is_version_lower( string $version = '', string $key = 'cms' ) : bool {
		return version_compare( $this->version( $key ), $version, '<' );
	}

	/**
	 * Update the cached REST request state.
	 *
	 * @return void
	 */
	public function rest_api() : void {
		$this->_switches['rest'] = defined( 'REST_REQUEST' ) && REST_REQUEST;
	}

	/**
	 * Get the current execution context.
	 *
	 * @return string
	 */
	public function context() : string {
		if ( $this->_switches['context'] === false ) {
			if ( $this->_switches['cli'] ) {
				$this->_switches['context'] = 'CLI';
			} else if ( $this->_switches['cron'] ) {
				$this->_switches['context'] = 'CRON';
			} else if ( $this->_switches['ajax'] ) {
				$this->_switches['context'] = 'AJAX';
			} else if ( $this->_switches['rest'] ) {
				$this->_switches['context'] = 'REST';
			} else {
				$this->_switches['context'] = '';
			}
		}

		return $this->_switches['context'];
	}

	/**
	 * Check whether the CoreActivity plugin is available.
	 *
	 * @return bool
	 */
	public function has_coreactivity() : bool {
		if ( ! isset( $this->_cached['has_coreactivity'] ) ) {
			$this->_cached['has_coreactivity'] = defined( 'COREACTIVITY_VERSION' ) && function_exists( 'coreactivity' ) && class_exists( '\Dev4Press\Plugin\CoreActivity\Basic\Plugin' );
		}

		return $this->_cached['has_coreactivity'];
	}
}