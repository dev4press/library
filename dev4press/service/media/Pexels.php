<?php

/*
Name:    Dev4Press\Service\Media\Pexels
Version: v2.9.1
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

namespace Dev4Press\Service\Media;

if (!defined('ABSPATH')) {
    exit;
}

class Pexels {
    private $_api_key;
    private $_api_url = 'https://api.pexels.com/';

    public function __construct($api_key) {
        $this->_api_key = $api_key;
    }

    public static function instance($api_key) {
        static $_d4p_pexels = false;

        if (!$_d4p_pexels) {
            $_d4p_pexels = new Pexels($api_key);
        }

        return $_d4p_pexels;
    }

    public function image($id) {
        $url = 'https://api.pexels.com/v1/photos/'.$id;

        $raw = $this->_request($url);

        if (is_wp_error($raw)) {
            return $raw;
        }

        $body = wp_remote_retrieve_body($raw);
        $response = json_decode($body);

        return $this->_format_image($response);
    }

    public function images($args = array()) {
        $defaults = array(
            'query' => '',
            'page' => 1,
            'per_page' => 15
        );

        $args = wp_parse_args($args, $defaults);

        $url = add_query_arg($args, $this->_api_url.'v1/search');

        $raw = $this->_request($url);

        if (is_wp_error($raw)) {
            return $raw;
        }

        $body = wp_remote_retrieve_body($raw);
        $response = json_decode($body);

        $out = array(
            'page' => $response->page,
            'per_page' => $response->per_page,
            'total' => $response->total_results,
            'results' => array()
        );

        foreach ($response->photos as $img) {
            $out['results'][] = $this->_format_image($img);
        }

        return (object)$out;
    }

    public function video($id) {
        $url = 'https://api.pexels.com/videos/videos/'.$id;

        $raw = $this->_request($url);

        if (is_wp_error($raw)) {
            return $raw;
        }

        $body = wp_remote_retrieve_body($raw);
        $response = json_decode($body);

        return $this->_format_video($response);
    }

    public function videos($args = array()) {
        $defaults = array(
            'query' => '',
            'page' => 1,
            'per_page' => 15,
            'min_width' => '',
            'max_width' => '',
            'min_duration' => '',
            'max_duration' => ''
        );

        $args = wp_parse_args($args, $defaults);

        $url = add_query_arg($args, $this->_api_url.'videos/search');

        $raw = $this->_request($url);

        if (is_wp_error($raw)) {
            return $raw;
        }

        $body = wp_remote_retrieve_body($raw);
        $response = json_decode($body);

        $out = array(
            'page' => $response->page,
            'per_page' => $response->per_page,
            'total' => $response->total_results,
            'results' => array()
        );

        foreach ($response->videos as $img) {
            $out['results'][] = $this->_format_video($img);
        }

        return (object)$out;
    }

    private function _request($url) {
        $args = array(
            'headers' => array(
                'Authorization' => $this->_api_key
            )
        );

        return wp_remote_get($url, $args);
    }

    private function _format_image($response) {
        return new PexelsImage($response);
    }

    private function _format_video($response) {
        return new PexelsVideo($response);
    }
}
