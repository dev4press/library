<?php

/*
Name:    Dev4Press\v36\Core\UI\Enqueue
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

namespace Dev4Press\v36\Core\UI;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Enqueue {
	private $_version = '3.6';
	private $_enqueue_prefix = 'd4plib3-';
	private $_library = 'd4plib';
	private $_debug;
	private $_url;
	private $_rtl = false;

	/** @var \Dev4Press\v36\Core\Admin\Plugin|\Dev4Press\v36\Core\Admin\Menu\Plugin|\Dev4Press\v36\Core\Admin\Submenu\Plugin */
	private $_admin;

	private $_loaded = array(
		'js'  => array(),
		'css' => array()
	);

	private $_libraries = array(
		'js'  => array(
			'meta'                   => array(
				'path' => 'js/',
				'file' => 'meta',
				'ext'  => 'js',
				'min'  => true
			),
			'media'                  => array(
				'path' => 'js/',
				'file' => 'media',
				'ext'  => 'js',
				'min'  => true
			),
			'ctrl'                   => array(
				'path' => 'js/',
				'file' => 'ctrl',
				'ext'  => 'js',
				'min'  => true
			),
			'helpers'                => array(
				'path' => 'js/',
				'file' => 'helpers',
				'ext'  => 'js',
				'min'  => true
			),
			'customizer'             => array(
				'path' => 'js/',
				'file' => 'customizer',
				'ext'  => 'js',
				'min'  => true
			),
			'widgets'                => array(
				'path' => 'js/',
				'file' => 'widgets',
				'ext'  => 'js',
				'min'  => true
			),
			'wizard'                 => array(
				'path' => 'js/',
				'file' => 'wizard',
				'ext'  => 'js',
				'min'  => true
			),
			'confirmsubmit'          => array(
				'path' => 'js/',
				'file' => 'confirmsubmit',
				'ext'  => 'js',
				'min'  => true
			),
			'admin'                  => array(
				'path' => 'js/',
				'file' => 'admin',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'confirmsubmit' )
			),
			'flatpickr'              => array(
				'path'       => 'libraries/flatpickr/',
				'file'       => 'flatpickr.min',
				'ver'        => '4.6.9',
				'ext'        => 'js',
				'min'        => false,
				'min_locale' => true,
				'locales'    => array( 'de', 'es', 'fr', 'it', 'nl', 'pl', 'pt', 'ru', 'sr' )
			),
			'flatpickr-confirm-date' => array(
				'path' => 'libraries/flatpickr/plugins',
				'file' => 'confirm-date',
				'ver'  => '4.6.9',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'flatpickr' )
			),
			'flatpickr-month-select' => array(
				'path' => 'libraries/flatpickr/plugins',
				'file' => 'month-select',
				'ver'  => '4.6.9',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'flatpickr' )
			),
			'flatpickr-range'        => array(
				'path' => 'libraries/flatpickr/plugins',
				'file' => 'range',
				'ver'  => '4.6.9',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'flatpickr' )
			),
			'clipboard'              => array(
				'path' => 'libraries/',
				'file' => 'clipboard.min',
				'ver'  => '2.0.4',
				'ext'  => 'js',
				'min'  => false
			),
			'cookies'                => array(
				'path' => 'libraries/',
				'file' => 'cookies.min',
				'ver'  => '2.2.1',
				'ext'  => 'js',
				'min'  => false
			),
			'alphanumeric'           => array(
				'path' => 'libraries/',
				'file' => 'jquery.alphanumeric.min',
				'ver'  => '2017',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'mark'                   => array(
				'path' => 'libraries/',
				'file' => 'jquery.mark.min',
				'ver'  => '8.11.1',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'fitvids'                => array(
				'path' => 'libraries/',
				'file' => 'jquery.fitvids.min',
				'ver'  => '1.2.0',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'jqeasycharcounter'      => array(
				'path' => 'libraries/',
				'file' => 'jquery.jqeasycharcounter.min',
				'ver'  => '1.0',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'limitkeypress'          => array(
				'path' => 'libraries/',
				'file' => 'jquery.limitkeypress.min',
				'ver'  => '2016',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'numeric'                => array(
				'path' => 'libraries/',
				'file' => 'jquery.numeric.min',
				'ver'  => '1.4.1',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'select'                 => array(
				'path' => 'libraries/',
				'file' => 'jquery.select.min',
				'ver'  => '2.2.6',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'textrange'              => array(
				'path' => 'libraries/',
				'file' => 'jquery.textrange.min',
				'ver'  => '1.4.0',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'wpcolorpickeralpha'     => array(
				'path' => 'libraries/',
				'file' => 'wp-color-picker-alpha.min',
				'ver'  => '2.1.3',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'wp-color-picker' )
			)
		),
		'css' => array(
			'pack'                   => array(
				'path' => 'css/',
				'file' => 'pack',
				'ext'  => 'css',
				'min'  => true
			),
			'about'                  => array(
				'path' => 'css/',
				'file' => 'about',
				'ext'  => 'css',
				'min'  => true
			),
			'flags'                  => array(
				'path' => 'css/',
				'file' => 'flags',
				'ext'  => 'css',
				'min'  => true
			),
			'font'                   => array(
				'path' => 'css/',
				'file' => 'font',
				'ext'  => 'css',
				'min'  => true
			),
			'font-embed'             => array(
				'path' => 'css/',
				'file' => 'font-embed',
				'ext'  => 'css',
				'min'  => true
			),
			'grid'                   => array(
				'path' => 'css/',
				'file' => 'grid',
				'ext'  => 'css',
				'min'  => true
			),
			'ctrl'                   => array(
				'path' => 'css/',
				'file' => 'ctrl',
				'ext'  => 'css',
				'min'  => true
			),
			'meta'                   => array(
				'path' => 'css/',
				'file' => 'meta',
				'ext'  => 'css',
				'min'  => true
			),
			'options'                => array(
				'path' => 'css/',
				'file' => 'options',
				'ext'  => 'css',
				'min'  => true
			),
			'shared'                 => array(
				'path' => 'css/',
				'file' => 'shared',
				'ext'  => 'css',
				'min'  => true
			),
			'widgets'                => array(
				'path' => 'css/',
				'file' => 'widgets',
				'ext'  => 'css',
				'min'  => true
			),
			'customizer'             => array(
				'path' => 'css/',
				'file' => 'customizer',
				'ext'  => 'css',
				'min'  => true
			),
			'responsive'             => array(
				'path' => 'css/',
				'file' => 'responsive',
				'ext'  => 'css',
				'min'  => true
			),
			'admin'                  => array(
				'path' => 'css/',
				'file' => 'admin',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'shared' )
			),
			'wizard'                 => array(
				'path' => 'css/',
				'file' => 'wizard',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'admin' )
			),
			'rtl'                    => array(
				'path' => 'css/',
				'file' => 'rtl',
				'ext'  => 'css',
				'min'  => true
			),
			'balloon'                => array(
				'path' => 'css/',
				'file' => 'balloon',
				'ext'  => 'css',
				'min'  => true
			),
			'flatpickr'              => array(
				'path' => 'libraries/flatpickr/',
				'file' => 'flatpickr.min',
				'ver'  => '4.6.9',
				'ext'  => 'css',
				'min'  => false
			),
			'flatpickr-confirm-date' => array(
				'path' => 'libraries/flatpickr/plugins',
				'file' => 'confirm-date',
				'ver'  => '4.6.9',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'flatpickr' )
			),
			'flatpickr-month-select' => array(
				'path' => 'libraries/flatpickr/plugins',
				'file' => 'month-select',
				'ver'  => '4.6.9',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'flatpickr' )
			)
		)
	);

	public function __construct( $base_url, $admin ) {
		$this->_url   = $base_url;
		$this->_admin = $admin;

		add_action( 'admin_init', array( $this, 'start' ), 15 );
	}

	public static function instance( $base_url, $admin ) : Enqueue {
		static $_d4p_lib_loader = array();

		$base_url = trailingslashit( $base_url );

		if ( ! isset( $_d4p_lib_loader[ $base_url ] ) ) {
			$_d4p_lib_loader[ $base_url ] = new Enqueue( $base_url, $admin );
		}

		return $_d4p_lib_loader[ $base_url ];
	}

	public function is_rtl() : bool {
		return $this->_rtl;
	}

	public function start() {
		$this->_rtl   = is_rtl();
		$this->_debug = $this->_admin->is_debug;
	}

	public function register( $type, $name, $args = array() ) : Enqueue {
		$this->_libraries[ $type ][ $name ] = $args;

		return $this;
	}

	public function js( $name ) : Enqueue {
		$this->add( 'js', $name );

		return $this;
	}

	public function css( $name ) : Enqueue {
		$this->add( 'css', $name );

		return $this;
	}

	public function flatpickr( $plugins = array() ) : Enqueue {
		$this->add( 'js', 'flatpickr' );
		$this->add( 'css', 'flatpickr' );

		if ( ! empty( $plugins ) ) {
			foreach ( $plugins as $plug ) {
				$this->add( 'js', 'flatpickr-' . $plug );
				$this->add( 'css', 'flatpickr-' . $plug );
			}
		}

		return $this;
	}

	public function wp( $includes = array() ) : Enqueue {
		$defaults = array( 'dialog' => false, 'color_picker' => false, 'media' => false, 'sortable' => false );
		$includes = shortcode_atts( $defaults, $includes );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-form' );

		if ( $includes['dialog'] === true ) {
			wp_enqueue_script( 'wpdialogs' );
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
		}

		if ( $includes['color_picker'] === true ) {
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );
		}

		if ( $includes['sortable'] === true ) {
			wp_enqueue_script( 'jquery-ui-sortable' );
		}

		if ( $includes['media'] === true ) {
			wp_enqueue_media();
		}

		return $this;
	}

	public function prefix() : string {
		return $this->_enqueue_prefix;
	}

	public function locale() {
		return apply_filters( 'plugin_locale', get_user_locale() );
	}

	public function locale_js_code( $script ) {
		$locale = $this->locale();

		if ( ! empty( $locale ) && isset( $this->_libraries['js'][ $script ]['locales'] ) ) {
			$code = strtolower( substr( $locale, 0, 2 ) );

			if ( in_array( $code, $this->_libraries['js'][ $script ]['locales'] ) ) {
				return $code;
			}
		}

		return false;
	}

	private function add( $type, $name ) {
		if ( isset( $this->_libraries[ $type ][ $name ] ) ) {
			if ( ! $this->is_added( $type, $name ) ) {
				$obj = $this->_libraries[ $type ][ $name ];
				$req = $obj['req'] ?? array();

				if ( isset( $obj['int'] ) && ! empty( $obj['int'] ) ) {
					foreach ( $obj['int'] as $lib ) {
						$req[] = $this->prefix() . $lib;

						if ( ! $this->is_added( $type, $lib ) ) {
							$this->add( $type, $lib );
						}
					}
				}

				$handle = $this->prefix() . $name;
				$ver    = $obj['ver'] ?? $this->_version;
				$footer = $obj['footer'] ?? true;

				$this->enqueue( $type, $handle, $this->url( $obj ), $req, $ver, $footer );

				$this->_loaded[ $type ][] = $name;

				if ( isset( $obj['locales'] ) ) {
					$_locale = $this->locale_js_code( $name );

					if ( $_locale !== false ) {
						$this->enqueue( $type, $handle . '-' . $_locale, $this->url( $obj, $_locale ), array( $handle ), $ver, $footer );
					}
				}

				if ( $name == 'admin' ) {
					$this->localize_admin();
				}

				if ( $name == 'meta' ) {
					$this->localize_meta();
				}

				if ( $name == 'media' ) {
					$this->localize_media();
				}
			}
		}
	}

	private function url( $obj, $locale = null ) : string {
		$plugin = isset( $obj['src'] ) && $obj['src'] == 'plugin';
		$path   = $plugin ? trailingslashit( $obj['path'] ) : trailingslashit( 'resources/' . $obj['path'] );
		$base   = $this->_url;

		if ( is_null( $locale ) ) {
			$min  = $obj['min'];
			$path .= $obj['file'];
		} else {
			$min  = $obj['min_locale'];
			$path .= 'l10n/' . $locale;
		}

		if ( $min && ! $this->_debug ) {
			$path .= '.min';
		}

		$path .= '.' . $obj['ext'];

		if ( ! $plugin ) {
			$base .= $this->_library . '/';
		}

		return $base . $path;
	}

	private function is_added( $type, $name ) : bool {
		return in_array( $name, $this->_loaded[ $type ] );
	}

	private function enqueue( $type, $handle, $url, $req, $version, $footer = true ) {
		if ( $type == 'js' ) {
			wp_enqueue_script( $handle, $url, $req, $version, $footer );
		} else if ( $type == 'css' ) {
			wp_enqueue_style( $handle, $url, $req, $version );
		}
	}

	private function localize_admin() {
		wp_localize_script( $this->prefix() . 'admin', 'd4plib_admin_data',
			array(
				'plugin'  => array(
					'name'   => $this->_admin->plugin,
					'prefix' => $this->_admin->plugin_prefix
				),
				'page'    => array(
					'panel'    => $this->_admin->panel,
					'subpanel' => $this->_admin->subpanel
				),
				'content' => array(
					'nonce' => wp_create_nonce( $this->_admin->plugin . '-admin-internal' )
				)
			) + $this->localize_shared_args() );
	}

	private function localize_meta() {
		wp_localize_script( $this->prefix() . 'meta', 'd4plib_meta_data',
			$this->localize_shared_args()
		);
	}

	private function localize_media() {
		wp_localize_script( $this->prefix() . 'media', 'd4plib_media_data', array(
			'strings' => array(
				'image_remove'       => __( "Remove", "d4plib" ),
				'image_preview'      => __( "Preview", "d4plib" ),
				'image_title'        => __( "Select Image", "d4plib" ),
				'image_button'       => __( "Use Selected Image", "d4plib" ),
				'image_not_selected' => __( "Image not selected.", "d4plib" ),
				'are_you_sure'       => __( "Are you sure you want to do this?", "d4plib" )
			),
			'icons'   => array(
				'remove'  => "<i aria-hidden='true' class='d4p-icon d4p-ui-ban d4p-icon-fw'></i>",
				'preview' => "<i aria-hidden='true' class='d4p-icon d4p-ui-search d4p-icon-fw'></i>"
			)
		) );
	}

	private function localize_shared_args() : array {
		return array(
			'lib' => array(
				'flatpickr' => $this->locale_js_code( 'flatpickr' )
			),
			'ui'  => array(
				'icons'    => array(
					'spinner' => '<i class="d4p-icon d4p-ui-spinner d4p-icon-fw d4p-icon-spin"></i>',
					'ok'      => '<i class="d4p-icon d4p-ui-check d4p-icon-fw" aria-hidden="true"></i> ',
					'send'    => '<i class="d4p-icon d4p-ui-paper-plane d4p-icon-fw" aria-hidden="true"></i> ',
					'cancel'  => '<i class="d4p-icon d4p-ui-cancel d4p-icon-fw" aria-hidden="true"></i> ',
					'delete'  => '<i class="d4p-icon d4p-ui-trash d4p-icon-fw" aria-hidden="true"></i> ',
					'disable' => '<i class="d4p-icon d4p-ui-times d4p-icon-fw" aria-hidden="true"></i> ',
					'empty'   => '<i class="d4p-icon d4p-ui-eraser d4p-icon-fw" aria-hidden="true"></i> ',
				),
				'buttons'  => array(
					'ok'      => __( "OK", "d4plib" ),
					'cancel'  => __( "Cancel", "d4plib" ),
					'delete'  => __( "Delete", "d4plib" ),
					'disable' => __( "Disable", "d4plib" ),
					'empty'   => __( "Empty", "d4plib" ),
					'send'    => __( "Send", "d4plib" ),
					'start'   => __( "Start", "d4plib" ),
					'stop'    => __( "Stop", "d4plib" ),
					'wait'    => __( "Wait", "d4plib" )
				),
				'messages' => array(
					'areyousure' => __( "Are you sure you want to do this?", "d4plib" ),
					'pleasewait' => __( "Please Wait...", "d4plib" )
				)
			)
		);
	}
}
