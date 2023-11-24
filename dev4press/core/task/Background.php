<?php
/**
 * Name:    Dev4Press\v45\Core\Task\Background
 * Version: v4.5
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)
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

namespace Dev4Press\v45\Core\Task;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Background {
	protected $nonce = '';
	protected $action = '';
	protected $transient = '';
	protected $key = '';

	public function __construct() {
		add_action( 'wp_ajax_' . $this->action, array( $this, 'handler' ) );
		add_action( 'wp_ajax_nopriv_' . $this->action, array( $this, 'handler' ) );
	}

	/** @return static */
	public static function instance() {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	protected function init_data() : array {
		return array(
			'data'     => array(),
			'messages' => array(),
			'status'   => 'idle',
			'total'    => 0,
			'done'     => 0,
		);
	}

	public function check_nonce() {
		$ajax_nonce = isset( $_REQUEST['_ajax_nonce'] ) ? sanitize_key( $_REQUEST['_ajax_nonce'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$nonce      = wp_verify_nonce( $ajax_nonce, $this->nonce );

		if ( $nonce === false ) {
			wp_die( - 1 );
		}
	}
}
