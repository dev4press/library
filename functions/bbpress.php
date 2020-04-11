<?php

/*
Name:    Base Library Functions: bbPress
Version: v3.0.1
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

if (!defined('ABSPATH')) { exit; }

if (!function_exists('d4p_has_bbpress')) {
    function d4p_has_bbpress() {
        return d4p_get_bbpress_major_version_code() > 24;
    }
}

if (!function_exists('d4p_get_bbpress_major_version_code')) {
    function d4p_get_bbpress_major_version_code() {
        if (function_exists('bbp_get_version')) {
            $version = bbp_get_version();

            return intval(substr(str_replace('.', '', $version), 0, 2));
        }

        return 0;
    }
}

if (!function_exists('d4p_get_bbpress_major_version_number')) {
    function d4p_get_bbpress_major_version_number($ret = 'number') {
        if (function_exists('bbp_get_version')) {
            $version = bbp_get_version();

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

if (!function_exists('d4p_get_bbpress_user_roles')) {
    function d4p_get_bbpress_user_roles() {
        $roles = array();

        $dynamic_roles = bbp_get_dynamic_roles();

        foreach ($dynamic_roles as $role => $obj) {
            $roles[$role] = $obj['name'];
        }

        return $roles;
    }
}

if (!function_exists('d4p_get_bbpress_mederator_roles')) {
    function d4p_get_bbpress_mederator_roles() {
        $roles = array();

        $dynamic_roles = bbp_get_dynamic_roles();

        foreach ($dynamic_roles as $role => $obj) {
            if (isset($obj['capabilities']['moderate']) && $obj['capabilities']['moderate']) {
                $roles[$role] = $obj['name'];
            }
        }

        return $roles;
    }
}

if (!function_exists('d4p_get_bbpress_forums_list')) {
    function d4p_get_bbpress_forums_list($args = array()) {
        $defaults = array(
            'post_type' => bbp_get_forum_post_type(),
            'numberposts' => -1,
        );

        $args = wp_parse_args($args, $defaults);

        $_forums = get_posts($args);

        $forums = array();

        foreach ($_forums as $forum) {
            $forums[$forum->ID] = (object)array(
                'id' => $forum->ID,
                'url' => get_permalink($forum->ID),
                'parent' => $forum->post_parent,
                'title' => $forum->post_title
            );
        }

        return $forums;
    }
}
