<?php

/*
Name:    Base Library Functions: Bridge
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

namespace Dev4Press\v35\Functions;

use Dev4Press\v35\Core\Cache\Store;
use Dev4Press\v35\Core\Helpers\Download;
use Dev4Press\v35\Core\Helpers\ObjectsSort;
use Dev4Press\v35\Core\UI\Admin\Panel;
use Dev4Press\v35\WordPress\Media\ToLibrary\LocalImage;
use Dev4Press\v35\WordPress\Media\ToLibrary\RemoteImage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'panel' ) ) {
	/** @return \Dev4Press\v35\Core\UI\Admin\Panel */
	function panel() {
		return Panel::instance();
	}
}

if ( ! function_exists( 'local_image_to_media_library' ) ) {
	function local_image_to_media_library( $path, $data = array(), $post_parent = 0, $args = array() ) {
		$obj = new LocalImage( $path, $data, $args );

		return $obj->upload( $post_parent );
	}
}

if ( ! function_exists( 'remote_image_to_media_library' ) ) {
	function remote_image_to_media_library( $url, $data = array(), $post_parent = 0, $args = array() ) {
		$obj = new RemoteImage( $url, $data, $args );

		return $obj->download( $post_parent );
	}
}

if ( ! function_exists( 'get_objects_sort' ) ) {
	function get_objects_sort( $objects_array, $properties = array(), $uasort = false ) {
		$_sort = new ObjectsSort( $objects_array, $properties, $uasort );

		return $_sort->sorted;
	}
}

if ( ! function_exists( 'read_file' ) ) {
	function read_file( $file_path, $part_size_mb = 2, $return_size = true ) {
		return Download::instance( $file_path )->read_file( $part_size_mb, $return_size );
	}
}

if ( ! function_exists( 'download_file_simple' ) ) {
	function download_file_simple( $file_path, $file_name = null, $gdr_readfile = true ) {
		Download::instance( $file_path, $file_name )->simple( ! $gdr_readfile );
	}
}

if ( ! function_exists( 'download_file_resume' ) ) {
	function download_file_resume( $file_path, $file_name = null ) {
		Download::instance( $file_path, $file_name )->resume();
	}
}

if ( ! function_exists( 'object_cache' ) ) {
	function object_cache() : Store {
		return Store::instance();
	}
}
