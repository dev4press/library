<?php

/*
Name:    Base Library Functions: Deprecated
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

if (!defined('ABSPATH')) { exit; }

if (!function_exists('d4p_get_icon_class')) {
    function d4p_get_icon_class($name, $extra = array()) {
        $class = '';
        $d4p = false;
        $dashicons = false;

        if (substr($name, 0, 3) == 'd4p') {
            $class.= 'd4p-icon '.$name;
            $d4p = true;
        } else if (substr($name, 0, 9) == 'dashicons') {
            $class.= 'dashicons '.$name;
            $dashicons = true;
        } else if (strpos($name, ' ') > 0) {
            $class.= $name;
        } else {
            $class.= 'fa fa-'.$name;
        }

        if (!empty($extra) && !$dashicons) {
            $extra = (array)$extra;

            foreach ($extra as $key) {
                $class.= ' '.($d4p ? 'd4p-icon' : 'fa').'-'.$key;
            }
        }

        return $class;
    }
}

if (!function_exists('d4p_render_icon')) {
    function d4p_render_icon($name, $tag = 'i', $aria_hidden = true, $fw = false, $class = '', $attr = array()) {
        $icon = '<'.$tag;

        if ($aria_hidden) {
            $icon.= ' aria-hidden="true"';
        }

        $extra = $fw ? 'fw' : '';
        $classes = d4p_get_icon_class($name, $extra).' '.$class;

        $icon.= ' class="'.trim($classes).'"';

        foreach ($attr as $key => $value) {
            $icon.= ' '.$key.'="'.esc_attr($value).'"';
        }

        $icon.= '></'.$tag.'>';

        return $icon;
    }
}
