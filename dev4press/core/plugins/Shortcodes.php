<?php

/*
Name:    Dev4Press\Core\Plugins\Widget
Version: v3.5
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

namespace Dev4Press\Core\Plugins;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Shortcodes {
	public $shortcake = '';
	public $shortcake_full = '';
	public $shortcake_title = 'Dev4Press';

	public $prefix = 'd4p';
	public $shortcodes = array();
	public $registered = array();

	public function __construct() {
		$this->init();

		$this->_register();
	}

	abstract public function init();

	protected function _real_code( $code ) : string {
		return $this->prefix != '' ? $this->prefix . '_' . $code : $code;
	}

	protected function _wrapper( $content, $name, $extra_class = '', $tag = 'div' ) : string {
		$classes = array(
			$this->prefix . '-shortcode-wrapper',
			$this->prefix . '-shortcode-' . str_replace( '_', '-', $name )
		);

		if ( ! empty( $extra_class ) ) {
			$classes[] = $extra_class;
		}

		$wrapper = '<' . $tag . ' class="' . join( ' ', $classes ) . '">';
		$wrapper .= $content;
		$wrapper .= '</' . $tag . '>';

		return $wrapper;
	}

	protected function _register() {
		$list = array_keys( $this->shortcodes );

		foreach ( $list as $shortcode ) {
			$name = $this->_real_code( $shortcode );

			add_shortcode( $name, array( $this, 'shortcode_' . $shortcode ) );

			$this->registered[ $name ] = $shortcode;
		}
	}

	protected function _args( $code ) : array {
		return isset( $this->shortcodes[ $code ]['args'] ) ? $this->shortcodes[ $code ]['args'] : array();
	}

	protected function _atts( $code, $atts = array() ) : array {
		$real_code = $this->_real_code( $code );

		if ( isset( $atts[0] ) ) {
			$atts[ $real_code ] = substr( $atts[0], 1 );
			unset( $atts[0] );
		}

		$default               = $this->shortcodes[ $code ]['atts'];
		$default[ $real_code ] = '';

		return shortcode_atts( $default, $atts );
	}

	protected function _content( $content, $raw = false ) : string {
		if ( $raw ) {
			return $content;
		} else {
			return do_shortcode( $content );
		}
	}

	protected function _regex( $list = array() ) : string {
		if ( empty( $list ) ) {
			$tagnames  = array_keys( $this->registered );
			$tagregexp = join( '|', array_map( 'preg_quote', $tagnames ) );
		} else {
			$tagregexp = join( '|', $list );
		}

		return '\\['
		       . '(\\[?)'
		       . "($tagregexp)"
		       . '(?![\\w-])'
		       . '('
		       . '[^\\]\\/]*'
		       . '(?:'
		       . '\\/(?!\\])'
		       . '[^\\]\\/]*'
		       . ')*?'
		       . ')'
		       . '(?:'
		       . '(\\/)'
		       . '\\]'
		       . '|'
		       . '\\]'
		       . '(?:'
		       . '('
		       . '[^\\[]*+'
		       . '(?:'
		       . '\\[(?!\\/\\2\\])'
		       . '[^\\[]*+'
		       . ')*+'
		       . ')'
		       . '\\[\\/\\2\\]'
		       . ')?'
		       . ')'
		       . '(\\]?)';

	}
}
