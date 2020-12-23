<?php

/*
Name:    Dev4Press\Core\Options\Process
Version: v3.3.1
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2020 Milan Petrovic (email: support@dev4press.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace Dev4Press\Core\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Process {
	public $bool_values = array( true, false );

	public $base = 'd4pvalue';
	public $prefix = 'd4p';
	public $settings;

	public function __construct( $base, $prefix = 'd4p' ) {
		$this->base   = $base;
		$this->prefix = $prefix;
	}

	/** @return \Dev4Press\Core\Options\Process */
	public static function instance( $base = 'd4pvalue', $prefix = 'd4p' ) {
		static $process = array();

		if ( ! isset( $process[ $base ] ) ) {
			$process[ $base ] = new Process( $base, $prefix );
		}

		return $process[ $base ];
	}

	public function prepare( $settings ) {
		$this->settings = $settings;

		return $this;
	}

	public function process( $request = false ) {
		$list = array();

		if ( $request === false ) {
			$request = $_REQUEST;
		}

		foreach ( $this->settings as $setting ) {
			if ( $setting->type != '_' ) {
				$post = isset( $request[ $this->base ][ $setting->type ] ) ? $request[ $this->base ][ $setting->type ] : array();

				$list[ $setting->type ][ $setting->name ] = $this->process_single( $setting, $post );
			}
		}

		return $list;
	}

	public function slug_slashes( $key ) {
		$key = strtolower( $key );
		$key = preg_replace( '/[^a-z0-9.\/_\-]/', '', $key );

		return $key;
	}

	public function process_single( $setting, $post ) {
		$input = $setting->input;
		$key   = $setting->name;
		$value = null;

		switch ( $input ) {
			default:
				$value = apply_filters( $this->prefix . '_process_option_call_back_for_' . $input, $value, $post[ $key ], $setting );

				if ( is_null( $value ) ) {
					$value = d4p_sanitize_basic( (string) $post[ $key ] );
				}
				break;
			case 'skip':
			case 'info':
			case 'hr':
			case 'custom':
				$value = null;
				break;
			case 'expandable_raw':
				$value = array();

				foreach ( $post[ $key ] as $id => $data ) {
					if ( $id > 0 ) {
						$_val = trim( stripslashes( $data['value'] ) );

						if ( $_val != '' ) {
							$value[] = $_val;
						}
					}
				}
				break;
			case 'expandable_text':
				$value = array();

				foreach ( $post[ $key ] as $id => $data ) {
					if ( $id > 0 ) {
						$_val = d4p_sanitize_basic( $data['value'] );

						if ( ! empty( $_val ) ) {
							$value[] = $_val;
						}
					}
				}
				break;
			case 'expandable_pairs':
				$value = array();

				foreach ( $post[ $key ] as $id => $data ) {
					if ( $id > 0 ) {
						$_key = d4p_sanitize_basic( $data['key'] );
						$_val = d4p_sanitize_basic( $data['value'] );

						if ( ! empty( $_key ) && ! empty( $_val ) ) {
							$value[ $_key ] = $_val;
						}
					}
				}
				break;
			case 'range_integer':
				$value = intval( $post[ $key ]['a'] ) . '=>' . intval( $post[ $key ]['b'] );
				break;
			case 'range_absint':
				$value = absint( $post[ $key ]['a'] ) . '=>' . absint( $post[ $key ]['b'] );
				break;
			case 'x_by_y':
				$value = d4p_sanitize_basic( $post[ $key ]['x'] ) . 'x' . d4p_sanitize_basic( $post[ $key ]['y'] );
				break;
			case 'html':
			case 'code':
			case 'text_html':
			case 'text_rich':
				$value = d4p_sanitize_html( $post[ $key ] );
				break;
			case 'bool':
				$value = isset( $post[ $key ] ) ? $this->bool_values[0] : $this->bool_values[1];
				break;
			case 'number':
				$value = floatval( $post[ $key ] );
				break;
			case 'integer':
				$value = intval( $post[ $key ] );
				break;
			case 'image':
			case 'absint':
			case 'dropdown_pages':
			case 'dropdown_categories':
				$value = absint( $post[ $key ] );
				break;
			case 'images':
				if ( ! isset( $post[ $key ] ) ) {
					$value = array();
				} else {
					$value = (array) $post[ $key ];
					$value = array_map( 'intval', $value );
					$value = array_filter( $value );
				}
				break;
			case 'listing':
				$value = d4p_split_textarea_to_list( stripslashes( $post[ $key ] ) );
				break;
			case 'media':
				$value = 0;
				if ( $post[ $key ] != '' ) {
					$value = absint( substr( $post[ $key ], 3 ) );
				}
				break;
			case 'checkboxes':
			case 'checkboxes_hierarchy':
			case 'select_multi':
			case 'group_multi':
				if ( ! isset( $post[ $key ] ) ) {
					$value = array();
				} else {
					$value = (array) $post[ $key ];
					$value = array_map( 'd4p_sanitize_basic', $value );
				}
				break;
			case 'css_size':
				$sizes = d4p_list_css_size_units();

				$value = d4p_sanitize_basic( $post[ $key ]['val'] );
				$unit  = strtolower( d4p_sanitize_basic( $post[ $key ]['unit'] ) );

				if ( ! isset( $sizes[ $unit ] ) ) {
					$unit = 'px';
				}

				$value = $value . $unit;
				break;
			case 'slug':
				$value = d4p_sanitize_slug( $post[ $key ] );
				break;
			case 'slug_ext':
				$value = d4p_sanitize_key_expanded( $post[ $key ] );
				break;
			case 'slug_slash':
				$value = $this->slug_slashes( $post[ $key ] );
				break;
			case 'email':
				$value = sanitize_email( $post[ $key ] );
				break;
			case 'date':
				$value = d4p_sanitize_date( $post[ $key ] );
				break;
			case 'time':
				$value = d4p_sanitize_time( $post[ $key ] );
				break;
			case 'datetime':
				$value = d4p_sanitize_date( $post[ $key ], 'Y-m-d H:i:s' );
				break;
			case 'month':
				$value = d4p_sanitize_month( $post[ $key ] );
				break;
			case 'text':
			case 'textarea':
			case 'password':
			case 'group':
			case 'link':
			case 'color':
			case 'block':
			case 'hidden':
			case 'select':
			case 'radios':
			case 'radios_hierarchy':
				$value = d4p_sanitize_basic( $post[ $key ] );
				break;
		}

		return $value;
	}
}
