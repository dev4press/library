<?php

/*
Name:    Base Library Functions: Deprecated
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

use function Dev4Press\v35\Functions\sanitize_basic;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'd4p_admin_enqueue_defaults' ) ) {
	/**
	 * @deprecated 3.5
	 */
	function d4p_admin_enqueue_defaults() {
		_deprecated_function( __FUNCTION__, '3.5' );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-form' );

		wp_enqueue_script( 'wpdialogs' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );

		d4p_enqueue_color_picker();

		wp_enqueue_media();
	}
}

if ( ! function_exists( 'd4p_enqueue_color_picker' ) ) {
	/**
	 * @deprecated 3.5
	 */
	function d4p_enqueue_color_picker() {
		_deprecated_function( __FUNCTION__, '3.5' );

		if ( is_admin() ) {
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );
		} else {
			$suffix = SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			) );
			wp_enqueue_script( 'wp-color-picker', admin_url( "js/color-picker$suffix.js" ), array( 'iris' ) );
			wp_enqueue_style( 'wp-color-picker', admin_url( "css/color-picker$suffix.css" ) );

			wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', array(
				'clear'         => __( "Clear", "d4plib" ),
				'defaultString' => __( "Default", "d4plib" ),
				'pick'          => __( "Select Color", "d4plib" ),
				'current'       => __( "Current Color", "d4plib" )
			) );
		}
	}
}

if ( ! function_exists( 'd4p_mysql_date' ) ) {
	/**
	 * @deprecated 3.5
	 */
	function d4p_mysql_date( $time ) {
		_deprecated_function( __FUNCTION__, '3.5' );

		return date( 'Y-m-d H:i:s', $time );
	}
}

if ( ! function_exists( 'd4p_mysql_datetime_format' ) ) {
	/**
	 * @deprecated 3.5
	 */
	function d4p_mysql_datetime_format() : string {
		_deprecated_function( __FUNCTION__, '3.5' );

		return 'Y-m-d H:i:s';
	}
}

if ( ! function_exists( 'd4p_render_toggle_block' ) ) {
	/**
	 * @deprecated 3.5
	 */
	function d4p_render_toggle_block( $title, $content, $classes = array() ) {
		_deprecated_function( __FUNCTION__, '3.5' );

		$render = '<div class="d4p-section-toggle ' . join( ' ', $classes ) . '">';
		$render .= '<div class="d4p-toggle-title">';
		$render .= '<i class="fa fa-caret-down fa-fw"></i> ' . $title;
		$render .= '</div>';
		$render .= '<div class="d4p-toggle-content" style="display: none;">';
		$render .= $content;
		$render .= '</div>';
		$render .= '</div>';

		return $render;
	}
}

if ( ! function_exists( 'd4p_user_agent' ) ) {
	/**
	 * @deprecated 3.5
	 */
	function d4p_user_agent() : string {
		_deprecated_function( __FUNCTION__, '3.5' );

		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return sanitize_basic( trim( $_SERVER['HTTP_USER_AGENT'] ) );
		}

		return '';
	}
}

if ( ! function_exists( 'd4p_json_encode' ) ) {
	/**
	 * @deprecated 3.5
	 */
	function d4p_json_encode( $data, $options = 0, $depth = 512 ) {
		_deprecated_function( __FUNCTION__, '3.5', 'wp_json_encode()' );

		if ( function_exists( 'wp_json_encode' ) ) {
			return wp_json_encode( $data, $options, $depth );
		} else {
			return json_encode( $data, $options, $depth );
		}
	}
}
