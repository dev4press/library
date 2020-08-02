<?php

/*
Name:    Base Library Functions: Panel
Version: v3.2
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

use Dev4Press\Core\UI\Admin\Panel;

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('d4p_panel')) {
    /** @return \Dev4Press\Core\UI\Admin\Panel */
    function d4p_panel() {
        return Panel::instance();
    }
}
