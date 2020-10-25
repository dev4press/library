<?php

/*
Name:    Dev4Press\Core\Cache\Core
Version: v3.3
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

namespace Dev4Press\Core\Cache;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Core {
    public $store = 'd4plib';

    public function __construct() {
        $this->clear();
    }

    public static function instance() {
        static $instance = array();

        $class = get_called_class();

        if (!isset($instance[$class])) {
            $instance[$class] = new $class();
        }

        return $instance[$class];
    }

    private function _key($group, $key) {
        return $group.'::'.$key;
    }

    public function add($group, $key, $data) {
        return d4p_object_cache()->add($this->_key($group, $key), $data, $this->store);
    }

    public function set($group, $key, $data) {
        return d4p_object_cache()->set($this->_key($group, $key), $data, $this->store);
    }

    public function get($group, $key, $default = false) {
        $obj = d4p_object_cache()->get($this->_key($group, $key), $this->store);

        return $obj === false ? $default : $obj;
    }

    public function delete($group, $key) {
        return d4p_object_cache()->delete($this->_key($group, $key), $this->store);
    }

    public function in($group, $key) {
        return d4p_object_cache()->in($this->_key($group, $key), $this->store);
    }

    public function clear() {
        d4p_object_cache()->flush($this->store);
    }

    public function storage() {
        return d4p_object_cache()->get_group($this->store);
    }
}
