<?php

/*
Name:    Dev4Press\v36\WordPress
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

namespace Dev4Press\v36;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @method bool is_admin()
 * @method bool is_ajax()
 * @method bool is_cron()
 * @method bool is_debug()
 * @method bool is_script_debug()
 * @method bool is_async_upload()
 */
class WordPress {
	private $_switches;

	public function __construct() {
		$this->_switches = array(
			'admin'        => defined( 'WP_ADMIN' ) && WP_ADMIN,
			'ajax'         => defined( 'DOING_AJAX' ) && DOING_AJAX,
			'cron'         => defined( 'DOING_CRON' ) && DOING_CRON,
			'debug'        => defined( 'WP_DEBUG' ) && WP_DEBUG,
			'script_debug' => defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG,
			'async_upload' => defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && 'upload-attachment' === $_REQUEST['action']
		);
	}

	public function __call( $name, $arguments ) {
		if ( substr( $name, 0, 3 ) === 'is_' ) {
			$switch = substr( $name, 3 );

			if ( isset( $this->_switches[ $switch ] ) ) {
				return $this->_switches[ $switch ];
			}
		}

		return false;
	}

	public static function instance() : WordPress {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new WordPress();
		}

		return $instance;
	}
}
