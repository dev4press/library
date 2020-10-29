<?php

/*
Name:    Dev4Press\Service\Media\Pixabay\Query
Version: v3.3
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

namespace Dev4Press\Service\Media\Pixabay;

use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Query {
	private $_api_key;
	private $_api_url = 'https://pixabay.com/api/';

	public function __construct( $api_key ) {
		$this->_api_key = $api_key;
	}

	public static function instance( $api_key ) {
		static $_d4p_pixabay = false;

		if ( ! $_d4p_pixabay ) {
			$_d4p_pixabay = new Query( $api_key );
		}

		return $_d4p_pixabay;
	}

	public function image( $id, $args = array() ) {
		$defaults = array(
			'lang' => 'en'
		);

		$args = wp_parse_args( $args, $defaults );

		$args['id']  = $id;
		$args['key'] = $this->_api_key;

		$url = add_query_arg( $args, $this->_api_url );

		$raw = wp_remote_get( $url );

		if ( is_wp_error( $raw ) ) {
			return $raw;
		}

		$body     = wp_remote_retrieve_body( $raw );
		$response = json_decode( $body );

		if ( isset( $response->hits[0] ) ) {
			return $this->_format_image( $response->hits[0] );
		}

		return new WP_Error( 'not_found', __( "Specified image ID not found.", "d4plib" ) );
	}

	public function images( $args = array() ) {
		$defaults = array(
			'q'              => '',
			'lang'           => 'en',
			'image_type'     => 'all',
			'orientation'    => 'all',
			'category'       => '',
			'min_width'      => 0,
			'min_height'     => 0,
			'colors'         => '',
			'safesearch'     => false,
			'editors_choice' => false,
			'order'          => 'popular',
			'page'           => 1,
			'per_page'       => 20
		);

		$args = wp_parse_args( $args, $defaults );

		$args['key'] = $this->_api_key;

		$url = add_query_arg( $args, $this->_api_url );

		$raw = wp_remote_get( $url );

		if ( is_wp_error( $raw ) ) {
			return $raw;
		}

		$body     = wp_remote_retrieve_body( $raw );
		$response = json_decode( $body );

		$out = array(
			'total'   => $response->totalHits,
			'results' => array()
		);

		foreach ( $response->hits as $img ) {
			$out['results'][] = $this->_format_image( $img );
		}

		return (object) $out;
	}

	public function video( $id, $args = array() ) {
		$defaults = array(
			'lang' => 'en'
		);

		$args = wp_parse_args( $args, $defaults );

		$args['id']  = $id;
		$args['key'] = $this->_api_key;

		$url = add_query_arg( $args, $this->_api_url . 'videos/' );

		$raw = wp_remote_get( $url );

		if ( is_wp_error( $raw ) ) {
			return $raw;
		}

		$body     = wp_remote_retrieve_body( $raw );
		$response = json_decode( $body );

		if ( isset( $response->hits[0] ) ) {
			return $this->_format_video( $response->hits[0] );
		}

		return new WP_Error( 'not_found', __( "Specified video ID not found.", "d4plib" ) );
	}

	public function videos( $args = array() ) {
		$defaults = array(
			'q'              => '',
			'lang'           => 'en',
			'video_type'     => 'all',
			'category'       => '',
			'min_width'      => 0,
			'min_height'     => 0,
			'safesearch'     => false,
			'editors_choice' => false,
			'order'          => 'popular',
			'page'           => 1,
			'per_page'       => 20
		);

		$args = wp_parse_args( $args, $defaults );

		$args['key'] = $this->_api_key;

		$url = add_query_arg( $args, $this->_api_url . 'videos/' );

		$raw = wp_remote_get( $url );

		if ( is_wp_error( $raw ) ) {
			return $raw;
		}

		$body     = wp_remote_retrieve_body( $raw );
		$response = json_decode( $body );

		$out = array(
			'total'   => $response->totalHits,
			'results' => array()
		);

		foreach ( $response->hits as $img ) {
			$out['results'][] = $this->_format_video( $img );
		}

		return (object) $out;
	}

	private function _format_image( $response ) {
		return new Image( $response );
	}

	private function _format_video( $response ) {
		return new Video( $response );
	}
}
