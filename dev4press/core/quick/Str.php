<?php

/*
Name:    Dev4Press\v37\Core\Quick\Str
Version: v3.7
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

namespace Dev4Press\v37\Core\Quick;

use DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Str {
	public static function starts_with( $haystack, $needle ) : bool {
		$length = strlen( $needle );

		return ! ( $length === 0 ) && substr( $haystack, 0, $length ) === $needle;
	}

	public static function ends_with( $haystack, $needle ) : bool {
		$length = strlen( $needle );

		return ! ( $length === 0 ) && substr( $haystack, - $length ) === $needle;
	}

	public static function is_valid_datetime( $date, $format = 'Y-m-d H:i:s' ) : bool {
		$d = DateTime::createFromFormat( $format, $date );

		return $d && $d->format( $format ) == $date;
	}

	public static function is_regex_valid( $regex ) {
		if ( preg_match( '/' . $regex . '/i', 'dev4press' ) !== false ) {
			return true;
		}

		return preg_last_error();
	}

	public static function is_valid_md5( $hash = '' ) : bool {
		return strlen( $hash ) == 32 && ctype_xdigit( $hash );
	}

	public static function left( $s1, $s2 ) {
		return substr( $s1, 0, strpos( $s1, $s2 ) );
	}

	public static function replace_first( $search, $replace, $subject ) {
		$pos = strpos( $subject, $search );

		if ( $pos !== false ) {
			$subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
		}

		return $subject;
	}

	public static function slug_to_name( $code, $sep = '_' ) : string {
		$exp = explode( $sep, $code );

		return ucwords( strtolower( join( ' ', $exp ) ) );
	}

	public static function to_ids( $input, $delimiter = ',', $map = 'absint' ) : string {
		$ids = strip_tags( stripslashes( $input ) );

		$ids = explode( $delimiter, $ids );
		$ids = array_map( 'trim', $ids );
		$ids = array_map( $map, $ids );

		return array_filter( $ids );
	}

	public static function replace_tags( $content, $tags, $before = '%', $after = '%' ) : string {
		foreach ( $tags as $tag => $replace ) {
			$_tag = $before . $tag . $after;

			if ( strpos( $content, $_tag ) !== false ) {
				$content = str_replace( $_tag, $replace, $content );
			}
		}

		return $content;
	}
}
