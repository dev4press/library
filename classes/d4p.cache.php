<?php

/*
Name:    d4pLib_Class_Cache
Version: v2.0.6
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2017 Milan Petrovic (email: milan@gdragon.info)

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

if (!class_exists('d4p_cache_core')) {
    abstract class d4p_cache_core {
        public $store = 'd4plib';

        private $cache_hits = 0;
        private $cache_misses = 0;

        public function __construct() {}

        private function _hit() {
            $this->cache_hits++;
        }

        private function _miss() {
            $this->cache_misses++;
        }

        private function _key($group, $key) {
            return $group.'::'.$key;
        }

        public function add($group, $key, $data, $expire = 0) {
            return wp_cache_add($this->_key($group, $key), $data, $this->store, $expire);
        }

        public function set($group, $key, $data, $expire = 0) {
            return wp_cache_set($this->_key($group, $key), $data, $this->store, $expire);
        }

        public function get($group, $key, $default = false) {
            $obj = wp_cache_get($this->_key($group, $key), $this->store);

            if ($obj === false) {
                $this->_miss();
            } else {
                $this->_hit();
            }

            return $obj === false ? $default : $obj;
        }

        public function delete($group, $key) {
            return wp_cache_delete($this->_key($group, $key), $this->store);
        }

        /** @global WP_Object_Cache $wp_object_cache */
        public function in($group, $key) {
            global $wp_object_cache;

            return isset($wp_object_cache->cache[$this->store][$this->_key($group, $key)]);
        }

        /** @global WP_Object_Cache $wp_object_cache */
        public function clear() {
            global $wp_object_cache;

            if (isset($wp_object_cache->cache[$this->store])) {
                unset($wp_object_cache->cache[$this->store]);
            }
        }

        /** @global WP_Object_Cache $wp_object_cache */
        public function storage() {
            global $wp_object_cache;
            
            if (isset($wp_object_cache->cache[$this->store])) {
                return $wp_object_cache->cache[$this->store];
            }

            return array();
        }
    }
}
