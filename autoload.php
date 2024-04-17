<?php
/**
 * Name:    Dev4Press Core Autoloader
 * Version: v4.8
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)
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

if ( ! function_exists( 'dev4press_core_library_autoloader_v48' ) ) {
	function dev4press_core_library_autoloader_v48( $class ) {
		$path = DEV4PRESS_V48_PATH;
		$base = 'Dev4Press\\v48\\';

		dev4press_v48_autoload_for_plugin( $class, $base, $path, 'dev4press/' );
	}

	spl_autoload_register( 'dev4press_core_library_autoloader_v48' );
}
