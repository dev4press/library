<?php

/*
Name:    Base Library Functions: Cache
Version: v2.9.0
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2019 Milan Petrovic (email: support@dev4press.com)

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

if (!function_exists('d4p_cache_flush')) {
    /** @global wpdb $wpdb */
    function d4p_cache_flush($cache = true, $queries = true) {
        if ($cache) {
            wp_cache_flush();
        }

        if ($queries) {
            global $wpdb;

            if (is_array($wpdb->queries) && !empty($wpdb->queries)) {
                unset($wpdb->queries);
                $wpdb->queries = array();
            }
        }
    }
}

if (!function_exists('d4p_posts_cache_by_ids')) {
    /** @global wpdb $wpdb */
    function d4p_posts_cache_by_ids($posts) {
        global $wpdb;

        $posts = _get_non_cached_ids($posts, 'posts');
        $posts = array_filter($posts);

        if (!empty($posts)) {
            $sql = 'SELECT * FROM '.$wpdb->posts.' WHERE ID IN ('.join(',', (array)$posts).')';
            $raw = $wpdb->get_results($sql);

            foreach ($raw as $_post) {
                $_post = sanitize_post($_post, 'raw');
                wp_cache_add($_post->ID, $_post, 'posts');
            }
        }
    }
}

if (!function_exists('d4p_transient_sql_query')) {
    function d4p_transient_sql_query($query, $key, $method, $output = OBJECT, $x = 0, $y = 0, $ttl = 86400) {
        $var = get_transient($key);

        if ($var === false) {
            global $wpdb;

            switch ($method) {
                case 'var':
                    $var = $wpdb->get_var($query, $x, $y);
                    break;
                case 'row':
                    $var = $wpdb->get_row($query, $output, $y);
                    break;
                case 'results':
                    $var = $wpdb->get_results($query, $output);
                    break;
            }

            set_transient($key, $var, $ttl);
        }

        return $var;
    }
}

if (!function_exists('d4p_site_transient_sql_query')) {
    function d4p_site_transient_sql_query($query, $key, $method, $output = OBJECT, $x = 0, $y = 0, $ttl = 86400) {
        $var = get_site_transient($key);

        if ($var === false) {
            global $wpdb;

            switch ($method) {
                case 'var':
                    $var = $wpdb->get_var($query, $x, $y);
                    break;
                case 'row':
                    $var = $wpdb->get_row($query, $output, $y);
                    break;
                case 'results':
                    $var = $wpdb->get_results($query, $output);
                    break;
            }

            set_site_transient($key, $var, $ttl);
        }

        return $var;
    }
}

if (!function_exists('d4p_delete_user_transient')) {
    function d4p_delete_user_transient($user_id, $transient) {
        $transient_option = '_transient_'.$transient;
        $transient_timeout = '_transient_timeout_'.$transient;

        delete_user_meta($user_id, $transient_option);
        delete_user_meta($user_id, $transient_timeout);
    }
}

if (!function_exists('d4p_get_user_transient')) {
    function d4p_get_user_transient($user_id, $transient) {
        $transient_option = '_transient_'.$transient;
        $transient_timeout = '_transient_timeout_'.$transient;

        if (get_user_meta($user_id, $transient_timeout, true) < time()) {
            delete_user_meta($user_id, $transient_option);
            delete_user_meta($user_id, $transient_timeout);
            return false;
        }

        return get_user_meta($user_id, $transient_option, true);
    }
}

if (!function_exists('d4p_set_user_transient')) {
    function d4p_set_user_transient($user_id, $transient, $value, $expiration = 86400) {
        $transient_option = '_transient_'.$transient;
        $transient_timeout = '_transient_timeout_'.$transient;

        if (get_user_meta($user_id, $transient_option, true) != '') {
            delete_user_meta($user_id, $transient_option);
            delete_user_meta($user_id, $transient_timeout);
        }

        add_user_meta($user_id, $transient_timeout, time() + $expiration, true);
        add_user_meta($user_id, $transient_option, $value, true);
    }
}
