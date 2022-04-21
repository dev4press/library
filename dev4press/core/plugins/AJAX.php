<?php

/*
Name:    Dev4Press\v38\Core\Plugins\AJAX
Version: v3.8
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

namespace Dev4Press\v38\Core\Plugins;

use Dev4Press\v38\Core\Quick\Sanitize;

abstract class AJAX {
	protected $prefix = 'd4plib';
	protected $form = 'd4plib-form';
	protected $no_cache_headers = true;
	protected $forms = array();

	public function __construct() {
		add_action( $this->prefix . '_ajax_request_error', array( $this, 'process_error' ), 10, 5 );
	}

	public function process_error( string $error, $request = null, string $message = '', int $code = 400, $data = null ) {
		if ( empty( $message ) ) {
			$message = 'Unspecified Problem.';
		}

		do_action( $this->prefix . '_ajax_live_handler_error',
			$error,
			$message,
			$code,
			array(
				'request' => $request,
				'data'    => $data
			) );

		$this->return_error( $message, $code );
	}

	protected function request_for_form( string $form_key = '' ) : array {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			$this->raise_error( 'request_invalid_method', null, 'Invalid Request method.', 405 );
		}

		$form_key = empty( $form_key ) ? $this->form : $form_key;

		if ( ! isset( $_REQUEST[ $form_key ] ) ) {
			$this->raise_malformed_error( null );
		}

		$request = (array) $_REQUEST[ $form_key ];

		if ( empty( $request ) || ! isset( $request['action'], $request['nonce'] ) ) {
			$this->raise_malformed_error( $request );
		}

		$request['action'] = Sanitize::key( $request['action'] );
		$request['nonce']  = Sanitize::key( $request['nonce'] );

		return $request;
	}

	protected function validate_form_request( array $request ) : array {
		if ( isset( $request['action'] ) && isset( $request['nonce'] ) ) {
			$action = $request['action'];

			if ( isset( $this->forms[ $action ] ) ) {
				$key = $this->forms[ $action ]['key'];
				$can = $this->forms[ $action ]['can'] ?? '';
				$non = $this->forms[ $action ]['nonce'] ?? false;

				if ( ! empty( $can ) && ! current_user_can( $can ) ) {
					$this->raise_unauthorized_error( $request );
				} else {
					$valid = true;

					if ( $non ) {
						if ( isset( $request[ $key ] ) ) {
							$request[ $key ] = Sanitize::basic( $request[ $key ] );
							$nonce           = $this->prefix . '-' . $action . '-' . $request[ $key ];

							if ( ! wp_verify_nonce( $request['nonce'], $nonce ) ) {
								$valid = false;
							}
						}
					}

					if ( $valid ) {
						if ( isset( $this->forms[ $action ]['required'] ) ) {
							foreach ( $this->forms[ $action ]['required'] as $req ) {
								if ( ! isset( $request[ $req ] ) ) {
									$valid = false;
									break;
								}
							}
						}
					}

					if ( $valid ) {
						return $request;
					}
				}
			}
		}

		$this->raise_malformed_error( $request );

		return array();
	}

	protected function raise_error( string $error, $request = null, string $message = '', int $code = 400, $data = null ) {
		do_action( $this->prefix . '_ajax_request_error', $error, $request, $message, $code, $data );
	}

	protected function raise_malformed_error( $request ) {
		$this->raise_error( 'request_malformed', $request, __( "Malformed Request.", "d4plib" ) );
	}

	protected function raise_unauthorized_error( $request ) {
		$this->raise_error( 'request_unauthorized', $request, __( "Unauthorized Request.", "d4plib" ), 401 );
	}

	protected function return_error( string $message = '', int $code = 400, array $args = array() ) {
		$result = array(
			'status'  => 'error',
			'message' => empty( $message ) ? __( "Invalid Request", "d4plib" ) : $message
		);

		if ( ! empty( $args ) ) {
			$result += $args;
		}

		$this->respond( $result, true, $code );
	}

	protected function respond( $response, bool $json = false, int $code = 200 ) {
		status_header( $code );

		if ( $this->no_cache_headers ) {
			nocache_headers();
		}

		if ( $json ) {
			header( 'Content-Type: application/json' );

			die( json_encode( $response ) );
		} else {
			die( $response );
		}
	}

	abstract public static function instance();
}
