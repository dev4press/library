<?php
/**
 * Name:    Dev4Press\v56\Core\Helpers\IP
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

namespace Dev4Press\v56\Core\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class IP {
	protected static $private_ipv4 = array(
		'10.0.0.0/8',
		'127.0.0.0/8',
		'172.16.0.0/12',
		'192.168.0.0/16',
	);

	protected static $private_ipv6 = array(
		'::1/128',
		'fd00::/8',
	);

	protected static $cloudflare_ipv4 = array(
		'103.21.244.0/22',
		'103.22.200.0/22',
		'103.31.4.0/22',
		'104.16.0.0/13',
		'104.24.0.0/14',
		'108.162.192.0/18',
		'131.0.72.0/22',
		'141.101.64.0/18',
		'162.158.0.0/15',
		'172.64.0.0/13',
		'173.245.48.0/20',
		'188.114.96.0/20',
		'190.93.240.0/20',
		'197.234.240.0/22',
		'198.41.128.0/17',
	);

	protected static $cloudflare_ipv6 = array(
		'2400:cb00::/32',
		'2405:8100::/32',
		'2405:b500::/32',
		'2606:4700::/32',
		'2803:f800::/32',
		'2a06:98c0::/29',
		'2c0f:f248::/32',
	);

	public static function is_v4( string $ip ) : bool {
		if ( preg_match( '/^[0-9.]+$/', $ip ) !== 1 ) {
			return false;
		}

		return filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) === $ip;
	}

	public static function is_v6( string $ip ) : bool {
		if ( strlen( $ip ) > 45 ) {
			if ( preg_match( '/^[0-9a-fA-F:]+$/', $ip ) !== 1 ) {
				return false;
			}
		}

		return filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) === $ip;
	}

	public static function is_in_range( string $ip, string $range ) : bool {
		return self::is_v6( $ip ) ? self::is_ipv6_in_range( $ip, $range ) : self::is_ipv4_in_range( $ip, $range );
	}

	public static function is_ipv4_in_range( string $ip, string $range ) : bool {
		if ( str_contains( $range, '/' ) ) {
			list( $subnet, $mask ) = explode( '/', $range, 2 );

			if ( $mask <= 0 ) {
				return true;
			}

			if ( $mask >= 32 ) {
				return $ip === $subnet;
			}

			$ip_long     = ip2long( $ip );
			$subnet_long = ip2long( $subnet );

			$netmask = - 1 << ( 32 - $mask );

			return ( ( $ip_long & $netmask ) === ( $subnet_long & $netmask ) );
		}

		if ( str_contains( $range, '*' ) ) {
			$lower = str_replace( '*', '0', $range );
			$upper = str_replace( '*', '255', $range );
			$range = "$lower-$upper";
		}

		if ( str_contains( $range, '-' ) ) {
			list( $lower, $upper ) = explode( '-', $range, 2 );

			$ip_dec    = sprintf( '%u', ip2long( $ip ) );
			$lower_dec = sprintf( '%u', ip2long( trim( $lower ) ) );
			$upper_dec = sprintf( '%u', ip2long( trim( $upper ) ) );

			return ( $ip_dec >= $lower_dec && $ip_dec <= $upper_dec );
		}

		return false;
	}

	public static function is_ipv6_in_range( string $ip, string $range ) : bool {
		if ( ! str_contains( $range, '/' ) ) {
			return $ip === $range;
		}

		list( $subnet, $mask ) = explode( '/', $range, 2 );

		$subnet_bin = inet_pton( $subnet );
		$ip_bin     = inet_pton( $ip );

		if ( strlen( $subnet_bin ) !== 16 || strlen( $ip_bin ) !== 16 ) {
			return false;
		}

		$mask_hex = str_repeat( 'f', (int) ( $mask / 4 ) );
		switch ( $mask % 4 ) {
			case 1:
				$mask_hex .= '8';
				break;
			case 2:
				$mask_hex .= 'c';
				break;
			case 3:
				$mask_hex .= 'e';
				break;
		}

		$mask_hex = str_pad( $mask_hex, 32, '0' );
		$mask_bin = pack( 'H*', $mask_hex );

		return ( $ip_bin & $mask_bin ) === ( $subnet_bin & $mask_bin );
	}

	public static function full_ip( string $ip ) : string {
		if ( self::is_v4( $ip ) ) {
			return $ip;
		} else if ( self::is_v6( $ip ) ) {
			$hex = bin2hex( inet_pton( $ip ) );

			if ( str_starts_with( $hex, '00000000000000000000ffff' ) ) {
				return long2ip( hexdec( substr( $hex, - 8 ) ) );
			}

			return implode( ':', str_split( $hex, 4 ) );
		}

		return '';
	}

	public static function is_private( string $ip = null ) : bool {
		if ( is_null( $ip ) ) {
			$ip = self::visitor();
		}

		if ( ! str_contains( $ip, ':' ) ) {
			foreach ( self::$private_ipv4 as $cf ) {
				if ( self::is_ipv4_in_range( $ip, $cf ) ) {
					return true;
				}
			}
		} else {
			foreach ( self::$private_ipv6 as $cf ) {
				if ( self::is_ipv6_in_range( $ip, $cf ) ) {
					return true;
				}
			}
		}

		return false;
	}

	public static function is_private_regex( string $ip = null ) : bool {
		if ( preg_match( '/^((127\.)|(192\.168\.)|(10\.)|(172\.1[6-9]\.)|(172\.2[0-9]\.)|(172\.3[0-1]\.)|(::1)|(fe80::))/', $ip ) ) {
			return true;
		}

		return false;
	}

	public static function is_cloudflare( string $ip = null ) : bool {
		if ( is_null( $ip ) ) {
			if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
				$ip = $_SERVER['REMOTE_ADDR'] ?? '';

				if ( empty( $ip ) ) {
					return false;
				}

				return self::is_cloudflare( $ip );
			} else {
				return false;
			}
		}

		if ( empty( $ip ) ) {
			return false;
		}

		if ( self::is_v4( $ip ) ) {
			foreach ( self::$cloudflare_ipv4 as $cf ) {
				if ( self::is_ipv4_in_range( $ip, $cf ) ) {
					return true;
				}
			}
		} else if ( self::is_v6( $ip ) ) {
			foreach ( self::$cloudflare_ipv6 as $cf ) {
				if ( self::is_ipv6_in_range( $ip, $cf ) ) {
					return true;
				}
			}
		}

		return false;
	}

	public static function is_loopback( string $ip ) : bool {
		if ( $ip === '127.0.0.1' || $ip === '::1' ) {
			return true;
		}

		$binary = @inet_pton( $ip );
		if ( ! $binary ) {
			return false;
		}

		if ( strlen( $binary ) === 16 ) {
			$hex = bin2hex( $binary );

			if ( str_starts_with( $hex, '00000000000000000000ffff7f' ) ) {
				return true;
			}
		}

		return false;
	}

	public static function server() : string {
		if ( ! isset( $_SERVER['SERVER_ADDR'] ) ) {
			return '';
		}

		$ip = self::validate( $_SERVER['SERVER_ADDR'] );  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		if ( self::is_loopback( $ip ) ) {
			$ip = '127.0.0.1';
		}

		return (string) $ip;
	}

	public static function visitor( bool $forwarded = true, bool $standard = true ) : string {
		$ip = false;

		if ( self::is_cloudflare() ) {
			$ips = self::get_all_ips( true, false, false, false, false );

			if ( ! empty( $ips ) ) {
				return $ips[0]['ip'];
			}
		}

		$ips = self::get_all_ips( false, true, false, $forwarded, ! $standard );

		$ip = self::process_ips_list_for_one_ip( $ips );

		return (string) $ip;
	}

	public static function validate( string $ip ) {
		$ips = explode( ',', $ip );

		foreach ( $ips as $_ip ) {
			$_ip = trim( $_ip );

			if ( $_ip === '' ) {
				continue;
			}

			if ( str_contains( $_ip, '/' ) ) {
				$filtered = self::validate_range( $_ip );

				if ( $filtered !== false ) {
					return $filtered;
				}
			}

			if ( ! self::is_v6( $_ip ) && ! self::is_v4( $_ip ) ) {
				continue;
			}

			$filtered = filter_var( $_ip, FILTER_VALIDATE_IP );

			if ( $filtered !== false ) {
				return $filtered;
			}
		}

		return false;
	}

	public static function validate_range( string $ip ) : bool|string {
		list( $addr, $mask ) = explode( '/', $ip, 2 );

		$addr = trim( $addr );
		$mask = trim( $mask );

		// IPv4 CIDR
		if ( filter_var( $addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			if ( ctype_digit( $mask ) ) {
				$prefix = (int) $mask;

				if ( $prefix >= 0 && $prefix <= 32 ) {
					return $addr . '/' . $prefix;
				}
			} else {
				if ( filter_var( $mask, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
					$bin = sprintf( '%032b', ip2long( $mask ) );

					if ( preg_match( '/^1+0*$/', $bin ) ) {
						$prefix = strlen( rtrim( $bin, '0' ) );

						return $addr . '/' . $prefix;
					}
				}
			}

			return false;
		}

		// IPv6 CIDR
		if ( filter_var( $addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
			if ( ctype_digit( $mask ) ) {
				$prefix = (int) $mask;

				if ( $prefix >= 0 && $prefix <= 128 ) {
					$norm = inet_ntop( inet_pton( $addr ) );

					return $norm . '/' . $prefix;
				}
			}

			return false;
		}

		return false;
	}

	public static function cleanup( string $ip ) : string {
		$ip = self::validate( $ip );

		return $ip === false ? '' : $ip;
	}

	public static function random_ipv4() : string {
		return wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 ) . '.' . wp_rand( 0, 255 );
	}

	public static function get_ip_key_value( string $key ) {
		$ip = false;

		if ( array_key_exists( $key, $_SERVER ) === true ) {
			$ip = ! empty( $_SERVER[ $key ] ) ? $_SERVER[ $key ] : false; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

			if ( $ip !== false ) {
				$ip = self::validate( $ip );
			}
		}

		return $ip;
	}

	public static function get_visitor_ip_non_standard() {
		$ips = self::get_all_ips( false, false, false, false, true );

		return self::process_ips_list_for_one_ip( $ips );
	}

	public static function process_ips_list_for_one_ip( array $ips ) {
		$public   = array();
		$private  = array();
		$loopback = array();

		foreach ( $ips as $entry ) {
			if ( ! $entry['is_valid'] ) {
				continue;
			}

			if ( ! $entry['is_private'] && ! $entry['is_loopback'] ) {
				$public[] = $entry['ip'];
			} else if ( $entry['is_private'] && ! $entry['is_loopback'] ) {
				$private[] = $entry['ip'];
			} else if ( $entry['is_loopback'] ) {
				$loopback[] = $entry['ip'];
			}
		}

		if ( ! empty( $public ) ) {
			return $public[0];
		}

		if ( ! empty( $private ) ) {
			return $private[0];
		}

		if ( ! empty( $loopback ) ) {
			return '127.0.0.1';
		}

		return false;
	}

	public static function get_all_ips( bool $cloudflare = true, bool $remote = true, bool $server = true, bool $forwarded = true, bool $nonstandard = true ) : array {
		$results = array();
		$headers = array();

		if ( $cloudflare ) {
			$headers[] = 'HTTP_CF_CONNECTING_IP';
		}

		if ( $remote ) {
			$headers[] = 'REMOTE_ADDR';
		}

		if ( $forwarded ) {
			$headers = array_merge( $headers, array(
				'HTTP_X_FORWARDED_FOR',
				'HTTP_X_FORWARDED',
			) );
		}

		if ( $nonstandard ) {
			$headers = array_merge( $headers, array(
				'HTTP_FORWARDED_FOR',
				'HTTP_FORWARDED',
				'HTTP_X_CLUSTER_CLIENT_IP',
				'HTTP_CLIENT_IP',
				'HTTP_X_REAL_IP',
			) );
		}
		if ( $server ) {
			$headers[] = 'SERVER_ADDR';
		}

		foreach ( $headers as $key ) {
			if ( ! empty( $_SERVER[ $key ] ) ) {
				$raw = explode( ',', $_SERVER[ $key ] );

				foreach ( $raw as $raw_ip ) {
					$trimmed_ip = self::get_ip_without_port( $raw_ip );

					$ip = array(
						'ip'          => $trimmed_ip,
						'source'      => $key,
						'is_v4'       => self::is_v4( $trimmed_ip ),
						'is_v6'       => self::is_v6( $trimmed_ip ),
						'is_loopback' => false,
						'is_private'  => false,
					);

					$ip['is_valid'] = $ip['is_v4'] || $ip['is_v6'];

					if ( $ip['is_valid'] ) {
						$ip['is_loopback'] = self::is_loopback( $trimmed_ip );
						$ip['is_private']  = self::is_private( $trimmed_ip );
					}

					$results[] = $ip;
				}
			}
		}

		return $results;
	}

	public static function get_ip_without_port( string $ip ) : string {
		$ip = trim( $ip );

		if ( str_starts_with( $ip, '[' ) ) {
			$endBracket = strpos( $ip, ']' );

			if ( $endBracket !== false ) {
				return substr( $ip, 1, $endBracket - 1 );
			}
		}

		$colonCount = substr_count( $ip, ':' );

		if ( $colonCount === 1 ) {
			return explode( ':', $ip )[0];
		}

		return $ip;
	}
}
