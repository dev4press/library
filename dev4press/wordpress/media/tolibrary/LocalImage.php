<?php

/*
Name:    Dev4Press\WordPress\Media\LocalImageToMediaLibrary
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

namespace Dev4Press\WordPress\Media\ToLibrary;

use Dev4Press\WordPress\Media\ImageToMediaLibrary;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LocalImage {
	use ImageToMediaLibrary;

	public function __construct( $path, $data = array(), $args = array() ) {
		$this->_init();

		$defaults = array(
			'name'        => '',
			'title'       => '',
			'slug'        => '',
			'caption'     => '',
			'alt'         => '',
			'description' => ''
		);

		$this->data = wp_parse_args( $data, $defaults );
		$this->path = $path;

		if ( empty( $this->data['name'] ) ) {
			$this->data['name'] = basename( $this->path );

			if ( ( $pos = strpos( $this->data['name'], '?' ) ) > 0 ) {
				$this->data['name'] = substr( $this->data['name'], 0, $pos );
			}
		}

		$this->_prepare( $args );
	}

	public function upload( $post_parent = 0, $featured = false ) {
		$file = $this->_sideload( $this->path );

		if ( is_wp_error( $file ) ) {
			return $file;
		}

		$attachment_id = $this->_attach( $file, $post_parent );

		if ( ! is_wp_error( $attachment_id ) && $featured ) {
			set_post_thumbnail( $post_parent, $attachment_id );
		}

		return $attachment_id;
	}
}
