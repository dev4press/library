<?php

/*
Name:    Dev4Press\v36\Core\Shared\Enqueue
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

namespace Dev4Press\v36\Core\Shared;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Resources {
	private $_ui = array(
		'js'  => array(
			'meta'               => array(
				'path' => 'js/',
				'file' => 'meta',
				'ext'  => 'js',
				'min'  => true
			),
			'media'              => array(
				'path' => 'js/',
				'file' => 'media',
				'ext'  => 'js',
				'min'  => true
			),
			'ctrl'               => array(
				'path' => 'js/',
				'file' => 'ctrl',
				'ext'  => 'js',
				'min'  => true
			),
			'helpers'            => array(
				'path' => 'js/',
				'file' => 'helpers',
				'ext'  => 'js',
				'min'  => true
			),
			'customizer'         => array(
				'path' => 'js/',
				'file' => 'customizer',
				'ext'  => 'js',
				'min'  => true
			),
			'widgets'            => array(
				'path' => 'js/',
				'file' => 'widgets',
				'ext'  => 'js',
				'min'  => true
			),
			'wizard'             => array(
				'path' => 'js/',
				'file' => 'wizard',
				'ext'  => 'js',
				'min'  => true
			),
			'confirmsubmit'      => array(
				'path' => 'js/',
				'file' => 'confirmsubmit',
				'ext'  => 'js',
				'min'  => true
			),
			'admin'              => array(
				'path' => 'js/',
				'file' => 'admin',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'confirmsubmit' )
			),
			'wpcolorpickeralpha' => array(
				'path' => 'libraries/',
				'file' => 'wp-color-picker-alpha.min',
				'ver'  => '2.1.3',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'wp-color-picker' )
			)
		),
		'css' => array(
			'pack'       => array(
				'path' => 'css/',
				'file' => 'pack',
				'ext'  => 'css',
				'min'  => true
			),
			'about'      => array(
				'path' => 'css/',
				'file' => 'about',
				'ext'  => 'css',
				'min'  => true
			),
			'flags'      => array(
				'path' => 'css/',
				'file' => 'flags',
				'ext'  => 'css',
				'min'  => true
			),
			'font'       => array(
				'path' => 'css/',
				'file' => 'font',
				'ext'  => 'css',
				'min'  => true
			),
			'font-embed' => array(
				'path' => 'css/',
				'file' => 'font-embed',
				'ext'  => 'css',
				'min'  => true
			),
			'grid'       => array(
				'path' => 'css/',
				'file' => 'grid',
				'ext'  => 'css',
				'min'  => true
			),
			'ctrl'       => array(
				'path' => 'css/',
				'file' => 'ctrl',
				'ext'  => 'css',
				'min'  => true
			),
			'meta'       => array(
				'path' => 'css/',
				'file' => 'meta',
				'ext'  => 'css',
				'min'  => true
			),
			'options'    => array(
				'path' => 'css/',
				'file' => 'options',
				'ext'  => 'css',
				'min'  => true
			),
			'shared'     => array(
				'path' => 'css/',
				'file' => 'shared',
				'ext'  => 'css',
				'min'  => true
			),
			'widgets'    => array(
				'path' => 'css/',
				'file' => 'widgets',
				'ext'  => 'css',
				'min'  => true
			),
			'customizer' => array(
				'path' => 'css/',
				'file' => 'customizer',
				'ext'  => 'css',
				'min'  => true
			),
			'responsive' => array(
				'path' => 'css/',
				'file' => 'responsive',
				'ext'  => 'css',
				'min'  => true
			),
			'admin'      => array(
				'path' => 'css/',
				'file' => 'admin',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'shared' )
			),
			'wizard'     => array(
				'path' => 'css/',
				'file' => 'wizard',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'admin' )
			),
			'rtl'        => array(
				'path' => 'css/',
				'file' => 'rtl',
				'ext'  => 'css',
				'min'  => true
			),
			'balloon'    => array(
				'path' => 'css/',
				'file' => 'balloon',
				'ext'  => 'css',
				'min'  => true
			)
		)
	);

	private $_shared = array(
		'js'  => array(
			'animated-popup'         => array(
				'lib'  => true,
				'path' => 'animated-popup/',
				'file' => 'animated-popup.min',
				'ver'  => '1.8',
				'ext'  => 'js',
				'min'  => false
			),
			'flatpickr'              => array(
				'lib'        => true,
				'path'       => 'flatpickr/',
				'file'       => 'flatpickr.min',
				'ver'        => '4.6.9',
				'ext'        => 'js',
				'min'        => false,
				'min_locale' => true,
				'locales'    => array( 'de', 'es', 'fr', 'it', 'nl', 'pl', 'pt', 'ru', 'sr' )
			),
			'flatpickr-confirm-date' => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'confirm-date',
				'ver'  => '4.6.9',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'flatpickr' )
			),
			'flatpickr-month-select' => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'month-select',
				'ver'  => '4.6.9',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'flatpickr' )
			),
			'flatpickr-range'        => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'range',
				'ver'  => '4.6.9',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'flatpickr' )
			),
			'clipboard'              => array(
				'lib'  => true,
				'path' => '',
				'file' => 'clipboard.min',
				'ver'  => '2.0.4',
				'ext'  => 'js',
				'min'  => false
			),
			'cookies'                => array(
				'lib'  => true,
				'path' => '',
				'file' => 'cookies.min',
				'ver'  => '2.2.1',
				'ext'  => 'js',
				'min'  => false
			),
			'alphanumeric'           => array(
				'lib'  => true,
				'path' => '',
				'file' => 'jquery.alphanumeric.min',
				'ver'  => '2017',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'mark'                   => array(
				'lib'  => true,
				'path' => '',
				'file' => 'jquery.mark.min',
				'ver'  => '8.11.1',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'fitvids'                => array(
				'lib'  => true,
				'path' => '',
				'file' => 'jquery.fitvids.min',
				'ver'  => '1.2.0',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'jqeasycharcounter'      => array(
				'lib'  => true,
				'path' => '',
				'file' => 'jquery.jqeasycharcounter.min',
				'ver'  => '1.0',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'limitkeypress'          => array(
				'lib'  => true,
				'path' => '',
				'file' => 'jquery.limitkeypress.min',
				'ver'  => '2016',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'numeric'                => array(
				'lib'  => true,
				'path' => '',
				'file' => 'jquery.numeric.min',
				'ver'  => '1.4.1',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'select'                 => array(
				'lib'  => true,
				'path' => '',
				'file' => 'jquery.select.min',
				'ver'  => '2.2.6',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			),
			'textrange'              => array(
				'lib'  => true,
				'path' => '',
				'file' => 'jquery.textrange.min',
				'ver'  => '1.4.0',
				'ext'  => 'js',
				'min'  => false,
				'req'  => array( 'jquery' )
			)
		),
		'css' => array(
			'animated-popup'         => array(
				'lib'  => true,
				'path' => 'animated-popup/',
				'file' => 'animated-popup.min',
				'ver'  => '1.8',
				'ext'  => 'css',
				'min'  => false
			),
			'flatpickr'              => array(
				'lib'  => true,
				'path' => 'flatpickr/',
				'file' => 'flatpickr.min',
				'ver'  => '4.6.9',
				'ext'  => 'css',
				'min'  => false
			),
			'flatpickr-confirm-date' => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'confirm-date',
				'ver'  => '4.6.9',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'flatpickr' )
			),
			'flatpickr-month-select' => array(
				'lib'  => true,
				'path' => 'flatpickr/plugins',
				'file' => 'month-select',
				'ver'  => '4.6.9',
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'flatpickr' )
			)
		)
	);

	public function __construct() {

	}

	public static function instance() : Resources {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Resources();
		}

		return $instance;
	}

	public function shared_js() : array {
		return $this->_shared['js'];
	}

	public function shared_css() : array {
		return $this->_shared['css'];
	}

	public function ui_js() : array {
		return $this->_ui['js'];
	}

	public function ui_css() : array {
		return $this->_ui['css'];
	}
}