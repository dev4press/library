<?php

/*
Name:    Base Library Functions: BuddyPress
Version: v3.1.2
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2020 Milan Petrovic (email: support@dev4press.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('d4p_has_buddypress')) {
    function d4p_has_buddypress() {
        if (d4p_is_plugin_active('buddypress/bp-loader.php')) {
            $version = bp_get_version();
            $version = intval(substr(str_replace('.', '', $version), 0, 2));

            return $version > 39;
        } else {
            return false;
        }
    }
}

if (!function_exists('d4p_get_buddypress_major_version_code')) {
    function d4p_get_buddypress_major_version_code() {
        if (function_exists('bp_get_version')) {
            $version = bp_get_version();

            return intval(substr(str_replace('.', '', $version), 0, 2));
        }

        return 0;
    }
}

if (!function_exists('d4p_get_buddypress_major_version_number')) {
    function d4p_get_buddypress_major_version_number($ret = 'number') {
        if (function_exists('bp_get_version')) {
            $version = bp_get_version();

            if (isset($version)) {
                if ($ret == 'number') {
                    return substr(str_replace('.', '', $version), 0, 2);
                } else {
                    return $version;
                }
            }
        }

        return 0;
    }
}
