<?php
/**
 * Name:    Dev4Press\v56\Core\Quick\File
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

use Exception;
use PharData;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class File {
	/**
	 * Formats a given size in bytes into a human-readable format.
	 *
	 * @param string|float|int $size     The size to be formatted, in bytes.
	 * @param int              $decimals Number of decimal places to use. Default is 2.
	 * @param string           $sep      Separator between the number and unit. Default is a space.
	 * @param bool             $strong   Whether to wrap the result in strong tags. Default is true.
	 *
	 * @return string The formatted size as a string.
	 */
	public static function size_format( string|float|int $size, int $decimals = 2, string $sep = ' ', bool $strong = true ) : string {
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

		if ( $strong ) {
			return '<strong>' . $_size . '</strong>' . $sep . $unit;
		} else {
			return $_size . $sep . $unit;
		}
	}

	/**
	 * Retrieves the contents of a file.
	 *
	 * @param string $path Path to the file whose contents are to be retrieved.
	 *
	 * @return string|false The content of the file on success, false on failure.
	 */
	public static function get_contents( string $path ) : bool|string {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';

			WP_Filesystem();
		}

		return $wp_filesystem->get_contents( $path );
	}

	/**
	 * Writes the given content to a file at the specified path.
	 *
	 * @param string $path    The full path to the file where content should be written.
	 * @param string $content The content to write to the file.
	 *
	 * @return bool True on success, false on failure.
	 */
	public static function put_contents( string $path, string $content ) : bool {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';

			WP_Filesystem();
		}

		return $wp_filesystem->put_contents( $path, $content );
	}

	/**
	 * Retrieves the uncompressed size of a gzip-compressed file.
	 *
	 * @param string $file_path The path to the gzip-compressed file.
	 *
	 * @return int|null The uncompressed size of the file, or null if the operation fails.
	 */
	public static function gzip_uncompressed_size( $file_path ) : ?int {
		$fp = fopen( $file_path, 'rb' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		fseek( $fp, - 4, SEEK_END );
		$buf = fread( $fp, 4 ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$elm = unpack( 'V', $buf );

		return end( $elm );
	}

	/**
	 * Scans a directory and returns an array of files or directories that match the specified criteria.
	 *
	 * @param string $path       The path to the directory to scan.
	 * @param string $filter     Optional. Filters the results to either 'files', 'folders', or 'all'. Default is 'files'.
	 * @param array  $extensions Optional. An array of file extensions to include in the results. Default is an empty array.
	 * @param string $reg_expr   Optional. A regular expression pattern to filter filenames. Default is an empty string.
	 * @param bool   $full_path  Optional. If true, returns the full path for each result. Default is false.
	 *
	 * @return array An array of filenames that match the specified criteria.
	 */
	public static function scan_dir( string $path, string $filter = 'files', array $extensions = array(), string $reg_expr = '', bool $full_path = false ) : array {
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
					if ( ! str_starts_with( $file, '.' ) ) {
						if (
							( is_dir( $path . $file ) && ( in_array( $filter, array( 'folders', 'all' ) ) ) ) ||
							( is_file( $path . $file ) && ( in_array( $filter, array( 'files', 'all' ) ) ) ) ||
							( ( is_file( $path . $file ) || is_dir( $path . $file ) ) && ( $filter == 'all' ) ) ) {
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

	/**
	 * Unpacks a .tar.gz file to the specified directory.
	 *
	 * @param string $file The path to the tar.gz file to be unpacked.
	 * @param string $path The target directory where the files will be extracted.
	 *
	 * @return bool|WP_Error True if extraction is successful, WP_Error object on failure.
	 */
	public static function unpack_tar_gz( string $file, string $path ) : WP_Error|bool {
		$result = false;
		$error  = '';

		try {
			$tar = new PharData( $file );

			$result = $tar->extractTo( $path, null, true );
		} catch ( Exception $e ) {
			$error = $e->getMessage();
		}

		if ( $result === true ) {
			return true;
		} else {
			return new WP_Error( 'extract', $error );
		}
	}
}
