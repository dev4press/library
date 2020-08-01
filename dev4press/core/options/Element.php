<?php

/*
Name:    Dev4Press\Core\Options\Element
Version: v3.1.4
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

class Element {
    public $type;
    public $name;
    public $title;
    public $notice;
    public $input;
    public $value;
    public $source;
    public $data;
    public $args;

    function __construct($type, $name, $title = '', $notice = '', $input = 'text', $value = '') {
        $this->type = $type;
        $this->name = $name;
        $this->title = $title;
        $this->notice = $notice;
        $this->input = $input;
        $this->value = $value;
    }

    public static function i($type, $name, $title = '', $notice = '', $input = 'text', $value = '') {
        return new Element($type, $name, $title, $notice, $input, $value);
    }

    public static function l($type, $name, $title = '', $notice = '', $input = 'text', $value = '', $source = '', $data = '', $args = array()) {
        return Element::i($type, $name, $title, $notice, $input, $value)->data($source, $data)->args($args);
    }

    public static function info($title = '', $notice = '') {
        return Element::i('', '', $title, $notice, Type::INFO);
    }

    public function data($source = '', $data = '') {
        $this->source = $source;
        $this->data = $data;

        return $this;
    }

    public function args($args = array()) {
        $this->args = $args;

        return $this;
    }
}
