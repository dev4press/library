<?php
/**
 * Name:    Dev4Press\v55\Core\Helpers\HTAccess
 * Version: v5.5
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

// phpcs:ignoreFile WordPress.WP.AlternativeFunctions

namespace Dev4Press\v55\Core\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HTAccess {
	public string $begin = 'BEGIN';
	public string $end = 'END';
	public string $path = '';

	public function __construct( string $path = '' ) {
		$this->path = $path == '' ? ABSPATH . '.htaccess' : $path;
	}

	public function is_writable() : bool {
		return is_writable( $this->path ) && is_writable( dirname( $this->path ) );
	}

	public function file_exists() : bool {
		return file_exists( $this->path );
	}

	public function insert( string $marker, array $insertion = array(), string $location = 'end', bool $cleanup = false, bool $backup = false ) : bool {
		if ( $this->is_writable() ) {
			if ( $backup ) {
				$this->create_backup();
			}

			$content = $this->generate_content( $marker, $insertion, $location, $cleanup );
			$tempath = $this->path . '.' . substr( md5( wp_generate_uuid4() ), 0, 16 ) . '.txt';

			$result = $this->write_content( $content, $tempath );

			if ( $result ) {
				$temp_file = $this->load( $tempath );
				$temp_diff = array_diff( $content, $temp_file );

				if ( empty( $temp_diff ) ) {
					if ( $this->file_exists() ) {
						wp_delete_file( $this->path );
					}

					$result = rename( $tempath, $this->path );
				} else {
					wp_delete_file( $tempath );

					$result = false;
				}
			}

			return $result;
		}

		return false;
	}

	public function load( string $path = '' ) {
		if ( ! empty( $path ) && file_exists( $path ) ) {
			return file( $path, FILE_IGNORE_NEW_LINES );
		} else if ( $this->file_exists() ) {
			return file( $this->path, FILE_IGNORE_NEW_LINES );
		} else {
			return array();
		}
	}

	public function remove( string $marker, bool $cleanup = false, bool $backup = false ) : bool {
		return $this->insert( $marker, array(), 'end', $cleanup, $backup );
	}

	public function check() : array {
		global $is_apache;

		$mods = $is_apache && function_exists( 'apache_get_modules' ) ? apache_get_modules() : array();

		$check = array(
			'is_apache'          => $is_apache,
			'file'               => '.htaccess',
			'htaccess'           => $this->path,
			'found'              => $is_apache && $this->file_exists(),
			'writable'           => $is_apache && $this->is_writable(),
			'automatic'          => false,
			'apache_get_modules' => ! empty( $mods ),
			'mod_rewrite'        => in_array( 'mod_rewrite', $mods ),
			'mod_alias'          => in_array( 'mod_alias', $mods ),
			'mod_setenvif'       => in_array( 'mod_setenvif', $mods ),
			'mod_headers'        => in_array( 'mod_headers', $mods ),
		);

		if ( $is_apache && ! $check['found'] ) {
			$check['writable'] = is_writable( ABSPATH );
		}

		if ( $is_apache && $check['writable'] && $check['is_apache'] ) {
			$check['automatic'] = true;
		}

		return $check;
	}

	protected function create_backup() {
		$backup_path = $this->path . '.backup';

		if ( file_exists( $this->path ) && is_writable( dirname( $backup_path ) ) ) {
			if ( file_exists( $backup_path ) ) {
				wp_delete_file( $backup_path );
			}

			copy( $this->path, $backup_path );
		}
	}

	protected function generate_content( string $marker, array $insertion = array(), string $location = 'end', bool $cleanup = false ) : array {
		$content = array();

		if ( ! $this->file_exists() ) {
			$input = array();
		} else {
			$input = $this->load();

			if ( ! $input ) {
				$input = array();
			}
		}

		if ( ! empty( $insertion ) ) {
			$insertion = array_merge(
				array( '# BEGIN ' . $marker ),
				$insertion,
				array( '# END ' . $marker ) );
		}

		if ( $location == 'start' ) {
			$content = $insertion;
		}

		if ( ! empty( $input ) ) {
			$state = true;

			foreach ( $input as $marker_line ) {
				if ( str_starts_with( $marker_line, '# ' . $this->begin . ' ' . $marker ) ) {
					$state = false;
				}

				if ( $state ) {
					$content[] = $marker_line;
				}

				if ( str_starts_with( $marker_line, '# ' . $this->end . ' ' . $marker ) ) {
					$state = true;
				}
			}
		}

		if ( $location == 'end' ) {
			$content = array_merge( $content, $insertion );
		}

		if ( $cleanup ) {
			$content = $this->cleanup_content( $content );
		}

		return $content;
	}

	protected function cleanup_content( array $marker_data = array() ) : array {
		$modded_data = array();
		$line_start  = 0;
		$line_end    = 0;
		$marker_size = count( $marker_data );

		for ( $i = 0; $i < $marker_size; $i ++ ) {
			if ( ! empty( trim( $marker_data[ $i ] ) ) ) {
				$line_start = $i;
				break;
			}
		}

		for ( $i = $marker_size - 1; $i > 0; $i -- ) {
			if ( ! empty( trim( $marker_data[ $i ] ) ) ) {
				$line_end = $i;
				break;
			}
		}

		$prev_empty     = false;
		$prev_comment   = false;
		$prev_directive = false;
		for ( $i = $line_start; $i < $line_end + 1; $i ++ ) {
			$begin      = false;
			$comment    = false;
			$end        = false;
			$directive  = false;
			$add_before = false;
			$add_after  = false;
			$marker     = $marker_data[ $i ];
			$line       = trim( $marker );

			if ( str_starts_with( $line, '# BEGIN ' ) ) {
				$begin = true;
			} else if ( str_starts_with( $line, '# END' ) ) {
				$end = true;
			} else if ( str_starts_with( $line, '# ' ) ) {
				$comment = true;
			} else if ( ! empty( $line ) ) {
				$directive = true;
			}

			if ( $directive ) {
				if ( ! $prev_comment && ! $prev_directive && ! $prev_empty ) {
					$add_before = true;
				}
			}

			if ( $comment ) {
				if ( $prev_directive || ( ! $prev_empty && ! $prev_comment ) ) {
					$add_before = true;
				}
			}

			if ( $end ) {
				$add_after = true;
			}

			if ( $add_before ) {
				$modded_data[] = '';
			}

			if ( $directive || $begin || $end || $comment ) {
				$modded_data[] = $marker;
			}

			if ( $add_after ) {
				$modded_data[] = '';
			}

			$prev_comment   = $begin || $end || $comment;
			$prev_empty     = $add_after;
			$prev_directive = $directive;
		}

		return $modded_data;
	}

	protected function write_content( array $content, string $path ) : bool {
		$f = fopen( $path, 'w' );

		if ( $f === false ) {
			return false;
		}

		$result = true;
		if ( flock( $f, LOCK_EX ) ) {
			fwrite( $f, implode( PHP_EOL, $content ) );
			fflush( $f );
			flock( $f, LOCK_UN );
		} else {
			$result = false;
		}

		fclose( $f );

		return $result;
	}
}
