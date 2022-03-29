<?php

/*
Name:    Dev4Press\v38\Functions
Version: v3.8
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2022 Milan Petrovic (email: support@dev4press.com)

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

namespace Dev4Press\v38\Functions;

use Dev4Press\v38\Core\Cache\Store;
use Dev4Press\v38\Core\Helpers\Download;
use Dev4Press\v38\Core\Helpers\ObjectsSort;
use Dev4Press\v38\Core\Quick\Arr;
use Dev4Press\v38\Core\Quick\File;
use Dev4Press\v38\Core\Quick\Misc;
use Dev4Press\v38\Core\Quick\Num;
use Dev4Press\v38\Core\Quick\Request;
use Dev4Press\v38\Core\Quick\Sanitize;
use Dev4Press\v38\Core\Quick\Str;
use Dev4Press\v38\Core\Quick\URL;
use Dev4Press\v38\Core\Quick\WPR;
use Dev4Press\v38\WordPress\Media\ToLibrary\LocalImage;
use Dev4Press\v38\WordPress\Media\ToLibrary\RemoteImage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( __NAMESPACE__ . '\string_ends_with' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function string_ends_with( $haystack, $needle ) : bool {
		return Str::ends_with( $haystack, $needle );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\replace_tags_in_content' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function replace_tags_in_content( $content, $tags, $before = '%', $after = '%' ) : string {
		return Str::replace_tags( $content, $tags, $before, $after );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\strleft' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function strleft( $s1, $s2 ) {
		return Str::left( $s1, $s2 );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\scan_dir' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function scan_dir( $path, $filter = 'files', $extensions = array(), $reg_expr = '', $full_path = false ) : array {
		return File::scan_dir( $path, $filter, $extensions, $reg_expr, $full_path );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\file_size_format' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function file_size_format( $size, $decimals = 2, $sep = ' ' ) : string {
		return File::size_format( $size, $decimals, $sep );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\list_css_size_units' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function list_css_size_units() : array {
		return Arr::get_css_size_units();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\text_length_limit' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function text_length_limit( $text, $length = 200, $append = '&hellip;' ) : string {
		return Str::to_length( $text, $length, $append );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\entity_decode' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function entity_decode( $content, $quote_style = null, $charset = null ) : string {
		return Str::entity_decode( $content, $quote_style, $charset );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\str_replace_first' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function str_replace_first( $search, $replace, $subject ) {
		return Str::replace_first( $search, $replace, $subject );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\extract_images_urls' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function extract_images_urls( $search, $limit = 0 ) {
		return Str::extract_images_urls( $search, $limit );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\gzip_uncompressed_size' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function gzip_uncompressed_size( $file_path ) {
		return File::gzip_uncompressed_size( $file_path );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\remove_from_array_by_value' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function remove_from_array_by_value( $input, $val, $preserve_keys = true ) : array {
		return Arr::remove_by_value( $input, $val, $preserve_keys );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\slug_to_name' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function slug_to_name( $code, $sep = '_' ) : string {
		return Str::slug_to_name( $code, $sep );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\has_gravatar' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function has_gravatar( $email ) : bool {
		return WPR::has_gravatar( $email );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\php_ini_size_value' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function php_ini_size_value( $name ) {
		return Misc::php_ini_size_value( $name );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\url_campaign_tracking' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function url_campaign_tracking( $url, $campaign = '', $medium = '', $content = '', $term = '', $source = null ) : string {
		return URL::add_campaign_tracking( $url, $campaign, $medium, $content, $term, $source );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\array_to_html_attributes' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function array_to_html_attributes( $input ) : string {
		return Arr::to_html_attributes( $input );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_regex_error' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_regex_error( $error_code ) : string {
		return Misc::get_regex_error( $error_code );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\split_textarea_to_list' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function split_textarea_to_list( $value, $empty_lines = false ) {
		return Str::split_to_list( $value, $empty_lines );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\ids_from_string' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function ids_from_string( $input, $delimiter = ',', $map = 'absint' ) : string {
		return Str::to_ids( $input, $delimiter, $map );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\domain_name' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function domain_name( $url ) {
		return URL::domain_name( $url );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\current_request_path' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function current_request_path() {
		return URL::current_request_path();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\current_url_request' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function current_url_request() : string {
		return URL::current_url_request();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\current_url' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function current_url( $use_wp = true ) : string {
		return URL::current_url( $use_wp );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_date' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_date( $value, $format = 'Y-m-d', $return_on_error = '' ) : string {
		return Sanitize::date( $value, $format, $return_on_error );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_time' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_time( $value, $format = 'H:i:s', $return_on_error = '' ) : string {
		return Sanitize::time( $value, $format, $return_on_error );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_month' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_month( $value, $format = 'Y-m', $return_on_error = '' ) : string {
		return Sanitize::month( $value, $format, $return_on_error );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_file_path' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_file_path( $filename ) : string {
		return Sanitize::file_path( $filename );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_key_expanded' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_key_expanded( $key ) : string {
		return Sanitize::key_expanded( $key );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_extended' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_extended( $text, $tags = null, $protocols = array(), $strip_shortcodes = false ) : string {
		return Sanitize::extended( $text, $tags, $protocols, $strip_shortcodes );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_basic' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_basic( $text, $strip_shortcodes = true ) : string {
		return Sanitize::basic( $text, $strip_shortcodes );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_html' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_html( $text, $tags = null, $protocols = array() ) : string {
		return Sanitize::html( $text, $tags, $protocols );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_slug' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_slug( $text ) : string {
		return Sanitize::slug( $text );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_html_classes' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_html_classes( $classes ) : string {
		return Sanitize::html_classes( $classes );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_basic_array' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_basic_array( $input, $strip_shortcodes = true ) : array {
		return Sanitize::basic_array( $input, $strip_shortcodes );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\sanitize_ids_list' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function sanitize_ids_list( $ids, $map = 'absint' ) : array {
		return Sanitize::ids_list( $ids, $map );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_odd' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_odd( $number ) : bool {
		return Num::is_odd( $number );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_divisible' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_divisible( $number, $by_number ) : bool {
		return Num::is_divisible( $number );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_associative_array' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_associative_array( $array ) : bool {
		return Arr::is_associative( $array );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_valid_md5' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_valid_md5( $hash = '' ) : bool {
		return Str::is_valid_md5( $hash );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_valid_datetime' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_valid_datetime( $date, $format = 'Y-m-d H:i:s' ) : bool {
		return Str::is_valid_datetime( $date, $format );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_request_post' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_request_post() : bool {
		return Request::is_post();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\is_regex_valid' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function is_regex_valid( $regex ) {
		return Str::is_regex_valid( $regex );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\download_file_simple' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function download_file_simple( $file_path, $file_name = null, $gdr_readfile = true ) {
		Download::file_simple( $file_path, $file_name, $gdr_readfile );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\download_file_resume' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function download_file_resume( $file_path, $file_name = null ) {
		Download::file_resume( $file_path, $file_name );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\read_file' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function read_file( $file_path, $part_size_mb = 2, $return_size = true ) {
		return Download::file_read( $file_path, $part_size_mb, $return_size );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\object_cache' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function object_cache() : Store {
		return Store::instance();
	}
}

if ( ! function_exists( __NAMESPACE__ . '\local_image_to_media_library' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function local_image_to_media_library( $path, $data = array(), $post_parent = 0, $args = array() ) {
		return LocalImage::run( $path, $data, $post_parent, $args );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\remote_image_to_media_library' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function remote_image_to_media_library( $url, $data = array(), $post_parent = 0, $args = array() ) {
		return RemoteImage::run( $url, $data, $post_parent, $args );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\get_objects_sort' ) ) {
	/** @deprecated since 3.7 to be removed in 3.9 */
	function get_objects_sort( $objects_array, $properties = array(), $uasort = false ) {
		return ObjectsSort::run( $objects_array, $properties, $uasort );
	}
}
