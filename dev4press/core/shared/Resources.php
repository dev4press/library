<?php
/**
 * Name:    Dev4Press\v56\Core\Shared\Enqueue
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

namespace Dev4Press\v56\Core\Shared;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Resources {
	private array $_ui = array(
		'js'  => array(
			'meta'          => array(
				'file' => 'meta',
				'ext'  => 'js',
				'min'  => true,
			),
			'media'         => array(
				'file' => 'media',
				'ext'  => 'js',
				'min'  => true,
			),
			'ctrl'          => array(
				'file' => 'ctrl',
				'ext'  => 'js',
				'min'  => true,
			),
			'customizer'    => array(
				'file' => 'customizer',
				'ext'  => 'js',
				'min'  => true,
			),
			'widgets'       => array(
				'file' => 'widgets',
				'ext'  => 'js',
				'min'  => true,
			),
			'wizard'        => array(
				'file' => 'wizard',
				'ext'  => 'js',
				'min'  => true,
			),
			'confirmsubmit' => array(
				'file' => 'confirmsubmit',
				'ext'  => 'js',
				'min'  => true,
			),
			'dialogs'       => array(
				'file' => 'dialogs',
				'ext'  => 'js',
				'min'  => true,
			),
			'admin'         => array(
				'file' => 'admin',
				'ext'  => 'js',
				'min'  => true,
				'int'  => array( 'dialogs', 'confirmsubmit' ),
			),
		),
		'css' => array(
			'pack'       => array(
				'path' => 'css/',
				'file' => 'pack',
				'ext'  => 'css',
			),
			'about'      => array(
				'path' => 'css/',
				'file' => 'about',
				'ext'  => 'css',
			),
			'grid'       => array(
				'path' => 'css/',
				'file' => 'grid',
				'ext'  => 'css',
			),
			'ctrl'       => array(
				'path' => 'css/',
				'file' => 'ctrl',
				'ext'  => 'css',
			),
			'meta'       => array(
				'path' => 'css/',
				'file' => 'meta',
				'ext'  => 'css',
			),
			'options'    => array(
				'path' => 'css/',
				'file' => 'options',
				'ext'  => 'css',
			),
			'shared'     => array(
				'path' => 'css/',
				'file' => 'shared',
				'ext'  => 'css',
			),
			'widgets'    => array(
				'path' => 'css/',
				'file' => 'widgets',
				'ext'  => 'css',
			),
			'customizer' => array(
				'path' => 'css/',
				'file' => 'customizer',
				'ext'  => 'css',
			),
			'admin'      => array(
				'path' => 'css/',
				'file' => 'admin',
				'ext'  => 'css',
				'int'  => array( 'shared' ),
			),
			'wizard'     => array(
				'path' => 'css/',
				'file' => 'wizard',
				'ext'  => 'css',
				'int'  => array( 'admin' ),
			),
			'rtl'        => array(
				'path' => 'css/',
				'file' => 'rtl',
				'ext'  => 'css',
			),
		),
	);

	private array $_shared = array(
		'js'  => array(
			'flyin'      => array(
				'lib'  => true,
				'path' => 'flyin/',
				'file' => 'flyin.umd',
				'ver'  => '1.2.0',
				'ext'  => 'js',
			),
			'field-text' => array(
				'lib'  => true,
				'path' => 'field/',
				'file' => 'field-text.umd',
				'ver'  => '0.9.0',
				'ext'  => 'js',
			),
			'field-up'   => array(
				'lib'  => true,
				'path' => 'field/',
				'file' => 'field-up.umd',
				'ver'  => '0.9.0',
				'ext'  => 'js',
			),
			'cookies'    => array(
				'lib'  => true,
				'path' => 'js-cookie/',
				'file' => 'cookies.min',
				'ver'  => '3.0.8',
				'ext'  => 'js',
			),
			'mark'       => array(
				'lib'  => true,
				'path' => 'mark-js',
				'file' => 'mark.min',
				'ver'  => '8.11.1',
				'ext'  => 'js',
			),
			'fitvids'    => array(
				'lib'  => true,
				'path' => 'fitvids',
				'file' => 'fitvids.min',
				'ver'  => '2.1.1',
				'ext'  => 'js',
			),
		),
		'css' => array(
			'flags'      => array(
				'lib'  => true,
				'path' => 'flags/css/',
				'file' => 'flags',
				'ver'  => '2015.10',
				'ext'  => 'css',
				'min'  => false,
			),
			'flyin'      => array(
				'lib'  => true,
				'path' => 'flyin/',
				'file' => 'flyin',
				'ver'  => '1.2.0',
				'ext'  => 'css',
				'min'  => false,
			),
			'field-text' => array(
				'lib'  => true,
				'path' => 'field/',
				'file' => 'field-text',
				'ver'  => '0.9.0',
				'ext'  => 'css',
				'min'  => false,
			),
			'field-up'   => array(
				'lib'  => true,
				'path' => 'field/',
				'file' => 'field-up',
				'ver'  => '0.9.0',
				'ext'  => 'css',
				'min'  => false,
			),
			'font'       => array(
				'lib'  => false,
				'path' => 'css/',
				'file' => 'font',
				'ext'  => 'css',
				'min'  => true,
			),
			'grid-table' => array(
				'lib'  => false,
				'path' => 'css/',
				'file' => 'table',
				'ext'  => 'css',
				'min'  => true,
			),
		),
	);

	protected function __construct() {
	}

	/** @deprecated 5.5.0 Use self::i() instead. To be removed in 5.7.0. */
	public static function instance() : static {
		return static::i();
	}

	public static function i() : static {
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
