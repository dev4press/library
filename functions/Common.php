<?php

/*
Name:    Dev4Press\v35\Functions
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( __NAMESPACE__ . '\replace_tags_in_content' ) ) {
	function replace_tags_in_content( $content, $tags, $before = '%', $after = '%' ) : string {
		foreach ( $tags as $tag => $replace ) {
			$_tag = $before . $tag . $after;

			if ( strpos( $content, $_tag ) !== false ) {
				$content = str_replace( $_tag, $replace, $content );
			}
		}

		return $content;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\strleft' ) ) {
	function strleft( $s1, $s2 ) {
		return substr( $s1, 0, strpos( $s1, $s2 ) );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\scan_dir' ) ) {
	function scan_dir( $path, $filter = 'files', $extensions = array(), $reg_expr = '', $full_path = false ) : array {
		$extensions = (array) $extensions;
		$filter     = ! in_array( $filter, array( 'folders', 'files', 'all' ) ) ? 'files' : $filter;
		$path       = str_replace( '\\', '/', $path );

		$final = array();

		if ( file_exists( $path ) ) {
			$files = scandir( $path );

			$path = rtrim( $path, '/' ) . '/';
			foreach ( $files as $file ) {
				$ext = pathinfo( $file, PATHINFO_EXTENSION );

				if ( empty( $extensions ) || in_array( $ext, $extensions ) ) {
					if ( substr( $file, 0, 1 ) != '.' ) {
						if (
							( is_dir( $path . $file ) && ( in_array( $filter, array( 'folders', 'all' ) ) ) ) ||
							( is_file( $path . $file ) && ( in_array( $filter, array( 'files', 'all' ) ) ) ) ||
							( ( is_file( $path . $file ) || is_dir( $path . $file ) ) && ( in_array( $filter, array( 'all' ) ) ) ) ) {
							$add = $full_path ? $path : '';

							if ( $reg_expr == '' ) {
								$final[] = $add . $file;
							} else if ( preg_match( $reg_expr, $file ) ) {
								$final[] = $add . $file;
							}
						}
					}
				}
			}
		}

		return $final;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\file_size_format' ) ) {
	function file_size_format( $size, $decimals = 2, $sep = ' ' ) : string {
		$_size = intval( $size );

		if ( strlen( $_size ) >= 10 ) {
			$_size = number_format( $_size / 1073741824, $decimals );
			$unit  = 'GB';
		} else if ( strlen( $_size ) <= 9 && strlen( $_size ) >= 7 ) {
			$_size = number_format( $_size / 1048576, $decimals );
			$unit  = 'MB';
		} else if ( strlen( $_size ) <= 6 && strlen( $_size ) >= 4 ) {
			$_size = number_format( $_size / 1024, $decimals );
			$unit  = 'KB';
		} else {
			$unit = 'B';
		}

		if ( floatval( $_size ) == intval( $_size ) ) {
			$_size = intval( $_size );
		}

		return $_size . $sep . $unit;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\list_css_size_units' ) ) {
	function list_css_size_units() : array {
		return array(
			'px'   => 'px',
			'%'    => '%',
			'em'   => 'em',
			'rem'  => 'rem',
			'in'   => 'in',
			'cm'   => 'cm',
			'mm'   => 'mm',
			'pt'   => 'pt',
			'pc'   => 'pc',
			'ex'   => 'ex',
			'ch'   => 'ch',
			'vw'   => 'vw',
			'vh'   => 'vh',
			'vmin' => 'vmin',
			'vmax' => 'vmax'
		);
	}
}
