<?php

/**
 * Name:    Dev4Press\v53\Core\Iterator\DirectoryFilter
 * Version: v5.3
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2025 Milan Petrovic (email: support@dev4press.com)
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

namespace Dev4Press\v53\Core\Iterator;

use RecursiveFilterIterator;
use RecursiveIterator;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DirectoryFilter extends RecursiveFilterIterator {
	public $exclude_paths;
	public $exclude_names;

	public function __construct( RecursiveIterator $iterator, array $exclude_paths = array(), array $exclude_names = array() ) {
		parent::__construct( $iterator );

		$this->exclude_paths = $exclude_paths;
		$this->exclude_names = $exclude_names;
	}

	public function accept() : bool {
		$pt = wp_normalize_path( $this->current()->getPathname() );
		$nm = $this->current()->getFilename();

		if ( in_array( $nm, $this->exclude_names ) ) {
			return false;
		}

		foreach ( $this->exclude_paths as $ex ) {
			if ( str_contains( $pt, $ex ) ) {
				return false;
			}
		}

		return true;
	}
}
