<?php

/**
 * Name:    Dev4Press\v56\API\Access
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

namespace Dev4Press\v56\API;

use Dev4Press\v56\Core\Quick\URL;
use Dev4Press\v56\Library;
use Dev4Press\v56\WordPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Access {
	protected string $api_url = 'https://api.dev4press.com/%s/%s/';
	protected string $site_url;
	protected bool $sslverify = true;

	private function __construct() {
		$url = WordPress::i()->is_multisite() ? network_home_url() : site_url();

		$this->site_url = wp_parse_url( $url, PHP_URL_HOST );
	}

	public static function i() : static {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function disable_sslverify() : static {
		$this->sslverify = false;

		return $this;
	}

	public function get( string $api, string $version, string $format = 'json' ) : array {
		$url = $this->url( $api, $version );

		$options  = $this->options();
		$response = wp_remote_get( $url, $options );

		return $this->process_response( $response, $format );
	}

	public function post( string $api, string $version, array $data = array(), string $format = 'json' ) : array {
		$url = $this->url( $api, $version );
		$obj = array(
			'license' => $this->license(),
			'data'    => $data,
		);

		$options         = $this->options();
		$options['body'] = wp_json_encode( $obj );

		$response = wp_remote_post( $url, $options );

		return $this->process_response( $response, $format );
	}

	protected function options( int $timeout = 30 ) : array {
		return array(
			'timeout'   => $timeout,
			'sslverify' => $this->sslverify,
			'headers'   => array(
				'X-Dev4press-Source'  => $this->site_url,
				'X-Dev4press-Library' => Library::i()->version(),
				'Referer'             => URL::current_url(),
			),
		);
	}

	protected function url( $api, $version ) : string {
		return sprintf( $this->api_url, $api, $version );
	}

	protected function process_response( $response, $format = 'json' ) : array {
		if ( is_wp_error( $response ) ) {
			return array(
				'error'   => $response->get_error_code(),
				'message' => $response->get_error_message(),
			);
		} else {
			$code = wp_remote_retrieve_response_code( $response );
			$body = wp_remote_retrieve_body( $response );

			if ( $format == 'raw' ) {
				return array(
					'code' => $code,
					'body' => $body,
				);
			} else if ( $format == 'json' ) {
				$data = json_decode( $body, true );

				if ( is_array( $data ) && ! empty( $data ) ) {
					if ( $code == 200 ) {
						return $data;
					} else {
						if ( isset( $data['error'] ) ) {
							return array(
								'error'   => $code,
								'message' => $data['error'],
							);
						}
					}
				}
			}

			return array(
				'error'   => $code,
				'message' => __( 'Nothing received', 'd4plib' ),
			);
		}
	}

	protected function license() : array {
		$license = $this->freemius()->_get_license();
		$site    = $this->freemius()->get_site();

		return array(
			'plugin_id'  => absint( $site->plugin_id ),
			'plan_id'    => absint( $site->plan_id ),
			'user_id'    => absint( $site->user_id ),
			'license_id' => absint( $site->license_id ),
			'site_id'    => absint( $site->site_id ),
			'install_id' => absint( $site->id ),
			'website'    => $site->url,
			'domain'     => $this->site_url,
			'is_valid'   => $license->is_valid(),
		);
	}

	abstract protected function freemius();
}
