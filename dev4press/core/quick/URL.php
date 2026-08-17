<?php
/**
 * Name:    Dev4Press\v56\Core\Quick\URL
 * Version: v5.6
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2026 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Dev4Press\v56\Core\Quick;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class URL {
	/**
	 * Extracts the domain name from a given URL.
	 *
	 * @param string $url The URL to extract the domain name from. If an empty string is provided, returns an empty string.
	 *
	 * @return bool|string The domain name extracted from the URL.
	 */
	public static function domain_name( string $url = '' ) : bool|string {
		if ( empty( $url ) ) {
			return '';
		}

		return wp_parse_url( $url, PHP_URL_HOST );
	}

	/**
	 * Cleans the provided URL to extract and return only its domain name.
	 *
	 * @param string $url The full URL whose domain needs cleaning.
	 *
	 * @return string The cleaned domain extracted from the provided URL.
	 */
	public static function clean_domain_name( string $url = '' ) : string {
		$url = empty( $url ) ? get_option( 'siteurl' ) : $url;

		$domain = preg_replace( '|https?://|', '', $url );
		$slash  = strpos( $domain, '/' );

		if ( $slash ) {
			$domain = substr( $domain, 0, $slash );
		}

		return $domain;
	}

	/**
	 * Retrieves the current request path from the server environment.
	 *
	 * @return string The sanitized current request path.
	 */
	public static function current_request_path() {
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_url( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput,WordPress.Security.NonceVerification,WordPress.Security.ValidatedSanitizedInput,WordPress.WP.DeprecatedFunctions

		return wp_parse_url( $uri, PHP_URL_PATH );
	}

	/**
	 * Retrieves the current URL request path, excluding the site home path and optionally appending the query string if present.
	 *
	 * @return string The current URL request path, relative to the site home, with the query string included if applicable.
	 */
	public static function current_url_request() : string {
		$path_info = $_SERVER['PATH_INFO'] ?? ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
		list( $path_info ) = explode( '?', $path_info );
		$path_info = str_replace( '%', '%25', $path_info );

		$request         = explode( '?', $_SERVER['REQUEST_URI'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
		$req_uri         = $request[0];
		$req_query       = $request[1] ?? false;
		$home_path       = wp_parse_url( home_url(), PHP_URL_PATH );
		$home_path       = $home_path ? trim( $home_path, '/' ) : '';
		$home_path_regex = sprintf( '|^%s|i', preg_quote( $home_path, '|' ) );

		$req_uri = str_replace( $path_info, '', $req_uri );
		$req_uri = ltrim( $req_uri, '/' );
		$req_uri = preg_replace( $home_path_regex, '', $req_uri );
		$req_uri = ltrim( $req_uri, '/' );

		$url_request = $req_uri;

		if ( $req_query !== false ) {
			$url_request .= '?' . $req_query;
		}

		return $url_request;
	}

	/**
	 * Retrieves the current URL of the request.
	 *
	 * @param bool $use_wp Optional. Determines whether to use WordPress's home_url function.
	 *
	 * @return string The full URL of the current request.
	 */
	public static function current_url( bool $use_wp = true ) : string {
		if ( $use_wp ) {
			return home_url( self::current_url_request() );
		} else {
			$s        = is_ssl() ? 's' : '';
			$protocol = Str::left( strtolower( $_SERVER['SERVER_PROTOCOL'] ), '/' ) . $s; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
			$port     = isset( $_SERVER['SERVER_PORT'] ) ? absint( $_SERVER['SERVER_PORT'] ) : 80;
			$port     = $port === 80 || $port === 443 ? '' : ':' . $port;

			return $protocol . '://' . sanitize_url( $_SERVER['SERVER_NAME'] ) . $port . sanitize_url( $_SERVER['REQUEST_URI'] );  // phpcs:ignore WordPress.Security.EscapeOutput,WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification,WordPress.WP.DeprecatedFunctions
		}
	}

	/**
	 * Appends UTM campaign tracking parameters to a given URL.
	 *
	 * @param string      $url      The base URL to which campaign tracking parameters will be added.
	 * @param string      $campaign Optional. The campaign name (utm_campaign) to track the marketing campaign.
	 * @param string      $medium   Optional. The medium (utm_medium) used, such as email, CPC, or social.
	 * @param string      $content  Optional. The content (utm_content) to differentiate ads or links.
	 * @param string      $term     Optional. The term (utm_term), typically a keyword for paid search campaigns.
	 * @param string|null $source   Optional. The source (utm_source), such as a website or platform. Defaults to the current site URL's host.
	 *
	 * @return string The URL with appended campaign tracking parameters.
	 */
	public static function add_campaign_tracking( string $url, string $campaign = '', string $medium = '', string $content = '', string $term = '', ?string $source = null ) : string {
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
			$source = wp_parse_url( get_bloginfo( 'url' ), PHP_URL_HOST );
		}

		if ( ! empty( $source ) ) {
			$url = add_query_arg( 'utm_source', $source, $url );
		}

		return $url;
	}
}
