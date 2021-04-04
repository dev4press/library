<?php

/*
Name:    Base Library Functions: Helpers
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

if ( ! function_exists( 'd4p_replace_tags_in_content' ) ) {
	function d4p_replace_tags_in_content( $content, $tags ) : string {
		foreach ( $tags as $tag => $replace ) {
			$_tag = '%' . $tag . '%';

			if ( strpos( $content, $_tag ) !== false ) {
				$content = str_replace( $_tag, $replace, $content );
			}
		}

		return $content;
	}
}

if ( ! function_exists( 'd4p_strleft' ) ) {
	function d4p_strleft( $s1, $s2 ) {
		return substr( $s1, 0, strpos( $s1, $s2 ) );
	}
}

if ( ! function_exists( 'd4p_scan_dir' ) ) {
	function d4p_scan_dir( $path, $filter = 'files', $extensions = array(), $reg_expr = '', $full_path = false ) {
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

if ( ! function_exists( 'd4p_filesize_format' ) ) {
	function d4p_filesize_format( $size, $decimals = 2, $sep = ' ' ) : string {
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

if ( ! function_exists( 'd4p_text_length_limit' ) ) {
	function d4p_text_length_limit( $text, $length = 200, $append = '&hellip;' ) : string {
		if ( function_exists( 'mb_strlen' ) ) {
			$text_length = mb_strlen( $text );
		} else {
			$text_length = strlen( $text );
		}

		if ( ! empty( $length ) && ( $text_length > $length ) ) {
			$text = substr( $text, 0, $length - 1 );
			$text .= $append;
		}

		return $text;
	}
}

if ( ! function_exists( 'd4p_entity_decode' ) ) {
	function d4p_entity_decode( $content, $quote_style = null, $charset = null ) : string {
		if ( null === $quote_style ) {
			$quote_style = ENT_QUOTES;
		}

		if ( null === $charset ) {
			$charset = D4P_CHARSET;
		}

		return html_entity_decode( $content, $quote_style, $charset );
	}
}

if ( ! function_exists( 'd4p_str_replace_first' ) ) {
	function d4p_str_replace_first( $search, $replace, $subject ) {
		$pos = strpos( $subject, $search );

		if ( $pos !== false ) {
			$subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
		}

		return $subject;
	}
}

if ( ! function_exists( 'd4p_extract_images_urls' ) ) {
	function d4p_extract_images_urls( $search, $limit = 0 ) {
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

if ( ! function_exists( 'd4p_gzip_uncompressed_size' ) ) {
	function d4p_gzip_uncompressed_size( $file_path ) {
		$fp = fopen( $file_path, "rb" );
		fseek( $fp, - 4, SEEK_END );
		$buf = fread( $fp, 4 );
		$elm = unpack( "V", $buf );

		return end( $elm );
	}
}

if ( ! function_exists( 'd4p_remove_from_array_by_value' ) ) {
	function d4p_remove_from_array_by_value( $input, $val, $preserve_keys = true ) {
		if ( empty( $input ) || ! is_array( $input ) ) {
			return false;
		}

		while ( in_array( $val, $input ) ) {
			unset( $input[ array_search( $val, $input ) ] );
		}

		$output = $preserve_keys ? $input : array_values( $input );

		return (array) $output;
	}
}

if ( ! function_exists( 'd4p_slug_to_name' ) ) {
	function d4p_slug_to_name( $code, $sep = '_' ) : string {
		$exp = explode( $sep, $code );

		return ucwords( strtolower( join( ' ', $exp ) ) );
	}
}

if ( ! function_exists( 'd4p_has_gravatar' ) ) {
	function d4p_has_gravatar( $email ) : bool {
		$hash = md5( strtolower( trim( $email ) ) );

		$url     = 'https://www.gravatar.com/avatar/' . $hash . '?d=404';
		$headers = get_headers( $url );

		return preg_match( "/200/", $headers[0] ) === 1;
	}
}

if ( ! function_exists( 'd4p_php_ini_size_value' ) ) {
	function d4p_php_ini_size_value( $name ) {
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

if ( ! function_exists( 'd4p_mysql_date' ) ) {
	function d4p_mysql_date( $time ) {
		return date( 'Y-m-d H:i:s', $time );
	}
}

if ( ! function_exists( 'd4p_mysql_datetime_format' ) ) {
	function d4p_mysql_datetime_format() : string {
		return 'Y-m-d H:i:s';
	}
}

if ( ! function_exists( 'd4p_url_campaign_tracking' ) ) {
	function d4p_url_campaign_tracking( $url, $campaign = '', $medium = '', $content = '', $term = '', $source = null ) : string {
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

if ( ! function_exists( 'd4p_user_agent' ) ) {
	function d4p_user_agent() : string {
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return sanitize_basic( trim( $_SERVER['HTTP_USER_AGENT'] ) );
		}

		return '';
	}
}

if ( ! function_exists( 'd4p_is_request_post' ) ) {
	function d4p_is_request_post() : bool {
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}
}

if ( ! function_exists( 'd4p_is_regex_valid' ) ) {
	function d4p_is_regex_valid( $regex ) {
		if ( preg_match( '/' . $regex . '/i', 'dev4press' ) !== false ) {
			return true;
		}

		return preg_last_error();
	}
}

if ( ! function_exists( 'd4p_get_regex_error' ) ) {
	function d4p_get_regex_error( $error_code ) : string {
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

if ( ! function_exists( 'd4p_file_size_format' ) ) {
	function d4p_file_size_format( $size, $decimals = 2 ) : string {
		return d4p_filesize_format( $size, $decimals );
	}
}

if ( ! function_exists( 'd4p_array_to_html_attributes' ) ) {
	function d4p_array_to_html_attributes( $input ) : string {
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

if ( ! function_exists( 'd4p_clean_ids_list' ) ) {
	function d4p_clean_ids_list( $ids, $map = 'absint' ) : array {
		$ids = (array) $ids;

		$ids = array_map( $map, $ids );
		$ids = array_unique( $ids );
		$ids = array_filter( $ids );

		return $ids;
	}
}

if ( ! function_exists( 'd4p_ids_from_string' ) ) {
	function d4p_ids_from_string( $input, $delimiter = ',', $map = 'absint' ) : string {
		$ids = strip_tags( stripslashes( $input ) );

		$ids = explode( $delimiter, $ids );
		$ids = array_map( 'trim', $ids );
		$ids = array_map( $map, $ids );
		$ids = array_filter( $ids );

		return $ids;
	}
}

if ( ! function_exists( 'd4p_kses_expanded_list_of_tags' ) ) {
	function d4p_kses_expanded_list_of_tags() : array {
		return array(
			'a'          => array(
				'class'    => true,
				'href'     => true,
				'title'    => true,
				'rel'      => true,
				'style'    => true,
				'download' => true,
				'target'   => true
			),
			'abbr'       => array(),
			'blockquote' => array(
				'class' => true,
				'style' => true,
				'cite'  => true
			),
			'div'        => array(
				'class' => true,
				'style' => true
			),
			'span'       => array(
				'class' => true,
				'style' => true
			),
			'code'       => array(
				'class' => true,
				'style' => true
			),
			'p'          => array(
				'class' => true,
				'style' => true
			),
			'pre'        => array(
				'class' => true,
				'style' => true
			),
			'em'         => array(
				'class' => true,
				'style' => true
			),
			'i'          => array(
				'class' => true,
				'style' => true
			),
			'b'          => array(
				'class' => true,
				'style' => true
			),
			'strong'     => array(
				'class' => true,
				'style' => true
			),
			'del'        => array(
				'datetime' => true,
				'class'    => true,
				'style'    => true
			),
			'h1'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'h2'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'h3'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'h4'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'h5'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'h6'         => array(
				'align' => true,
				'class' => true,
				'style' => true
			),
			'ul'         => array(
				'class' => true,
				'style' => true
			),
			'ol'         => array(
				'class' => true,
				'style' => true,
				'start' => true
			),
			'li'         => array(
				'class' => true,
				'style' => true
			),
			'img'        => array(
				'class'  => true,
				'style'  => true,
				'src'    => true,
				'border' => true,
				'alt'    => true,
				'height' => true,
				'width'  => true
			),
			'table'      => array(
				'align'   => true,
				'bgcolor' => true,
				'border'  => true,
				'class'   => true,
				'style'   => true
			),
			'tbody'      => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			),
			'td'         => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			),
			'tfoot'      => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			),
			'th'         => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			),
			'thead'      => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			),
			'tr'         => array(
				'align'  => true,
				'valign' => true,
				'class'  => true,
				'style'  => true
			)
		);
	}
}

if ( ! function_exists( 'd4p_split_textarea_to_list' ) ) {
	function d4p_split_textarea_to_list( $value, $empty_lines = false ) {
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

if ( ! function_exists( 'd4p_list_css_size_units' ) ) {
	function d4p_list_css_size_units() {
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

if ( ! function_exists( 'd4p_render_toggle_block' ) ) {
	function d4p_render_toggle_block( $title, $content, $classes = array() ) {
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
