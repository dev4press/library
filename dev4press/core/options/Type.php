<?php

/*
Name:    Dev4Press\Core\Options\Type
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

namespace Dev4Press\Core\Options;

if (!defined('ABSPATH')) {
    exit;
}

class Type {
    const INFO = 'info';
    const CUSTOM = 'custom';

    const IMAGE = 'image';
    const IMAGES = 'images';
    const BOOLEAN = 'bool';
    const TEXT = 'text';
    const TEXTAREA = 'textarea';
    const SLUG = 'slug';
    const SLUG_EXT = 'slug_ext';
    const SLUG_SLASH = 'slug_slash';
    const PASSWORD = 'password';
    const FILE = 'file';
    const TEXT_HTML = 'text_html';
    const COLOR = 'color';
    const RICH = 'rich';
    const BLOCK = 'block';
    const HTML = 'html';
    const CODE = 'code';
    const EMAIL = 'email';
    const LINK = 'link';
    const CHECKBOXES = 'checkboxes';
    const CHECKBOXES_HIERARCHY = 'checkboxes_hierarchy';
    const RADIOS = 'radios';
    const RADIOS_HIERARCHY = 'radios_hierarchy';
    const SELECT = 'select';
    const SELECT_MULTI = 'select_multi';
    const GROUP = 'group';
    const GROUP_MULTI = 'group_multi';
    const NUMBER = 'number';
    const INTEGER = 'integer';
    const ABSINT = 'absint';
    const HIDDEN = 'hidden';
    const LISTING = 'listing';
    const X_BY_Y = 'x_by_y';

    const EXPANDABLE_PAIRS = 'expandable_pairs';
    const EXPANDABLE_TEXT = 'expandable_text';
    const EXPANDABLE_RAW = 'expandable_raw';

    public static $_values = array(
        'info' => self::INFO,
        'custom' => self::CUSTOM,

        'image' => self::IMAGE,
        'images' => self::IMAGES,
        'bool' => self::BOOLEAN,
        'text' => self::TEXT,
        'textarea' => self::TEXTAREA,
        'slug' => self::SLUG,
        'slug_ext' => self::SLUG_EXT,
        'slug_slash' => self::SLUG_SLASH,
        'password' => self::PASSWORD,
        'file' => self::FILE,
        'text_html' => self::TEXT_HTML,
        'color' => self::COLOR,
        'rich' => self::RICH,
        'block' => self::BLOCK,
        'html' => self::HTML,
        'code' => self::CODE,
        'email' => self::EMAIL,
        'link' => self::LINK,
        'checkboxes' => self::CHECKBOXES,
        'checkboxes_hierarchy' => self::CHECKBOXES_HIERARCHY,
        'radios' => self::RADIOS,
        'radios_hierarchy' => self::RADIOS_HIERARCHY,
        'select' => self::SELECT,
        'select_multi' => self::SELECT_MULTI,
        'group' => self::GROUP,
        'group_multi' => self::GROUP_MULTI,
        'number' => self::NUMBER,
        'integer' => self::INTEGER,
        'absint' => self::ABSINT,
        'listing' => self::LISTING,
        'hidden' => self::HIDDEN,
        'x_by_y' => self::X_BY_Y,

        'expandable_pairs' => self::EXPANDABLE_PAIRS,
        'expandable_text' => self::EXPANDABLE_TEXT,
        'expandable_raw' => self::EXPANDABLE_RAW
    );

    public static function to_string($value) {
        if (is_null($value)) {
            return null;
        }

        if (array_key_exists($value, self::$_values)) {
            return self::$_values[$value];
        }

        return 'UNKNOWN';
    }
}
