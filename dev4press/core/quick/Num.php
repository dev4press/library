<?php
/**
 * Name:    Dev4Press\v56\Core\Quick\Num
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

class Num {
	/**
	 * Checks if a given number is odd.
	 *
	 * @param numeric $number The number to check.
	 *
	 * @return bool Returns true if the number is odd, false otherwise.
	 */
	public static function is_odd( float|int|string $number ) : bool {
		return $number % 2 != 0;
	}

	/**
	 * Checks if a given number is divisible by another number without a remainder.
	 *
	 * @param numeric $number    The number to be divided.
	 * @param numeric $by_number The divisor.
	 *
	 * @return bool Returns true if the number is exactly divisible by the divisor, false otherwise.
	 */
	public static function is_divisible( float|int|string $number, float|int|string $by_number ) : bool {
		return $number % $by_number == 0;
	}

	/**
	 * Scales a number based on its suffix (k, m, g, t) and converts it to a float.
	 *
	 * @param string $number The number with an optional suffix (k for kilo, m for mega, g for giga, t for tera).
	 * @param int    $base   The base multiplier for scaling the number. Default is 1024.
	 *
	 * @return float Returns the scaled number as a float or 0 if the input is not numeric.
	 */
	public static function scale_numbers_conversion( string $number, int $base = 1024 ) : float {
		$number = strtolower( $number );
		$last   = substr( $number, - 1 );
		$number = substr( $number, 0, - 1 );

		if ( ! is_numeric( $number ) ) {
			return 0;
		}

		$number = floatval( $number );

		switch ( $last ) {
			case 'k':
				return $number * $base;
			case 'm':
				return $number * $base * $base;
			case 'g':
				return $number * $base * $base * $base;
			case 't':
				return $number * $base * $base * $base * $base;
		}

		return $number;
	}
}
