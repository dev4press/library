<?php

/*
Name:    Dev4Press\v36\Functions
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

namespace Dev4Press\v36\Functions;

use Dev4Press\v36\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( __NAMESPACE__ . '\string_ends_with' ) ) {
	function string_ends_with( $haystack, $needle ) : bool {
		$length = strlen( $needle );

		return ! ( $length === 0 ) && substr( $haystack, - $length ) === $needle;
	}
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

if ( ! function_exists( __NAMESPACE__ . '\str_replace_first' ) ) {
	function str_replace_first( $search, $replace, $subject ) {
		$pos = strpos( $subject, $search );

		if ( $pos !== false ) {
			$subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
		}

		return $subject;
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

if ( ! function_exists( __NAMESPACE__ . '\remove_from_array_by_value' ) ) {
	function remove_from_array_by_value( $input, $val, $preserve_keys = true ) : array {
		if ( empty( $input ) || ! is_array( $input ) ) {
			return array();
		}

		while ( in_array( $val, $input ) ) {
			unset( $input[ array_search( $val, $input ) ] );
		}

		return $preserve_keys ? $input : array_values( $input );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\slug_to_name' ) ) {
	function slug_to_name( $code, $sep = '_' ) : string {
		$exp = explode( $sep, $code );

		return ucwords( strtolower( join( ' ', $exp ) ) );
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

if ( ! function_exists( __NAMESPACE__ . '\url_campaign_tracking' ) ) {
	function url_campaign_tracking( $url, $campaign = '', $medium = '', $content = '', $term = '', $source = null ) : string {
		if ( ! empty( $campaign ) ) {
			$url = add_query_arg( 'utm_campaign', $campaign, $url );
		}

		if ( ! empty( $medium ) ) {
			$url = add_query_arg( 'utm_medium', $medium, $url );
		}

		if ( ! empty( $content ) ) {
			$url = add_query_arg( 'utm_content', $content, $url );
		}

		if ( ! empty( $term ) ) {
			$url = add_query_arg( 'utm_term', $term, $url );
		}

		if ( is_null( $source ) ) {
			$source = parse_url( get_bloginfo( 'url' ), PHP_URL_HOST );
		}

		if ( ! empty( $source ) ) {
			$url = add_query_arg( 'utm_source', $source, $url );
		}

		return $url;
	}
}

if ( ! function_exists( __NAMESPACE__ . '\array_to_html_attributes' ) ) {
	function array_to_html_attributes( $input ) : string {
		$list = array();

		foreach ( $input as $item => $value ) {
			if ( is_bool( $value ) ) {
				$list[] = $item;
			} else {
				$list[] = $item . '="' . esc_attr( $value ) . '"';
			}
		}

		return join( ' ', $list );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_regex_error' ) ) {
	function get_regex_error( $error_code ) : string {
		if ( is_bool( $error_code ) ) {
			return 'OK';
		}

		$errors = array_flip( get_defined_constants( true )['pcre'] );

		if ( isset( $errors[ $error_code ] ) ) {
			return $errors[ $error_code ];
		}

		return 'UNKNOWN_ERROR';
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

if ( ! function_exists( __NAMESPACE__ . '\ids_from_string' ) ) {
	function ids_from_string( $input, $delimiter = ',', $map = 'absint' ) : string {
		$ids = strip_tags( stripslashes( $input ) );

		$ids = explode( $delimiter, $ids );
		$ids = array_map( 'trim', $ids );
		$ids = array_map( $map, $ids );

		return array_filter( $ids );
	}
}
