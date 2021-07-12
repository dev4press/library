<?php

/*
Name:    Dev4Press\v36\Core\Admin\GetBack
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

namespace Dev4Press\v36\Core\Admin;

use function Dev4Press\v36\Functions\WP\is_current_user_admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class GetBack {
	protected $admin;

	public function __construct( $admin ) {
		$this->admin = $admin;

		$this->process();
	}

	public function a() {
		return $this->admin;
	}

	protected function process() {
		if ( $this->a()->panel == 'dashboard' ) {
			if ( $this->is_single_action( 'dismiss-topic-prefix', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdtox_hide' );
			}

			if ( $this->is_single_action( 'dismiss-forum-notices', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdfon_hide' );
			}

			if ( $this->is_single_action( 'dismiss-topic-polls', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdpol_hide' );
			}

			if ( $this->is_single_action( 'dismiss-bbpress-toolbox', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdbbx_hide' );
			}

			if ( $this->is_single_action( 'dismiss-power-search', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdpos_hide' );
			}

			if ( $this->is_single_action( 'dismiss-quantum-theme', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdqnt_hide' );
			}

			if ( $this->is_single_action( 'dismiss-members-directory', 'action' ) ) {
				$this->front_dismiss_plugin( 'notice_gdmed_hide' );
			}
		}

		if ( $this->a()->panel == 'tools' ) {
			if ( $this->is_single_action( 'export', 'run' ) ) {
				$this->tools_export();
			}
		}
	}

	protected function front_dismiss_plugin( $option ) {
		$this->a()->settings()->set( $option, true, 'core', true );

		wp_redirect( $this->a()->current_url( false ) );
		exit;
	}

	protected function tools_export() {
		check_ajax_referer( 'dev4press-plugin-' . $this->a()->plugin_prefix );

		if ( ! is_current_user_admin() ) {
			wp_die( __( "Only administrators can use export features.", "d4plib" ) );
		}

		$data = $this->a()->settings()->export_to_secure_json();

		if ( $data !== false ) {
			$export_date = date( 'Y-m-d-H-m-s' );
			$export_name = $this->a()->plugin . '-settings-' . $export_date . '.json';

			header( 'Content-type: application/force-download' );
			header( 'Content-Disposition: attachment; filename="' . $export_name . '"' );

			die( $data );
		} else {
			wp_redirect( $this->a()->current_url( false ) );
			exit;
		}
	}

	protected function is_single_action( $name, $key = 'single-action' ) : bool {
		return isset( $_GET[ $key ] ) && $_GET[ $key ] == $name;
	}

	protected function is_bulk_action() : bool {
		return ( isset( $_GET['action'] ) && $_GET['action'] != '-1' ) || ( isset( $_GET['action2'] ) && $_GET['action2'] != '-1' );
	}

	protected function get_bulk_action() : string {
		$action = isset( $_GET['action'] ) && $_GET['action'] != '' && $_GET['action'] != '-1' ? $_GET['action'] : '';

		if ( $action == '' ) {
			$action = isset( $_GET['action2'] ) && $_GET['action2'] != '' && $_GET['action2'] != '-1' ? $_GET['action2'] : '';
		}

		return $action;
	}
}
