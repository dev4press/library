<?php

/*
Name:    Base Library Functions: Bridge
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

use Dev4Press\Core\Cache\Store;
use Dev4Press\Core\Helpers\Download;
use Dev4Press\Core\Helpers\ObjectsSort;
use Dev4Press\WordPress\Media\ToLibrary\LocalImage;
use Dev4Press\WordPress\Media\ToLibrary\RemoteImage;

if ( ! function_exists( 'd4p_local_image_to_media_library' ) ) {
	function d4p_local_image_to_media_library( $path, $data = array(), $post_parent = 0, $args = array() ) {
		$obj = new LocalImage( $path, $data, $args );

		return $obj->upload( $post_parent );
	}
}

if ( ! function_exists( 'd4p_remote_image_to_media_library' ) ) {
	function d4p_remote_image_to_media_library( $url, $data = array(), $post_parent = 0, $args = array() ) {
		$obj = new RemoteImage( $url, $data, $args );

		return $obj->download( $post_parent );
	}
}

if ( ! function_exists( 'd4p_get_objects_sort' ) ) {
	function d4p_get_objects_sort( $objects_array, $properties = array(), $uasort = false ) {
		$_sort = new ObjectsSort( $objects_array, $properties, $uasort );

		return $_sort->sorted;
	}
}

if ( ! function_exists( 'd4p_read_file' ) ) {
	function d4p_read_file( $file_path, $part_size_mb = 2, $return_size = true ) {
		return Download::instance( $file_path )->read_file( $part_size_mb, $return_size );
	}
}

if ( ! function_exists( 'd4p_download_file_simple' ) ) {
	function d4p_download_file_simple( $file_path, $file_name = null, $gdr_readfile = true ) {
		Download::instance( $file_path, $file_name )->simple( ! $gdr_readfile );
	}
}

if ( ! function_exists( 'd4p_download_file_resume' ) ) {
	function d4p_download_file_resume( $file_path, $file_name = null ) {
		Download::instance( $file_path, $file_name )->resume();
	}
}

if ( ! function_exists( 'd4p_object_cache' ) ) {
	function d4p_object_cache() {
		return Store::instance();
	}
}
