<?php

/*
Name:    Dev4Press Core Autoloader
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

if (!function_exists('d4p_core_library_autoloader_290')) {
    function d4p_core_library_autoloader_290($class) {
        $path = dirname(__FILE__).'/';

        $parts = explode('\\', $class);

        $class_name = $parts[count($parts) - 1];
        unset($parts[count($parts) - 1]);

        $class_namespace = join('/', $parts);
        $class_namespace = strtolower($class_namespace);

        $path.= $class_namespace.'/'.$class_name.'.php';

        if (file_exists($path)) {
            include($path);
        }
    }

    spl_autoload_register('d4p_core_library_autoloader_290');
}