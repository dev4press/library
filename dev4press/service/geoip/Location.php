<?php

/*
Name:    Dev4Press\Services\GEOIP\Location
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

namespace Dev4Press\Service\GEOIP;

if (!defined('ABSPATH')) {
    exit;
}

class Location {
    public $status = 'invalid';

    public $ip = '';
    public $country_code = '';
    public $country_name = '';
    public $region_code = '';
    public $region_name = '';
    public $city = '';
    public $zip_code = '';
    public $time_zone = '';
    public $latitude = '';
    public $longitude = '';

    public $continent_code = '';

    public function __construct($input) {
        foreach ((array)$input as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function location() {
        $location = '';

        if ($this->status == 'active' && isset($this->country_name) && !empty($this->country_name)) {
            $location .= $this->country_name;

            if (isset($this->city) && !empty($this->city)) {
                $location .= ', '.$this->city;
            }
        }

        return $location;
    }

    public function flag($not_found = 'image') {
        if ($this->status == 'active') {
            if ($this->country_code != '') {
                return '<img src="'.D4PLIB_URL.'resources/flags/blank.gif" class="flag flag-'.strtolower($this->country_code).'" title="'.$this->location().'" alt="'.$this->location().'" />';
            }
        } else if ($this->status == 'private') {
            return '<img src="'.D4PLIB_URL.'resources/flags/blank.gif" class="flag flag-localhost" title="'.__("Localhost or Private IP", "d4plib").'" alt="'.__("Localhost or Private IP", "d4plib").'" />';
        }

        if ($not_found == 'image') {
            return '<img src="'.D4PLIB_URL.'resources/flags/blank.gif" class="flag flag-invalid" title="'.__("IP can't be geolocated.", "d4plib").'" alt="'.__("IP can't be geolocated.", "d4plib").'" />';
        } else {
            return '';
        }
    }
}