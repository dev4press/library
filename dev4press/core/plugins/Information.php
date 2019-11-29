<?php

/*
Name:    Dev4Press\Core\Plugins\Information
Version: v2.9.5
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

namespace Dev4Press\Core\Plugins;

use Dev4Press\API\Store;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Information {
    public $code = '';

    public $version = '';
    public $build = 0;
    public $updated = '';
    public $status = '';
    public $edition = '';
    public $released = '';

    public $author_name = 'Milan Petrovic';
    public $author_url = 'https://www.dev4press.com/';

    public $php = '5.6';
    public $mysql = '5.1';
    public $wordpress = '4.9';
    public $classicpress = '1.0';

    public $install = false;
    public $update = false;
    public $previous = 0;

    function __construct() {}

    public function to_array() {
        return (array)$this;
    }

    /** @return Information */
    public static function instance() {
        static $instance = array();

        $class = get_called_class();

        if (!isset($instance[$class])) {
            $instance[$class] = new $class();
        }

        return $instance[$class];
    }

    public function name() {
        return Store::instance()->name($this->code);
    }

    public function description() {
        return Store::instance()->description($this->code);
    }

    public function punchline() {
        return Store::instance()->punchline($this->code);
    }

    public function color() {
        return Store::instance()->color($this->code);
    }

    public function url() {
        return Store::instance()->url($this->code);
    }
}
