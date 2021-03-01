<?php

/*
Name:    Dev4Press Core Loader
Version: v3.4
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'D4P_CORE_VERSION' ) ) {
	define( 'D4P_CORE_VERSION', '3.5' );
	define( 'D4P_CORE_BUILD', '3499' );
}

if ( ! defined( 'D4P_EOL' ) ) {
	define( 'D4P_EOL', "\r\n" );
}

if ( ! defined( 'D4P_TAB' ) ) {
	define( 'D4P_TAB', "\t" );
}

if ( ! defined( 'D4P_PHP_VERSION' ) ) {
	$version = str_replace( '.', '', phpversion() );
	$version = intval( substr( $version, 0, 2 ) );

	define( 'D4P_PHP_VERSION', $version );
}

if ( ! defined( 'D4P_CHARSET' ) ) {
	define( 'D4P_CHARSET', get_option( 'blog_charset' ) );
}

if ( ! defined( 'D4P_ADMIN' ) ) {
	define( 'D4P_ADMIN', defined( 'WP_ADMIN' ) && WP_ADMIN );
}

if ( ! defined( 'D4P_AJAX' ) ) {
	define( 'D4P_AJAX', defined( 'DOING_AJAX' ) && DOING_AJAX );
}

if ( ! defined( 'D4P_ASYNC_UPLOAD' ) && D4P_AJAX ) {
	define( 'D4P_ASYNC_UPLOAD', isset( $_REQUEST['action'] ) && 'upload-attachment' === $_REQUEST['action'] );
}

if ( ! defined( 'D4P_CRON' ) ) {
	define( 'D4P_CRON', defined( 'DOING_CRON' ) && DOING_CRON );
}

if ( ! defined( 'D4P_DEBUG' ) ) {
	define( 'D4P_DEBUG', defined( 'WP_DEBUG' ) && WP_DEBUG );
}

if ( ! defined( 'D4P_SCRIPT_DEBUG' ) ) {
	define( 'D4P_SCRIPT_DEBUG', defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
}

if ( ! defined( 'D4PLIB_CACERT_PATH' ) ) {
	define( 'D4PLIB_CACERT_PATH', dirname( __FILE__ ) . '/resources/curl/cacert.pem' );
}

include( dirname( __FILE__ ) . '/autoload.php' );

include( dirname( __FILE__ ) . '/functions/bridge.php' );
include( dirname( __FILE__ ) . '/functions/access.php' );
include( dirname( __FILE__ ) . '/functions/helpers.php' );
include( dirname( __FILE__ ) . '/functions/cache.php' );
include( dirname( __FILE__ ) . '/functions/debug.php' );
include( dirname( __FILE__ ) . '/functions/sanitize.php' );

include( dirname( __FILE__ ) . '/functions/wordpress.php' );
include( dirname( __FILE__ ) . '/functions/bbpress.php' );
include( dirname( __FILE__ ) . '/functions/buddypress.php' );

if ( D4P_ADMIN ) {
	include( dirname( __FILE__ ) . '/functions/admin.php' );
}
