<?php
/**
 * Name:    Dev4Press\v56\Core\Quick\Request
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

class Request {
	/**
	 * Determines if the current request method is POST.
	 *
	 * @return bool True if the request method is POST, false otherwise.
	 */
	public static function is_post() : bool {
		return isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'POST';
	}

	/**
	 * Checks if the current HTTP request method is GET.
	 *
	 * @return bool True if the request method is GET, otherwise false.
	 */
	public static function is_get() : bool {
		return isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'GET';
	}

	/**
	 * Checks if a specified key exists in the given request scope.
	 *
	 * @param string $key   The key to check for existence.
	 * @param string $scope The request scope to check within. Defaults to 'REQUEST'.
	 *                      Possible values are 'REQUEST', 'POST', or 'GET'.
	 *
	 * @return bool True if the key exists in the specified scope, otherwise false.
	 */
	public static function has_key( $key, $scope = 'REQUEST' ) : bool {
		switch ( $scope ) {
			default:
			case 'REQUEST':
				return isset( $_REQUEST[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification
			case 'POST':
				return isset( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification
			case 'GET':
				return isset( $_GET[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification
		}
	}
}
