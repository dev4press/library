<?php
/**
 * Name:    Dev4Press\v45\Core\Options\Settings
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

namespace Dev4Press\v45\Core\Options;

use Dev4Press\v45\Core\DateTime;
use Dev4Press\v45\Core\Quick\Str;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Settings {
	protected $settings;

	public function __construct() {
		$this->init();
	}

	/** @return static */
	public static function instance() {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function i( string $type, string $name, string $title, string $notice, string $input ) : Element {
		return Element::i( $type, $name, $title, $notice, $input, $this->value( $name, $type ) );
	}

	public function info( string $title, string $notice ) : Element {
		return Element::info( $title, $notice );
	}

	public function all() {
		return $this->settings;
	}

	public function get( $panel, $group = '' ) {
		if ( $group == '' ) {
			return $this->settings[ $panel ];
		} else {
			return $this->settings[ $panel ][ $group ];
		}
	}

	public function settings( $panel ) : array {
		$list = array();

		if ( in_array( $panel, array( 'index', 'full' ) ) ) {
			foreach ( $this->settings as $panel ) {
				foreach ( $panel as $obj ) {
					$list = array_merge( $list, $this->settings_from_panel( $obj ) );
				}
			}
		} else {
			foreach ( $this->settings[ $panel ] as $obj ) {
				$list = array_merge( $list, $this->settings_from_panel( $obj ) );
			}
		}

		return $list;
	}

	public function settings_from_panel( $obj ) : array {
		$list = array();

		if ( isset( $obj['settings'] ) ) {
			$obj['sections'] = array(
				'label'    => '',
				'name'     => '',
				'class'    => '',
				'settings' => $obj['settings'],
			);
			unset( $obj['settings'] );
		}

		foreach ( $obj['sections'] as $s ) {
			foreach ( $s['settings'] as $o ) {
				if ( ! empty( $o->type ) ) {
					$list[] = $o;
				}
			}
		}

		return $list;
	}

	protected function settings_license() : array {
		$code = $this->value( 'code', 'license' );
		$info = $this->value( 'info', 'license' );
		$time = $this->value( 'check', 'license' );

		if ( empty( $code ) ) {
			$items = array(
				'<strong>' . __( 'License Code is not set.' ) . '</strong>',
			);
		} else if ( $time == 0 || empty( $info ) ) {
			$items = array(
				'<strong>' . __( 'License Code has not been checked yet.' ) . '</strong>',
			);
		} else {
			$items = array(
				'<span>' . __( 'Last Checked' ) . '</span>: <strong>' . DateTime::instance()->mysql_date( true, $time ) . '</strong>',
				'<hr/>',
				'<span>' . __( 'Valid' ) . '</span>: <strong>' . ( $info['valid'] === 'yes' ? esc_html__( 'Yes' ) : esc_html__( 'No' ) ) . '</strong><br/>',
				'<span>' . __( 'Status' ) . '</span>: <strong>' . esc_html( Str::slug_to_name( $info['status'], '-' ) ) . '</strong><br/>',
				'<span>' . __( 'Domain' ) . '</span>: <strong>' . esc_html( $info['domain'] ) . '</strong>',
			);
		}

		return array(
			'license-code' => array(
				'name'     => __( 'Dev4Press License', 'd4plib' ),
				'sections' => array(
					array(
						'label'    => __( "License Code" ),
						'name'     => '',
						'class'    => '',
						'settings' => array(
							$this->i( 'license', 'code', __( 'Code', 'd4plib' ), __( 'You can find your license code in the Dev4Press Dashboard.', 'd4plib' ), Type::LICENSE )->more( array(
								__( 'Make sure to enter the license code exactly as it is listed on the Dev4Press Dashboard.' ),
								__( 'License code is case sensitive, and all letters must be uppercase.', 'd4plib' ),
							) )->buttons(
								array(
									array(
										'type'   => 'a',
										'target' => '_blank',
										'link'   => 'https://https://my.dev4press.com/licenses/',
										'class'  => 'button-secondary',
										'title'  => __( 'Dev4Press Dashboard Licenses', 'd4plib' ),
									),
								)
							),
						),
					),
					array(
						'label'    => __( "License Information" ),
						'name'     => '',
						'class'    => '',
						'settings' => array(
							$this->info( __( 'Status' ), join( '', $items ) ),
						),
					),
				),
			),
		);
	}

	abstract protected function init();

	abstract protected function value( $name, $group = 'settings', $default = null );
}
