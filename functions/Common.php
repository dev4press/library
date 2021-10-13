<?php

/*
Name:    Dev4Press\v37\Functions
Version: v3.7
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

namespace Dev4Press\v37\Functions;

use Dev4Press\v37\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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

if ( ! function_exists( __NAMESPACE__ . '\text_length_limit' ) ) {
	function text_length_limit( $text, $length = 200, $append = '&hellip;' ) : string {
		$text_length = function_exists( 'mb_strlen' )
			?
			mb_strlen( $text )
			:
			strlen( $text );

		if ( ! empty( $length ) && ( $text_length > $length ) ) {

			$text = function_exists( 'mb_substr' )
				?
				mb_substr( $text, 0, $length - 1 )
				:
				substr( $text, 0, $length - 1 );
			$text .= $append;
		}

		return $text;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\entity_decode' ) ) {
	function entity_decode( $content, $quote_style = null, $charset = null ) : string {
		if ( null === $quote_style ) {
			$quote_style = ENT_QUOTES;
		}

		if ( null === $charset ) {
			$charset = Library::instance()->charset();
		}

		return html_entity_decode( $content, $quote_style, $charset );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\extract_images_urls' ) ) {
	function extract_images_urls( $search, $limit = 0 ) {
		$images  = array();
		$matches = array();

		if ( preg_match_all( "/<img(.+?)>/", $search, $matches ) ) {
			foreach ( $matches[1] as $image ) {
				$match = array();

				if ( preg_match( '/src=(["\'])(.*?)\1/', $image, $match ) ) {
					$images[] = $match[2];
				}
			}
		}

		if ( $limit > 0 && ! empty( $images ) ) {
			$images = array_slice( $images, 0, $limit );
		}

		if ( $limit == 1 ) {
			return count( $images ) > 0 ? $images[0] : '';
		} else {
			return $images;
		}
	}
}

if ( ! function_exists( __NAMESPACE__ . '\gzip_uncompressed_size' ) ) {
	function gzip_uncompressed_size( $file_path ) {
		$fp = fopen( $file_path, "rb" );
		fseek( $fp, - 4, SEEK_END );
		$buf = fread( $fp, 4 );
		$elm = unpack( "V", $buf );

		return end( $elm );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\has_gravatar' ) ) {
	function has_gravatar( $email ) : bool {
		$hash = md5( strtolower( trim( $email ) ) );

		$url     = 'https://www.gravatar.com/avatar/' . $hash . '?d=404';
		$headers = get_headers( $url );

		return preg_match( "/200/", $headers[0] ) === 1;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\php_ini_size_value' ) ) {
	function php_ini_size_value( $name ) {
		$ini = ini_get( $name );

		if ( $ini === false ) {
			return 0;
		}

		$ini  = trim( $ini );
		$last = strtoupper( $ini[ strlen( $ini ) - 1 ] );
		$ini  = absint( substr( $ini, 0, strlen( $ini ) - 1 ) );

		switch ( $last ) {
			case 'G':
				$ini = $ini * GB_IN_BYTES;
				break;
			case 'M':
				$ini = $ini * MB_IN_BYTES;
				break;
			case 'K':
				$ini = $ini * KB_IN_BYTES;
				break;
		}

		return $ini;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\split_textarea_to_list' ) ) {
	function split_textarea_to_list( $value, $empty_lines = false ) {
		$elements = preg_split( "/[\n\r]/", $value );

		if ( ! $empty_lines ) {
			$results = array();

			foreach ( $elements as $el ) {
				if ( trim( $el ) != '' ) {
					$results[] = $el;
				}
			}

			return $results;
		} else {
			return $elements;
		}
	}
}
