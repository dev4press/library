<?php
/**
 * Name:    Dev4Press\v50\Core\Helpers\Vendors
 * Version: v5.0
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2024 Milan Petrovic (email: support@dev4press.com)
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

namespace Dev4Press\v50\Core\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Vendors {
	public static function parsedown() {
		require DEV4PRESS_V50_PATH . 'vendor/parsedown-extra/Parsedown.php';
	}

	public static function parsedown_extra() {
		self::parsedown();

		require DEV4PRESS_V50_PATH . 'vendor/parsedown-extra/ParsedownExtra.php';
	}
}
